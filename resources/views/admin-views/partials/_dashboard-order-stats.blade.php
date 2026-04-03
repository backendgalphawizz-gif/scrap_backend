<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
@if(\App\CPU\Helpers::module_permission_check('application_management'))
    <div class="col-sm-6 col-lg-3 mb-2 ">
        <!-- Business Analytics Card -->
        <div class="business-analytics cursor-pointer pt-3 border-0 totale__sale carddivsec"
            onclick="location.href='{{route('admin.company.index')}}'">
            <div class="dashBoardCardTextBox">
                <h2 class="business-analytics__title">{{ $data['total_seller'] ?? '0' }}</h2>
                <h5 class="business-analytics__subtitle text-muted mb-0">{{\App\CPU\translate('Total CAB Registered')}}</h5>
                <!-- <img src="{{asset('/public/assets/back-end/img/total-sale.png')}}" class="business-analytics__img" alt=""> -->
            </div>
            <div class="icon__set__svg ">
                <img width="45px" src="{{asset('/public/assets/back-end/img/admin-dashboard-img/office-building.png')}}" alt="" class="cardimg">
            </div>
        </div>
        <!-- End Business Analytics Card -->
    </div>
    <div class="col-sm-6 col-lg-3 mb-2 d-none">
        <!-- Business Analytics Card -->
        <div class="business-analytics cursor-pointer pt-3 border-0 totale__sale carddivsec"
            onclick="location.href='{{route('admin.application.approved-list')}}'">
            <div class="icon__set__svg mb-3">
                <img src="{{asset('/public/assets/back-end/img/admin-dashboard-img/Total-order.svg')}}" alt="" class="cardimg">
            </div>
            <div>
                <h5 class="business-analytics__subtitle text-muted">{{\App\CPU\translate('Total Application')}}</h5>
                <h2 class="business-analytics__title">{{ $data['total_application'] }}</h2>
                <!-- <img src="{{asset('/public/assets/back-end/img/total-sale.png')}}" class="business-analytics__img" alt=""> -->
            </div>
        </div>
        <!-- End Business Analytics Card -->
    </div>
    @if (auth('admin')->user()->admin_role_id == 1)

    <div class="col-sm-6 col-lg-3 mb-2 d-none">
        <!-- Business Analytics Card -->
        <div class="business-analytics cursor-pointer border-0 pt-3 total__stores carddivsec"
            onclick="location.href='{{route('admin.application.assessment')}}'">
            <div class="icon__set__svg mb-3">
                <img src="{{asset('/public/assets/back-end/img/admin-dashboard-img/vendor.svg')}}" alt="" class="cardimg">
            </div>
            <div>
            <h5 class="business-analytics__subtitle">{{\App\CPU\translate('Pending Application')}}</h5>
            <h2 class="business-analytics__title">{{ $data['pending_application'] }}</h2>
            <!-- <img src="{{asset('/public/assets/back-end/img/total-stores.png')}}" class="business-analytics__img" alt=""> -->
        </div>
    </div>
        <!-- End Business Analytics Card -->
    </div>

    <div class="col-sm-6 col-lg-3 mb-2 d-none">
        <!-- Business Analytics Card -->
        <div class="business-analytics cursor-pointer border-0 pt-3 total__customer carddivsec"
            onclick="location.href='{{route('admin.application.approved-list')}}'">
            <div class="icon__set__svg mb-3">
                <img src="{{asset('/public/assets/back-end/img/admin-dashboard-img/delevery.svg')}}" alt="" class="cardimg">
            </div>
            <div>
                <h5 class="business-analytics__subtitle">{{\App\CPU\translate('Certificate Issue')}}</h5>
                <h2 class="business-analytics__title">{{ $data['complete_application'] }}</h2>
                <!-- <img src="{{asset('/public/assets/back-end/img/total-customer.png')}}" class="business-analytics__img" alt=""> -->
            </div>
        </div>
        <!-- End Business Analytics Card -->
    </div>

    <div class="col-sm-6 col-lg-3 mb-2 d-none">
        <!-- Business Analytics Card -->
        <div class="business-analytics cursor-pointer border-0 pt-3 total__product__up carddivsec"
            onclick="location.href='{{route('admin.application.assessment')}}'">
            <div class="icon__set__svg mb-3">
                <img src="{{asset('/public/assets/back-end/img/admin-dashboard-img/customer.svg')}}" alt="" class="cardimg">
            </div>
            <div>
                <h5 class="business-analytics__subtitle">{{\App\CPU\translate('Reject Applications')}}</h5>
                <h2 class="business-analytics__title">{{ $data['rejected_application'] }}</h2>
                <!-- <img src="{{asset('/public/assets/back-end/img/total-product.png')}}" class="business-analytics__img" alt=""> -->
            </div>
        </div>
        <!-- End Business Analytics Card -->
    </div>

    @endif
@endif

@if(\App\CPU\Helpers::module_permission_check('user_section'))
<div class="col-sm-6 col-lg-3 mb-2 d-none">
    <!-- Business Analytics Card -->
    <div class="business-analytics cursor-pointer border-0 pt-3 total__product__up carddivsec"
        onclick="location.href='{{route('admin.customer.list')}}'">
        <div class="icon__set__svg mb-3">
            <img src="{{asset('/public/assets/back-end/img/admin-dashboard-img/customer.svg')}}" alt="" class="cardimg">
        </div>
        <div>
            <h5 class="business-analytics__subtitle">{{\App\CPU\translate('Total Customer')}}</h5>
            <h2 class="business-analytics__title">{{ $data['total_customer'] }}</h2>
            <!-- <img src="{{asset('/public/assets/back-end/img/total-product.png')}}" class="business-analytics__img" alt=""> -->
        </div>
    </div>
    <!-- End Business Analytics Card -->
