<div id="tab-details" class="tab-section">
    
    <!-- ================= GENERAL INFORMATION ================= -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">General Information</h5>
        </div>
        <div class="card-body">

            <div class="row">

                <!-- Title -->
                <div class="col-md-6">
                    <h6 class="text-muted">Title:</h6>
                    <p>{{ $training->title }}</p>
                </div>

                <!-- Image -->
                <div class="col-md-6">
                    <h6 class="text-muted">Image:</h6>
                    @if($training->image)
                        <img src="{{ asset($training->image) }}"
                             style="width:150px;height:150px;object-fit:cover;border-radius:5px;">
                    @else
                        <p>No Image</p>
                    @endif
                </div>

                <!-- Description -->
                <div class="col-md-12 mt-3">
                    <h6 class="text-muted">Description:</h6>
                    <div>{!! $training->description !!}</div>
                </div>

            </div>

        </div>
    </div>

    <!-- ================= CATEGORY INFORMATION ================= -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">Categorization</h5>
        </div>
        <div class="card-body">

            <div class="row">

                <!-- Scheme -->
                <div class="col-md-4">
                    <h6 class="text-muted">Scheme:</h6>
                    <p>{{ $training->scheme->title ?? '-' }}</p>
                </div>

                <!-- Area -->
                <div class="col-md-4">
                    <h6 class="text-muted">Area:</h6>
                    <p>{{ $training->area->title ?? '-' }}</p>
                </div>

                <!-- Scope -->
                <div class="col-md-4">
                    <h6 class="text-muted">Scope:</h6>
                    <p>{{ $training->scopeData->title ?? '-' }}</p>
                </div>

            </div>

        </div>
    </div>

    <!-- ================= UPLOADED FILES ================= -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">Uploaded Files</h5>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Type</th>
                        <th>Preview</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($training->files as $k => $file)
                    <tr>
                        <td>{{ $k+1 }}</td>
                        <td>{{ ucfirst($file->file_type) }}</td>

                        <td>
                            @if($file->file_type == 'pdf')
                                <a href="{{ asset($file->file_path) }}" target="_blank"
                                   class="btn btn-info btn-sm">View PDF</a>
                            @else
                                <a href="{{ asset($file->file_path) }}" target="_blank"
                                   class="btn btn-primary btn-sm">View Video</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>

</div>
