<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicketMessage extends Model
{
    protected $fillable = [
        'support_ticket_id',
        'sender_type',
        'sender_user_id',
        'sender_seller_id',
        'body',
        'seen_by_admin',
        'seen_by_requester',
    ];

    protected $casts = [
        'seen_by_admin' => 'boolean',
        'seen_by_requester' => 'boolean',
    ];

    public static function readFlagsForSender(string $senderType): array
    {
        if ($senderType === 'admin') {
            return ['seen_by_admin' => true, 'seen_by_requester' => false];
        }

        return ['seen_by_admin' => false, 'seen_by_requester' => true];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }

    public function senderUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    public function senderSeller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'sender_seller_id');
    }
}