</div>
@endif

@if(auth('admin')->user()->admin_role_id==1)
<div class="col-sm-6 col-lg-3 mb-2">
    <!-- Business Analytics Card -->
    <div class="business-analytics cursor-pointer border-0 pt-3 total__product__up carddivsec"
        onclick="location.href='{{route('admin.employee.list')}}'">
        <div class="dashBoardCardTextBox">
            <h2 class="business-analytics__title">{{ $data['total_quality'] }}</h2>
            <h5 class="business-analytics__subtitle mb-0">{{\App\CPU\translate('Total Auditors')}}</h5>
            <!-- <img src="{{asset('/public/assets/back-end/img/total-product.png')}}" class="business-analytics__img" alt=""> -->
        </div>
        <div class="icon__set__svg ">
            <img width="45px" src="{{asset('/public/assets/back-end/img/admin-dashboard-img/auditor.png')}}" alt="" class="cardimg">
        </div>
    </div>
    <!-- End Business Analytics Card -->
</div>

<div class="col-sm-6 col-lg-3 mb-2">
    <!-- Business Analytics Card -->
    <div class="business-analytics cursor-pointer border-0 pt-3 total__product__up carddivsec"
        onclick="location.href='{{route('admin.assessor.assessor-list')}}'">
        <div class="dashBoardCardTextBox">
            <h2 class="business-analytics__title">{{ $data['total_assessor'] }}</h2>
            <h5 class="business-analytics__subtitle mb-0">{{\App\CPU\translate('Total Clients')}}</h5>
            <!-- <img src="{{asset('/public/assets/back-end/img/total-product.png')}}" class="business-analytics__img" alt=""> -->
        </div>
        <div class="icon__set__svg ">
            <img width="45px" src="{{asset('/public/assets/back-end/img/admin-dashboard-img/target.png')}}" alt="" class="cardimg">
        </div>
    </div>
    <!-- End Business Analytics Card -->
</div>

<div class="col-sm-6 col-lg-3 mb-2">
    <!-- Business Analytics Card -->
    <div class="business-analytics cursor-pointer border-0 pt-3 total__product__up carddivsec"
        onclick="location.href='{{route('admin.employee.list')}}'">
        <div class="dashBoardCardTextBox">
            <h2 class="business-analytics__title">{{ $data['total_accreditation'] }}</h2>
            <h5 class="business-analytics__subtitle mb-0">{{\App\CPU\translate('Total Certificates')}}</h5>
            <!-- <img src="{{asset('/public/assets/back-end/img/total-product.png')}}" class="business-analytics__img" alt=""> -->
        </div>
        <div class="icon__set__svg">
            <img width="45px" src="{{asset('/public/assets/back-end/img/admin-dashboard-img/certified.png')}}" alt="" class="cardimg">
        </div>
    </div>
    <!-- End Business Analytics Card -->
</div>
@endif



<!-- Added New Cards( copy pasted ) as per clind requirment -->
<div class="col-sm-6 col-lg-3 mb-2">
    <div class="business-analytics cursor-pointer border-0 pt-3 total__product__up carddivsec"
        onclick="location.href='{{route('admin.assessor.assessor-list')}}'">
        <div class="dashBoardCardTextBox">
            <h2 class="business-analytics__title">{{ $data['total_assessor'] }}</h2>
            <h5 class="business-analytics__subtitle mb-0">{{\App\CPU\translate('Total Suspended Certificates')}}</h5>
            <!-- <img src="{{asset('/public/assets/back-end/img/total-product.png')}}" class="business-analytics__img" alt=""> -->
        </div>
        <div class="icon__set__svg ">
            <img width="45px" src="{{asset('/public/assets/back-end/img/admin-dashboard-img/rejected.png')}}" alt="" class="cardimg">
        </div>
    </div>
</div>

<div class="col-sm-6 col-lg-3 mb-2">
    <div class="business-analytics cursor-pointer border-0 pt-3 total__product__up carddivsec"
        onclick="location.href='{{route('admin.assessor.assessor-list')}}'">
        <div class="dashBoardCardTextBox">
            <h2 class="business-analytics__title">{{ $data['total_assessor'] }}</h2>
            <h5 class="business-analytics__subtitle mb-0">{{\App\CPU\translate('Total Withdrawn Certificates')}}</h5>
            <!-- <img src="{{asset('/public/assets/back-end/img/total-product.png')}}" class="business-analytics__img" alt=""> -->
        </div>
        <div class="icon__set__svg ">
            <img width="45px" src="{{asset('/public/assets/back-end/img/admin-dashboard-img/approvedd.png')}}" alt="" class="cardimg">
        </div>
    </div>
</div>

<div class="col-sm-6 col-lg-3 mb-2">
    <div class="business-analytics cursor-pointer border-0 pt-3 total__product__up carddivsec"
        onclick="location.href='{{route('admin.assessor.assessor-list')}}'">
        <div class="dashBoardCardTextBox">
            <h2 class="business-analytics__title">{{ $data['total_assessor'] }}</h2>
            <h5 class="business-analytics__subtitle mb-0">{{\App\CPU\translate('Total Overdue Clients')}}</h5>
            <!-- <img src="{{asset('/public/assets/back-end/img/total-product.png')}}" class="business-analytics__img" alt=""> -->
        </div>
        <div class="icon__set__svg ">
            <img width="45px" src="{{asset('/public/assets/back-end/img/admin-dashboard-img/due-date.png')}}" alt="" class="cardimg">
        </div>
    </div>
</div>



<!-- End Added New Cards( copy pasted ) as per clind requirment -->








<div class="col-md-1"></div>


