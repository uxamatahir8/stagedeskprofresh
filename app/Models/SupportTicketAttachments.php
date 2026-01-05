<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicketAttachments extends Model
{
    //
    protected $fillable = [
        'support_ticket_id',
        'file_path',
        'original_name',
    ];

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class);
    }
}
