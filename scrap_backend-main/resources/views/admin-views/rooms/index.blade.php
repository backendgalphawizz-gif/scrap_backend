@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Chat'))

@section('content')
    <style>
        .delete-message {
            cursor: pointer;
            font-size: 14px;
            color: red;
        }
    </style>
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex gap-2 align-items-baseline backbtndiv w-100">
                <!-- <img width="20" src="{{asset('/public/assets/back-end/img/customer_review.png')}}" alt=""> -->
                <a class="textfont-set" href="{{route('admin.dashboard.index')}}"> 
                <i class="tio-chevron-left"></i>Back</a>
                {{\App\CPU\translate('Chat')}}
            </h2>
        </div>
        <div class="col-xl-9 col-lg-8 mt-4 mt-lg-0">
            <div class="card card-body">

                <div id="chatHeader" class="d-none border p-3 rounded mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar avatar-sm avatar-circle">
                            <img id="chatProfileImage" class="avatar-img"
                                onerror="this.src='{{ asset('public/assets/front-end/img/image-place-holder.png') }}'">

                        </div>
                        <div>
                            <h5 id="chatProfileName" class="mb-1"></h5>
                            <small id="chatProfileRef"></small>
                        </div>
                    </div>
                </div>

                <div id="chatBody" class="msg_history d-flex flex-column-reverse" style="height:400px; overflow-y:auto; scrollbar-width: thin;"></div>

                <div id="chatInputBox" class="mt-3">
                    <form action="#" method="post" id="form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="message_type" value="text">
                        <input type="hidden" name="room_id"> 
                        <textarea id="chatMessage" class="form-control" name="message" placeholder="Type a message..." style="min-height:40px; max-height:200px; resize:none; overflow-y:auto;"></textarea>
                        <button type="button" class="file-upload-attachment btn btn-primary btn-sm"><i class="tio-attachment"></i></button>
                        <input type="file" name="file" class="form-control d-none upload-file">
                        <button type="button" id="sendBtn" class="send-message btn btn-primary mt-2">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        let room_id = ""
        let room_type = "{{ $type }}"
        let application_id = "{{ $application_id }}"
        let logged_user_id = "{{ $user_id }}"
        let html = ''

        $(document).on('click', '.file-upload-attachment', function() {
            $('.upload-file').click()
        })

        $(document).on('click', '.send-message', function() {
            let message = $('[name=message]').val();
            let formData = new FormData($('#form-data')[0])
            let clearMsg = ""
            $.ajax({
                type: "POST",
                url: "{{ route('admin.application-chat.send-message') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (response) {
                    $('[name=message]').val(clearMsg)
                    loadMessages(response.user_room_messages)
                }
            });
        })

        function loadMessages(messages) {
            let bubble = ''
            messages.forEach(msg => {
                let seenTick = msg.is_read ? `<span class='text-info ms-2'> ✔✔ </span>` : "";
                let filePrev = ''
                if(msg.message.file != '' && msg.message.file != null) {
                    var arr = msg.message.file.split('.')
                    var ext = arr[arr.length - 1]
                    imageExt = ['png', 'jpg', 'jpeg'];

                    console.log('imageExt.includes(ext) -- ', imageExt.includes(ext), ext)

                    if(imageExt.includes(ext)) {
                        filePrev = `<img src="${msg.message.file_url}" width="100px" height="100px">`
                    } else {
                        filePrev = `<a href="${msg.message.file_url}" target="_blank">View File</a>`
                    }
                }

                bubble += msg.message.created_by == logged_user_id ?
                    `<div class="text-end mb-2">
                        
                        <span class="badge bg-primary p-2">${msg.message.message} <i class="tio-delete delete-message" data-id="${msg.message.id}"></i></span>
                        <p>${filePrev}</p>
                        <small class="text-muted d-block">${new Date(msg.created_at).toDateString()} ${seenTick}</small>
                   </div>` :
                    `<div class="text-start mb-2">
                        <span class="badge bg-secondary p-2">${msg.message.message}</span>
                        <p>${filePrev}</p>
                        <small class="text-muted d-block">${new Date(msg.created_at).toDateString()} </small>
                        <small class="profile-name mb-1">By ${msg.message.user.name}</small>
                   </div>`;
            });
            
            $("#chatBody").html(bubble);

            $("#chatBody").scrollTop($("#chatBody")[0].scrollHeight);

        }

        function loadHistory() {
            $.ajax({
                type: "POST",
                url: "{{ route('admin.application-chat.history') }}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    type: room_type,
                    application_id: application_id,
                    user_id: logged_user_id,
                },
                dataType: "json",
                success: function (response) {
                    room_id = response.room.id
                    $('input[name=room_id]').val(room_id)
                    loadMessages(response.user_room_messages)
                }
            });
        }

        $(document).ready(function() {
            setTimeout(() => {
                loadHistory()
            }, 1000)

            setInterval(() => {
                loadHistory()
            }, 10000)

        })

        $(document).on("keydown", "#chatMessage", function (e) {
            if (e.key === "Enter" && !e.shiftKey) {
                e.preventDefault(); 
                $(".send-message").click();
            }
        });

        $(document).on('click', '.delete-message', function() {
            let message_id = $(this).data('id')
            if(!confirm('Are you sure you want to delete this message?')) {
                return;
            }
            $.ajax({
                type: "POST",
                url: "{{ route('admin.application-chat.delete-message') }}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    message_id: message_id,
                },
                dataType: "json",
                success: function (response) {
                    loadHistory()
                }
            });
        })

    </script>
@endpush
