@php
    $testingDetail = \App\Model\SchemeMsCertificationBody::where('application_id', $application->id)->first();
@endphp

<div class="container-fluid">

    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
        <thead>
            <tr>
                <th colspan="3">Identify the management system certification scheme (s) for which accreditation is sought</th>
            </tr>
            <tr>
                <th>{{ \App\CPU\translate('Tick')}}</th>
                <th>{{ \App\CPU\translate('Certification area')}}</th>
                <th>{{ \App\CPU\translate('Scope')}}</th>
                <th>{{ \App\CPU\translate('Geographical Areas (countries)')}}</th>
                <th>{{ \App\CPU\translate('Remark')}}</th>
                <th>{{ \App\CPU\translate('Remark By')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($testingDetail->area_data as $area_data)
                <tr>
                    <td><input type="checkbox" name="" id="" disabled></td>
                    <td>{{ $area_data['area_title'] ?? '-' }}</td>
                    <td>{{ $area_data['scope'] ?? '-' }}</td>
                    <td>{{ $area_data['geographical'] ?? '-' }}</td>
                    <td>{{ $area_data['remark'] ?? '-' }}</td>
                    <td>{{ $area_data['remark_by'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>