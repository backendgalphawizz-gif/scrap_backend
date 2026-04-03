@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('client_witness'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 d-flex gap-10">
                <img src="{{asset('/public/assets/back-end/img/brand-setup.png')}}" alt="">
                {{\App\CPU\translate('client_witness')}} {{\App\CPU\translate('Setup')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <form action="{{route('admin.application.store-witness')}}" method="POST" >
                            @csrf
                            <div class="row">
                                <div class="col-3">
                                    <label for="">Select Application</label>
                                    <select name="application_id" id="applicationSearch" required>
                                        <option value="">Search Application...</option>

                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="">Select Client</label>
                                    <select name="client_id" id="clientSearch" required>
                                        <option value="">Search Application...</option>

                                        <!-- @if(isset($rateChart))
                                            <option value="{{ $rateChart->center_id }}" selected>
                                                {{ $rateChart->center->name }} 
                                                ({{ $rateChart->center->dairy_name }}) 
                                                ({{ $rateChart->center->unique_code }})
                                            </option>
                                        @endif -->
                                    </select>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="title-color" for="priority">{{ \App\CPU\translate('Accreditation Type') }}</label>
                                        <select class="form-control" name="application_type" required>
                                            <option>Accreditation Type</option>
                                            <option value="Initial Accreditation">Initial Accreditation</option>
                                            <option value="Re Accreditation">Re Accreditation</option>
                                            <option value="Surveillance">Surveillance</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="title-color" for="priority">{{ \App\CPU\translate('Witness Type') }}</label>
                                        <select class="form-control" name="standards" required>
                                            <option>Witness Type</option>
                                            <option value="Standards">Standards</option>
                                            <option value="Regulations">Regulations</option>
                                            <option value="Methods">Methods</option>
                                            <option value="Procedures">Procedures</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="title-color" for="priority">{{ \App\CPU\translate('Product') }}</label>
                                        <input type="text" name="area" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="title-color" for="priority">{{ \App\CPU\translate('Due Before Date') }}</label>
                                        <input type="date" name="due_before_date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="title-color" for="priority">{{ \App\CPU\translate('Remark') }}</label>
                                        <textarea class="form-control" name="remark" required></textarea>
                                    </div>
                                </div>
                                

                                <div class="col-3">
                                    <div class="d-flex flex-wrap gap-2 mt-4 justify">
                                        <button type="reset" id="reset" class="btn btn-secondary">{{\App\CPU\translate('reset')}}</button>
                                        <button type="submit" class="btn btn--primary">{{\App\CPU\translate('submit')}}</button>
                                    </div>
                                </div>
                                
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-20" id="cate-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row align-items-center">
                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                <h5 class="text-capitalize d-flex gap-1">
                                    {{ \App\CPU\translate('Scheme_Area_list')}}
                                    <span class="badge badge-soft-dark radius-50 fz-12">{{ $categories->total() }}</span>
                                </h5>
                            </div>
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <!-- Search -->
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="" type="search" name="search" class="form-control"
                                            placeholder="{{ \App\CPU\translate('search_here')}}" value="{{ $search }}" required>
                                        <button type="submit" class="btn btn--primary">{{\App\CPU\translate('search')}}</button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{ \App\CPU\translate('Application ID')}}</th>
                                    <th>{{ \App\CPU\translate('Client')}}</th>
                                    <th>{{ \App\CPU\translate('Auditor Team')}}</th>
                                    <th>{{ \App\CPU\translate('Date')}}</th>
                                    <th>{{ \App\CPU\translate('Client Status')}}</th>
                                    <th>{{ \App\CPU\translate('Witness Status')}}</th>
                                    <th>{{ \App\CPU\translate('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $key=>$category)
                                <tr>
                                   
                                    <td>{{$category['application_number']}}</td>
                                    <td>{{$category->client['name'] ?? '--'}}</td>
                                    <td>{{$category->client['name'] ?? '--'}}</td>
                                    <td>{{$category['due_before_date'] ?? '--'}}</td>
                                    <td>
                                        @if($category->client_status === 0)
                                            <span class="badge badge-warning">{{ \App\CPU\translate('Pending') }}</span>
                                        @elseif($category->client_status === 1)
                                            <span class="badge badge-success">{{ \App\CPU\translate('Accepted') }}</span>
                                        @elseif($category->client_status === 2)
                                            <span class="badge badge-danger">{{ \App\CPU\translate('Rejected') }}</span>
                                        @else
                                            <span class="badge badge-secondary">--</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($category->witness_status === 0)
                                            <span class="badge badge-warning">{{ \App\CPU\translate('Pending') }}</span>
                                        @elseif($category->witness_status === 1)
                                            <span class="badge badge-info">{{ \App\CPU\translate('Started') }}</span>
                                        @elseif($category->witness_status === 2)
                                            <span class="badge badge-success">{{ \App\CPU\translate('Completed') }}</span>
                                        @else
                                            <span class="badge badge-secondary">--</span>
                                        @endif
                                    </td>

                                   
                                  <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">

                                        <!-- Allot Team -->
                                        <button type="button"
                                                class="view-application btn btn--primary btn-sm"
                                                data-application-id="{{ $category->application_id }}"
                                                data-witness-id="{{ $category->id }}"
                                                data-type="witness_assessment">
                                            Allot Team
                                        </button>

                                        <!-- View -->
                                       <a href="{{ route('admin.application.witness-view', $category->id) }}"
                                            class="btn btn-outline-info btn-sm">
                                                View
                                            </a>



                                    </div>
                                </td>


                                    
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>


                    <div class="table-responsive mt-4">
                        <div class="d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {{$categories->links()}}
                        </div>
                    </div>
                    @if(count($categories)==0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                            <p class="mb-0">{{\App\CPU\translate('no_data_found')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
    <div class="modal fade assementModal" style="background: #00000047; align-content: center;" id="assementModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Allot Unit</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                   <form action="{{ route('admin.application.update-witness-team') }}"
                        id="post-form-data"
                        method="POST">

                    <input type="hidden" name="witness_id" id="witness_id">


                        @csrf

                        
                        <input type="hidden" name="type" id="modalType">

                        <h4>
                            <span class="roleTitle">Witness Assessment</span> Select Team
                        </h4>

                        <div class="form-group mt-3">
                            <label class="title-color">
                                Team Lead of <span class="roleTitle">Witness Assessment</span>
                            </label>
                            <select name="auditor_id"
                                    id="auditor_id"
                                    class="form-control"
                                    required>
                            </select>
                        </div>

                        <div class="form-group mt-3">
                            <label class="title-color">
                                Other Team Member of <span class="roleTitle">Witness Assessment</span>
                            </label>
                            <select name="other_auditor_id[]"
                                    id="other_auditor_id"
                                    class="form-control"
                                    multiple>
                            </select>
                        </div>

                        <div class="mt-3" style="display:flex; gap:7px;">
                            <a class="customSecondBtn" data-dismiss="modal">Cancel</a>
                            <button type="submit" class="customPrimaryBtn">Allot</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

@push('script')
<script>
    $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
    
    <script>
        $(document).on('change', '.category-status', function () {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.category.status')}}",
                method: 'POST',
                data: {
                    id: id,
                    home_status: status
                },
                success: function (data) {
                    if(data.success == true) {
                        toastr.success('{{\App\CPU\translate('Status updated successfully')}}');
                    }
                }
            });
        });
    </script>
    <script>
        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            Swal.fire({
                title: '{{\App\CPU\translate('Are_you_sure')}}?',
                text: "{{\App\CPU\translate('You_will_not_be_able_to_revert_this')}}!",
                showCancelButton: true,
                type: 'warning',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{\App\CPU\translate('Yes')}}, {{\App\CPU\translate('delete_it')}}!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.scheme.area.delete')}}",
                        method: 'POST',
                        data: {id: id},
                        success: function () {
                            toastr.success('{{\App\CPU\translate('Scheme_area_deleted_Successfully.')}}');
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>



<script>
// $(document).ready(function () {
//     $('#city_id').select2({
//         placeholder: "-- Select City --",
//         minimumInputLength: 2, // start searching after 2 characters
//         ajax: {
//             url: "{{ route('admin.area.getCity') }}", 
//             dataType: 'json',
//             delay: 250,
//             data: function (params) {
//                 return {
//                     search: params.term // search term
//                 };
//             },
//             processResults: function (data) {
//                 return {
//                     results: $.map(data, function (city) {
//                         return {
//                             id: city.id,
//                             text: city.name
//                         }
//                     })
//                 };
//             },
//             cache: true
//         }
//     });
// });

$(document).ready(function () {
    $("#applicationSearch").select2({
        placeholder: "Search Center...",
        allowClear: true,
        ajax: {
            url: "{{ route('admin.application.getApplication') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term    
                };
            },
            processResults: function (data) {
                return {
                    results: data    
                };
            },
            minimumInputLength: 3
        }
    });
});
$(document).ready(function () {
    $("#clientSearch").select2({
        placeholder: "Search Center...",
        allowClear: true,
        ajax: {
            url: "{{ route('admin.application.getClient') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term    
                };
            },
            processResults: function (data) {
                return {
                    results: data    
                };
            },
            minimumInputLength: 3
        }
    });
});
</script>
<script>
$(document).on('click', '.view-application', function () {

    let applicationId = $(this).data('application-id'); // for list
    let witnessId = $(this).data('witness-id');         // for save
    let type = $(this).data('type');

    $('#post-form-data')[0].reset();
    $('#auditor_id').empty();
    $('#other_auditor_id').empty();

    // ✅ SET ONCE – ALWAYS CORRECT
    $('#witness_id').val(witnessId);

    $('.roleTitle').text(type.replace('_',' '));
    $('#modalType').val(type);

    $.ajax({
        type: "POST",
        url: "{{ route('admin.application.get-list') }}",
        data: {
            id: applicationId,
            type: type,
            _token: "{{ csrf_token() }}"
        },
        dataType: "json",
        success: function (response) {

            let leadOptions = '<option value="">Select Team Lead</option>';
            leadOptions += response.acc_html;

            $('#auditor_id').html(leadOptions).val('');
            $('#other_auditor_id').html(response.other_html);

            $('#assementModal').modal('show');
        }
    });
});


</script>

<script>
$(document).on('submit', '#post-form-data', function (e) {
    e.preventDefault();

    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function () {
            toastr.success('Team allotted successfully');
            $('#assementModal').modal('hide');
            location.reload();
        },
        error: function () {
            toastr.error('Something went wrong');
        }
    });
});
</script>
<script>
$(document).on('change', '#auditor_id', function () {
    let leadId = $(this).val();

    $('#other_auditor_id option').each(function () {
        if ($(this).val() == leadId) {
            $(this).prop('disabled', true).prop('selected', false);
        } else {
            $(this).prop('disabled', false);
        }
    });

    $('#other_auditor_id').trigger('change');
});
</script>
<script>
    $('#assementModal').on('shown.bs.modal', function () {
    $('#other_auditor_id').select2({
        dropdownParent: $('#assementModal'),
        width: '100%',
        placeholder: 'Select team members'
    });
});

$('#assementModal').on('hidden.bs.modal', function () {
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
});
</script>
    
@endpush
