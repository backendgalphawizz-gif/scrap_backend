@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Update Notification'))

@push('css_or_js')
@endpush

@section('content')

<div class="content-wrapper">

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-bell"></i>
            </span> {{\App\CPU\translate('push_notification_update')}}
        </h3>

        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    {{\App\CPU\translate('push_notification_update')}}
                    <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">

        <div class="col-lg-12">
            <div class="card">

                <div class="card-header">
                    <h4>{{\App\CPU\translate('Update Notification')}}</h4>
                </div>

                <div class="card-body">

                    <form action="{{route('admin.notification.update',[$notification['id']])}}"
                        method="post"
                        enctype="multipart/form-data"
                        style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">

                        @csrf

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">
                                    {{\App\CPU\translate('Title')}} <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                    value="{{$notification['title']}}"
                                    name="title"
                                    class="form-control"
                                    placeholder="{{\App\CPU\translate('New notification')}}"
                                    required>
                            </div>


                            <div class="col-md-6">
                                <label class="form-label">
                                    {{\App\CPU\translate('Description')}} <span class="text-danger">*</span>
                                </label>

                                <textarea name="description"
                                    class="form-control"
                                    rows="4"
                                    required>{{$notification['description']}}</textarea>
                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    {{\App\CPU\translate('Image')}}
                                </label>

                                <input type="file"
                                    name="image"
                                    id="customFileEg1"
                                    class="form-control"
                                    accept=".jpg,.png,.jpeg,.gif,.bmp,.tif,.tiff|image/*">

                                <img id="viewer"
                                    src="{{asset('storage/app/public/notification')}}/{{$notification['image']}}"
                                    onerror="this.src='https://demofree.sirv.com/nope-not-here.jpg'"
                                    class="img-thumbnail mt-2"
                                    style="max-width:200px;">

                            </div>

                        </div>


                        <div class="row mt-3">

                            <div class="col-md-12 d-flex justify-content-end gap-3">

                                <button type="reset"
                                    class="btn btn-secondary px-4">
                                    {{\App\CPU\translate('reset')}}
                                </button>

                                <button type="submit"
                                    class="btn btn-primary px-4">
                                    {{\App\CPU\translate('Update')}}
                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>
        </div>

    </div>

</div>

@endsection


@push('script_2')
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {

            var reader = new FileReader();

            reader.onload = function(e) {
                $('#viewer').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#customFileEg1").change(function() {
        readURL(this);
    });
</script>
@endpush