@extends('layouts.back-end.app')
@section('title', 'Edit Training')

@push('css_or_js')
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .select2-container--default .select2-selection--multiple {
            background-color: #fff !important;
            border: 1px solid #ced4da !important;
            min-height: 40px !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #e9ecef !important;
            border: 1px solid #ced4da !important;
            color: #000 !important;
            padding: 4px 8px !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #000 !important;
            margin-right: 5px !important;
        }
    </style>

@endpush

@section('content')
    <div class="content container-fluid">

        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-baseline gap-2 backbtndiv">
                <a class="textfont-set" href="{{ route('admin.training.list-training') }}">
                    <i class="tio-chevron-left"></i> Back
                </a>
                Edit Training
            </h2>
        </div>

        <form action="{{ route('admin.training.training-update', $training->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <!-- ================= GENERAL ================= -->
            <div class="card">
                <div class="card-body">

                    <h5 class="mb-3 page-header-title border-bottom pb-3">
                        <i class="tio-user"></i> General Information
                    </h5>

                    <div class="row">

                        <div class="col-md-6">
                            <label class="title-color">Training Title</label>
                            <input type="text" class="form-control" name="title" value="{{ $training->title }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="title-color">Training Image</label>
                            <input type="file" name="image" id="imageInput" class="form-control" accept="image/*">

                            <div class="mt-2">
                                <img id="previewImage" src="{{ asset($training->image) }}"
                                    style="width:120px;height:120px;object-fit:cover;border:1px solid #ccc;">
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="title-color">Description</label>
                            <textarea name="description" id="descriptionEditor" class="form-control" required>
                                    {!! $training->description !!}
                                </textarea>
                        </div>

                    </div>

                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">

                    <h5 class="mb-3 page-header-title border-bottom pb-3">
                        <i class="tio-user"></i> Training Access Control
                    </h5>

                    <div class="row">

                        <!-- Training Type -->
                        <div class="col-md-4 mb-3">
                            <label class="title-color">Training For</label>
                            <select name="type" id="training_for" class="form-control">
                                <option value="all" {{ $training->type == 'all' ? 'selected' : '' }}>All Assessors</option>
                                <option value="specific" {{ $training->type == 'specific' ? 'selected' : '' }}>Specific
                                    Assessors</option>
                            </select>
                        </div>

                        <!-- Select Assessors -->
                        <div class="col-md-8 mb-3" id="assessor_block"
                            style="display: {{ $training->type == 'specific' ? 'block' : 'none' }};">

                            <label class="title-color">Select Assessors</label>
                            <select name="assessor_ids[]" id="assessor_ids" class="form-control js-select2-custom" multiple>
                                @foreach($assessors as $as)
                                    @if($as->admin)
                                        <option value="{{ $as->assessor_id }}" {{ in_array($as->assessor_id, $selectedAssessors) ? 'selected' : '' }}>
                                            {{ $as->admin->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>

                        </div>

                    </div>

                </div>
            </div>

            <!-- ================= CATEGORIZATION ================= -->
            <div class="card mt-3">
                <div class="card-body">

                    <h5 class="mb-3 page-header-title border-bottom pb-3">
                        <i class="tio-user"></i> Categorization
                    </h5>

                    <div class="row">

                        <div class="col-md-4">
                            <label>Scheme <span class="optionalText"></span></label>
                            <select name="scheme_id" id="scheme_id" class="form-control schemeAttr" required>
                                @foreach($schemes as $scheme)
                                    <option value="{{ $scheme->id }}" {{ $training->scheme_id == $scheme->id ? 'selected' : '' }}>
                                        {{ $scheme->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Area <span class="optionalText"></span></label>
                            <select name="area_id" id="area_id" class="form-control schemeAttr" required>
                                <option value="">Loading...</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Scope <span class="optionalText"></span></label>
                            <select name="scope_id" id="scope_id" class="form-control">
                                <option value="">Loading...</option>
                            </select>
                        </div>

                    </div>

                </div>
            </div>
            




            <!-- ================= FILES SECTION ================= -->
            <div class="card mt-3">
                <div class="card-body">

                    <h5 class="mb-3 page-header-title border-bottom pb-3">
                        <i class="tio-user"></i> Upload Files (Video / PDF)
                    </h5>

                    <div id="deleted-files"></div>

                    <div id="section-wrapper">
                        @foreach($training->files as $file)
                            <div class="row section-row mb-3">

                                <input type="hidden" name="existing_file_id[]" class="existing-id" value="{{ $file->id }}">

                                <div class="col-md-3">
                                    <label>Select Type</label>
                                    <select name="section_type[]" class="form-control section-type">
                                        <option value="video" {{ $file->file_type == 'video' ? 'selected' : '' }}>Video</option>
                                        <option value="pdf" {{ $file->file_type == 'pdf' ? 'selected' : '' }}>PDF</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label>Upload File</label>
                                    <input type="file" name="section_file[]" class="form-control section-file">
                                    <p class="text-info mt-1 mb-0">{{ $file->file_path }}</p>
                                </div>

                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger remove-section-row w-100">Remove</button>
                                </div>

                            </div>
                        @endforeach

                        @if(count($training->files) == 0)
                            <div class="row section-row mb-3">

                                <input type="hidden" name="existing_file_id[]" class="existing-id" value="">

                                <div class="col-md-3">
                                    <label>Select Type</label>
                                    <select name="section_type[]" class="form-control section-type">
                                        <option value="video">Video</option>
                                        <option value="pdf">PDF</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label>Upload File</label>
                                    <input type="file" name="section_file[]" class="form-control section-file" accept="video/*">
                                </div>

                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger remove-section-row w-100">Remove</button>
                                </div>

                            </div>
                        @endif

                    </div>

                    <button type="button" id="add-section-row" class="btn btn--primary px-4 mt-3">
                        + Add New Row
                    </button>

                </div>
            </div>


            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn--primary px-4">Update</button>
            </div>

        </form>

    </div>
@endsection


@push('script')

    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>

    <script>
        (function ($) {

            $(document).ready(function () {

                CKEDITOR.replace('descriptionEditor', { height: 200 });

                $('#imageInput').change(function (e) {
                    const reader = new FileReader();
                    reader.onload = (ev) => $('#previewImage').attr('src', ev.target.result);
                    reader.readAsDataURL(e.target.files[0]);
                });

                function loadAreas(schemeId, selectedAreaId = null) {
                    if (!schemeId) return;
                    $.post("{{ route('admin.training.getAreas') }}", {
                        _token: "{{ csrf_token() }}",
                        scheme_id: schemeId
                    }, function (data) {
                        let html = '<option value="">-- Select Area --</option>';
                        data.forEach(a => {
                            html += `<option value="${a.id}">${a.title}</option>`;
                        });
                        $('#area_id').html(html);
                        if (selectedAreaId) $('#area_id').val(selectedAreaId).trigger('change');
                    });
                }

                function loadScopes(areaId, selectedScopeId = null) {
                    if (!areaId) return;
                    $.post("{{ route('admin.training.getScopes') }}", {
                        _token: "{{ csrf_token() }}",
                        area_id: areaId
                    }, function (data) {
                        let html = '<option value="">-- Select Scope --</option>';
                        data.forEach(s => {
                            html += `<option value="${s.id}">${s.title}</option>`;
                        });
                        $('#scope_id').html(html);
                        if (selectedScopeId) $('#scope_id').val(selectedScopeId);
                    });
                }


                $('#scheme_id').change(function () {
                    loadAreas($(this).val());
                    $('#scope_id').html('<option value="">-- Select Scope --</option>');
                });


                $('#area_id').change(function () {
                    loadScopes($(this).val());
                });


                loadAreas({{ $training->scheme_id ?? 'null' }}, {{ $training->area_id ?? 'null' }});
                setTimeout(() => {
                    loadScopes({{ $training->area_id ?? 'null' }}, {{ $training->scope_id ?? 'null' }});
                }, 500);

                function setAccept(row) {
                    const type = row.find('.section-type').val();
                    const input = row.find('.section-file');
                    input.attr('accept', type === 'video' ? 'video/*' : 'application/pdf');
                }

                $('.section-row').each(function () { setAccept($(this)); });

                $(document).on('change', '.section-type', function () {
                    setAccept($(this).closest('.section-row'));
                });


                $('#add-section-row').click(function () {
                    const row = `
                        <div class="row section-row mb-3">
                            <input type="hidden" name="existing_file_id[]" value="">
                            <div class="col-md-3">
                                <label>Select Type</label>
                                <select name="section_type[]" class="form-control section-type">
                                    <option value="video" selected>Video</option>
                                    <option value="pdf">PDF</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Upload File</label>
                                <input type="file" name="section_file[]" required class="form-control section-file" accept="video/*">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="button" class="btn btn-danger remove-section-row w-100">Remove</button>
                            </div>
                        </div>
                    `;
                    $('#section-wrapper').append(row);
                });


                $(document).on('click', '.remove-section-row', function () {

                    if ($('.section-row').length <= 1) {
                        toastr.error("At least one row is required.");
                        return;
                    }

                    const row = $(this).closest('.section-row');
                    const id = row.find('.existing-id').val();

                    if (id) {
                        $('#deleted-files').append(
                            `<input type="hidden" name="delete_file_ids[]" value="${id}">`
                        );
                    }

                    row.remove();
                    toastr.success(" removed successfully.");
                });

            });

        })(jQuery);
    </script>
    <script>
        $(document).ready(function () {

            $('#assessor_ids').select2({
                placeholder: "Search & select assessors",
                allowClear: true,
                width: "100%"
            });

       
            $("#training_for").change(function () {
                if ($(this).val() === "specific") {
                    $("#assessor_block").slideDown();
                    $(".optionalText").html('(Optional)');
                    $(".schemeAttr").prop('required', false);
                } else {
                    $("#assessor_block").slideUp();
                    $(".optionalText").html('');
                    $(".schemeAttr").prop('required', true);
                    $("#assessor_ids").val(null).trigger('change');
                }
            });

            $("#training_for").trigger('change');
        });
    </script>


@endpush