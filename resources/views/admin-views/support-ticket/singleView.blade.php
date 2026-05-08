@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Support Ticket'))

@push('css_or_js')
<style>
   .chat-box {
    width: 70%;       /* Desktop / Tablet fixed width */
    max-width: 70%;
    border-radius: 10px;
    font-size: 14px;
    word-wrap: break-word;
    padding: 10px;
}

/* Mobile screens */
@media (max-width: 767px) {
    .chat-box {
        width: 90% !important;   /* Mobile responsive width */
        max-width: 90% !important;
    }
}
</style>
@endpush

@section('content')
<div class="content-wrapper">

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-chat"></i>
            </span>
            {{\App\CPU\translate('support_ticket')}}
        </h3>
    </div>

    <div class="card">

        <!-- HEADER -->
        <div class="card-header d-flex justify-content-between align-items-center">

            <div class="d-flex align-items-center gap-3">
                <img class="rounded-circle avatar"
                     style="width:50px; height:50px; object-fit:cover;"
                     src="{{ 
                        ($ticket->user && $ticket->user->image) 
                        ? asset($ticket->user->image) 
                        : (($ticket->seller && $ticket->seller->image) 
                            ? asset($ticket->seller->image) 
                            : 'https://media.istockphoto.com/id/2171382633/vector/user-profile-icon-anonymous-person-symbol-blank-avatar-graphic-vector-illustration.jpg'
                        )
                     }}"
                     onerror="this.src='https://media.istockphoto.com/id/2171382633/vector/user-profile-icon-anonymous-person-symbol-blank-avatar-graphic-vector-illustration.jpg'"
                     alt="User Avatar">

                <div>
                    <h6>{{ $ticket->user->name ?? $ticket->seller->f_name }}</h6>
                    <small>{{ $ticket->user->mobile ?? $ticket->seller->phone }}</small>
                </div>
            </div>

            <div>
                <span class="btn btn-success btn-sm">
                    {{ \App\CPU\translate(str_replace('_',' ',$ticket->status)) }}
                </span>
            </div>

        </div>

        <!-- BODY -->
        <div class="card-body">

            <!-- Chat Container -->
            <div style="max-height: 500px; overflow-y: auto; padding-right:10px;">

                <!-- Ticket First Message -->
                <div class="d-flex mb-4">
                    <div class="bg-light p-3 rounded shadow-sm chat-box" style="width: 70%;">
                        <p class="mb-1 font-weight-bold text-dark">
                            {{ $ticket->subject }}
                        </p>
                        <small class="text-muted">
                            {{ $ticket->created_at->timezone('Asia/Kolkata')->format('d/m/Y h:i A') }}
                        </small>
                    </div>
                </div>

                <!-- Messages -->
                @foreach($ticket->messages as $message)

                {{-- USER / SELLER --}}
                @if($message->sender_type == 'user' || $message->sender_type == 'brand')
                <div class="d-flex mb-3">
                    <div class="bg-light p-3 rounded shadow-sm chat-box" style="width: 70%;"> 
                        <p class="mb-1 text-dark">
                            {{ $message->body }}
                        </p>
                        <small class="text-muted">
                            {{ $message->created_at->timezone('Asia/Kolkata')->format('d/m/Y h:i A') }}
                        </small>
                    </div>
                </div>
                @endif

                {{-- ADMIN --}}
                @if($message->sender_type == 'admin')
                <div class="d-flex justify-content-end mb-3">
                    <div class="bg-primary text-white p-3 rounded shadow-sm chat-box" style="width: 70%;">
                        <p class="mb-1">
                            {{ $message->body }}
                        </p>
                        <small style="opacity: 0.8;">
                            {{ $message->created_at->timezone('Asia/Kolkata')->format('d/m/Y h:i A') }}
                        </small>
                    </div>
                </div>
                @endif

                @endforeach

            </div>

            <!-- Reply Box -->
            <div class="border-top pt-3 mt-3">

                <h6 class="mb-2">
                    {{ \App\CPU\translate('Leave_a_Message') }}
                </h6>

                <form action="{{route('admin.support-ticket.replay',$ticket->id)}}" method="POST">
                    @csrf

                    <div class="d-flex gap-2">
                        <textarea class="form-control"
                                  name="replay"
                                  rows="2"
                                  placeholder="Type your message..."
                                  style="resize: none;"
                                  required></textarea>

                        <button class="btn btn--primary px-4">
                            Send
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>

</div>
@endsection