<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $fillable = [
        'type',
        'level',
        'message',
        'exception_class',
        'exception_message',
        'stack_trace',
        'file',
        'line',
        'url',
        'method',
        'request_data',
        'user_id',
        'user_agent',
        'ip_address',
        'controller',
        'action',
        'context',
        'is_resolved',
        'resolved_at',
        'resolved_by',
        'resolution_notes',
    ];

    protected $casts = [
        'request_data' => 'array',
        'context' => 'array',
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Scope to get unresolved errors
     */
    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    /**
     * Scope to get errors by level
     */
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope to get errors by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
