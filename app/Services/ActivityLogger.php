<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ActivityLogger
{
    /**
     * Request-level guard to avoid exact duplicates created by overlapping hooks.
     *
     * @var array<string, bool>
     */
    private static array $dedup = [];

    public static function info(string $eventKey, string $description, array $context = []): ?ActivityLog
    {
        return self::write('info', $eventKey, 'success', $description, $context);
    }

    public static function success(string $eventKey, string $description, array $context = []): ?ActivityLog
    {
        return self::write('success', $eventKey, 'success', $description, $context);
    }

    public static function warning(string $eventKey, string $description, array $context = []): ?ActivityLog
    {
        return self::write('warning', $eventKey, 'failed', $description, $context);
    }

    public static function error(string $eventKey, string $description, array $context = []): ?ActivityLog
    {
        return self::write('error', $eventKey, 'failed', $description, $context);
    }

    public static function write(
        string $severity,
        string $eventKey,
        ?string $status,
        string $description,
        array $context = []
    ): ?ActivityLog {
        $request = request();
        $route = $request?->route();
        $target = $context['target'] ?? null;
        $targetType = $context['target_type'] ?? ($target instanceof Model ? get_class($target) : null);
        $targetId = $context['target_id'] ?? ($target instanceof Model ? $target->getKey() : null);
        $category = $context['category'] ?? self::deriveCategory($eventKey);
        $correlationKey = $context['correlation_key'] ?? self::deriveCorrelationKey($targetType, $targetId);
        $requestId = self::resolveRequestId();
        $fingerprint = implode('|', [
            $eventKey,
            $status ?? '',
            $targetType ?? '',
            (string) ($targetId ?? ''),
            $requestId ?? '',
            $description,
        ]);

        if (isset(self::$dedup[$fingerprint])) {
            return null;
        }
        self::$dedup[$fingerprint] = true;

        $properties = array_filter([
            'route' => $route?->getName(),
            'method' => $request?->method(),
            'url' => $request?->fullUrl(),
            'category' => $category,
            'severity' => $severity,
            'status' => $status,
            'event_key' => $eventKey,
            'correlation_key' => $correlationKey,
            'request_id' => $requestId,
            'context' => $context['context'] ?? null,
            'exception' => $context['exception'] ?? null,
            'metadata' => $context['metadata'] ?? null,
        ], static fn($value) => $value !== null && $value !== '');

        return ActivityLog::create([
            'user_id' => $context['user_id'] ?? Auth::id(),
            'action' => $context['action'] ?? self::deriveAction($eventKey),
            'severity' => $severity,
            'status' => $status,
            'category' => $category,
            'event_key' => $eventKey,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'model_type' => $context['model_type'] ?? $targetType,
            'model_id' => $context['model_id'] ?? $targetId,
            'description' => $description,
            'properties' => $properties,
            'ip_address' => $context['ip_address'] ?? $request?->ip(),
            'user_agent' => $context['user_agent'] ?? $request?->userAgent(),
            'request_id' => $requestId,
            'correlation_key' => $correlationKey,
        ]);
    }

    public static function fromLegacy(string $action, $model = null, ?string $description = null, array $properties = []): ?ActivityLog
    {
        $eventKey = $properties['event_key'] ?? 'legacy.' . $action;
        $severity = $properties['severity'] ?? 'info';
        $status = $properties['status'] ?? 'success';
        $category = $properties['category'] ?? self::deriveCategory($eventKey);

        return self::write(
            $severity,
            $eventKey,
            $status,
            $description ?? ('Legacy action: ' . $action),
            [
                'action' => $action,
                'target' => $model,
                'category' => $category,
                'context' => $properties,
            ]
        );
    }

    private static function deriveAction(string $eventKey): string
    {
        $parts = explode('.', $eventKey);
        return $parts[1] ?? $parts[0] ?? 'access';
    }

    private static function deriveCategory(string $eventKey): string
    {
        $first = explode('.', $eventKey)[0] ?? 'general';
        $allowed = ['auth', 'crud', 'email', 'booking', 'payment', 'review', 'security', 'notification', 'system'];

        return in_array($first, $allowed, true) ? $first : 'general';
    }

    private static function deriveCorrelationKey(?string $targetType, mixed $targetId): ?string
    {
        if (!$targetType || !$targetId) {
            return null;
        }

        return class_basename($targetType) . ':' . $targetId;
    }

    private static function resolveRequestId(): ?string
    {
        $request = request();
        if (!$request) {
            return null;
        }

        if (!$request->attributes->has('request_id')) {
            $request->attributes->set('request_id', (string) Str::uuid());
        }

        return $request->attributes->get('request_id');
    }
}
