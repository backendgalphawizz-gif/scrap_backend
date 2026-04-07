<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use App\Models\SupportTicketMessage;
class SupportTicketController extends Controller
{
    public function index(){

        $tickets = SupportTicket::with('user:id,name,mobile', 'seller:id,name,mobile')->paginate(10);
    //  dd($tickets);
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
