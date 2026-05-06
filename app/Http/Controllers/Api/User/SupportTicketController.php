<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\Support\SupportTicketPresenter;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $tickets = SupportTicket::query()
            ->where('user_id', $user->id)
            ->whereNull('seller_id')
            ->withCount('messages')
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Support tickets',
            'data' => $tickets->map(fn ($t) => SupportTicketPresenter::ticketSummary($t))->values()->all(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:10000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();

        $ticket = DB::transaction(function () use ($request, $user) {
            $ticket = SupportTicket::create([
                'user_id' => $user->id,
                'seller_id' => null,
                'subject' => $request->subject,
                'status' => 'open',
            ]);

            SupportTicketMessage::create([
                'support_ticket_id' => $ticket->id,
                'sender_type' => 'user',
                'sender_user_id' => $user->id,
                'sender_seller_id' => null,
                'body' => $request->message,
            ]);

            return $ticket;
        });

        $ticket->load('messages');

        AdminNotification::fire(
            type:        'support.ticket_created',
            title:       'New Support Ticket',
            message:     "User #{$user->id} opened ticket #{$ticket->id}: \"{$ticket->subject}\".",
            relatedId:   $ticket->id,
            relatedType: 'SupportTicket'
        );

        return response()->json([
            'status' => true,
            'message' => 'Support ticket created',
            'data' => SupportTicketPresenter::ticket($ticket, true),
        ], 201);
    }

    public function show(Request $request, int $id)
    {
        $user = $request->user();
        $ticket = SupportTicket::with('messages')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->whereNull('seller_id')
            ->first();

        if (!$ticket) {
            return response()->json([
                'status' => false,
                'message' => 'Ticket not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => SupportTicketPresenter::ticket($ticket, true),
        ]);
    }

    public function destroy(Request $request, int $id)
    {
        $user = $request->user();
        $ticket = SupportTicket::query()
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->whereNull('seller_id')
            ->first();

        if (!$ticket) {
            return response()->json([
                'status' => false,
                'message' => 'Ticket not found',
            ], 404);
        }

        $ticket->delete();

        return response()->json([
            'status' => true,
            'message' => 'Support ticket deleted',
        ]);
    }

    public function sendMessage(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:10000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();
        $ticket = SupportTicket::query()
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->whereNull('seller_id')
            ->first();

        if (!$ticket) {
            return response()->json([
                'status' => false,
                'message' => 'Ticket not found',
            ], 404);
        }

        $msg = SupportTicketMessage::create([
            'support_ticket_id' => $ticket->id,
            'sender_type' => 'user',
            'sender_user_id' => $user->id,
            'sender_seller_id' => null,
            'body' => $request->message,
        ]);

        if ($ticket->status === 'closed') {
            $ticket->update(['status' => 'open']);
        }

        return response()->json([
            'status' => true,
            'message' => 'Message sent',
            'data' => SupportTicketPresenter::message($msg),
        ], 201);
    }
}
