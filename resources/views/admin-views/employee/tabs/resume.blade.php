<div class="card">
    <div class="card-body">


        <h5 class="mb-3 border-bottom pb-2"><i class="tio-user"></i> Personal Information</h5>
        <table class="table table-bordered table-striped">
            <tr>
                <th width="30%">ID Number</th>
                <td>{{ $assessor->id_number }}</td>
            </tr>
            <tr>
                <th>Applied Designation</th>
                <td>{{ $assessor->apply_designation }}</td>
            </tr>
            <tr>
                <th>Home Address</th>
                <td>{{ $assessor->home_address }}</td>
            </tr>
        </table>

      
        <h5 class="mt-4 mb-3 border-bottom pb-2"><i class="tio-book"></i> Qualification Details</h5>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-secondary">
                <tr>
                    <th>Degree/Certificate</th>
                    <th>Institute</th>
                    <th>Year</th>
                    <th>Remark</th>
                    <th>Certificate</th>
                </tr>
            </thead>
            <tbody>
                @foreach($qualifications as $q)
                <tr>
                    <td>{{ $q['qualification'] }}</td>
                    <td>{{ $q['institute'] ?? 'N/A' }}</td>
                    <td>{{ $q['year'] ?? 'N/A' }}</td>
                    <td>{{ $q['remark'] ?? '-' }}</td>
                    <td>
                        @if(!empty($q['file']))
                            <a href="{{ asset($q['file']) }}" class="btn btn-sm btn-primary" target="_blank">View</a>
                        @else
                            <span class="text-muted">No File</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

      
        <h5 class="mt-4 mb-3 border-bottom pb-2"><i class="tio-briefcase"></i> Professional Experience</h5>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-secondary">
                <tr>
                    <th>Organization</th>
                    <th>Position</th>
                    <th>Duration</th>
                    <th>Key Responsibilities</th>
                </tr>
            </thead>
            <tbody>
                @foreach($experience as $exp)
                <tr>
                    <td>{{ $exp['organization'] }}</td>
                    <td>{{ $exp['position'] }}</td>
                    <td>{{ $exp['duration'] }}</td>
                    <td>{{ $exp['key_responsibilities'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    
        <h5 class="mt-4 mb-3 border-bottom pb-2"><i class="tio-award"></i> Competence Assessment Summary</h5>

        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="table-secondary">
                <tr>
                    <th>Competence Area</th>
                    <th>Remark</th>
                    <th>Rating (C/S/N)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assessment as $a)
                <tr>
                    <td class="text-start">{{ $a['area'] }}</td>
                    <td>{{ $a['remark'] }}</td>
                    <td><span class="badge bg-info text-dark">{{ $a['rating'] }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
