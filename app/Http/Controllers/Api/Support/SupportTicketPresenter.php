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
            'seen_by_admin' => (bool) $m->seen_by_admin,
            'seen_by_requester' => (bool) $m->seen_by_requester,
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
            'unread_count' => $ticket->unread_messages_count
                ?? $ticket->messages()
                    ->where('sender_type', 'admin')
                    ->where('seen_by_requester', false)
                    ->count(),
            'admin_unread_count' => isset($ticket->admin_unread_messages_count)
                ? (int) $ticket->admin_unread_messages_count
                : $ticket->messages()
                    ->whereIn('sender_type', ['user', 'brand'])
                    ->where('seen_by_admin', false)
                    ->count(),
            'created_at' => $ticket->created_at,
            'updated_at' => $ticket->updated_at,
        ];
    }
}
