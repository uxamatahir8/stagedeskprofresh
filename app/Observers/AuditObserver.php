<?php

namespace App\Observers;

use App\Services\ActivityLogger;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    public function created(Model $model): void
    {
        if ($this->shouldSkip($model)) {
            return;
        }

        ActivityLogger::success(
            $this->eventKey($model, 'created'),
            class_basename($model) . ' created',
            [
                'category' => 'crud',
                'action' => 'created',
                'target' => $model,
                'metadata' => [
                    'attributes' => $this->safeAttributes($model->getAttributes()),
                ],
            ]
        );
    }

    public function updated(Model $model): void
    {
        if ($this->shouldSkip($model)) {
            return;
        }

        $changes = $model->getChanges();
        if (count($changes) === 1 && array_key_exists('updated_at', $changes)) {
            return;
        }

        ActivityLogger::info(
            $this->eventKey($model, 'updated'),
            class_basename($model) . ' updated',
            [
                'category' => 'crud',
                'action' => 'updated',
                'target' => $model,
                'metadata' => [
                    'changes' => $this->safeAttributes($changes),
                    'original' => $this->safeAttributes($model->getOriginal()),
                ],
            ]
        );
    }

    public function deleted(Model $model): void
    {
        if ($this->shouldSkip($model)) {
            return;
        }

        ActivityLogger::warning(
            $this->eventKey($model, 'deleted'),
            class_basename($model) . ' deleted',
            [
                'category' => 'crud',
                'action' => 'deleted',
                'target_type' => get_class($model),
                'target_id' => $model->getKey(),
                'metadata' => [
                    'attributes' => $this->safeAttributes($model->getAttributes()),
                ],
            ]
        );
    }

    private function shouldSkip(Model $model): bool
    {
        return $model instanceof \App\Models\ActivityLog;
    }

    private function eventKey(Model $model, string $action): string
    {
        return 'crud.' . strtolower(class_basename($model)) . '.' . $action;
    }

    private function safeAttributes(array $attributes): array
    {
        $sensitive = ['password', 'remember_token', 'token', 'verification_token'];
        foreach ($sensitive as $key) {
            if (array_key_exists($key, $attributes)) {
                $attributes[$key] = '***';
            }
        }

        return $attributes;
    }
}
