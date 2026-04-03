<div class="content container-fluid">

    <!-- PAGE TITLE + SEARCH -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h1 mb-0">Training Questions</h2>

          <form method="GET" action="{{ url()->current() }}" class="d-flex">
            <input type="hidden" name="tab" value="questions">

            <input type="text" name="search" class="form-control"
                   placeholder="Search question or options..."
                   value="{{ $search ?? '' }}" >

            <button class="btn btn--primary ml-2">Search</button>
        </form>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            

            <button class="btn btn--primary btn-sm" data-toggle="modal" data-target="#addQuestionModal">
                <i class="tio-add"></i> Add Question
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th style="width:60px;">SL</th>
                        <th>Question</th>
                        <th style="width:120px;">Correct</th>
                        <th style="width:140px;" class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody id="questionList">
                    @foreach($questions as $k => $q)
                    <tr id="row-{{ $q->id }}">
                        <td>{{ $questions->firstItem() + $k }}</td>
                        <td>{{ $q->question }}</td>
                        <td><span class="badge badge-success">{{ strtoupper($q->correct_answer) }}</span></td>
                        <td class="text-center">
                            <a href="{{ route('admin.training.questions-edit', $q->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="tio-edit"></i>
                            </a>
                            <button class="btn btn-outline-danger btn-sm delete-question" data-id="{{ $q->id }}">
                                <i class="tio-delete"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>


            </table>
            <div class="card-footer">
                <div class="d-flex justify-content-end">
                    {!! $questions->links() !!}
                </div>
            </div>

        </div>
    </div>

</div>

<div class="modal fade" id="addQuestionModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title"  style="color:#fff">
                    <i class="tio-add"></i> Add New Question
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <!-- BODY -->
            <div class="modal-body bg-light">
                <form id="addQuestionForm">
                    @csrf
                    <input type="hidden" name="training_id" value="{{ $training->id }}">

                    <div class="form-group">
                        <label class="title-color font-weight-bold">Question</label>
                        <textarea name="question" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="row">
                        @foreach(['a','b','c','d'] as $opt)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="title-color">Option {{ strtoupper($opt) }}</label>
                                <input type="text" name="option_{{ $opt }}" class="form-control" required>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="form-group">
                        <label class="title-color font-weight-bold">Correct Answer</label>
                        <select name="correct_answer" class="form-control" required>
                            <option value="option_a">Option A</option>
                            <option value="option_b">Option B</option>
                            <option value="option_c">Option C</option>
                            <option value="option_d">Option D</option>
                        </select>
                    </div>

                </form>
            </div>

            <!-- FOOTER -->
            <div class="modal-footer border-0">
                <button class="btn btn-secondary" data-dismiss="modal">
                    <i class="tio-clear"></i> Cancel
                </button>

                <button class="btn btn--primary px-4" id="saveQuestionBtn">
                    <i class="tio-save"></i> Save Question
                </button>
            </div>

        </div>
    </div>
</div>


@push('script')
<script>

$("#saveQuestionBtn").click(function () {

    let formData = $("#addQuestionForm").serialize();

    $.ajax({
        url: "{{ route('admin.training.questions-store') }}",
        method: "POST",
        data: formData,

        success: function (res) {
            $("#addQuestionModal").modal("hide");
            $("#addQuestionForm")[0].reset();
            toastr.success("Question added successfully");

           $("#questionList").load(location.href + " #questionList > *");
           $(".card-footer").load(location.href + " .card-footer > *");

        },

        error: function () {
            toastr.error("Error adding question");
        }
    });
});


// DELETE QUESTION
$(document).on('click', '.delete-question', function () {

    let id = $(this).data("id");

    Swal.fire({
        title: 'Are you sure?',
        text: "Delete this question?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
    }).then((result) => {

        if (result.value) {

            $.post("{{ route('admin.training.questions-delete') }}",
                { id: id, _token: "{{ csrf_token() }}" },

                function () {
                    toastr.success("Deleted successfully");
                    $("#row-" + id).fadeOut(300, function () {
                        $(this).remove();
                    });
                }
            );

        }

    });
});

</script>
@endpush
