@extends('layouts.back-end.app')

@section('title','Application Chat')

<style>
    .ChatSilderBar {
        height: 100%;
        max-height: 700px !important;
        overflow-y: auto;
        scrollbar-width: thin;
    }
    #chatMessage {
        min-height: 40px;
        max-height: 200px; 
        resize: none;
        overflow-y: auto;
    }
</style>
@section('content')
<div class="content container-fluid">

    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{asset('/public/assets/back-end/img/support-ticket.png')}}" alt="">
            Application Chat
        </h2>
    </div>

    <div class="row">
        @if(isset($applications) && $applications->count())
        <!-- LEFT APPLICATION LIST -->
        <div class="col-xl-3 col-lg-4 ChatSilderBarDiv pr-0">
            <div class="card card-body px-0  ChatSilderBar ">

                <div class="media align-items-center px-3 gap-3 mb-4">
                    <div class="avatar avatar-sm avatar-circle">
                        <img class="avatar-img"
                            src="{{asset('storage/app/public/admin/'.auth('admin')->user()->image)}}"
                            onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'">
                    </div>
                    <div class="media-body">
                        <h5 class="profile-name mb-1">{{ auth('admin')->user()->name }}</h5>
                        <span class="fz-12">{{ $admin?->role?->name }}</span>
                    </div>
                </div>

                <div class="px-3">
                    <input id="chatSearch" class="form-control"
                        placeholder="Search users..." />
                </div>

                <div class="inbox_chat mt-3">
                    @foreach($applications as $app)
                    <div class="chat_list p-3 app-item" data-app-id="{{ $app->application_id }}" data-user-id="{{ $app->user_id }}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar avatar-sm avatar-circle">
                                <img class="avatar-img"
                                    src="{{ asset('storage/app/public/profile/'.$app->image) }}"
                                    onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'">
                            </div>

                            <div>
                                <h6 class="mb-0">{{ $app->f_name.' '.$app->l_name }}</h6>
                                <small class="text-muted">App Ref: {{ $app->reference_number }}</small>
                            </div>
                        </div>

                        <div class="unread-dot bg-danger d-none" id="notif-{{ $app->application_id }}"></div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>

        <!-- RIGHT CHAT WINDOW -->
        <div class="col-xl-9 col-lg-8 mt-4 mt-lg-0">
            <div class="card card-body h-100">

                <div id="chatHeader" class="d-none border p-3 rounded mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar avatar-sm avatar-circle">
                            <img id="chatProfileImage" class="avatar-img"
                                onerror="this.src='{{ asset('public/assets/front-end/img/image-place-holder.png') }}'">
                            
                        </div>
                        <div>
                            <h5 id="chatProfileName" class="mb-1"></h5>
                            <small id="chatProfileRef"></small>
                            <i class="tio-delete delete-all"></i>
                        </div>
                    </div>
                </div>

                <div id="chatBody" class="msg_history d-flex flex-column-reverse" style="height:400px; overflow-y:auto; scrollbar-width: thin;"></div>

                <div id="chatInputBox" class="d-none mt-3">
                    <textarea id="chatMessage" class="form-control"
                            placeholder="Type a message..."
                            style="min-height:40px; max-height:200px; resize:none; overflow-y:auto;"></textarea>

                    <button id="sendBtn" class="btn btn-primary mt-2">Send</button>
                </div>


            </div>
        </div>

        @else
        <p>No scheduled applications found</p>
        @endif
    </div>
</div>
@endsection

@push('script')
<script>
    let activeApp = "{{ $last_app->application_id ?? '' }}";

    // AUTO OPEN LAST APPLICATION CHAT ON PAGE LOAD
    $(document).ready(function() {
        if (activeApp) {
            $(".app-item[data-app-id='" + activeApp + "']").addClass("active");
            loadAppHeader(activeApp);
            loadChat();
            $("#chatHeader").removeClass("d-none");
            $("#chatInputBox").removeClass("d-none");
        }
    });

    $("#chatSearch").on("keyup", function () {
        let search = $(this).val();

        $.get("{{ route('admin.user-chat') }}?search=" + search, function (response) {
            $(".inbox_chat").html(response); 
        });
    });

    $("#chatMessage").on("keydown", function (e) {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault(); 
            $("#sendBtn").click();
        }
    });

    $("#chatMessage").on("input", function () {
        this.style.height = "40px";  
        this.style.height = (this.scrollHeight) + "px";
    });


    $(document).on("click", ".app-item", function() {
        activeApp = $(this).data("app-id");
        user_id = $(this).data("app-id");
        $(".app-item.active").removeClass("active");
        $(this).addClass("active");

        loadAppHeader(activeApp);
        $("#chatHeader").removeClass("d-none");
        $("#chatInputBox").removeClass("d-none");
        loadChat();
    });

    function loadAppHeader(appId) {
        let app = $(".app-item[data-app-id='" + appId + "']");
        $("#chatProfileImage").attr("src", app.find("img").attr("src"));
        $("#chatProfileName").text(app.find("h6").text());
        $("#chatProfileRef").text(app.find("small").text());
    }

    function loadChat() {
        $.get("{{ route('admin.ajax-user-chat') }}?application_id=" + activeApp, function(messages) {
            $("#chatBody").html("");

            if (!messages.length) {
                $("#chatBody").html(`
                <div class="text-center text-muted">
                    <h5>Start your conversation</h5>
                </div>
            `);
                return;
            }
            messages.forEach(msg => {
                let seenTick = msg.sent_by_admin && msg.seen ?
                    `<span class='text-info ms-2'> ✔✔ </span>` :
                    "";

                let bubble = msg.sent_by_admin ?
                    `<div class="text-end mb-2">
                        <span class="badge bg-primary p-2">${msg.message}  <i class="tio-delete delete-message" data-id="${msg.id}"></i></span>
                        <small class="text-muted d-block">${msg.created_at}${seenTick}</small>
                        <small class="profile-name mb-1">By ${msg.admin_name}</small> | <small class="fz-12">${msg.admin_role}</small>
                   </div>` :
                    `<div class="text-start mb-2">
                        <span class="badge bg-secondary p-2">${msg.message}</span>
                        <small class="text-muted d-block">${msg.created_at}</small>
                   </div>`;

                $("#chatBody").append(bubble);
            });

            $("#chatBody").scrollTop($("#chatBody")[0].scrollHeight);
        });
    }

    // SEND MESSAGE
    $("#sendBtn").click(function() {
        let message = $("#chatMessage").val();
        if (message.trim() === "") return;

        $.post("{{ route('admin.store-user-chat') }}", {
                application_id: activeApp,
                user_id: activeApp,
                message: message,
                _token: "{{ csrf_token() }}"
            },
            function() {
                $("#chatMessage").val("");
                loadChat();
            }
        );
    });

    $(document).on('click', '.delete-message', function() {
        let message_id = $(this).data('id')
        if(!confirm('Are you sure you want to delete this message?')) {
            return;
        }
        $.ajax({
            type: "GET",
            url: "{{ route('admin.delete-chat') }}/" + message_id,
            data: {
                '_token': "{{ csrf_token() }}",
                message_id: message_id
            },
            dataType: "json",
            success: function (response) {
                loadChat()
            }
        });
    })

    $(document).on('click', '.delete-all', function() {
        if(!confirm('Are you sure you want to delete this message?')) {
            return;
        }
        $.ajax({
            type: "GET",
            url: "{{ route('admin.delete-all') }}/" + activeApp,
            dataType: "json",
            success: function (response) {
                loadChat()
            }
        });
    })

    setInterval(() => {
        if (activeApp) loadChat();
    }, 5000);
</script>
@endpush