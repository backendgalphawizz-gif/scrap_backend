@extends('layouts.back-end.app')
@section('title', 'Training List')

@push('css_or_js')
<link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="content container-fluid">

    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-baseline gap-2 backbtndiv w-100">
            <a class="textfont-set" href="{{route('admin.dashboard.index')}}">
                <i class="tio-chevron-left"></i> Back
            </a>
            Training List
        </h2>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header flex-wrap gap-10">

                    <h5 class="mb-0 d-flex gap-2 align-items-center">
                        Training Table
                        <span class="badge badge-soft-dark radius-50 fz-12">{{$trainings->total()}}</span>
                    </h5>

                    <div>
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="input-group input-group-merge input-group-custom">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input type="search" name="search" class="form-control"
                                       placeholder="Search by title"
                                       value="{{$search}}">
                                <button type="submit" class="btn btn--primary">Search</button>
                            </div>
                        </form>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{route('admin.training.add-training')}}" class="btn btn--primary">
                            <i class="tio-add"></i>
                            <span class="text">Add New</span>
                        </a>
                    </div>

                </div>

               <div class="table-responsive">
                    <table class="table table-hover table-borderless table-thead-bordered w-100">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>SL</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Scheme</th>
                            <th>Area</th>
                            <th>Training For</th>
                            <!-- <th>Scope</th> -->
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($trainings as $k=>$t)
                            <tr>
                                <td>{{$k + 1}}</td>

                                <td>
                                    @if($t->image)
                                        <img src="{{ asset($t->image) }}" 
                                            alt="image" 
                                            width="60" 
                                            height="60" 
                                            style="object-fit: cover; border-radius:6px;">
                                    @else
                                        <span class="badge badge-soft-danger">No Image</span>
                                    @endif
                                </td>

                                <td>{{$t->title}}</td>

                                <td>{{$t->scheme->title ?? '-'}}</td>

                                <td>{{$t->area->title ?? '-'}}</td>
                                <td>
                                    @if($t->type === 'all')
                                        <span class="badge badge-success">All Assessors</span>
                                    @elseif($t->type === 'specific')
                                        <span class="badge badge-info">Specific Assessors</span>
                                    @else
                                        <span class="badge badge-secondary">-</span>
                                    @endif
                                </td>


                                <!-- <td>{{$t->scopeData->title ?? '-'}}</td> -->

                                <td>
                                    <label class="switcher">
                                        <input type="checkbox"
                                            class="switcher_input training-status"
                                            data-id="{{$t->id}}"
                                            {{$t->status ? 'checked' : ''}}>
                                        <span class="switcher_control"></span>
                                    </label>
                                </td>

                               <td>
                                    <div class="d-flex justify-content-center gap-2">
                                         <!-- <a href="{{ route('admin.training.questions-list', $t->id) }}"
                                        class="btn btn-outline-warning btn-sm square-btn">
                                            <i class="tio-book"></i>
                                        </a> -->

                                        <a href="{{ route('admin.training.training-view', $t->id) }}"
                                        class="btn btn-outline-info btn-sm square-btn">
                                            <i class="tio-visible"></i>
                                        </a>

                                        <a href="{{ route('admin.training.training-edit', $t->id) }}"
                                        class="btn btn-outline--primary btn-sm square-btn">
                                            <i class="tio-edit"></i>
                                        </a>

                                        <button class="btn btn-outline-danger btn-sm square-btn delete-training"
                                                data-id="{{$t->id}}">
                                            <i class="tio-delete"></i>
                                        </button>

                                    </div>
                                </td>


                            </tr>
                        @endforeach
                        </tbody>

                    </table>
              </div>



                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-lg-end">
                        {{$trainings->links()}}
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection
@push('script')
<script>

$(document).on('change', '.training-status', function () {

    let checkbox = $(this);
    let id = checkbox.data("id");
    let status = checkbox.is(":checked") ? 1 : 0;

    Swal.fire({
        title: 'Are you sure?',
        text: "Want to change status?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {

        if (result.value) {

            $.post("{{ route('admin.training.status-update') }}", {
                id: id,
                status: status,
                _token: '{{ csrf_token() }}'
            },
            function () {
                toastr.success('Status updated successfully');
            });

        } else {
            checkbox.prop('checked', !status); 
        }

    });
});

$(document).on('click', '.delete-training', function () {

    let id = $(this).data("id");

    Swal.fire({
        title: 'Are you sure?',
        text: "This will delete training & all related files",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {

        if (result.value) {

            $.post("{{ route('admin.training.delete') }}", {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            function () {
                toastr.success('Training deleted successfully');
                location.reload();
            });

        }

    });

});
</script>
@endpush('script')
