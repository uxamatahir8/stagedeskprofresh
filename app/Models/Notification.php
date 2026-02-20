<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //
    protected $fillable = [
        'user_id',
        'company_id',
        'title',
        'message',
        'type',
        'category',
        'priority',
        'link',
        'is_read',
        'data',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'priority' => 'integer',
        'data' => 'array',
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }
}
