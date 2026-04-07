@foreach($applications as $app)
<div class="chat_list p-3 app-item" 
     data-app-id="{{ $app->application_id }}" 
     data-user-id="{{ $app->user_id }}">
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
</div>
@endforeach
