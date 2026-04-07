<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use App\Models\SupportTicketMessage;
class SupportTicketController extends Controller
{
public function index(Request $request)
{
    $tickets = SupportTicket::with('user:id,name,mobile,image', 'seller:id,f_name,phone,image');

    // 1️⃣ Search filter (only if search not empty)
    if ($request->filled('search')) {
        $search = $request->search;
        $tickets->where(function($query) use ($search){
            $query->where('subject', 'like', '%'.$search.'%')
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', '%'.$search.'%')
                        ->orWhere('mobile', 'like', '%'.$search.'%');
                  })
                  ->orWhereHas('seller', function($q) use ($search) {
                      $q->where('f_name', 'like', '%'.$search.'%')
                        ->orWhere('phone', 'like', '%'.$search.'%');
                  });
        });
    }

    // 2️⃣ Status filter
    if ($request->filled('status') && $request->status != 'all') {
        $tickets->where('status', $request->status);
    }

    // 3️⃣ Latest first + paginate + preserve query string
    $tickets = $tickets->latest()->paginate(10)->withQueryString();

    return view('admin-views.support-ticket.view', compact('tickets'));
}


public function view($id)
{
    $ticket = SupportTicket::with(['user', 'seller','messages'])->find($id);

    if (!$ticket) {
        abort(404);
    }
    // dd($ticket);

    return view('admin-views.support-ticket.singleView', compact('ticket'));
}


public function reply(Request $request, $id)
{
    $request->validate([
        'replay' => 'required'
    ]);

    SupportTicketMessage::create([
        'support_ticket_id' => $id,
        'sender_type' => 'admin',
        'body' => $request->replay,
    ]);

    return back()->with('success', 'Reply sent successfully');
}
    
}
