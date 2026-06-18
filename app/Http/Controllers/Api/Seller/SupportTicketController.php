<?php

namespace App\Http\Controllers\Api\Seller;

use App\CPU\Helpers;
use App\Http\Controllers\Api\Support\SupportTicketPresenter;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use function App\CPU\translate;

class SupportTicketController extends Controller
{
    private function sellerOrUnauthorized(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] != 1) {
            return [null, response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => [],
            ], 401)];
        }

        return [$data['data'], null];
    }

    public function index(Request $request)
    {
        [$seller, $error] = $this->sellerOrUnauthorized($request);
        if ($error) {
            return $error;
        }

        $tickets = SupportTicket::query()
            ->where('seller_id', $seller->id)
            ->whereNull('user_id')
            ->withCount([
                'messages',
                'messages as unread_messages_count' => function ($q) {
                    $q->where('sender_type', 'admin')
                        ->where('seen_by_requester', false);
                },
            ])
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
        [$seller, $error] = $this->sellerOrUnauthorized($request);
        if ($error) {
            return $error;
        }

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

        $ticket = DB::transaction(function () use ($request, $seller) {
            $ticket = SupportTicket::create([
                'user_id' => null,
                'seller_id' => $seller->id,
                'subject' => $request->subject,
                'status' => 'open',
            ]);

            SupportTicketMessage::create(array_merge([
                'support_ticket_id' => $ticket->id,
                'sender_type' => 'brand',
                'sender_user_id' => null,
                'sender_seller_id' => $seller->id,
                'body' => $request->message,
            ], SupportTicketMessage::readFlagsForSender('brand')));

            return $ticket;
        });

        $ticket->load('messages');

        AdminNotification::fire(
            type:        'support.ticket_created',
            title:       'New Brand Support Ticket',
            message:     "Brand {$seller->username} (#{$seller->id}) opened ticket #{$ticket->id}: \"{$ticket->subject}\".",
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
        [$seller, $error] = $this->sellerOrUnauthorized($request);
        if ($error) {
            return $error;
        }

        $ticket = SupportTicket::with('messages')
            ->where('id', $id)
            ->where('seller_id', $seller->id)
            ->whereNull('user_id')
            ->first();

        if (!$ticket) {
            return response()->json([
                'status' => false,
                'message' => 'Ticket not found',
            ], 404);
        }

        SupportTicketMessage::where('support_ticket_id', $ticket->id)
            ->where('sender_type', 'admin')
            ->where('seen_by_requester', false)
            ->update(['seen_by_requester' => true]);

        return response()->json([
            'status' => true,
            'data' => SupportTicketPresenter::ticket($ticket, true),
        ]);
    }

    public function destroy(Request $request, int $id)
    {
        [$seller, $error] = $this->sellerOrUnauthorized($request);
        if ($error) {
            return $error;
        }

        $ticket = SupportTicket::query()
            ->where('id', $id)
            ->where('seller_id', $seller->id)
            ->whereNull('user_id')
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
        [$seller, $error] = $this->sellerOrUnauthorized($request);
        if ($error) {
            return $error;
        }

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

        $ticket = SupportTicket::query()
            ->where('id', $id)
            ->where('seller_id', $seller->id)
            ->whereNull('user_id')
            ->first();

        if (!$ticket) {
            return response()->json([
                'status' => false,
                'message' => 'Ticket not found',
            ], 404);
        }

        $msg = SupportTicketMessage::create(array_merge([
            'support_ticket_id' => $ticket->id,
            'sender_type' => 'brand',
            'sender_user_id' => null,
            'sender_seller_id' => $seller->id,
            'body' => $request->message,
        ], SupportTicketMessage::readFlagsForSender('brand')));

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
