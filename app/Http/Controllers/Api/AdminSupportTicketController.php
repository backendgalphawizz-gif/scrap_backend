<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Support\SupportTicketPresenter;
use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminSupportTicketController extends Controller
{
    private function ensureAdmin(Request $request)
    {
        $user = $request->user();
        if (!$user instanceof User || (int) $user->role_id !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. Admin access only.',
            ], 403);
        }

        return null;
    }

    public function index(Request $request)
    {
        if ($err = $this->ensureAdmin($request)) {
            return $err;
        }

        $query = SupportTicket::query()->withCount('messages')->latest();

        if ($request->filled('requester_type')) {
            $request->validate([
                'requester_type' => 'in:user,brand',
            ]);
            if ($request->requester_type === 'user') {
                $query->whereNotNull('user_id')->whereNull('seller_id');
            } else {
                $query->whereNotNull('seller_id')->whereNull('user_id');
            }
        }

        if ($request->filled('status')) {
            $request->validate([
                'status' => 'in:open,closed',
            ]);
            $query->where('status', $request->status);
        }

        $tickets = $query->paginate((int) $request->get('per_page', 20));

        return response()->json([
            'status' => true,
            'message' => 'Support tickets',
            'data' => collect($tickets->items())->map(fn ($t) => SupportTicketPresenter::ticketSummary($t))->values()->all(),
            'meta' => [
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
                'per_page' => $tickets->perPage(),
                'total' => $tickets->total(),
            ],
        ]);
    }

    public function show(Request $request, int $id)
    {
        if ($err = $this->ensureAdmin($request)) {
            return $err;
        }

        $ticket = SupportTicket::with(['messages', 'user:id,name,email,mobile', 'seller:id,f_name,l_name,username,phone,email'])
            ->find($id);

        if (!$ticket) {
            return response()->json([
                'status' => false,
                'message' => 'Ticket not found',
            ], 404);
        }

        $payload = SupportTicketPresenter::ticket($ticket, true);
        if ($ticket->user_id) {
            $u = $ticket->user;
            $payload['requester'] = [
                'type' => 'user',
                'id' => $ticket->user_id,
                'name' => $u?->name,
                'email' => $u?->email,
                'mobile' => $u?->mobile,
            ];
        } else {
            $s = $ticket->seller;
            $payload['requester'] = [
                'type' => 'brand',
                'id' => $ticket->seller_id,
                'username' => $s?->username,
                'name' => $s ? trim(($s->f_name ?? '') . ' ' . ($s->l_name ?? '')) : null,
                'email' => $s?->email,
                'phone' => $s?->phone,
            ];
        }

        return response()->json([
            'status' => true,
            'data' => $payload,
        ]);
    }

    public function destroy(Request $request, int $id)
    {
        if ($err = $this->ensureAdmin($request)) {
            return $err;
        }

        $ticket = SupportTicket::find($id);
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
        if ($err = $this->ensureAdmin($request)) {
            return $err;
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

        $admin = $request->user();
        $ticket = SupportTicket::find($id);
        if (!$ticket) {
            return response()->json([
                'status' => false,
                'message' => 'Ticket not found',
            ], 404);
        }

        $msg = SupportTicketMessage::create([
            'support_ticket_id' => $ticket->id,
            'sender_type' => 'admin',
            'sender_user_id' => $admin->id,
            'sender_seller_id' => null,
            'body' => $request->message,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Message sent',
            'data' => SupportTicketPresenter::message($msg),
        ], 201);
    }

}
