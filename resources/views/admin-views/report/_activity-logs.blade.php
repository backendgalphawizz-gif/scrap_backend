@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Activity Logs'))

@push('css_or_js')
@endpush

@section('content')

<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-history menu-icon"></i>
            </span> {{\App\CPU\translate('Activity Logs')}}
        </h3>
        <nav aria-label="breadcrumb"></nav>
    </div>

    <div class="row">
        <div class="col-lg-12">
            

            <!-- Table Section -->
            <div class="card">
                <div class="table-responsive">
                    <table id="datatable"
                        class="table">
                        <thead class="text-capitalize">
                            <tr>
                                <th>Activity By</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Module</th>
                                <th>Date</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($logs as $log)
                                <tr>
                                    <td>{{ ucwords($log->causer->name ?? ($log->causer->username ?? 'System')) }}</td>
                                    <td>
                                        @if($log->causer_type == 'App\Models\Seller')
                                            Brand
                                        @elseif($log->causer_type == 'App\Models\Sale')
                                            Sale
                                        @elseif($log->causer_type == 'App\Models\Admin')
                                            Admin
                                        @else
                                            User
                                        @endif

                                    </td>
                                    <td>{{ $log->description }}</td>
                                    <td>{{ str_replace('_', ' ', ucwords($log->log_name)) }}</td>
                                    <td>{{ \App\CPU\Helpers::setDateTime($log) }}</td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>


                <!-- Pagination Styled Same As Campaign Page -->
                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-lg-end">
                        {{ $logs->links() }}
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>

<div class="content container-fluid"></div>

@endsection

@push('script')
@endpush

@push('script_2')

<script>
    $('#from_date,#to_date').change(function() {

        let fr = $('#from_date').val();
        let to = $('#to_date').val();

        if (fr != '') {
            $('#to_date').attr('required', 'required');
        }

        if (to != '') {
            $('#from_date').attr('required', 'required');
        }

        if (fr != '' && to != '') {

            if (fr > to) {

                $('#from_date').val('');
                $('#to_date').val('');

                toastr.error('Invalid date range!', Error, {
                    CloseButton: true,
                    ProgressBar: true
                });

            }

        }

    });


    $("#date_type").change(function() {

        let val = $(this).val();

        $('#from_div').toggle(val === 'custom_date');
        $('#to_div').toggle(val === 'custom_date');

        if (val === 'custom_date') {

            $('#from_date').attr('required', 'required');
            $('#to_date').attr('required', 'required');

        } else {

            $('#from_date').val(null).removeAttr('required')
            $('#to_date').val(null).removeAttr('required');

        }

    }).change();
</script>

@endpush