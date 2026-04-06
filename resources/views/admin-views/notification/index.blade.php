@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Add new notification'))

@section('content')

<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-bell"></i>
            </span> {{\App\CPU\translate('push_notification')}}
        </h3>

        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    {{\App\CPU\translate('push_notification')}}
                    <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">

        <!-- Notification Form -->
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h4>{{\App\CPU\translate('add_new_notification')}}</h4>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.notification.store')}}" method="post"
                        enctype="multipart/form-data"
                        style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">

                        @csrf

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">{{\App\CPU\translate('Title')}} <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control"
                                    placeholder="{{\App\CPU\translate('New notification')}}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">User Type <span class="text-danger">*</span></label>
                                <select name="user_type" class="form-control form-select" required>
                                    <option value="">--- Select User Type ---</option>
                                    <option value="sale">Sale</option>
                                    <option value="user">User</option>
                                    <option value="brand">Brand</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{\App\CPU\translate('Image')}}</label>
                                <input type="file" name="image" id="customFileEg1"
                                    class="form-control"
                                    accept=".jpg,.png,.jpeg,.gif,.bmp,.tif,.tiff|image/*">

                                <img id="viewer"
                                    src="{{asset('public/assets/admin/img/900x400/img1.jpg')}}"
                                    class="img-thumbnail mt-2"
                                    style="max-width:200px;">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{\App\CPU\translate('Description')}} <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control" rows="4" required></textarea>
                            </div>

                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12 d-flex justify-content-end gap-3">
                                <button type="reset" class="btn btn-secondary px-4">
                                    {{\App\CPU\translate('reset')}}
                                </button>

                                <button type="submit" class="btn btn-primary px-4">
                                    {{\App\CPU\translate('Send')}} {{\App\CPU\translate('Notification')}}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>



            </div>
        </div>


        <!-- Notification Table -->
        <div class="col-lg-12">
            <div class="card">

                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <h5 class="mb-0 text-capitalize d-flex gap-2">
                                {{ \App\CPU\translate('push_notification_table')}}
                                <span class="badge badge-soft-dark radius-50 fz-12">
                                    {{$notifications->total()}}
                                </span>
                            </h5>
                        </div>

                        <div class="col-md-6 text-end">
                            <form action="{{ url()->current() }}" method="GET">
                                <div class="input-group input-group-merge input-group-custom">
                                    <!-- <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="tio-search"></i>
                                        </div>
                                    </div> -->
                                    <input type="search" name="search" class="form-control"
                                        placeholder="{{\App\CPU\translate('Search by Title')}}"
                                        value="{{$search}}" required>

                                    <button type="submit" class="btn btn-primary">
                                        {{\App\CPU\translate('search')}}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="table-responsive">
                    <table
                        
                        class="table">

                        <thead class="text-capitalize">
                            <tr>
                                <th>{{\App\CPU\translate('SL')}}</th>
                                <th>{{\App\CPU\translate('Title')}}</th>
                                <th>{{\App\CPU\translate('Description')}}</th>
                                <th>{{\App\CPU\translate('Image')}}</th>
                                <th>{{\App\CPU\translate('notification_count')}}</th>
                                <th>{{\App\CPU\translate('Status')}}</th>
                                <th>{{\App\CPU\translate('Resend')}}</th>
                                <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($notifications as $key=>$notification)
                            <tr>

                                <td>{{$notifications->firstItem()+$key}}</td>

                                <td>
                                    {{\Illuminate\Support\Str::limit($notification['title'],30)}}
                                </td>

                                <td>
                                    {{\Illuminate\Support\Str::limit($notification['description'],40)}}
                                </td>

                                <td>
                                    <img width="75" height="75"
                                        src="{{asset('storage/app/public/notification')}}/{{$notification['image']}}">
                                </td>

                                <td id="count-{{$notification->id}}">
                                    {{$notification['notification_count']}}
                                </td>

                                <td>
                                    <label class="switcher">
                                        <input type="checkbox"
                                            class="status switcher_input"
                                            id="{{$notification['id']}}"
                                            {{$notification->status == 1?'checked':''}}>
                                        <span class="switcher_control"></span>
                                    </label>
                                </td>

                                <td>
                                    <a href="javascript:void(0)"
                                        class="btn btn-gradient-success btn-sm"
                                        onclick="resendNotification(this)"
                                        data-id="{{$notification->id}}">
                                        Resend
                                    </a>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">

                                        <a class="btn btn-outline-info btn-sm square-btn"
                                            href="{{route('admin.notification.edit',[$notification['id']])}}">
                                            Edit
                                        </a>

                                        <a class="btn btn-outline-danger btn-sm delete"
                                            href="javascript:"
                                            id="{{$notification['id']}}">
                                            Delete
                                        </a>

                                    </div>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>


                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-lg-end">
                        {{$notifications->links()}}
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>
@endsection