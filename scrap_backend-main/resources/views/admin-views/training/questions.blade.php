@extends('layouts.back-end.app')
@section('title', 'Training Questions')

@section('content')
<div class="content container-fluid">

    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-baseline gap-2 backbtndiv">
            <a class="textfont-set" href="{{ route('admin.training.list-training') }}">
                <i class="tio-chevron-left"></i> Back
            </a>
            Questions for: {{ $training->title }}
        </h2>
    </div>

    <!-- =====================================================
         ✅ ADD QUESTION FORM
    ====================================================== -->
    <div class="card">
        <div class="card-body">

            <h5 class="mb-3 page-header-title border-bottom pb-3">
                <i class="tio-add"></i> Add New Question
            </h5>

            <form action="{{ route('admin.training.questions-store') }}" method="POST">
                @csrf
                <input type="hidden" name="training_id" value="{{ $training->id }}">

                <div class="row">

                    <div class="col-md-12 mb-3">
                        <label for="question" class="title-color">Question</label>
                        <textarea id="question" name="question" class="form-control" required></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="option_a" class="title-color">Option A</label>
                        <input id="option_a" type="text" name="option_a" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="option_b" class="title-color">Option B</label>
                        <input id="option_b" type="text" name="option_b" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="option_c" class="title-color">Option C</label>
                        <input id="option_c" type="text" name="option_c" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="option_d" class="title-color">Option D</label>
                        <input id="option_d" type="text" name="option_d" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="correct_answer" class="title-color">Correct Answer</label>
                        <select id="correct_answer" name="correct_answer" class="form-control" required>
                            <option value="a">A</option>
                            <option value="b">B</option>
                            <option value="c">C</option>
                            <option value="d">D</option>
                        </select>
                    </div>

                </div>

                <button type="submit" class="btn btn--primary px-4 mt-2">Add Question</button>

            </form>
        </div>
    </div>



    <!-- =====================================================
         ✅ QUESTIONS LIST
    ====================================================== -->
    <div class="card mt-4">
        <div class="card-body">

            <h5 class="mb-3 page-header-title border-bottom pb-3">
                <i class="tio-list"></i> Questions List
            </h5>

            <div class="table-responsive">
                <table class="table table-hover table-thead-bordered table-borderless w-100">

                    <thead class="thead-light">
                        <tr>
                            <th>SL</th>
                            <th>Question</th>
                            <th>Correct</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($questions as $k => $q)
                            <tr>
                                <td>{{ $k + 1 }}</td>

                                <td>{{ $q->question }}</td>

                                <td>
                                    <span class="badge badge-success">
                                        {{ strtoupper($q->correct_answer) }}
                                    </span>
                                </td>

                                <td class="text-center d-flex justify-content-center gap-2">

                                    <!-- EDIT -->
                                    <a href="{{ route('admin.training.questions-edit', $q->id) }}"
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="tio-edit"></i>
                                    </a>

                                    <!-- DELETE -->
                                   <button class="btn btn-outline-danger btn-sm square-btn delete-question"
                                            data-id="{{$q->id}}">
                                        <i class="tio-delete"></i>
                                    </button>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>
@endsection



@push('script')
<script>
$(document).on('click', '.delete-question', function () {

    let id = $(this).data("id");

    Swal.fire({
        title: 'Are you sure?',
        text: "Delete this question?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {

        if (result.value) {

            $.post("{{ route('admin.training.questions-delete') }}", {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            function () {
                toastr.success(' deleted successfully');
                location.reload();
            });

        }

    });

});

</script>
@endpush
