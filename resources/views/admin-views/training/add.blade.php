@extends('layouts.back-end.app')

@section('title', 'Add Training')

@push('css_or_js')
    <link href="{{ asset('public/assets/back-end/css/select2.min.css') }}" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3 customBtnDiv backbtndiv">
            <a href="{{ route('admin.training.list-training') }}">
                <button class="btn btn--primary px-4">
                    <i class="tio-chevron-left"></i> Back
                </button>
            </a>

            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{ asset('public/assets/back-end/img/add-new-employee.png') }}" alt="">
                Add Training
            </h2>
        </div>

        <form action="{{ route('admin.training.store-training') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">

                    <h5 class="mb-0 page-header-title d-flex align-items-center gap-2 border-bottom pb-3 mb-3">
                        <i class="tio-user"></i>
                        General Information
                    </h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="title-color">Training Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Enter Training Title"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="title-color">Upload Image</label>
                                <div class="custom-file text-left">
                                    <input type="file" name="image" id="imageUpload" class="custom-file-input"
                                        accept="image/*">
                                    <label class="custom-file-label">Choose file</label>
                                </div>

                                <div class="text-center mt-2">
                                    <img class="upload-img-view" id="imageViewer"
                                        src="{{ asset('public/assets/back-end/img/400x400/img2.jpg') }}"
                                        alt="Training thumbnail" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="form-group">
                                <label class="title-color">Description</label>
                                <textarea name="description" id="descriptionEditor" class="form-control" rows="5"
                                    placeholder="Enter training description" required></textarea>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">

                    <h5 class="mb-0 page-header-title d-flex align-items-center gap-2 border-bottom pb-3 mb-3">
                        <i class="tio-user"></i>
                        Training Access Control
                    </h5>

                    <div class="row">

                        <div class="col-md-4">
                            <label class="title-color">Training For</label>
                            <select name="training_for" id="training_for" class="form-control">
                                <option value="all">All Assessors</option>
                                <option value="specific">Specific Assessors</option>
                            </select>
                        </div>

                        <div class="col-md-8" id="assessor_block" style="display:none;">
                            <label class="title-color">Select Assessors</label>
                            <select name="assessor_ids[]" id="assessor_ids" class="form-control js-select2-custom" multiple>
                                @foreach($assessors as $assessor)
                                    @if($assessor->admin)
                                        <option value="{{ $assessor->assessor_id }}">
                                            {{ $assessor->admin->name }} 
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                    </div>

                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">

                    <h5 class="mb-0 page-header-title d-flex align-items-center gap-2 border-bottom pb-3 mb-3">
                        <i class="tio-user"></i>
                        Categorization
                    </h5>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="title-color">Scheme <span class="optionalText"></span></label>
                                <select name="scheme_id" id="scheme_id" class="form-control schemeAttr" required>
                                    <option value="">-- Select Scheme --</option>
                                    @foreach($schemes as $scheme)
                                        <option value="{{ $scheme->id }}">{{ $scheme->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="title-color">Area <span class="optionalText"></span></label>
                                <select name="area_id" id="area_id" class="form-control schemeAttr" required>
                                    <option value="">-- Select Area --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="title-color">Scope <span class="optionalText"></span></label>
                                <select name="scope_id" id="scope_id" class="form-control">
                                    <option value="">-- Select Scope --</option>
                                </select>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
            

            <div class="card mt-3">
                <div class="card-body">

                    <h5 class="mb-0 page-header-title d-flex align-items-center gap-2 border-bottom pb-3 mb-3">
                        <i class="tio-user"></i>
                        Upload File (Video / PDF)
                    </h5>

                    <div id="section-wrapper">

                        <!-- Default First Row -->
                        <div class="row section-row mb-3">

                            <div class="col-md-3">
                                <label class="title-color">Select Type</label>
                                <select name="section_type[]" class="form-control section-type">
                                    <option value="video">Video</option>
                                    <option value="pdf">PDF</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="title-color">Upload File</label>
                                <div class="custom-file text-left">
                                    <input type="file" name="section_file[]" class="custom-file-input section-file"
                                        accept="video/*" required>
                                    <label class="custom-file-label">Choose file</label>
                                </div>
                            </div>

                            <div class="col-md-3 d-flex align-items-end">
                                <button type="button" class="btn btn-danger remove-section-row w-100">
                                    Remove
                                </button>
                            </div>

                        </div>

                    </div>

                    <button type="button" id="add-section-row" class="btn btn--primary px-4 mt-3">
                        + Add New Row
                    </button>

                </div>

            </div>
            <div class="d-flex justify-content-end gap-3 mt-4">
                <button type="reset" class="btn btn-secondary px-4">Reset</button>
                <button type="submit" class="btn btn--primary px-4">Submit</button>
            </div>

        </form>

    </div>
@endsection

@push('script')

    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('descriptionEditor', { height: 200 });
    </script>
    <script>
        document.getElementById('imageUpload').addEventListener('change', function (event) {
            let reader = new FileReader();
            reader.onload = function () {
                document.getElementById('imageViewer').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#scheme_id').on('change', function () {
                let schemeId = $(this).val();
                $('#area_id').html('<option value="">Loading...</option>');
                $('#scope_id').html('<option value="">-- Select Scope --</option>');

                $.ajax({
                    url: "{{ route('admin.training.getAreas') }}",
                    type: "POST",
                    data: {
                        scheme_id: schemeId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (data) {
                        $('#area_id').html('<option value="">-- Select Area --</option>');
                        $.each(data, function (key, area) {
                            $('#area_id').append('<option value="' + area.id + '">' + area.title + '</option>');
                        });
                    }
                });
            });
            $('#area_id').on('change', function () {
                let areaId = $(this).val();
                $('#scope_id').html('<option value="">Loading...</option>');

                $.ajax({
                    url: "{{ route('admin.training.getScopes') }}",
                    type: "POST",
                    data: {
                        area_id: areaId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (data) {
                        $('#scope_id').html('<option value="">-- Select Scope --</option>');
                        $.each(data, function (key, scope) {
                            $('#scope_id').append('<option value="' + scope.id + '">' + scope.title + '</option>');
                        });
                    }
                });
            });

        });
    </script>
    <script>
        $(document).ready(function () {
            function refreshFileLabel(input) {
                let fileName = $(input).val().split('\\').pop();
                $(input).siblings('.custom-file-label').text(fileName);
            }

            function updateAcceptType(row) {
                let selectedType = row.find('.section-type').val();
                let fileInput = row.find('.section-file');

                if (selectedType === "video") {
                    fileInput.attr("accept", "video/*");
                } else if (selectedType === "pdf") {
                    fileInput.attr("accept", "application/pdf");
                }
            }
            $('#add-section-row').click(function () {

                let newRow = `
                <div class="row section-row mb-3">

                    <div class="col-md-3">
                        <label class="title-color">Select Type</label>
                        <select name="section_type[]" class="form-control section-type">
                            <option value="video">Video</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="title-color">Upload File</label>
                        <div class="custom-file text-left">
                            <input type="file" name="section_file[]" class="custom-file-input section-file" required accept="video/*">
                            <label class="custom-file-label">Choose file</label>
                        </div>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-section-row w-100">Remove</button>
                    </div>

                </div>`;

                $('#section-wrapper').append(newRow);
                let appendedRow = $('#section-wrapper .section-row').last();
                updateAcceptType(appendedRow);
            });

            $(document).on('change', '.section-type', function () {

                let row = $(this).closest('.section-row');
                let fileInput = row.find('.section-file');

                updateAcceptType(row);
                fileInput.val('');
                fileInput.siblings('.custom-file-label').text('Choose file');
            });

            $(document).on('change', '.section-file', function () {

                let row = $(this).closest('.section-row');
                let selectedType = row.find('.section-type').val();
                let file = this.files[0];

                if (file) {
                    let fileType = file.type;

                    if (selectedType === "video" && !fileType.startsWith("video/")) {
                        toastr.error("Please upload a valid video file.");
                        $(this).val('');
                        $(this).siblings('.custom-file-label').text('Choose file');
                        return;
                    }
                    if (selectedType === "pdf" && fileType !== "application/pdf") {
                        toastr.error("Please upload a valid PDF file.");
                        $(this).val('');
                        $(this).siblings('.custom-file-label').text('Choose file');
                        return;
                    }
                    refreshFileLabel(this);
                }
            });
            $(document).on('click', '.remove-section-row', function () {

                let totalRows = $('#section-wrapper .section-row').length;

                if (totalRows <= 1) {
                    toastr.error("You must keep at least one row.");
                    return;
                }

                let rowIndex = $(this).closest('.section-row').index() + 1;

                $(this).closest('.section-row').remove();

                toastr.success(" Removed successfully.");
            });


        });

        
    </script>

<script>
    $(document).ready(function () {

        $("#training_for").change(function () {
            if ($(this).val() === "specific") {
                $("#assessor_block").show();
                $(".optionalText").html('(Optional)');
                $(".schemeAttr").prop('required', false);
            } else {
                $("#assessor_block").hide();
                $(".optionalText").html('');
                $(".schemeAttr").prop('required', true);
                $("#assessor_ids").val(null).trigger('change');
            }
        });

    });
</script>


@endpush