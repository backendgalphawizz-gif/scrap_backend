@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Support Ticket'))

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-chat"></i>
            </span> {{\App\CPU\translate('support_ticket')}}
        </h3>

      



      
    </div>

    

    <div class="card">

        <!-- HEADER -->
        <div class="card-header d-flex justify-content-between align-items-center">

            <div class="d-flex align-items-center gap-3">
                <img class="rounded-circle avatar"
                     src="{{asset('storage/app/public/profile/'.$ticket->user->image)}}"
                     width="50"
                     onerror="this.src='{{asset('https://media.istockphoto.com/id/2171382633/vector/user-profile-icon-anonymous-person-symbol-blank-avatar-graphic-vector-illustration.jpg?s=612x612&w=0&k=20&c=ZwOF6NfOR0zhYC44xOX06ryIPAUhDvAajrPsaZ6v1-w=')}}'">

                <div>
                    <h6>{{ $ticket->user->name ?? '' }}</h6>
                    <small>{{ $ticket->user->mobile ?? '' }}</small>
                </div>
            </div>

            <div class="d-flex gap-3">
                <span class="badge badge-info">
                    {{ \App\CPU\translate(str_replace('_',' ',$ticket->status)) }}
                </span>

                <!-- <span class="badge badge-primary">
                    {{ \App\CPU\translate(str_replace('_',' ',$ticket->priority)) }}
                </span> -->
            </div>

        </div>

        <!-- BODY -->
       <div class="card-body">

    <!-- Chat Container -->
    <div style="max-height: 500px; overflow-y: auto; padding-right:10px;">

        <!-- Ticket (First Message) -->
        <div class="d-flex mb-4">
            <div class="bg-light p-3 rounded shadow-sm" style="max-width: 70%;">
                <p class="mb-1 font-weight-bold text-dark">
                    {{ $ticket->subject }}
                </p>
                <small class="text-muted">
                    {{ \Carbon\Carbon::parse($ticket->created_at)->format('d M Y h:i A') }}
                </small>
            </div>
        </div>

        <!-- Messages -->
        @foreach($ticket->messages as $message)

            {{-- Customer Message (Left) --}}
            @if($message->sender_type == 'user'|| $message->sender_type == 'seller')
                <div class="d-flex mb-3">
                    <div class="bg-light p-3 rounded shadow-sm" style="max-width: 70%;">
                        <p class="mb-1 text-dark">
                            {{ $message->body }}
                        </p>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($message->created_at)->format('d M Y h:i A') }}
                        </small>
                    </div>
                </div>
            @endif

            {{-- Admin Message (Right) --}}
            @if($message->sender_type == 'admin')
                <div class="d-flex justify-content-end mb-3">
                    <div class="bg-primary text-white p-3 rounded shadow-sm" style="max-width: 70%;">
                        <p class="mb-1">
                            {{ $message->body }}
                        </p>
                        <small style="opacity: 0.8;">
                            {{ \Carbon\Carbon::parse($message->updated_at)->format('d M Y h:i A') }}
                        </small>
                    </div>
                </div>
            @endif

        @endforeach

    </div>

    <!-- Reply Box -->
    <div class="border-top pt-3 mt-3">

        <h6 class="mb-2">{{ \App\CPU\translate('Leave_a_Message') }}</h6>

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

<style>
    .message-box {
    border-radius: 10px;
    font-size: 14px;
}
</style>