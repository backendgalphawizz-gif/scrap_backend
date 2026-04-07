@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Assign Bulk Orders'))

@push('css_or_js')
<link rel="stylesheet" href="//cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
@endpush

@section('content')
    <div class="content container-fluid">
        
        <div class="card mb-2">
            <div class="card-body">
              <form action="{{ route('admin.delivery-man.assign-bulk-order-store') }}" method="POST" id="form-data">
                @csrf
                <div class="row gx-2 gy-3 align-items-center text-left">

                    <!-- Delivery Man Dropdown -->
                    <div class="col-sm-6 col-md-3">
                        <select name="driver_id" required>
                        <option value="">{{ \App\CPU\translate('select_driver') }}</option>
                       @foreach($drivers as $driver)
                       @dd($driver);
                       
                    @endforeach
                    </select>

                    </div>

                    <!-- Multiple Order IDs -->
                    <div class="col-sm-6 col-md-6">
                        <select class="js-select2-custom form-control" name="order_ids[]" multiple required>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}">
                                    {{ $order->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-sm-6 col-md-3 submit-btn">
                        <button type="submit" class="btn btn--primary px-4 px-md-5">
                            {{ \App\CPU\translate('submit') }}
                        </button>
                    </div>

                </div>
            </form>

            </div>
        </div>

       
       

    </div>
@endsection

@push('script_2')

    <!-- Chart JS -->
    
    <!-- Chart JS -->
    <!-- Apex Charts -->
   
    <!-- Apex Charts -->

    <!-- Dognut Pie Chart -->

    <!-- Dognut Pie Chart -->
<script>
    $(document).ready(function () {
    $('.js-select2-custom').select2({
        placeholder: "Select options"
    });
});

</script>
@endpush
