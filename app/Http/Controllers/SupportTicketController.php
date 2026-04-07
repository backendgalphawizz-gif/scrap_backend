<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function index(){

        $tickets = SupportTicket::with('user:id,name,mobile', 'seller:id,name,mobile')->paginate(10);
     
        return view('admin-views.support-ticket.view', compact('tickets'));
    }
}
