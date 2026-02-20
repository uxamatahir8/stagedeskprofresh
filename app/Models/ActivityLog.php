<?php

namespace App\Models;

use App\Services\ActivityLogger;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'severity',
        'status',
        'category',
        'event_key',
        'target_type',
        'target_id',
        'model_type',
        'model_id',
        'description',
        'properties',
        'ip_address',
        'user_agent',
        'request_id',
        'correlation_key',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * Get the user who performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the model that was affected
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Log an activity
     */
    public static function log(string $action, $model = null, string $description = null, array $properties = [])
    {
        return ActivityLogger::fromLegacy($action, $model, $description, $properties);
    }
}
