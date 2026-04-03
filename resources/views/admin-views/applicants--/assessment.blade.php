@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('All Assessment'))

@push('css_or_js')
<link href="{{asset('public/assets/back-end')}}/css/select2.min.css" rel="stylesheet"/>
@endpush
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<style>
    .ul.select2-selection__rendered{
        display: flex !important;
    }
    .select2-selection__choice__remove:hover{
        background-color: unset !important;
    }
    .select2-selection__choice__remove:hover{
        border-right: none !important;
    }
    li.select2-selection__choice{
        background: linear-gradient(260deg, rgba(184, 28, 24, 1) 0%, rgba(252, 68, 64, 1) 100%);
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove{
        border-right: none !important;

    }
</style>
@section('content')
<div class="content container-fluid">
    <div class="mb-3">
        <h2 class="h1 mb-0 d-flex gap-10">
            <img src="{{asset('/public/assets/back-end/img/brand-setup.png')}}" alt="">
            {{\App\CPU\translate('All Assessment')}}
        </h2>
    </div>


    <div class="row mt-20" id="cate-table">
        <div class="col-md-12">
            <div class="card my-3">
                 <div class="row px-2 my-3 ">

                    <div class="col-md-3 mb-2">
                        <a href="{{ url()->current() }}?status=" class="text-decoration-none">
                            <div class="card text-center p-3 shadow-sm {{ $status=='' || $status===null ? 'border-primary' : '' }}">
                                <h5>All</h5>
                                <h2>{{ $count_all }}</h2>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-3 mb-2">
                        <a href="{{ url()->current() }}?status=0" class="text-decoration-none">
                            <div class="card text-center p-3 shadow-sm {{ $status==='0' ? 'border-warning' : '' }}">
                                <h5>Pending</h5>
                                <h2>{{ $count_pending }}</h2>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-3 mb-2">
                        <a href="{{ url()->current() }}?status=1" class="text-decoration-none">
                            <div class="card text-center p-3 shadow-sm {{ $status==='1' ? 'border-success' : '' }}">
                                <h5>Accepted</h5>
                                <h2>{{ $count_accepted }}</h2>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-3 mb-2">
                        <a href="{{ url()->current() }}?status=2" class="text-decoration-none">
                            <div class="card text-center p-3 shadow-sm {{ $status==='2' ? 'border-danger' : '' }}">
                                <h5>Rejected</h5>
                                <h2>{{ $count_rejected }}</h2>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="px-3 py-4">
                    <div class="row align-items-start">
                        <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                            <h6 class="customHeading mb-0">Allot SA Assessment</h6>
                        </div>
                        <div class="col-sm-8 col-md-6 col-lg-4">
                            <form action="" method="GET">
                                <input type="hidden" name="status" value="{{ request()->status }}">
                                <div class="input-group input-group-custom input-group-merge">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="tio-search"></i>
                                        </div>
                                    </div>
                                    <input id="" type="search" name="search" class="form-control" placeholder="Search here" value="" required="">
                                    <button type="submit" class="btn btn--primary">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
               

                <div class="table-responsive">
                    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                        class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                        <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{ \App\CPU\translate('Action')}}</th>
                                <th>{{ \App\CPU\translate('Alotment')}}</th>
                                <th>{{ \App\CPU\translate('Application status')}}</th>
                                <th>{{ \App\CPU\translate('Company Details')}}</th>
                                <th>{{ \App\CPU\translate('User Details')}}</th>
                                <th>{{ \App\CPU\translate('scheme_name')}}</th>
                                <th>{{ \App\CPU\translate('reference_number')}}</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applications as $application)
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: start;">
                                            <a href="{{ route('admin.application.view-detail', $application['id']) }}" class="customPrimaryBtn">
                                                View Application
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display: flex; align-items: start;">
                                            <a class="customPrimaryBtn view-application" href="javascript:void(0)" data-id="{{ $application['id'] }}">
                                                Allotment
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        @if($application->is_accept == '0')
                                            <select name="application_status" class="status form-control application_status" data-id="{{$application['id']}}">
                                                <option value="0" {{ $application->is_accept == '0' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                                <option value="1" {{ $application->is_accept == '1' ? 'selected' : '' }}>{{ __('Accept') }}</option>
                                                <option value="2" {{ $application->is_accept == '2' ? 'selected' : '' }}>{{ __('Reject') }}</option>
                                            </select>
                                        @else
                                            <span class="badge badge-{{ $application->is_accept == '1' ? 'success' : 'danger' }}">{{ ucwords($application->is_accept == '1' ? 'accepted' : 'rejected') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="tableDetails">
                                            <span>{{$application->company->name}}</span>
                                            <h6>{{$application->company->organization}}</h6>
                                            <p> {{$application->company->address}}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="tableDetails">
                                            <h6>{{$application->user->name}}</h6>
                                            <span>{{$application->user->phone}}</span>
                                            <p> {{$application->user->email}}</p>
                                        </div>
                                    </td>
                                    <td>{{$application->scheme->title}} </td>
                                    <td>{{$application->reference_number}} </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td> {{ $applications->links() }} </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade assementModal" id="assementModal" tabindex="-1" aria-labelledby="assementModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="assementModalLabel">Allot Unit</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.application.update_accessor') }}" class="post" id="post-form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id">
                    <input type="hidden" name="assessment_type">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="title-color" for="priority">Assessment Date*
                            </label>
                            <input type="date" class="form-control" name="allotted_date" required>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <div class="form-group">
                            <label class="title-color" for="priority">Mode of Assessment
                            </label>
                            <select name="mode_of_auditor" class="form-control" id="mode_of_auditor" required>
                                <option selected disabled>Select Mode of Assessment</option>
                                <option value="Desktop">Desktop</option>
                                <option value="Onsite">Onsite</option>
                                <option value="Remote">Remote</option>
                                <option value="Hybrid">Hybrid</option>
                            </select>
                        </div>
                    </div>
                    
                    <h4>Assessment Select Team</h4>
                    <div class="col-12 mt-3">
                        <div class="form-group">
                            <label class="title-color" for="priority">Team Lead of Assessment
                            </label>
                            <select name="auditor_id" class="form-control" id="auditor_id" required>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-12 mt-3">
                        <div class="form-group">
                            <label class="title-color" for="priority">Other Team Member of Assessment
                            </label>
                            <select name="other_auditor_id[]" class="form-control" id="other_auditor_id" multiple>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <div style="display: flex; gap: 7px; align-items: center; width:100%;">
                            <a class="customSecondBtn" data-bs-dismiss="modal" role="button">Cancel</a>
                            <button type="submit" class="customPrimaryBtn">
                                Allot
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush

@push('script_2')

<script>
$(document).ready(function() {
    $('#other_auditor_id').select2({
      placeholder: "Select an option",
      allowClear: true
    });
});
</script>
<script>
    $(document).on('change', '.application_status', function() {
        let status = $(this).val()
        let id = $(this).attr('data-id')

        if(confirm('are you sure want to update?')) {
            $.ajax({
                type: 'POST',
                url: "{{ route('admin.application.status_update') }}",
                data: {
                    id:id,
                    status:status,
                    '_token': "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function (response) {
                    Swal.fire('success', 'Status updated success', 'success').then(() => {
                        window.location.reload()
                    })
                }
            });
        }
    })

    // $(document).on('click', '.view-application', function() {
    //     var id = $(this).attr('data-id')
    //     $.ajax({
    //         type: "POST",
    //         url: "{{ route('admin.application.get-list') }}",
    //         data: {
    //             id:id,
    //             '_token':"{{ csrf_token() }}"
    //         },
    //         dataType: "json",
    //         success: function (response) {

    //             if(response.application.allot_date) {
    //                 $('#assementModal').find('input[name=allotted_date]').val(response.application.allot_date)
    //                 $('#assementModal').find('input[name=allotted_date]').attr('readonly', true)
    //             }

    //             $('#auditor_id').html(response.acc_html)
    //             $('#other_auditor_id').html(response.acc_html)
    //             $('#assementModal').find('input[name=id]').val(id)

    //             $('#assementModal').modal('show')
    //         }
    //     });
    // })

    $(document).on('click', '.view-application', function() {
        var id = $(this).data('id');

        $('#assementModal').find('input[name=id]').val('');
        $('#assementModal').find('input[name=allotted_date]').val('').prop('readonly', false);
        $('#auditor_id').empty();
        $('#other_auditor_id').empty();

        $.ajax({
            type: "POST",
            url: "{{ route('admin.application.get-list') }}",
            data: {
                id: id,
                _token: "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(response) {
                if (response.application.allot_date || response.application.allotted_date) {
                    var ad = response.application.allot_date || response.application.allotted_date;
                    $('#assementModal').find('input[name=allotted_date]').val(ad).prop('readonly', true);
                } else {
                    $('#assementModal').find('input[name=allotted_date]').prop('readonly', false);
                }

                $('#auditor_id').html(response.acc_html);
                $('#other_auditor_id').html(response.other_html);

                $('#assementModal').find('input[name=id]').val(response.application.id);
                $('#assementModal').find('input[name=assessment_type]').val(response.application.assessment_type);

                if (response.application.mode_of_auditor) {
                    $('#mode_of_auditor').val(response.application.mode_of_auditor).trigger('change');
                } else {
                    $('#mode_of_auditor').val('').trigger('change');
                }

                if (response.application.auditor_id) {
                    $('#auditor_id').val(response.application.auditor_id).trigger('change');
                } else {
                    $('#auditor_id').val('').trigger('change');
                }

                var otherCsv = response.application.other_auditor_id || '';
                var othersArr = [];
                if (otherCsv.length) {
                    othersArr = otherCsv.split(',').map(function(v){ return v.trim(); }).filter(Boolean);
                    $('#other_auditor_id').val(othersArr).trigger('change');
                } else {
                    $('#other_auditor_id').val([]).trigger('change');
                }

                $('#assementModal').modal('show');
            },
            error: function(xhr) {
                alert('Could not fetch assessors');
            }
        });
    });

    $(document).on('submit','#post-form-data', function(e) {
        e.preventDefault()

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                Swal.fire('', 'Accessor alloted successfully', 'success').then(() => {
                    window.location.reload()
                })
            }
        });

    })

    $(document).on("change", "#auditor_id", function () {
        var leadId = $(this).val();
        $("#other_auditor_id option").each(function () {
            if ($(this).val() == leadId) {
                $(this).prop("disabled", true).prop("selected", false);
            } else {
                $(this).prop("disabled", false);
            }
        });

        $("#other_auditor_id").trigger("change");
    });
    $("#auditor_id").trigger("change");

</script>
    
@endpush