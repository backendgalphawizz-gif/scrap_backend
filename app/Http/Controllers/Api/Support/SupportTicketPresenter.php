<?php

namespace App\Http\Controllers\Api\Support;

use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;

class SupportTicketPresenter
{
    public static function message(SupportTicketMessage $m): array
    {
        return [
            'id' => $m->id,
            'sender_type' => $m->sender_type,
            'body' => $m->body,
            'created_at' => $m->created_at,
        ];
    }

    public static function ticket(SupportTicket $ticket, bool $withMessages = true): array
    {
        $row = [
            'id' => $ticket->id,
            'subject' => $ticket->subject,
            'status' => $ticket->status,
            'requester_type' => $ticket->user_id ? 'user' : 'brand',
            'user_id' => $ticket->user_id,
            'seller_id' => $ticket->seller_id,
            'created_at' => $ticket->created_at,
            'updated_at' => $ticket->updated_at,
        ];

        if ($withMessages && $ticket->relationLoaded('messages')) {
            $row['messages'] = $ticket->messages->map(fn ($m) => self::message($m))->values()->all();
        }

        return $row;
    }

    public static function ticketSummary(SupportTicket $ticket): array
    {
        return [
            'id' => $ticket->id,
            'subject' => $ticket->subject,
            'status' => $ticket->status,
            'requester_type' => $ticket->user_id ? 'user' : 'brand',
            'messages_count' => $ticket->messages_count ?? $ticket->messages()->count(),
            'created_at' => $ticket->created_at,
            'updated_at' => $ticket->updated_at,
        ];
    }
}
