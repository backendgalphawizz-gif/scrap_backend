

<div class="col-lg-4">
    <!-- Card -->
    <div class="card d-flex justify-content-center align-items-center">
        <div class="card-body d-flex gap-10 align-items-end justify-content-between w-75">
            <div class="text-capitalize">
                <h3 class="for-card-count mb-0 fz-24">${{ $data['total_earning'] ?? 0 }}</h3>
                {{\App\CPU\translate('Total Earning')}}
            </div>
            <img width="45" src="{{asset('/public/assets/back-end/img/inhouse-earning.png')}}" alt="">
            <!-- <h3 class="for-card-count mb-0 fz-24">$ {{ (\App\CPU\BackEndHelper::usd_to_currency($data['total_earning']))}}<h3> -->
        </div>
    </div>
    <!-- End Card -->
</div>
<div class="col-lg-4">
    <!-- Card -->
    <div class="card d-flex justify-content-center align-items-center">
        <div class="card-body d-flex gap-10 align-items-end justify-content-between w-75">
            <div class="text-capitalize">
                <h3 class="mb-0 fz-24">${{ $data['pending_amount'] ?? 0}}</h3>
                {{\App\CPU\translate('pending_amount')}}
            </div>
            <img width="45" src="{{asset('/public/assets/back-end/img/pa.png')}}" alt="">
        </div>
    </div>
    <!-- End Card -->
</div>

