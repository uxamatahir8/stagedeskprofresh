<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityLog extends Model
{
    protected $fillable = [
        'user_id',
        'event_type',
        'ip_address',
        'user_agent',
        'description',
        'metadata',
        'severity',
        'detected_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'detected_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function logEvent($eventType, $description, $severity = 'info', $userId = null, array $metadata = [])
    {
        return self::create([
            'user_id' => $userId,
            'event_type' => $eventType,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => $description,
            'metadata' => $metadata,
            'severity' => $severity,
            'detected_at' => now()
        ]);
    }

    public function scopeCritical($query)
    {
        return $query->where('severity', 'critical');
    }

    public function scopeWarning($query)
    {
        return $query->where('severity', 'warning');
    }

    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('detected_at', '>=', now()->subHours($hours));
    }
}
