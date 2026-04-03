<div id="sidebarMain" class="d-none">
    <aside style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="bg-white js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container">
            <div class="navbar-vertical-footer-offset pb-0">
                <div class="navbar-brand-wrapper justify-content-between side-logo">
                    <!-- Logo -->
                    @php($e_commerce_logo=\App\Model\BusinessSetting::where(['type'=>'company_web_logo'])->first()->value)
                    <a class="navbar-brand" href="{{route('admin.dashboard.index')}}" aria-label="Front">
                        <img onerror="this.src='{{asset('public/assets/back-end/img/900x400/img1.jpg')}}'"
                            class="navbar-brand-logo-mini for-web-logo max-h-30"
                            src="{{asset("storage/app/public/company/$e_commerce_logo")}}" alt="Logo">
                    </a>
                    <!-- Navbar Vertical Toggle -->
                    <button type="button"
                        class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                          <img src="{{asset('public/assets/back-end/img/login-img/semi-logo-for-company.png')}}" alt="" width="100%">
                        
                    </button>
                    <!-- End Navbar Vertical Toggle -->

                     <!-- <button type="button" class="js-navbar-vertical-aside-toggle-invoker close">
                        <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                            data-placement="right" title=""></i>
                        <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                            data-template="<div class=&quot;tooltip d-sm-block&quot; role=&quot;tooltip&quot;><div class=&quot;arrow&quot;></div><div class=&quot;tooltip-inner&quot;></div></div>"
                            data-toggle="tooltip" data-placement="right" title=""></i>
                    </button> -->
                </div>

                <!-- Content -->
                <div class="navbar-vertical-content pt-3">
                    <!-- Search Form -->
                    <div class="sidebar--search-form pb-3 pt-4 d-none">
                        <div class="search--form-group">
                            <button type="button" class="btn"><i class="tio-search"></i></button>
                            <input type="text" class="js-form-search form-control form--control" id="search-bar-input"
                                placeholder="{{\App\CPU\translate('search_menu')}}...">
                        </div>
                    </div>
                    <!-- <div class="input-group">
                        <diV class="card search-card" id="search-card"
                             style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                            <div class="card-body search-result-box" id="search-result-box">

                            </div>
                        </diV>
                    </div> -->
                    <!-- End Search Form -->

                    <ul class="navbar-nav navbar-nav-lg nav-tabs">
                        <!-- Dashboards -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/dashboard')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                title="{{\App\CPU\translate('Dashboard')}}" href="{{route('admin.dashboard.index')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M3 6.5C3 3.87479 3.02811 3 6.5 3C9.97189 3 10 3.87479 10 6.5C10 9.12521 10.0111 10 6.5 10C2.98893 10 3 9.12521 3 6.5Z"
                                        stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M14 6.5C14 3.87479 14.0281 3 17.5 3C20.9719 3 21 3.87479 21 6.5C21 9.12521 21.0111 10 17.5 10C13.9889 10 14 9.12521 14 6.5Z"
                                        stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M3 17.5C3 14.8748 3.02811 14 6.5 14C9.97189 14 10 14.8748 10 17.5C10 20.1252 10.0111 21 6.5 21C2.98893 21 3 20.1252 3 17.5Z"
                                        stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M14 17.5C14 14.8748 14.0281 14 17.5 14C20.9719 14 21 14.8748 21 17.5C21 20.1252 21.0111 21 17.5 21C13.9889 21 14 20.1252 14 17.5Z"
                                        stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Dashboard')}} fdgsdfhgshdfghsdfiu
                                </span>
                            </a>
                        </li>
                        <!-- End Dashboards -->

                        <!-- POS -->
                        @if (\App\CPU\Helpers::module_permission_check('pos_management'))
                        <li class="navbar-vertical-aside-has-menu d-none {{Request::is('admin/pos*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link" title="{{\App\CPU\translate('POS')}}"
                                href="{{route('admin.pos.index')}}">
                                <i class="tio-shopping nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('POS')}}</span>
                            </a>
                        </li>
                        @endif
                        <!-- End POS -->

                        <!-- Order Management -->
                        @if(\App\CPU\Helpers::module_permission_check('order_management'))
                        <li
                            class="nav-item {{ Request::is('admin/report/order') || Request::is('admin/refund-section/refund/details/*') || Request::is('admin/refund-section/refund/list/*') || Request::is('admin/orders*')?'scroll-here':''}}">
                            <small class="nav-subtitle" title="">{{\App\CPU\translate('order_management')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>
                        <!-- Order -->
                        <li
                            class="navbar-vertical-aside-has-menu {{Request::is('admin/report/order') || Request::is('admin/refund-section/refund/details/*') || Request::is('admin/refund-section/refund/list/*') || Request::is('admin/orders*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link  its-drop" href="javascript:void(0)"
                                title="{{\App\CPU\translate('Order_Management')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M2.75 3.25L4.83 3.61L5.793 15.083C5.87 16.02 6.653 16.739 7.593 16.736H18.502C19.399 16.738 20.16 16.078 20.287 15.19L21.236 8.632C21.342 7.899 20.833 7.219 20.101 7.113C20.037 7.104 5.164 7.099 5.164 7.099"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M13.375 10.7949C13.375 10.3807 13.7108 10.0449 14.125 10.0449H16.898C17.3122 10.0449 17.648 10.3807 17.648 10.7949C17.648 11.2091 17.3122 11.5449 16.898 11.5449H14.125C13.7108 11.5449 13.375 11.2091 13.375 10.7949Z"
                                        fill="white" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M7.15337 19.4521C6.43726 19.4521 5.85938 20.0328 5.85938 20.7461C5.85938 21.4596 6.43637 22.0411 7.15337 22.0411C7.87038 22.0411 8.44737 21.4596 8.44737 20.7461C8.44737 20.0328 7.86949 19.4521 7.15337 19.4521ZM18.4346 19.4521C17.7185 19.4521 17.1406 20.0328 17.1406 20.7461C17.1406 21.4596 17.7176 22.0411 18.4346 22.0411C19.1498 22.0411 19.7296 21.4614 19.7296 20.7461C19.7296 20.031 19.1489 19.4521 18.4346 19.4521Z"
                                        fill="white" />
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Order_Management')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub ffff"
                                style="display: {{Request::is('admin/report/order') || Request::is('admin/refund-section/refund/details/*') || Request::is('admin/refund-section/refund/list/*') || Request::is('admin/order*')?'block':'none'}}">
                                <li class="nav-item {{Request::is('admin/orders/list/all')?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.orders.list',['all'])}}"
                                        title="{{\App\CPU\translate('All')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M11.8743 22.0457C9.45143 22.0457 7.02857 22.0457 4.60571 22.0457C3.05143 22.0457 2 20.9943 2 19.44C2 17.7029 2 15.9657 2 14.2057V12.0343V9.77143C2 8.03429 2 6.29714 2 4.53714C2 3.21143 2.8 2.25143 4.10286 2.02286C4.26286 2 4.44571 2 4.62857 2C7.6 2 10.5714 2 13.5429 2H19.3714C20.6971 2 21.6571 2.73143 21.9314 3.96571C21.9771 4.14857 22 4.37714 22 4.62857V9.54286C22 12.8343 22 16.1029 22 19.3943C22 20.88 20.8343 22.0229 19.3714 22.0229C16.88 22.0457 14.3886 22.0457 11.8743 22.0457ZM4.67429 3.53143C3.89714 3.53143 3.53143 3.89714 3.53143 4.67428C3.53143 9.56571 3.53143 14.48 3.53143 19.3943C3.53143 20.1714 3.89714 20.5143 4.65143 20.5143H19.3714C20.1486 20.5143 20.4914 20.1714 20.4914 19.3943V4.65143C20.4914 3.89714 20.1257 3.53143 19.3714 3.53143H12.0343H4.67429Z"
                                                fill="white" />
                                            <path
                                                d="M10.8017 15.8059C10.756 15.8059 10.6874 15.8059 10.6417 15.7831C10.4131 15.7145 10.2303 15.5317 10.0703 15.3717C9.15599 14.4802 8.21885 13.5888 7.28171 12.7431C6.96171 12.4459 6.61885 12.1259 6.64171 11.7145C6.64171 11.3488 6.98457 11.0059 7.35028 11.0059C7.37314 11.0059 7.39599 11.0059 7.41885 11.0059C7.71599 11.0288 7.94456 11.2345 8.19599 11.4631C8.44742 11.7145 8.72171 11.9431 8.97314 12.1717C9.11028 12.2859 9.24742 12.4002 9.36171 12.5145L9.95599 13.0631C10.0017 13.0859 10.0246 13.1317 10.0703 13.1774C10.2531 13.3602 10.5046 13.6117 10.7789 13.6117C10.8017 13.6117 10.8246 13.6117 10.8474 13.6117C10.9617 13.5888 11.0531 13.5202 11.1217 13.4517C11.3274 13.2688 11.5331 13.0631 11.716 12.8574C11.8074 12.7659 11.876 12.6745 11.9674 12.6059L15.0989 9.40595L15.9674 8.51452C16.1503 8.33166 16.3331 8.24023 16.5388 8.24023C16.7217 8.24023 16.9046 8.30881 17.0417 8.46881C17.3617 8.78881 17.3617 9.22309 17.0417 9.56595C16.4474 10.1831 15.8531 10.7774 15.2589 11.3717L15.2131 11.4174C13.956 12.6974 12.676 14.0231 11.396 15.3717C11.3731 15.3945 11.3503 15.4402 11.3046 15.4631C11.1674 15.6231 11.0303 15.7602 10.8246 15.7831C10.8474 15.8059 10.8246 15.8059 10.8017 15.8059Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('All')}}
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{\App\Model\Order::count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/orders/list/pending')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.orders.list',['pending'])}}"
                                        title="{{\App\CPU\translate('pending')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 3.5C7.30521 3.5 3.5 7.30521 3.5 12C3.5 16.6948 7.30521 20.5 12 20.5C16.6948 20.5 20.5 16.6948 20.5 12C20.5 7.30521 16.6948 3.5 12 3.5ZM2 12C2 6.47679 6.47679 2 12 2C17.5232 2 22 6.47679 22 12C22 17.5232 17.5232 22 12 22C6.47679 22 2 17.5232 2 12Z" fill="white"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.6602 7.09766C12.0744 7.09766 12.4102 7.43344 12.4102 7.84766V12.2688L15.8144 14.2996C16.1701 14.5118 16.2865 14.9722 16.0743 15.3279C15.862 15.6836 15.4016 15.8 15.0459 15.5878L11.2759 13.3388C11.0491 13.2034 10.9102 12.9588 10.9102 12.6947V7.84766C10.9102 7.43344 11.2459 7.09766 11.6602 7.09766Z" fill="white"/>
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('pending')}}
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'pending'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/orders/list/confirmed')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.orders.list',['confirmed'])}}"
                                        title="{{\App\CPU\translate('confirmed')}}">
                                        <img src="{{ asset('public/assets/front-end/img/confirmationicon.png') }}">
                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('confirmed')}}
                                            <span class="badge badge-soft-success badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'confirmed'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="d-none nav-item {{Request::is('admin/orders/list/processing')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.orders.list',['processing'])}}"
                                        title="{{\App\CPU\translate('Packaging')}}">
                                        <img src="{{ asset('public/assets/front-end/img/package-icon.png') }}">
                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Packaging')}}
                                            <span class="badge badge-soft-warning badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'processing'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/orders/list/shipped')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.orders.list',['shipped'])}}"
                                        title="{{\App\CPU\translate('Packaging')}}">
                                        <img src="{{ asset('public/assets/front-end/img/package-icon.png') }}">
                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Shipped')}}
                                            <span class="badge badge-soft-warning badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'shipped'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li
                                class="nav-item {{Request::is('admin/orders/list/out_for_delivery')?'active':''}}">
                                <a class="nav-link " href="{{route('admin.orders.list',['out_for_delivery'])}}"
                                title="{{\App\CPU\translate('out_for_delivery')}}">
                                
                                <img src="{{ asset('public/assets/front-end/img/out-of-the-box.png') }}">
                                <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                <span class="text-truncate">
                                    {{\App\CPU\translate('out_for_delivery')}}
                                    <span class="badge badge-soft-warning badge-pill ml-1">
                                        {{\App\Model\Order::where(['order_status'=>'out_for_delivery'])->count()}}
                                    </span>
                                </span>
                            </a>
                        </li>
                        <li class="nav-item {{Request::is('admin/orders/list/delivered')?'active':''}}">
                            <a class="nav-link " href="{{route('admin.orders.list',['delivered'])}}"
                            title="{{\App\CPU\translate('delivered')}}">
                            <img src="{{ asset('public/assets/front-end/img/box.png') }}">
                            <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                            <span class="text-truncate">
                                {{\App\CPU\translate('delivered')}}
                                <span class="badge badge-soft-success badge-pill ml-1">
                                    {{\App\Model\Order::where(['order_status'=>'delivered'])->count()}}
                                </span>
                            </span>
                        </a>
                    </li>
                    <li class="nav-item {{Request::is('admin/orders/list/returned')?'active':''}}">
                        <a class="nav-link " href="{{route('admin.orders.list',['returned'])}}"
                        title="{{\App\CPU\translate('returned')}}">
                        <img src="{{ asset('public/assets/front-end/img/product-return.png') }}">
                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('returned')}}
                                            <span class="badge badge-soft-danger badge-pill ml-1">
                                                {{\App\Model\Order::where('order_status','returned')->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/orders/list/failed')?'active':''}} d-none">
                                    <a class="nav-link " href="{{route('admin.orders.list',['failed'])}}"
                                        title="{{\App\CPU\translate('failed')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Failed_to_Deliver')}}
                                            <span class="badge badge-soft-danger badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'failed'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/orders/list/canceled')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.orders.list',['canceled'])}}"
                                        title="{{\App\CPU\translate('canceled')}}">
                                          <img src="{{ asset('public/assets/front-end/img/cancel.png') }}">
                                        <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('canceled')}}
                                            <span class="badge badge-soft-danger badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'canceled'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                 <li class="nav-item {{Request::is('admin/delivery-man/assign-bulk-order')?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.delivery-man.assign-bulk-order')}}"
                                        title="{{\App\CPU\translate('Assign')}} {{\App\CPU\translate('Bulk')}} {{\App\CPU\translate('Orders')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M7.48549 6.96064C7.66189 6.96064 7.86035 6.96064 8.03675 6.96064C8.38957 6.96064 8.72033 6.96064 9.02904 6.93859C9.42595 6.91654 9.75671 6.58578 9.73466 6.21092C9.71261 5.83605 9.4039 5.52734 8.98494 5.52734C8.91878 5.52734 8.85263 5.52734 8.78648 5.52734C8.67623 5.52734 8.56597 5.52734 8.45572 5.52734C8.38957 5.52734 8.30137 5.52734 8.23521 5.52734H7.9706C7.81625 5.52734 7.63984 5.52734 7.48549 5.52734C7.08857 5.54939 6.75781 5.8581 6.75781 6.23297C6.75781 6.62988 7.08857 6.93859 7.48549 6.96064Z"
                                                fill="white" />
                                            <path
                                                d="M20.8247 7.00551C20.2293 4.02867 17.8038 2.02205 14.7828 2.02205C11.8721 2 8.93936 2 6.44763 2C6.22712 2 6.00662 2.02205 5.80816 2.06615C4.74973 2.30871 4.0882 3.19074 4.06615 4.31533C4.06615 5.52811 4.06615 6.7409 4.0441 7.93164L4.02205 14.2602C4.02205 16.0243 4 17.8104 4 19.5965C4 20.9857 4.97023 21.9559 6.35943 21.9559C10.4168 21.9779 14.4741 21.9779 18.5094 22H18.5755C19.9206 21.9779 20.8688 21.0077 20.8688 19.6406V19.4201V17.7663C20.8909 14.613 20.8909 11.3936 20.8909 8.2183C20.935 7.75524 20.8909 7.35832 20.8247 7.00551ZM5.5215 4.31533C5.5215 3.74201 5.85226 3.41125 6.42558 3.41125H6.64609V3.34509L6.66814 3.41125C9.40243 3.41125 12.1147 3.4333 14.849 3.4333C15.0033 3.4333 15.1577 3.45535 15.3341 3.45535H15.3561C15.4443 3.45535 15.5325 3.4774 15.6207 3.4774H15.6648V7.22602C15.6648 8.19625 16.2823 8.83572 17.2525 8.83572H19.3694C19.3694 8.83572 19.3914 8.83572 19.4135 8.83572L19.4796 8.85777V8.96803C19.4796 9.03418 19.4796 9.07828 19.4796 9.14443C19.4796 12.6284 19.4576 16.1125 19.4355 19.6185C19.4355 20.2139 19.1047 20.5447 18.5094 20.5447L6.35943 20.5006C6.07277 20.5006 5.83021 20.4123 5.67586 20.258C5.5215 20.1036 5.4333 19.8611 5.4333 19.5744L5.5215 4.31533ZM17.0981 7.40243L17.1202 4.02867L17.1863 4.07277C18.4212 4.84454 19.1709 5.92503 19.4576 7.35833V7.40243H17.0981Z"
                                                fill="white" />
                                            <path
                                                d="M9.18352 11.2838C8.6543 11.2838 8.14714 11.2838 7.61792 11.3058C7.35331 11.3058 7.11075 11.4381 6.97845 11.6366C6.84614 11.813 6.82409 12.0335 6.9123 12.254C7.02255 12.5407 7.28716 12.7171 7.66202 12.7171C8.25739 12.7171 8.85276 12.7171 9.44813 12.695H10.1758H11.0137C11.3665 12.695 11.7414 12.695 12.0942 12.673C12.3588 12.673 12.6014 12.5407 12.7337 12.3422C12.866 12.1658 12.888 11.9453 12.7998 11.7248C12.6896 11.4381 12.425 11.2617 12.0281 11.2617C11.5209 11.2617 10.9917 11.2617 10.4625 11.2838H9.18352Z"
                                                fill="white" />
                                            <path
                                                d="M9.07261 8.19588H8.65365C8.32289 8.19588 7.94803 8.19588 7.59522 8.21793C7.44086 8.21793 7.26446 8.26203 7.13215 8.35023C6.86754 8.48254 6.75729 8.8133 6.82344 9.09996C6.91164 9.40867 7.1542 9.60712 7.48496 9.62918H7.55111C8.41109 9.62918 9.38132 9.60713 10.528 9.58507C10.6382 9.58507 10.7485 9.54097 10.8808 9.49687C11.1454 9.36457 11.3218 9.05586 11.2556 8.7692C11.1674 8.39433 10.9028 8.17383 10.5059 8.17383H10.3516C10.0649 8.17383 9.77824 8.17383 9.49158 8.17383L9.07261 8.19588Z"
                                                fill="white" />
                                            <path
                                                d="M12.5547 18.1412C12.5547 18.3176 12.6429 18.494 12.7752 18.6263C12.9075 18.7586 13.0839 18.8468 13.2603 18.8468H13.2824C13.6352 18.8468 13.988 18.494 13.988 18.1412C13.988 17.7884 13.6572 17.4355 13.2824 17.4355C12.9075 17.4576 12.5547 17.7884 12.5547 18.1412Z"
                                                fill="white" />
                                            <path
                                                d="M16.3281 18.8683H16.3502C16.703 18.8683 17.0337 18.5375 17.0558 18.1847C17.0558 18.0083 16.9896 17.8539 16.8573 17.6996C16.725 17.5452 16.5266 17.457 16.3502 17.457C16.1737 17.457 15.9973 17.5452 15.865 17.6775C15.7327 17.8098 15.6445 17.9862 15.6445 18.1627C15.6445 18.5155 15.9973 18.8462 16.3281 18.8683Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Assign_Bulk_Order')}}
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/report/order')?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.report.order')}}"
                                        title="{{\App\CPU\translate('Order')}} {{\App\CPU\translate('Report')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M7.48549 6.96064C7.66189 6.96064 7.86035 6.96064 8.03675 6.96064C8.38957 6.96064 8.72033 6.96064 9.02904 6.93859C9.42595 6.91654 9.75671 6.58578 9.73466 6.21092C9.71261 5.83605 9.4039 5.52734 8.98494 5.52734C8.91878 5.52734 8.85263 5.52734 8.78648 5.52734C8.67623 5.52734 8.56597 5.52734 8.45572 5.52734C8.38957 5.52734 8.30137 5.52734 8.23521 5.52734H7.9706C7.81625 5.52734 7.63984 5.52734 7.48549 5.52734C7.08857 5.54939 6.75781 5.8581 6.75781 6.23297C6.75781 6.62988 7.08857 6.93859 7.48549 6.96064Z"
                                                fill="white" />
                                            <path
                                                d="M20.8247 7.00551C20.2293 4.02867 17.8038 2.02205 14.7828 2.02205C11.8721 2 8.93936 2 6.44763 2C6.22712 2 6.00662 2.02205 5.80816 2.06615C4.74973 2.30871 4.0882 3.19074 4.06615 4.31533C4.06615 5.52811 4.06615 6.7409 4.0441 7.93164L4.02205 14.2602C4.02205 16.0243 4 17.8104 4 19.5965C4 20.9857 4.97023 21.9559 6.35943 21.9559C10.4168 21.9779 14.4741 21.9779 18.5094 22H18.5755C19.9206 21.9779 20.8688 21.0077 20.8688 19.6406V19.4201V17.7663C20.8909 14.613 20.8909 11.3936 20.8909 8.2183C20.935 7.75524 20.8909 7.35832 20.8247 7.00551ZM5.5215 4.31533C5.5215 3.74201 5.85226 3.41125 6.42558 3.41125H6.64609V3.34509L6.66814 3.41125C9.40243 3.41125 12.1147 3.4333 14.849 3.4333C15.0033 3.4333 15.1577 3.45535 15.3341 3.45535H15.3561C15.4443 3.45535 15.5325 3.4774 15.6207 3.4774H15.6648V7.22602C15.6648 8.19625 16.2823 8.83572 17.2525 8.83572H19.3694C19.3694 8.83572 19.3914 8.83572 19.4135 8.83572L19.4796 8.85777V8.96803C19.4796 9.03418 19.4796 9.07828 19.4796 9.14443C19.4796 12.6284 19.4576 16.1125 19.4355 19.6185C19.4355 20.2139 19.1047 20.5447 18.5094 20.5447L6.35943 20.5006C6.07277 20.5006 5.83021 20.4123 5.67586 20.258C5.5215 20.1036 5.4333 19.8611 5.4333 19.5744L5.5215 4.31533ZM17.0981 7.40243L17.1202 4.02867L17.1863 4.07277C18.4212 4.84454 19.1709 5.92503 19.4576 7.35833V7.40243H17.0981Z"
                                                fill="white" />
                                            <path
                                                d="M9.18352 11.2838C8.6543 11.2838 8.14714 11.2838 7.61792 11.3058C7.35331 11.3058 7.11075 11.4381 6.97845 11.6366C6.84614 11.813 6.82409 12.0335 6.9123 12.254C7.02255 12.5407 7.28716 12.7171 7.66202 12.7171C8.25739 12.7171 8.85276 12.7171 9.44813 12.695H10.1758H11.0137C11.3665 12.695 11.7414 12.695 12.0942 12.673C12.3588 12.673 12.6014 12.5407 12.7337 12.3422C12.866 12.1658 12.888 11.9453 12.7998 11.7248C12.6896 11.4381 12.425 11.2617 12.0281 11.2617C11.5209 11.2617 10.9917 11.2617 10.4625 11.2838H9.18352Z"
                                                fill="white" />
                                            <path
                                                d="M9.07261 8.19588H8.65365C8.32289 8.19588 7.94803 8.19588 7.59522 8.21793C7.44086 8.21793 7.26446 8.26203 7.13215 8.35023C6.86754 8.48254 6.75729 8.8133 6.82344 9.09996C6.91164 9.40867 7.1542 9.60712 7.48496 9.62918H7.55111C8.41109 9.62918 9.38132 9.60713 10.528 9.58507C10.6382 9.58507 10.7485 9.54097 10.8808 9.49687C11.1454 9.36457 11.3218 9.05586 11.2556 8.7692C11.1674 8.39433 10.9028 8.17383 10.5059 8.17383H10.3516C10.0649 8.17383 9.77824 8.17383 9.49158 8.17383L9.07261 8.19588Z"
                                                fill="white" />
                                            <path
                                                d="M12.5547 18.1412C12.5547 18.3176 12.6429 18.494 12.7752 18.6263C12.9075 18.7586 13.0839 18.8468 13.2603 18.8468H13.2824C13.6352 18.8468 13.988 18.494 13.988 18.1412C13.988 17.7884 13.6572 17.4355 13.2824 17.4355C12.9075 17.4576 12.5547 17.7884 12.5547 18.1412Z"
                                                fill="white" />
                                            <path
                                                d="M16.3281 18.8683H16.3502C16.703 18.8683 17.0337 18.5375 17.0558 18.1847C17.0558 18.0083 16.9896 17.8539 16.8573 17.6996C16.725 17.5452 16.5266 17.457 16.3502 17.457C16.1737 17.457 15.9973 17.5452 15.865 17.6775C15.7327 17.8098 15.6445 17.9862 15.6445 18.1627C15.6445 18.5155 15.9973 18.8462 16.3281 18.8683Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Order_Report')}}
                                        </span>
                                    </a>
                                </li>


                                <!-- refund section -->
                                <li
                                    class="d-none navbar-vertical-aside-has-menu sub-menu1 {{Request::is('admin/refund-section/refund/*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:"
                                        title="{{\App\CPU\translate('Refund_Requests')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M21.8594 21.1341C21.6472 20.8335 21.3289 20.8335 21.2228 20.8335H21.2051C20.9399 20.8335 20.6746 20.8335 20.4271 20.8335H19.4898V15.44H20.4094C20.6746 15.44 20.9399 15.44 21.2051 15.44H21.2228C21.3289 15.44 21.6472 15.44 21.8594 15.1394L21.9125 15.0686V14.6796L21.8594 14.6089C21.6649 14.3259 21.3996 14.3082 21.2051 14.3082C20.8515 14.3082 20.5155 14.3082 20.1618 14.3082C19.7728 14.3082 19.3661 14.3082 18.977 14.3082C18.924 14.3082 18.8532 14.2906 18.8179 14.2729C18.0044 13.7777 17.1202 13.5302 16.2361 13.5302C16.1477 13.5302 16.0592 13.5302 15.9708 13.5302C15.3696 13.5655 14.7683 13.6893 14.0256 13.9369L13.7958 14.0253C13.2653 14.2022 12.7701 14.379 12.2396 14.4144C11.4262 14.4497 10.6127 14.4674 9.85234 14.4851C9.41026 14.4851 8.89743 14.5558 8.47303 14.9272L7.51812 14.3436C6.72237 13.8662 5.87356 13.3533 5.02475 12.8582C4.70645 12.6814 4.40583 12.5399 4.1229 12.4692C3.96375 12.4161 3.78691 12.3984 3.62776 12.3984C2.88505 12.3984 2.26613 12.8936 2.05393 13.6716C1.8771 14.3436 2.14235 15.0333 2.74359 15.4754C2.95579 15.6345 3.16799 15.7937 3.39788 15.9528C4.21132 16.5541 4.95402 17.1022 5.71441 17.6151C6.95225 18.4639 8.03094 19.1535 9.02122 19.7548C10.1706 20.4444 11.4615 20.7981 12.8585 20.7981C12.9116 20.7981 12.9469 20.7981 13 20.7981C13.5659 20.7804 14.2202 20.7804 15.0867 20.7804C15.6525 20.7804 16.2007 20.7804 16.7666 20.7804H16.8373C17.3501 20.7804 17.863 20.7804 18.3581 20.7804V21.3286C18.3581 21.7707 18.5703 22.0006 19.0124 22.0006H20.2325C20.5685 22.0006 20.8868 22.0006 21.2228 22.0006H21.2405C21.3466 22.0006 21.6649 22.0006 21.8771 21.6999L21.9301 21.6292V21.2402L21.8594 21.1341ZM3.09725 14.1314C3.07957 13.9899 3.13262 13.8308 3.25641 13.6716C3.34482 13.5479 3.45093 13.4948 3.55703 13.4948C3.59239 13.4948 3.62776 13.4948 3.68081 13.5125C3.94606 13.5832 4.17595 13.6716 4.35278 13.7777C5.53757 14.4674 6.70468 15.1747 7.97789 15.9528C7.94253 16.5894 8.24315 17.0846 8.86207 17.4205C9.49867 17.7565 10.1883 17.8273 10.7188 17.8626C11.4615 17.898 12.2042 17.9157 12.9293 17.951L14.6269 18.0218C14.6622 18.0218 14.6976 18.0218 14.7507 18.0218C15.0866 18.0218 15.3342 17.7919 15.3519 17.4736C15.3696 17.1376 15.122 16.89 14.7683 16.8724C14.2555 16.837 13.7427 16.8193 13.2299 16.8016C12.9293 16.7839 12.6286 16.7839 12.328 16.7663H12.2927C11.6561 16.7486 11.0018 16.7309 10.3652 16.6778C10.0292 16.6602 9.69319 16.5187 9.3572 16.3949C9.18037 16.3242 9.10964 16.2004 9.12732 16.0059C9.16269 15.6876 9.30416 15.6522 9.42794 15.6345C9.62246 15.6168 9.83466 15.5991 10.0999 15.5991C10.2237 15.5991 10.3652 15.5991 10.4889 15.5991C10.6304 15.5991 10.7719 15.5991 10.931 15.5991C11.6914 15.5991 12.3103 15.5461 12.8939 15.4046C13.2652 15.3162 13.6189 15.2101 13.9726 15.0863L14.008 15.0686C14.1671 15.0156 14.3263 14.9625 14.5031 14.9095C14.9982 14.768 15.5818 14.6089 16.2007 14.6089C16.3599 14.6089 16.5013 14.6266 16.6428 14.6442C17.244 14.715 17.8099 14.9449 18.3404 15.2808C18.3404 16.3949 18.3404 17.509 18.3404 18.623V19.4365V19.5779C17.8276 19.5779 17.3324 19.5779 16.8196 19.5779C16.2891 19.5779 15.7586 19.5779 15.2281 19.5779C14.397 19.5779 13.725 19.5779 13.1415 19.5956C13.0884 19.5956 13.0177 19.5956 12.9646 19.5956C11.6737 19.5956 10.4536 19.2419 9.21574 18.5169C7.80106 17.6858 6.45712 16.7132 5.14854 15.7583L5.06012 15.6876C4.47657 15.2632 3.87533 14.8211 3.43324 14.4851C3.22104 14.4144 3.13262 14.2729 3.09725 14.1314Z"
                                                fill="white" />
                                            <path
                                                d="M9.83409 10.3996L11.3018 12.1149C11.4256 12.2564 11.5848 12.4156 11.8146 12.4156C12.0445 12.4156 12.2214 12.2564 12.3275 12.1326L17.491 6.08488C17.5971 5.9611 17.7916 5.73121 17.6502 5.39523C17.491 5.04156 17.155 5.04156 17.0136 5.04156C17.0136 5.04156 16.3947 5.04156 16.094 5.04156H15.0507V4.22812C15.0507 3.7153 15.0507 3.20248 15.0507 2.70734C15.0507 2.60124 15.0507 2.26525 14.7501 2.05305L14.6794 2H8.9676L8.89686 2.05305C8.57856 2.26525 8.57856 2.60124 8.59624 2.70734C8.59624 3.29089 8.59624 3.87445 8.59624 4.458V5.04156H7.53524C7.23462 5.04156 6.91632 5.04156 6.59801 5.04156C6.45655 5.04156 6.12056 5.04156 5.96141 5.39523C5.81994 5.71353 6.03214 5.9611 6.12056 6.08488C7.37608 7.51724 8.61393 8.9496 9.83409 10.3996ZM9.72798 5.43059V3.13174H13.9013V5.48364C13.9013 5.9611 14.1135 6.1733 14.5909 6.1733H15.8995L11.797 10.9655L7.69439 6.1733H8.94991C9.55115 6.1733 9.72798 5.99646 9.72798 5.43059Z"
                                                fill="white" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('Refund_Requests')}}
                                        </span>
                                    </a>


                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{Request::is('admin/refund-section/refund*')?'block':'none'}}">
                                        <li
                                            class="nav-item {{Request::is('admin/refund-section/refund/list/pending')?'active':''}}">
                                            <a class="nav-link"
                                                href="{{route('admin.refund-section.refund.list',['pending'])}}"
                                                title="{{\App\CPU\translate('pending')}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M21.25 12.0005C21.25 17.1095 17.109 21.2505 12 21.2505C6.891 21.2505 2.75 17.1095 2.75 12.0005C2.75 6.89149 6.891 2.75049 12 2.75049C17.109 2.75049 21.25 6.89149 21.25 12.0005Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M15.4302 14.9427L11.6602 12.6937V7.84668" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                <span class="text-truncate">
                                                    {{\App\CPU\translate('pending')}}
                                                    <span class="badge badge-soft-danger badge-pill ml-1">
                                                        {{\App\Model\RefundRequest::where('status','pending')->count()}}
                                                    </span>
                                                </span>
                                            </a>
                                        </li>

                                        <li
                                            class="nav-item {{Request::is('admin/refund-section/refund/list/approved')?'active':''}}">
                                            <a class="nav-link"
                                                href="{{route('admin.refund-section.refund.list',['approved'])}}"
                                                title="{{\App\CPU\translate('approved')}}">
                                               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M22.0015 11.8471C21.9478 11.6907 21.927 11.5343 22.0015 11.3779C22.0015 11.5343 22.0015 11.6907 22.0015 11.8471Z" fill="#383838"/>
                                                    <path d="M22.0015 12.6288C21.927 12.4724 21.9478 12.316 22.0015 12.1597C22.0015 12.316 22.0015 12.4724 22.0015 12.6288Z" fill="#383838"/>
                                                    <path d="M11.3906 22.007C11.547 21.9325 11.7022 21.9544 11.8585 22.007C11.7034 22.007 11.547 22.007 11.3906 22.007Z" fill="#3C3C3C"/>
                                                    <path d="M22 11.2607C21.9585 11.031 21.978 10.7977 21.9462 10.5667C21.7764 9.32792 21.3696 8.16972 20.7673 7.07628C20.3152 6.2565 19.7459 5.52102 19.0874 4.86373C18.4655 4.24309 17.7704 3.7092 17.0007 3.27426C15.9842 2.70005 14.9079 2.30299 13.7582 2.09896C13.4003 2.03543 13.0386 2.04276 12.6794 2C12.6538 2 12.6269 2 12.6013 2C12.4583 2.03176 12.3154 2.03176 12.1724 2C12.0686 2 11.9647 2 11.8609 2C11.7179 2.03176 11.575 2.03176 11.4321 2C11.3795 2 11.3282 2 11.2757 2C11.0582 2.03543 10.8371 2.01955 10.6208 2.04765C9.5616 2.18937 8.55123 2.49969 7.59584 2.97372C6.4975 3.51861 5.52868 4.24065 4.68935 5.13984C3.95387 5.92785 3.37477 6.81238 2.90807 7.78243C2.10051 9.46353 1.89526 11.2448 2.04553 13.075C2.14816 14.3333 2.53178 15.5184 3.11577 16.6424C3.73152 17.825 4.54763 18.8403 5.56289 19.6979C6.93977 20.861 8.51947 21.5941 10.2934 21.9105C10.6062 21.9667 10.9238 21.9594 11.2366 22.007C11.2891 22.007 11.3404 22.007 11.393 22.007C11.5493 21.974 11.7045 21.974 11.8609 22.007C11.9647 22.007 12.0686 22.007 12.1724 22.007C12.3154 21.9557 12.4583 21.93 12.6013 22.007C12.6403 22.007 12.6794 22.007 12.7185 22.007C12.9482 21.9704 13.1816 21.9899 13.4113 21.9594C14.4681 21.8164 15.4784 21.5085 16.4338 21.0357C17.7007 20.4078 18.793 19.5538 19.7007 18.4652C20.8552 17.0822 21.5895 15.505 21.9035 13.7298C21.9585 13.4158 21.9524 13.0982 22 12.7854C22 12.7329 22 12.6816 22 12.629C21.967 12.4726 21.967 12.3163 22 12.1599C22 12.056 22 11.951 22 11.8471C21.967 11.6907 21.967 11.5344 22 11.378C22 11.3389 22 11.2998 22 11.2607ZM12.0209 20.4432C7.37348 20.4469 3.59713 16.6668 3.59346 12.0072C3.5898 7.35116 7.36249 3.56625 12.0124 3.56259C16.6598 3.55892 20.4362 7.33895 20.4399 11.9986C20.4435 16.6558 16.6708 20.4395 12.0209 20.4432Z" fill="white"/>
                                                    <path d="M10.6729 15.4034C10.4115 15.4059 10.2209 15.3057 10.0584 15.1432C9.24961 14.3332 8.4396 13.5257 7.63448 12.712C7.29362 12.3675 7.28507 11.902 7.60028 11.5807C7.91182 11.263 8.38585 11.2655 8.73037 11.6051C9.32291 12.1891 9.91056 12.7792 10.4921 13.3742C10.6069 13.4914 10.6631 13.5061 10.789 13.379C12.2722 11.8824 13.7627 10.3944 15.2495 8.90264C15.4926 8.65951 15.7651 8.5349 16.1096 8.64241C16.6117 8.79879 16.8145 9.39744 16.5103 9.82871C16.4578 9.90201 16.3943 9.9692 16.3295 10.0327C14.6496 11.7175 12.9673 13.401 11.2874 15.087C11.1091 15.2654 10.9148 15.4059 10.6729 15.4034Z" fill="white"/>
                                                    </svg>
                                                <span class="text-truncate">
                                                    {{\App\CPU\translate('approved')}}
                                                    <span class="badge badge-soft-info badge-pill ml-1">
                                                        {{\App\Model\RefundRequest::where('status','approved')->count()}}
                                                    </span>
                                                </span>
                                            </a>
                                        </li>
                                        <li
                                            class="nav-item {{Request::is('admin/refund-section/refund/list/refunded')?'active':''}}">
                                            <a class="nav-link"
                                                href="{{route('admin.refund-section.refund.list',['refunded'])}}"
                                                title="{{\App\CPU\translate('refunded')}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M11.8911 22C11.736 21.8759 11.5699 21.7628 11.4278 21.6247C9.78639 20.0254 8.14903 18.423 6.51066 16.8207C6.46862 16.7796 6.42559 16.7396 6.38656 16.6956C6.22142 16.5074 6.17338 16.2922 6.27546 16.062C6.37655 15.8329 6.5617 15.7108 6.81391 15.7098C7.46446 15.7067 8.116 15.7087 8.76654 15.7087C8.8366 15.7087 8.90666 15.7087 8.98873 15.7087C8.99273 15.6497 8.99873 15.6057 8.99873 15.5616C8.99974 14.852 8.99673 14.1424 9.00174 13.4329C9.00274 13.3228 8.96771 13.2617 8.87363 13.1997C6.94302 11.9326 5.9602 10.1461 6.00123 7.83318C6.04927 5.07187 8.19707 2.61082 10.9013 2.1054C14.167 1.49489 17.2696 3.59965 17.9702 6.7863C18.5047 9.21633 17.4188 11.8215 15.271 13.1676C15.1399 13.2497 15.0888 13.3348 15.0908 13.4909C15.0998 14.2195 15.0948 14.9481 15.0948 15.7087C15.1709 15.7087 15.2399 15.7087 15.308 15.7087C15.9455 15.7087 16.5841 15.7077 17.2216 15.7087C17.5248 15.7098 17.734 15.8359 17.8261 16.0791C17.9262 16.3413 17.8481 16.5625 17.654 16.7526C15.9365 18.432 14.2191 20.1114 12.4987 21.7888C12.4126 21.8719 12.3015 21.9299 12.2024 22C12.0993 22 11.9952 22 11.8911 22ZM12.0533 3.17029C9.36504 3.16728 7.16921 5.35711 7.16621 8.04535C7.1632 10.7336 9.35503 12.9304 12.0423 12.9334C14.7305 12.9364 16.9273 10.7436 16.9294 8.05737C16.9314 5.36812 14.7425 3.17429 12.0533 3.17029ZM12.0483 20.5948C13.3123 19.3588 14.5614 18.1378 15.8454 16.8817C15.3921 16.8817 14.9917 16.8827 14.5904 16.8817C14.16 16.8807 13.9238 16.6475 13.9228 16.2212C13.9218 15.4926 13.9228 14.763 13.9228 14.0344C13.9228 13.9673 13.9228 13.8992 13.9228 13.8112C12.6598 14.2045 11.4238 14.2025 10.1717 13.8112C10.1717 13.9052 10.1717 13.9763 10.1717 14.0464C10.1717 14.769 10.1727 15.4916 10.1717 16.2142C10.1707 16.6445 9.93752 16.8807 9.51116 16.8827C9.17288 16.8837 8.8346 16.8827 8.49531 16.8827C8.42826 16.8827 8.3612 16.8827 8.25011 16.8827C9.53618 18.1388 10.7822 19.3568 12.0483 20.5948Z" fill="white"/>
                                                    </svg>
                                                <span class="text-truncate">
                                                    {{\App\CPU\translate('refunded')}}
                                                    <span class="badge badge-soft-success badge-pill ml-1">
                                                        {{\App\Model\RefundRequest::where('status','refunded')->count()}}
                                                    </span>
                                                </span>
                                            </a>
                                        </li>
                                        <li
                                            class="nav-item {{Request::is('admin/refund-section/refund/list/rejected')?'active':''}}">
                                            <a class="nav-link"
                                                href="{{route('admin.refund-section.refund.list',['rejected'])}}"
                                                title="{{\App\CPU\translate('rejected')}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M12.0132 2C6.51569 1.99498 2.00881 6.46791 2.00001 11.939C1.99121 17.4931 6.44906 21.9899 11.9755 22C17.4919 22.0088 21.9937 17.5321 21.9987 12.0295C22.005 6.49683 17.5333 2.00629 12.0132 2ZM11.9755 20.6661C7.19832 20.6485 3.32379 16.7577 3.33385 11.9855C3.34391 7.19957 7.24358 3.3175 12.0245 3.33384C16.8029 3.35018 20.6775 7.24232 20.6662 12.0145C20.6548 16.8017 16.7552 20.6837 11.9755 20.6661Z" fill="white"/>
                                                    <path d="M7.3719 15.9733C7.36813 15.747 7.46744 15.5886 7.61075 15.4466C8.71202 14.3478 9.80825 13.2453 10.9133 12.1516C11.039 12.0271 11.0327 11.9681 10.9108 11.8486C9.80574 10.7537 8.70824 9.65365 7.6095 8.55238C7.32412 8.26701 7.28893 7.89489 7.51395 7.61706C7.7679 7.30278 8.21796 7.28392 8.52219 7.58312C9.0263 8.0797 9.52539 8.58255 10.0257 9.0829C10.6392 9.69639 11.2565 10.3074 11.8637 10.9259C11.9731 11.0378 12.0271 11.0365 12.1365 10.9259C13.2415 9.81205 14.3516 8.70324 15.4617 7.59569C15.7345 7.32289 16.1091 7.29272 16.3819 7.51398C16.6962 7.76918 16.7163 8.21672 16.4158 8.52221C15.9783 8.9685 15.5333 9.40724 15.092 9.8485C14.4195 10.5211 13.7507 11.1962 13.0718 11.8625C12.9587 11.9731 12.9637 12.0271 13.0731 12.1353C14.1819 13.2353 15.2831 14.3428 16.3894 15.4453C16.5943 15.6502 16.6811 15.8841 16.5969 16.1619C16.4561 16.627 15.8853 16.7817 15.5195 16.4573C15.3799 16.3329 15.253 16.1946 15.1197 16.0613C14.1228 15.0644 13.1246 14.07 12.134 13.0693C12.0208 12.9549 11.968 12.965 11.8612 13.0731C10.7612 14.1819 9.65488 15.2844 8.54984 16.3882C8.22424 16.7125 7.77293 16.7062 7.5127 16.3781C7.4159 16.2574 7.36184 16.1166 7.3719 15.9733Z" fill="white"/>
                                                    </svg>
                                                <span class="text-truncate">
                                                    {{\App\CPU\translate('rejected')}}
                                                    <span class="badge badge-soft-danger badge-pill ml-1">
                                                        {{\App\Model\RefundRequest::where('status','rejected')->count()}}
                                                    </span>
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <!-- end refund section -->
                            </ul>
                        </li>




                        @endif
                        <!--Order Management Ends-->

                        <!--Product Management -->
                        @if(\App\CPU\Helpers::module_permission_check('product_management'))
                        <li
                            class="nav-item {{(Request::is('admin/brand*') || Request::is('admin/category*') || Request::is('admin/sub*') || Request::is('admin/attribute*') || Request::is('admin/product*'))?'scroll-here':''}}  ">
                            <small class="nav-subtitle" title="">{{\App\CPU\translate('product_management')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>


                        <!-- Product management  -->
                        <li
                            class="navbar-vertical-aside-has-menu {{(Request::is('admin/product/list/in_house') || Request::is('admin/category*') || Request::is('admin/product/bulk-import') || (Request::is('admin/product/add-new')) ||  (Request::is('admin/product/barcode/*')))?'active':''}} ">
                            <a class="js-navbar-vertical-aside-menu-link nav-link  its-drop" href="javascript:"
                                title="{{\App\CPU\translate('Product_Management')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M15.7729 9.30507V6.27307C15.7729 4.18907 14.0839 2.50004 12.0009 2.50004C9.91694 2.49107 8.21994 4.17207 8.21094 6.25607V6.27307V9.30507"
                                        stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M16.7422 21.0004H7.25778C4.90569 21.0004 3 19.0954 3 16.7454V11.2294C3 8.87936 4.90569 6.97437 7.25778 6.97437H16.7422C19.0943 6.97437 21 8.87936 21 11.2294V16.7454C21 19.0954 19.0943 21.0004 16.7422 21.0004Z"
                                        stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    <span class="text-truncate">{{\App\CPU\translate('Product_Management')}}</span>
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{(Request::is('admin/product/list/in_house') || Request::is('admin/category*') || Request::is('admin/sub-category*') || Request::is('admin/sub-sub-category*') || Request::is('admin/attribute*') || Request::is('admin/brand*') ||  Request::is('admin/category/edit/*') || (Request::is('admin/product/stock-limit-list/in_house')) || (Request::is('admin/product/bulk-import')) || (Request::is('admin/product/add-new')) || (Request::is('admin/product/barcode/*')))?'block':''}}">

                                <!-- Category -->

                                <li class="nav-item {{Request::is('admin/category/view') || Request::is('admin/category/edit/*')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.category.view')}}"
                                        title="{{\App\CPU\translate('Categories')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M2.98153 7.96306C2.44169 7.96306 2 7.52137 2 6.98153C2 6.44169 2.44169 6 2.98153 6C3.52137 6 3.96306 6.44169 3.96306 6.98153C3.96306 7.52137 3.52137 7.96306 2.98153 7.96306Z"
                                                fill="white" />
                                            <path
                                                d="M2.98153 13.3615C2.44169 13.3615 2 12.9198 2 12.38C2 11.8401 2.44169 11.3984 2.98153 11.3984C3.52137 11.3984 3.96306 11.8401 3.96306 12.38C3.96306 12.9198 3.52137 13.3615 2.98153 13.3615Z"
                                                fill="white" />
                                            <path
                                                d="M14.2943 6.02539C16.5027 6.02539 18.6866 6.02539 20.8951 6.02539C21.5331 6.02539 21.9502 6.39346 21.9993 6.93331C22.0238 7.44861 21.7048 7.86576 21.165 7.96391C21.0668 7.98845 20.9687 7.98845 20.846 7.98845C16.4782 7.98845 12.1104 7.98845 7.74256 7.98845C7.27633 7.98845 6.88372 7.84122 6.68742 7.42407C6.36842 6.81061 6.81011 6.07447 7.52172 6.04993C8.03702 6.02539 8.55232 6.04993 9.06763 6.04993C10.8098 6.02539 12.5521 6.02539 14.2943 6.02539Z"
                                                fill="white" />
                                            <path
                                                d="M12.9932 11.3984C14.76 11.3984 16.5022 11.3984 18.269 11.3984C18.7352 11.3984 19.1033 11.6193 19.2505 12.0119C19.3977 12.4045 19.3241 12.748 19.0296 13.0425C18.7843 13.2879 18.4898 13.337 18.1708 13.337C14.9808 13.337 11.7663 13.337 8.57634 13.337C8.25735 13.337 7.93835 13.337 7.64389 13.337C7.05497 13.3124 6.61328 12.8953 6.61328 12.3554C6.61328 11.8156 7.05497 11.3984 7.64389 11.3984C9.41065 11.3984 11.2019 11.3984 12.9932 11.3984Z"
                                                fill="white" />
                                            <path
                                                d="M2.98153 18.7111C2.44169 18.7111 2 18.2694 2 17.7296C2 17.1897 2.44169 16.748 2.98153 16.748C3.52137 16.748 3.96306 17.1897 3.96306 17.7296C3.96306 18.2694 3.52137 18.7111 2.98153 18.7111Z"
                                                fill="white" />
                                            <path
                                                d="M11.7172 16.748C13.1159 16.748 14.5391 16.748 15.9378 16.748C16.3059 16.748 16.6004 16.9689 16.723 17.3615C16.8457 17.7541 16.7721 18.0977 16.5513 18.3921C16.355 18.6375 16.1341 18.6866 15.8642 18.6866C13.3122 18.6866 10.7357 18.6866 8.18373 18.6866C7.93835 18.6866 7.69297 18.6866 7.42305 18.6866C6.95682 18.662 6.61328 18.2449 6.61328 17.705C6.61328 17.1652 6.95682 16.748 7.44758 16.748C8.84626 16.748 10.2695 16.748 11.7172 16.748Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('Categories')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/sub-category/view')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.sub-category.view')}}"
                                        title="{{\App\CPU\translate('Sub_Categories')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M16.3461 21.9287C15.5138 21.9287 14.8955 21.5244 14.515 20.7396C14.4198 20.5731 14.3247 20.5018 14.1582 20.5018H14.1345C13.968 20.5018 13.8015 20.5018 13.6351 20.5018H12.9692C12.3984 20.5018 12.1606 20.264 12.1606 19.6932V12.6302H8.40319C7.30925 12.6302 6.71472 12.6302 6.66716 12.6302C6.28666 12.6302 5.9775 12.321 6.00129 11.9643C6.00129 11.6076 6.28666 11.3222 6.66716 11.2985C6.7385 11.2985 7.35682 11.2985 8.47453 11.2985H12.1844V4.23543C12.1844 3.66468 12.4222 3.42687 12.993 3.42687H14.3247L14.3485 3.40309C14.4436 3.28418 14.5387 3.16528 14.6101 3.07015C14.7766 2.83234 14.943 2.61831 15.1333 2.45184C15.49 2.14269 15.9418 2 16.4175 2C17.1071 2 17.7492 2.33294 18.1297 2.92747C18.4626 3.40309 18.5815 3.99762 18.4389 4.56837C18.2962 5.13912 17.9395 5.63853 17.44 5.9239C17.1309 6.11415 16.7504 6.20927 16.3937 6.20927C15.5851 6.20927 14.8717 5.75743 14.5387 5.04399C14.4436 4.85374 14.3485 4.7824 14.1582 4.7824C14.1345 4.7824 14.1107 4.7824 14.0869 4.7824C14.0156 4.7824 13.9442 4.7824 13.8729 4.7824C13.8253 4.7824 13.754 4.7824 13.7064 4.7824C13.6588 4.7824 13.5875 4.7824 13.5161 4.7824H13.4686V11.3222C13.4686 11.3222 13.8491 11.3222 13.9204 11.3222C14.0393 11.3222 14.1582 11.3222 14.2771 11.3222C14.3723 11.3222 14.4436 11.2033 14.4674 11.1082C14.8479 10.371 15.4186 9.94292 16.2034 9.89536C16.2748 9.89536 16.3223 9.89536 16.3937 9.89536C17.1309 9.89536 17.7016 10.2283 18.1297 10.8704C18.4389 11.346 18.534 11.9405 18.3913 12.5113C18.2486 13.082 17.8919 13.5577 17.3687 13.843C17.0596 14.0095 16.7028 14.1046 16.3699 14.1046C15.5613 14.1046 14.8479 13.6528 14.4912 12.9156C14.4198 12.7491 14.3009 12.6778 14.1582 12.6778C14.1345 12.6778 14.1345 12.6778 14.1107 12.6778C14.0393 12.6778 13.968 12.6778 13.8966 12.6778C13.8491 12.6778 13.4924 12.6778 13.4924 12.6778V19.2176C13.4924 19.2176 13.8729 19.2176 13.9442 19.2176C14.0631 19.2176 14.182 19.2176 14.3009 19.2176C14.3961 19.2176 14.4912 19.0511 14.4912 19.0036C14.8479 18.2426 15.5851 17.7907 16.3937 17.7907C17.0358 17.7907 17.6303 18.0999 18.0346 18.6231C18.5578 19.3127 18.6291 20.0737 18.2486 20.8347C17.8681 21.5957 17.226 22 16.3937 22L16.3461 21.9287ZM16.3461 19.0511C15.9418 19.0511 15.6089 19.4078 15.6089 19.8359C15.6089 20.2402 15.9656 20.5731 16.3699 20.5731C16.798 20.5731 17.1309 20.2164 17.1309 19.8121C17.1309 19.4078 16.798 19.0511 16.3461 19.0511ZM16.3699 11.2033C15.9418 11.2033 15.6089 11.5363 15.6089 11.9643C15.6089 12.1784 15.6802 12.3686 15.8229 12.5113C15.9656 12.654 16.1559 12.7491 16.3699 12.7491C16.798 12.7491 17.1309 12.3924 17.1309 11.9881C17.1309 11.5838 16.798 11.2033 16.3699 11.2033ZM16.3699 3.30797C15.9656 3.30797 15.6089 3.6409 15.6089 4.06896C15.6089 4.283 15.6802 4.47325 15.8229 4.61593C15.9656 4.75862 16.1559 4.85375 16.3699 4.85375C16.798 4.85375 17.1309 4.49702 17.1309 4.09274C17.1309 3.68846 16.798 3.33175 16.3699 3.30797Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('Sub_Categories')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/sub-sub-category/view')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.sub-sub-category.view')}}"
                                        title="{{\App\CPU\translate('Sub_Sub_Categories')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M14.895 18.6587C14.4893 18.1337 13.8926 17.8234 13.2482 17.8234C12.4368 17.8234 11.6969 18.3007 11.3389 19.0406C11.315 19.0644 11.2434 19.2315 11.148 19.2554C11.0286 19.2554 10.9093 19.2554 10.79 19.2554C10.7184 19.2554 10.3365 19.2554 10.3365 19.2554V19.2315V15.3174C10.3365 15.3174 10.6945 15.3174 10.7422 15.3174C10.8138 15.3174 10.8854 15.3174 10.957 15.3174C10.9809 15.3174 10.9809 15.3174 11.0048 15.3174C11.1718 15.3174 11.2673 15.389 11.3389 15.5561C11.6969 16.2959 12.4129 16.7494 13.2243 16.7494C13.5823 16.7494 13.9165 16.6539 14.2267 16.4869C14.7279 16.2005 15.1098 15.7231 15.253 15.1504C15.3962 14.5776 15.3007 14.0048 14.9905 13.5036C14.5609 12.8592 13.9881 12.5251 13.2482 12.5251C13.2005 12.5251 13.1289 12.5251 13.0573 12.5251C12.2697 12.5728 11.673 13.0024 11.315 13.7422C11.2912 13.8138 11.1957 13.9332 11.1241 13.957C11.0048 13.957 10.8854 13.957 10.7661 13.957C10.6945 13.957 10.3126 13.957 10.3126 13.957V11.2363V10.0668C10.3126 10.0668 10.6706 10.0668 10.7184 10.0668C10.79 10.0668 10.8616 10.0668 10.9332 10.0668C10.957 10.0668 10.957 10.0668 10.9809 10.0668C11.148 10.0668 11.2434 10.1384 11.315 10.3055C11.673 11.0453 12.389 11.4988 13.2005 11.4988C13.5585 11.4988 13.8926 11.4033 14.2029 11.2363C14.7041 10.9499 15.0859 10.4726 15.2291 9.89976C15.3723 9.32697 15.2768 8.75418 14.9666 8.25298C14.537 7.60859 13.9642 7.27446 13.2243 7.27446C13.1766 7.27446 13.105 7.27446 13.0334 7.27446C12.2458 7.3222 11.6492 7.72792 11.2912 8.49165C11.2673 8.56324 11.1718 8.68258 11.1002 8.70645C10.9809 8.70645 10.8616 8.73031 10.7422 8.73031C10.6706 8.73031 10.2888 8.73031 10.2888 8.73031V4.79236H10.3365C10.4081 4.79236 10.4558 4.79236 10.5274 4.79236C10.5752 4.79236 10.6468 4.79236 10.6945 4.79236C10.7661 4.79236 10.8377 4.79236 10.9093 4.79236C10.9332 4.79236 10.957 4.79236 10.9809 4.79236C11.148 4.79236 11.2673 4.86396 11.3628 5.05489C11.7208 5.79475 12.4368 6.22434 13.2243 6.22434C13.5823 6.22434 13.9642 6.12888 14.2745 5.93795C14.7757 5.65155 15.1336 5.15036 15.2768 4.57757C15.42 4.00477 15.3007 3.40812 14.9666 2.93079C14.5847 2.358 13.9165 2 13.2482 2C12.7709 2 12.3174 2.16707 11.9594 2.45346C11.7685 2.62053 11.6014 2.83532 11.4344 3.07399C11.3389 3.19332 11.2673 3.31265 11.1718 3.40812L11.148 3.43198H9.81145C9.23866 3.43198 9 3.67065 9 4.24344V5.12649V18.1098V19.7566C9 20.3294 9.23866 20.568 9.81145 20.568H10.4797C10.6468 20.568 10.8138 20.568 10.9809 20.568H11.0048C11.1957 20.568 11.2912 20.6396 11.3628 20.8067C11.7446 21.5943 12.3652 22 13.2005 22H13.2243C14.0597 22 14.7041 21.5943 15.0859 20.8306C15.4916 20.1146 15.3962 19.3508 14.895 18.6587ZM13.2243 13.8854C13.6539 13.8854 13.9881 14.2196 13.9881 14.6492C13.9881 15.0788 13.6539 15.4129 13.2243 15.4129C13.0095 15.4129 12.8186 15.3413 12.6754 15.1742C12.5322 15.0072 12.4606 14.8401 12.4606 14.6253C12.4606 14.2196 12.8186 13.8854 13.2243 13.8854ZM13.2243 8.65871C13.6539 8.65871 13.9881 8.99284 13.9881 9.42244C13.9881 9.85203 13.6539 10.1862 13.2243 10.1862C13.0095 10.1862 12.8186 10.1146 12.6754 9.94749C12.5322 9.8043 12.4606 9.61337 12.4606 9.39857C12.4606 8.99284 12.8186 8.65871 13.2243 8.65871ZM13.2243 3.38425C13.6539 3.38425 13.9881 3.71838 13.9881 4.14797C13.9881 4.57757 13.6539 4.91169 13.2243 4.91169C13.0095 4.91169 12.8186 4.84009 12.6754 4.67303C12.5322 4.52983 12.4606 4.3389 12.4606 4.12411C12.4606 3.71838 12.8186 3.38425 13.2243 3.38425ZM13.2243 20.7112C12.8186 20.7112 12.4606 20.3771 12.4606 19.9714C12.4606 19.5656 12.7947 19.2076 13.2005 19.1838H13.2243C13.6539 19.1838 13.9881 19.5179 13.9881 19.9475C13.9881 20.3532 13.6539 20.6874 13.2243 20.7112Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('Sub_Sub_Categories')}}</span>
                                    </a>
                                </li>
                                <!-- Category End -->


                              
                                <li
                                    class="d-none nav-item {{(Request::is('admin/product/list/in_house') ||  (Request::is('admin/product/view/*')) || (Request::is('admin/product/barcode/*')))?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.product.list',['in_house', ''])}}"
                                        title="{{\App\CPU\translate('Products_list')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M15.0424 9.48466C14.2164 9.48466 13.3653 9.48466 12.5393 9.48466C12.0637 9.48466 11.7383 9.18428 11.7383 8.75875C11.7383 8.33322 12.0637 8.00781 12.5393 8.00781C14.2164 8.00781 15.8935 8.00781 17.5455 8.00781C17.9711 8.00781 18.2965 8.25813 18.3465 8.65863C18.3966 8.98403 18.1713 9.33447 17.8459 9.4346C17.7458 9.45963 17.6457 9.45963 17.5205 9.45963C16.6945 9.48466 15.8684 9.48466 15.0424 9.48466Z"
                                                fill="white" />
                                            <path
                                                d="M15.0446 14.89C15.8957 14.89 16.7217 14.89 17.5728 14.89C18.1235 14.89 18.4739 15.3656 18.3237 15.8412C18.2236 16.1416 17.9733 16.3418 17.6479 16.3418C17.4476 16.3418 17.2474 16.3418 17.0471 16.3418C15.5703 16.3418 14.0935 16.3418 12.5916 16.3418C12.4414 16.3418 12.2912 16.3418 12.166 16.2918C11.8406 16.1666 11.6654 15.8162 11.7155 15.4908C11.7655 15.1654 12.0409 14.9151 12.3913 14.89C12.7418 14.865 13.0922 14.89 13.4176 14.89C13.9683 14.89 14.519 14.89 15.0446 14.89Z"
                                                fill="white" />
                                            <path
                                                d="M7.28575 15.3661C7.8865 14.7653 8.41216 14.2397 8.96285 13.689C9.18813 13.4637 9.41341 13.3886 9.71379 13.4637C10.2645 13.6139 10.4397 14.2647 10.0392 14.7153C9.66372 15.1158 9.23819 15.5163 8.86272 15.9168C8.53732 16.2422 8.23694 16.5676 7.91153 16.868C7.53606 17.2184 7.13556 17.2184 6.78512 16.868C6.55984 16.6427 6.33456 16.4174 6.10928 16.1921C5.80891 15.8667 5.78387 15.4161 6.08425 15.1158C6.38463 14.8404 6.83519 14.8404 7.13557 15.1408C7.1606 15.1909 7.21065 15.266 7.28575 15.3661Z"
                                                fill="white" />
                                            <path
                                                d="M7.30847 8.43254C7.38356 8.35745 7.43363 8.30739 7.50873 8.25733C8.00935 7.78173 8.48494 7.28111 8.98557 6.78048C9.31098 6.45508 9.76154 6.45507 10.0619 6.75545C10.3623 7.05582 10.3623 7.50639 10.0369 7.8318C9.31097 8.5577 8.6101 9.25858 7.88419 9.98448C7.53376 10.3349 7.13326 10.3349 6.75779 9.95946C6.53251 9.73417 6.30722 9.50889 6.08194 9.28361C5.78156 8.9582 5.75653 8.53267 6.05691 8.23229C6.33225 7.93192 6.80785 7.93192 7.13326 8.25733C7.15829 8.30739 7.23338 8.35745 7.30847 8.43254Z"
                                                fill="white" />
                                            <path
                                                d="M17.0188 21.3742H6.98123C4.22778 21.3742 2 19.1464 2 16.393V6.98123C2 4.22778 4.22778 2 6.98123 2H17.0188C19.7722 2 22 4.22778 22 6.98123V16.393C22 19.1464 19.7472 21.3742 17.0188 21.3742ZM6.98123 3.47684C5.05382 3.47684 3.50188 5.02879 3.50188 6.9562V16.393C3.50188 18.3204 5.05382 19.8723 6.98123 19.8723H17.0188C18.9462 19.8723 20.4981 18.3204 20.4981 16.393V6.98123C20.4981 5.05382 18.9462 3.50188 17.0188 3.50188H6.98123V3.47684Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('Products List')}}</span>
                                    </a>
                                </li>

                                <li class="d-none nav-item {{Request::is('admin/product/bulk-import')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.product.bulk-import')}}"
                                        title="{{\App\CPU\translate('bulk_import')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M14.4869 3.01175H7.83489C5.75489 3.00378 4.05089 4.66078 4.00089 6.74078V17.4778C3.95589 19.5798 5.62389 21.3198 7.72489 21.3648C7.76189 21.3648 7.79889 21.3658 7.83489 21.3648H15.8229C17.9129 21.2908 19.5649 19.5688 19.553 17.4778V8.28778L14.4869 3.01175Z"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M14.2266 3V5.909C14.2266 7.329 15.3756 8.48 16.7956 8.484H19.5496"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M11.3906 10.1582V16.1992" stroke="white" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M13.7369 12.5152L11.3919 10.1602L9.04688 12.5152" stroke="white"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('Upload_Product')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/attribute*')?'active':''}} ">
                                    <a class=" nav-link" href="{{route('admin.attribute.view')}}"
                                        title="{{\App\CPU\translate('Product_Attributes')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <mask id="path-1-inside-1_3808_11933" fill="white">
                                                <rect x="2.85547" y="1.95312" width="7.62423" height="7.62423" rx="1" />
                                            </mask>
                                            <rect x="2.85547" y="1.95312" width="7.62423" height="7.62423" rx="1"
                                                stroke="white" stroke-width="2.6"
                                                mask="url(#path-1-inside-1_3808_11933)" />
                                            <rect x="14.5133" y="12.8258" width="7.53717" height="7.53717" rx="3.76859"
                                                stroke="white" stroke-width="1.3" />
                                            <path
                                                d="M17.8497 2.825C17.9844 2.59167 18.3212 2.59167 18.4559 2.825L21.7885 8.59719C21.9232 8.83053 21.7548 9.12219 21.4854 9.12219H14.8202C14.5508 9.12219 14.3824 8.83052 14.5171 8.59719L17.8497 2.825Z"
                                                stroke="white" stroke-width="1.3" />
                                            <path
                                                d="M6.34295 12.3382C6.45571 12.0335 6.88669 12.0335 6.99945 12.3382L7.60905 12.1126L6.99945 12.3382L7.70385 14.2418C7.87097 14.6935 8.22706 15.0496 8.6787 15.2167L10.5823 15.9211C10.887 16.0338 10.887 16.4648 10.5823 16.5776L8.67869 17.282C8.22706 17.4491 7.87097 17.8052 7.70385 18.2568L6.99945 20.1604C6.88669 20.4652 6.45571 20.4652 6.34295 20.1604L5.63855 18.2568C5.47143 17.8052 5.11534 17.4491 4.6637 17.282L2.76008 16.5776C2.45536 16.4648 2.45536 16.0338 2.76008 15.9211L4.6637 15.2167C5.11534 15.0496 5.47143 14.6935 5.63855 14.2418L6.34295 12.3382L5.73335 12.1126L6.34295 12.3382Z"
                                                stroke="white" stroke-width="1.3" />
                                        </svg>

                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('Product_Attributes')}}</span>
                                    </a>
                                </li>
                                <li
                                    class="d-none nav-item {{ (Request::is('admin/report/all-product') ||Request::is('admin/stock/product-in-wishlist') || Request::is('admin/stock/product-stock')) ?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.report.all-product')}}"
                                        title="{{\App\CPU\translate('Inventory_Report')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M6.29592 21.949C4.94388 21.949 4 21.0051 4 19.6786C4 15.3163 4 10.9541 4 6.59184C4 5.23979 4.94388 4.32144 6.27041 4.32144H7.11225V3.88776C7.11225 3.70919 7.11225 3.53062 7.11225 3.37756C7.11225 2.58674 7.72449 2 8.51531 2C9.53571 2 10.5816 2 11.602 2C12.648 2 13.6939 2 14.7653 2C15.5561 2 16.1684 2.61224 16.1684 3.40306C16.1684 3.55612 16.1684 3.73469 16.1684 3.91326V4.34694H17.0102C18.3878 4.34694 19.3061 5.26531 19.3061 6.64286C19.3061 11.0051 19.3061 15.3418 19.3061 19.7041C19.3061 21.0561 18.3622 22 17.0357 22H11.6531L6.29592 21.949ZM6.32143 5.4949C5.58163 5.4949 5.22449 5.87756 5.22449 6.61735V19.6275C5.22449 20.3673 5.60714 20.75 6.34694 20.75H16.9592C17.75 20.75 18.1071 20.3929 18.1071 19.602V6.59184C18.1071 6.5153 18.1071 6.46429 18.1071 6.38776C18.0561 5.92857 17.699 5.57144 17.2653 5.52042C17.1378 5.52042 17.0357 5.52042 16.9082 5.52042C16.8316 5.52042 16.7551 5.52042 16.7041 5.52042H16.6531C16.551 5.52042 16.4745 5.52042 16.398 5.52042H16.2194L16.1939 5.67347C16.0663 6.64286 15.5561 7.07654 14.6122 7.07654H14.2551C14.1786 7.07654 14.102 7.07654 14.0255 7.07654H13.9745C13.3112 7.07654 12.8265 6.77041 12.5459 6.13265C12.4694 5.92857 12.2398 5.7245 12.0357 5.59695C11.9082 5.54593 11.7806 5.4949 11.6531 5.4949C11.2704 5.4949 10.9388 5.75 10.7602 6.13265C10.5306 6.66837 10.199 6.97449 9.71428 7.02551C9.45918 7.05102 9.22959 7.07654 8.97449 7.07654C8.71939 7.07654 8.4898 7.05102 8.2602 7.02551C7.62245 6.94898 7.2398 6.4643 7.11225 5.64797L7.08673 5.4949H6.32143ZM11.6786 4.29592C12.5204 4.29592 13.2857 4.80613 13.6429 5.59695C13.6939 5.69899 13.8214 5.87755 13.9745 5.87755H14.051C14.2296 5.87755 14.3827 5.87756 14.5612 5.85205C14.6378 5.85205 14.8673 5.82653 14.8673 5.82653H14.9439V3.14796H8.33673V5.82653C8.33673 5.82653 8.87245 5.82653 8.92347 5.82653C9 5.82653 9.10204 5.82653 9.17857 5.82653C9.20408 5.82653 9.20408 5.82653 9.22959 5.82653C9.48469 5.82653 9.58673 5.67348 9.66327 5.52042C10.0459 4.7296 10.6837 4.29593 11.551 4.24491C11.602 4.29593 11.6531 4.29592 11.6786 4.29592Z"
                                                fill="white" />
                                            <path
                                                d="M7.82549 14.1169C7.62141 14.1169 7.41733 14.0404 7.28978 13.9129C7.18774 13.8108 7.13672 13.6577 7.13672 13.5047C7.13672 13.1475 7.41733 12.918 7.851 12.918H15.4786C15.9122 12.918 16.1673 13.1475 16.1928 13.5047C16.1928 13.6577 16.1418 13.8108 16.0398 13.9129C15.9122 14.0404 15.7337 14.1169 15.5041 14.1169H11.6775H7.82549Z"
                                                fill="white" />
                                            <path
                                                d="M7.77447 17.2302C7.41733 17.2302 7.13672 16.9751 7.13672 16.6435C7.13672 16.4904 7.18774 16.3374 7.31529 16.2098C7.44284 16.0823 7.5959 16.0312 7.79998 16.0312C8.3357 16.0312 8.89692 16.0312 9.43264 16.0312H13.8204C14.4071 16.0312 14.9939 16.0312 15.5806 16.0312C15.8612 16.0312 16.0908 16.2098 16.1673 16.4649C16.2439 16.72 16.1418 16.9751 15.9377 17.1027C15.8102 17.1792 15.6826 17.2047 15.5806 17.2047C14.05 17.2047 12.5194 17.2047 11.0143 17.2047C9.89182 17.2302 8.8459 17.2302 7.77447 17.2302Z"
                                                fill="white" />
                                            <path
                                                d="M7.79998 10.9801C7.5959 10.9801 7.41733 10.9036 7.28978 10.8015C7.18774 10.6995 7.13672 10.5464 7.13672 10.3934C7.13672 10.0618 7.41733 9.80664 7.79998 9.80664C8.05509 9.80664 9.12651 9.80664 9.12651 9.80664H11.5755C11.9837 9.80664 12.2388 10.0617 12.2643 10.4189C12.2643 10.572 12.2132 10.725 12.1112 10.827C11.9837 10.9546 11.8051 11.0056 11.601 11.0056C10.9632 11.0056 10.3255 11.0056 9.71325 11.0056C9.101 11.0056 8.43774 10.9801 7.79998 10.9801Z"
                                                fill="white" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('Inventory_Report')}}
                                        </span>
                                    </a>
                                </li>


                                <!-- Brand -->
                                <li class="navbar-vertical-aside-has-menu {{Request::is('admin/brand*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:"
                                        title="{{\App\CPU\translate('brands')}}">

                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M13.1043 3.67701L14.9317 7.32776C15.1108 7.68616 15.4565 7.93467 15.8573 7.99218L19.9453 8.58062C20.9554 8.72644 21.3573 9.95055 20.6263 10.6519L17.6702 13.4924C17.3797 13.7718 17.2474 14.1733 17.3162 14.5676L18.0138 18.5778C18.1856 19.5698 17.1298 20.3267 16.227 19.8574L12.5732 17.9627C12.215 17.7768 11.786 17.7768 11.4268 17.9627L7.773 19.8574C6.87023 20.3267 5.81439 19.5698 5.98724 18.5778L6.68385 14.5676C6.75257 14.1733 6.62033 13.7718 6.32982 13.4924L3.37368 10.6519C2.64272 9.95055 3.04464 8.72644 4.05466 8.58062L8.14265 7.99218C8.54354 7.93467 8.89028 7.68616 9.06937 7.32776L10.8957 3.67701C11.3477 2.77433 12.6523 2.77433 13.1043 3.67701Z"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('brands')}}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{Request::is('admin/brand*')?'block':'none'}}">
                                        <li class="nav-item {{Request::is('admin/brand/add-new')?'active':''}}"
                                            title="{{\App\CPU\translate('add_new')}}">
                                            <a class="nav-link " href="{{route('admin.brand.add-new')}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M11.9985 7.88672C12.3838 7.88672 12.6961 8.19908 12.6961 8.58439V15.3996C12.6961 15.7849 12.3838 16.0973 11.9985 16.0973C11.6131 16.0973 11.3008 15.7849 11.3008 15.3996V8.58439C11.3008 8.19908 11.6131 7.88672 11.9985 7.88672Z"
                                                        fill="white" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M7.89062 11.9906C7.89062 11.6053 8.20298 11.293 8.5883 11.293H15.41C15.7953 11.293 16.1077 11.6053 16.1077 11.9906C16.1077 12.376 15.7953 12.6883 15.41 12.6883H8.5883C8.20298 12.6883 7.89062 12.376 7.89062 11.9906Z"
                                                        fill="white" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M3.51958 3.67792C4.5211 2.60357 5.94906 2 7.6412 2H16.3588C18.0549 2 19.4833 2.60329 20.4841 3.67825C21.4796 4.74758 22 6.22683 22 7.89317V16.1068C22 17.7732 21.4796 19.2524 20.4841 20.3217C19.4833 21.3967 18.0549 22 16.3588 22H7.6412C5.94508 22 4.51675 21.3967 3.51595 20.3217C2.5204 19.2524 2 17.7732 2 16.1068V7.89317C2 6.22597 2.52312 4.74684 3.51958 3.67792ZM4.54022 4.62938C3.82461 5.39703 3.39535 6.51565 3.39535 7.89317V16.1068C3.39535 17.4852 3.8229 18.6037 4.53721 19.3709C5.24627 20.1326 6.2897 20.6047 7.6412 20.6047H16.3588C17.7103 20.6047 18.7537 20.1326 19.4628 19.3709C20.1771 18.6037 20.6047 17.4852 20.6047 16.1068V7.89317C20.6047 6.51479 20.1771 5.39629 19.4628 4.62905C18.7537 3.86745 17.7103 3.39535 16.3588 3.39535H7.6412C6.29457 3.39535 5.25077 3.86717 4.54022 4.62938Z"
                                                        fill="white" />
                                                </svg>
                                                <span class="text-truncate">{{\App\CPU\translate('add_new')}}</span>
                                            </a>
                                        </li>
                                        <li class="nav-item {{Request::is('admin/brand/list')?'active':''}}"
                                            title="{{\App\CPU\translate('List')}}">
                                            <a class="nav-link " href="{{route('admin.brand.list')}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M15.0424 9.48466C14.2164 9.48466 13.3653 9.48466 12.5393 9.48466C12.0637 9.48466 11.7383 9.18428 11.7383 8.75875C11.7383 8.33322 12.0637 8.00781 12.5393 8.00781C14.2164 8.00781 15.8935 8.00781 17.5455 8.00781C17.9711 8.00781 18.2965 8.25813 18.3465 8.65863C18.3966 8.98403 18.1713 9.33447 17.8459 9.4346C17.7458 9.45963 17.6457 9.45963 17.5205 9.45963C16.6945 9.48466 15.8684 9.48466 15.0424 9.48466Z"
                                                        fill="white" />
                                                    <path
                                                        d="M15.0446 14.89C15.8957 14.89 16.7217 14.89 17.5728 14.89C18.1235 14.89 18.4739 15.3656 18.3237 15.8412C18.2236 16.1416 17.9733 16.3418 17.6479 16.3418C17.4476 16.3418 17.2474 16.3418 17.0471 16.3418C15.5703 16.3418 14.0935 16.3418 12.5916 16.3418C12.4414 16.3418 12.2912 16.3418 12.166 16.2918C11.8406 16.1666 11.6654 15.8162 11.7155 15.4908C11.7655 15.1654 12.0409 14.9151 12.3913 14.89C12.7418 14.865 13.0922 14.89 13.4176 14.89C13.9683 14.89 14.519 14.89 15.0446 14.89Z"
                                                        fill="white" />
                                                    <path
                                                        d="M7.28575 15.3661C7.8865 14.7653 8.41216 14.2397 8.96285 13.689C9.18813 13.4637 9.41341 13.3886 9.71379 13.4637C10.2645 13.6139 10.4397 14.2647 10.0392 14.7153C9.66372 15.1158 9.23819 15.5163 8.86272 15.9168C8.53732 16.2422 8.23694 16.5676 7.91153 16.868C7.53606 17.2184 7.13556 17.2184 6.78512 16.868C6.55984 16.6427 6.33456 16.4174 6.10928 16.1921C5.80891 15.8667 5.78387 15.4161 6.08425 15.1158C6.38463 14.8404 6.83519 14.8404 7.13557 15.1408C7.1606 15.1909 7.21065 15.266 7.28575 15.3661Z"
                                                        fill="white" />
                                                    <path
                                                        d="M7.30847 8.43254C7.38356 8.35745 7.43363 8.30739 7.50873 8.25733C8.00935 7.78173 8.48494 7.28111 8.98557 6.78048C9.31098 6.45508 9.76154 6.45507 10.0619 6.75545C10.3623 7.05582 10.3623 7.50639 10.0369 7.8318C9.31097 8.5577 8.6101 9.25858 7.88419 9.98448C7.53376 10.3349 7.13326 10.3349 6.75779 9.95946C6.53251 9.73417 6.30722 9.50889 6.08194 9.28361C5.78156 8.9582 5.75653 8.53267 6.05691 8.23229C6.33225 7.93192 6.80785 7.93192 7.13326 8.25733C7.15829 8.30739 7.23338 8.35745 7.30847 8.43254Z"
                                                        fill="white" />
                                                    <path
                                                        d="M17.0188 21.3742H6.98123C4.22778 21.3742 2 19.1464 2 16.393V6.98123C2 4.22778 4.22778 2 6.98123 2H17.0188C19.7722 2 22 4.22778 22 6.98123V16.393C22 19.1464 19.7472 21.3742 17.0188 21.3742ZM6.98123 3.47684C5.05382 3.47684 3.50188 5.02879 3.50188 6.9562V16.393C3.50188 18.3204 5.05382 19.8723 6.98123 19.8723H17.0188C18.9462 19.8723 20.4981 18.3204 20.4981 16.393V6.98123C20.4981 5.05382 18.9462 3.50188 17.0188 3.50188H6.98123V3.47684Z"
                                                        fill="white" />
                                                </svg>
                                                <span class="text-truncate">{{\App\CPU\translate('List')}}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <!-- Brand End -->

                                <!-- product add start -->
                                <li class="nav-item {{Request::is('admin/product/add-new')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.product.add-new')}}"
                                        title="{{\App\CPU\translate('Add_New_Product')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M11.9985 7.88672C12.3838 7.88672 12.6961 8.19908 12.6961 8.58439V15.3996C12.6961 15.7849 12.3838 16.0973 11.9985 16.0973C11.6131 16.0973 11.3008 15.7849 11.3008 15.3996V8.58439C11.3008 8.19908 11.6131 7.88672 11.9985 7.88672Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M7.89062 11.9906C7.89062 11.6053 8.20298 11.293 8.5883 11.293H15.41C15.7953 11.293 16.1077 11.6053 16.1077 11.9906C16.1077 12.376 15.7953 12.6883 15.41 12.6883H8.5883C8.20298 12.6883 7.89062 12.376 7.89062 11.9906Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M3.51958 3.67792C4.5211 2.60357 5.94906 2 7.6412 2H16.3588C18.0549 2 19.4833 2.60329 20.4841 3.67825C21.4796 4.74758 22 6.22683 22 7.89317V16.1068C22 17.7732 21.4796 19.2524 20.4841 20.3217C19.4833 21.3967 18.0549 22 16.3588 22H7.6412C5.94508 22 4.51675 21.3967 3.51595 20.3217C2.5204 19.2524 2 17.7732 2 16.1068V7.89317C2 6.22597 2.52312 4.74684 3.51958 3.67792ZM4.54022 4.62938C3.82461 5.39703 3.39535 6.51565 3.39535 7.89317V16.1068C3.39535 17.4852 3.8229 18.6037 4.53721 19.3709C5.24627 20.1326 6.2897 20.6047 7.6412 20.6047H16.3588C17.7103 20.6047 18.7537 20.1326 19.4628 19.3709C20.1771 18.6037 20.6047 17.4852 20.6047 16.1068V7.89317C20.6047 6.51479 20.1771 5.39629 19.4628 4.62905C18.7537 3.86745 17.7103 3.39535 16.3588 3.39535H7.6412C6.29457 3.39535 5.25077 3.86717 4.54022 4.62938Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('Add_New_Product')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/product/product-for-you')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.product.product-for-you')}}"
                                        title="{{\App\CPU\translate('Product_For_You')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M11.9985 7.88672C12.3838 7.88672 12.6961 8.19908 12.6961 8.58439V15.3996C12.6961 15.7849 12.3838 16.0973 11.9985 16.0973C11.6131 16.0973 11.3008 15.7849 11.3008 15.3996V8.58439C11.3008 8.19908 11.6131 7.88672 11.9985 7.88672Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M7.89062 11.9906C7.89062 11.6053 8.20298 11.293 8.5883 11.293H15.41C15.7953 11.293 16.1077 11.6053 16.1077 11.9906C16.1077 12.376 15.7953 12.6883 15.41 12.6883H8.5883C8.20298 12.6883 7.89062 12.376 7.89062 11.9906Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M3.51958 3.67792C4.5211 2.60357 5.94906 2 7.6412 2H16.3588C18.0549 2 19.4833 2.60329 20.4841 3.67825C21.4796 4.74758 22 6.22683 22 7.89317V16.1068C22 17.7732 21.4796 19.2524 20.4841 20.3217C19.4833 21.3967 18.0549 22 16.3588 22H7.6412C5.94508 22 4.51675 21.3967 3.51595 20.3217C2.5204 19.2524 2 17.7732 2 16.1068V7.89317C2 6.22597 2.52312 4.74684 3.51958 3.67792ZM4.54022 4.62938C3.82461 5.39703 3.39535 6.51565 3.39535 7.89317V16.1068C3.39535 17.4852 3.8229 18.6037 4.53721 19.3709C5.24627 20.1326 6.2897 20.6047 7.6412 20.6047H16.3588C17.7103 20.6047 18.7537 20.1326 19.4628 19.3709C20.1771 18.6037 20.6047 17.4852 20.6047 16.1068V7.89317C20.6047 6.51479 20.1771 5.39629 19.4628 4.62905C18.7537 3.86745 17.7103 3.39535 16.3588 3.39535H7.6412C6.29457 3.39535 5.25077 3.86717 4.54022 4.62938Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('Product_For_You')}}</span>
                                    </a>
                                </li>
                                <!-- product add end -->
                            </ul>
                        </li>

                        <!-- Product End  -->
                        <!-- vendor Product management -->
                        <li
                            class="navbar-vertical-aside-has-menu {{Request::is('admin/product/list/seller*')|| (Request::is('admin/product/view/*')) || Request::is('admin/product/updated-product-list')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:"
                                title="{{\App\CPU\translate('Seller')}} {{\App\CPU\translate('Products')}} {{\App\CPU\translate('Management')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none">
                                    <path
                                        d="M19.9826 6.31182C19.9818 6.08382 19.9527 5.85997 19.8989 5.63861C19.6054 4.44638 19.2862 3.26079 18.9935 2.06857C18.9205 1.77176 18.8509 1.47494 18.7688 1.18062C18.6071 0.596945 18.0807 -0.00165473 17.2358 3.43705e-06C12.4006 0.00580703 7.56539 0.00249094 2.73017 0.00414911C2.53617 0.00414911 2.34382 0.0215595 2.15976 0.106126C1.62915 0.349877 1.32322 0.76442 1.18476 1.32239C0.938523 2.31895 0.688969 3.31385 0.437756 4.30958C0.274427 4.95627 0.0663267 5.59301 0 6.26125C0 6.33918 0 6.41712 0 6.49505C0 6.57298 0 6.65092 0 6.72885C0 6.87145 0 7.01488 0 7.15749C0.039796 7.21635 0.0190689 7.28434 0.0248725 7.34735C0.0870538 7.98326 0.394644 8.51967 0.725449 9.04117C0.861418 9.25507 0.921113 9.46483 0.920283 9.71521C0.915309 12.5722 0.9236 15.4293 0.912822 18.2871C0.910334 19.0175 1.40696 19.9154 2.30402 19.9859C2.34299 19.9859 2.38196 19.9859 2.42093 19.9859C7.46093 19.9859 12.5009 19.9859 17.5401 19.9859C17.5658 19.9859 17.5923 19.9859 17.618 19.9859C17.8212 19.9519 18.0293 19.9494 18.2158 19.8383C18.8094 19.4851 19.0781 18.9504 19.0797 18.2797C19.0855 16.415 19.0822 11.2333 19.0822 9.36866C19.0822 9.35124 19.0814 9.33301 19.0805 9.31559C19.1394 9.24844 19.1966 9.17879 19.2513 9.10666C19.6709 8.55284 19.9445 7.93683 20 7.23542C20 7.01406 20 6.79352 20 6.57215C19.9627 6.48759 19.9925 6.39805 19.9826 6.31182ZM13.9369 1.38375C14.984 1.39038 16.032 1.38872 17.0791 1.3854C17.2342 1.38457 17.3444 1.42188 17.3859 1.58687C17.7805 3.16379 18.1976 4.73573 18.564 6.31928C18.8625 7.61017 17.8991 8.96323 16.5809 9.17879C15.2386 9.39767 13.9518 8.44754 13.7868 7.11355C13.7686 6.96597 13.7587 6.81591 13.7587 6.6675C13.7562 5.84919 13.757 5.03089 13.757 4.21258C13.757 3.32961 13.7611 2.44663 13.7528 1.56366C13.752 1.41276 13.7943 1.38292 13.9369 1.38375ZM7.87547 1.38375C9.32056 1.38872 10.7657 1.38872 12.2108 1.38375C12.3525 1.38292 12.3956 1.41193 12.394 1.562C12.3857 2.45824 12.3898 3.35531 12.3898 4.25155C12.389 4.25155 12.3882 4.25155 12.3873 4.25155C12.3873 5.13535 12.3998 6.01915 12.384 6.90213C12.365 7.94678 11.5856 8.90603 10.5915 9.14397C9.33051 9.44576 8.08274 8.67222 7.77515 7.39958C7.72126 7.17821 7.6939 6.95353 7.6939 6.72554C7.69556 5.00353 7.69722 3.28235 7.69058 1.56034C7.68976 1.40696 7.73618 1.38292 7.87547 1.38375ZM1.40115 7.06297C1.33897 6.52821 1.48821 6.03408 1.6134 5.53248C1.9326 4.25072 2.25843 2.96978 2.58094 1.68885C2.65058 1.41359 2.68292 1.38706 2.96481 1.38706C4.01277 1.38706 5.0599 1.39038 6.10703 1.38375C6.25129 1.38292 6.29109 1.41608 6.28943 1.56366C6.28197 2.45326 6.28529 3.34287 6.28529 4.23248C6.28529 5.05742 6.28695 5.88153 6.28446 6.70647C6.28031 8.02968 5.36749 9.0478 4.05505 9.19786C2.79484 9.34295 1.5479 8.32898 1.40115 7.06297ZM8.33064 16.2666C8.33064 15.552 8.33395 14.8381 8.32815 14.1235C8.32649 13.9809 8.34888 13.922 8.51469 13.9236C9.50379 13.9328 10.4929 13.9319 11.482 13.9245C11.6412 13.9228 11.6735 13.9717 11.6727 14.1201C11.6669 15.5619 11.6677 17.0037 11.6718 18.4455C11.6718 18.5756 11.6486 18.6229 11.5044 18.6221C10.502 18.6154 9.50048 18.6154 8.49811 18.6221C8.35966 18.6229 8.32649 18.5839 8.32732 18.4488C8.33395 17.7217 8.33064 16.9937 8.33064 16.2666ZM17.5617 18.0832C17.5617 18.5383 17.5376 18.5624 17.0941 18.5624C15.8894 18.5624 14.6847 18.5624 13.4809 18.5624C13.4162 18.5624 13.3507 18.5632 13.2861 18.5591C13.2007 18.5533 13.1567 18.5101 13.1509 18.4247C13.1468 18.3534 13.1468 18.2821 13.1468 18.21C13.1468 16.6182 13.1468 15.0263 13.1468 13.4337C13.1468 13.277 13.1393 13.1219 13.0854 12.9727C12.9768 12.6692 12.7289 12.4819 12.4064 12.481C10.8046 12.4777 9.20284 12.4802 7.60104 12.4794C7.30009 12.4794 7.09862 12.6294 6.95767 12.879C6.86813 13.0374 6.8557 13.2115 6.85653 13.3889C6.85736 15.0006 6.85736 16.6115 6.85736 18.2233C6.85736 18.5624 6.85736 18.5624 6.50914 18.5624C5.71488 18.5624 4.92061 18.5624 4.12635 18.5624C3.67036 18.5624 3.21519 18.5649 2.75919 18.5615C2.50964 18.5599 2.45243 18.4994 2.44248 18.249C2.44082 18.21 2.44165 18.171 2.44165 18.1321C2.44165 15.6498 2.44165 13.1675 2.44165 10.6852C2.44165 10.6272 2.43917 10.5683 2.44663 10.5103C2.45575 10.4456 2.49306 10.4216 2.55607 10.4332C2.6141 10.444 2.66799 10.4672 2.72437 10.4838C3.69772 10.7723 4.64287 10.6968 5.55569 10.2574C6.05978 10.0145 6.50168 9.68537 6.84658 9.23932C6.92949 9.13237 6.99084 9.11081 7.08038 9.236C7.19396 9.39519 7.34403 9.52286 7.48414 9.65883C8.5263 10.672 10.2864 10.9472 11.5939 10.3088C12.1096 10.0576 12.5714 9.74174 12.9312 9.28823C13.0664 9.11827 13.0962 9.12325 13.2396 9.29487C13.3234 9.39519 13.408 9.49302 13.5025 9.58256C14.6466 10.6595 16.2235 10.9108 17.5608 10.3901C17.5625 12.6867 17.5617 16.5444 17.5617 18.0832Z"
                                        fill="white" />
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Seller')}} {{\App\CPU\translate('Products')}}
                                    {{\App\CPU\translate('Management')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/product/list/seller*')|| (Request::is('admin/product/view/*')) || Request::is('admin/product/updated-product-list')?'block':''}}">

                                @if (\App\CPU\Helpers::get_business_settings('product_wise_shipping_cost_approval')==1)
                                <li class="d-none nav-item {{Request::is('admin/product/updated-product-list')?'active':''}}">
                                    <a class="nav-link" title="{{\App\CPU\translate('updated_products')}}"
                                        href="{{route('admin.product.updated-product-list')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M21.9074 16.5536C21.7907 16.3903 21.6274 16.2969 21.4407 16.2969C21.4173 16.2969 21.3707 16.2969 21.324 16.2969C21.254 16.3202 21.184 16.3436 21.0906 16.3903C21.0206 16.4369 20.7872 16.577 20.6939 16.647V16.5303C20.8106 14.8499 20.2971 13.3796 19.1535 12.1894C18.9902 12.026 18.8268 11.886 18.6634 11.746C18.6634 10.5557 18.6634 9.36548 18.6634 8.17524C18.6634 8.08188 18.6634 7.98853 18.6168 7.89518C18.1967 6.91498 17.7999 5.93477 17.3565 4.95457C16.773 3.67097 15.7462 3.0175 14.3459 3.0175C11.662 2.99417 9.00145 2.99417 6.31756 3.0175C4.89393 3.0175 3.86705 3.71765 3.2836 5.02459C2.86351 5.98145 2.46676 6.93832 2.04667 7.89518C2.02333 7.96519 2 8.03521 2 8.10522C2 8.66534 2 9.22545 2 9.76223C2 9.80891 2 9.85558 2.02334 9.87892C2.02334 9.9256 2 9.97227 2 10.0189V14.3132C2 14.3132 2 14.3132 2 14.3365C2 14.8266 2 15.34 2 15.8301C2 16.0635 2.02334 16.2969 2.07001 16.507C2.42008 18.3973 3.98374 19.6809 5.92081 19.6809C7.2044 19.6809 9.44487 19.6809 10.9852 19.6809L10.8452 19.4476C10.9385 19.5642 11.0085 19.6576 11.1019 19.751C11.2186 19.891 11.3586 20.0077 11.4753 20.1244C12.5022 21.0579 13.8091 21.548 15.1861 21.548C15.5128 21.548 15.8395 21.5247 16.1663 21.4546C17.0531 21.2913 17.9166 20.8945 18.6401 20.3344C18.7801 20.2177 18.8735 20.0544 18.8968 19.891C18.9202 19.751 18.8735 19.5876 18.7568 19.4709C18.6401 19.3309 18.5001 19.2609 18.3367 19.2609C18.29 19.2609 18.2667 19.2609 18.22 19.2609C18.1033 19.2842 18.01 19.3309 17.9166 19.4009C17.6599 19.5876 17.3798 19.7976 17.0764 19.9377C16.7264 20.1244 16.3296 20.2411 15.9562 20.3111C15.6995 20.3577 15.4428 20.3811 15.1627 20.3811C13.4357 20.3811 11.8487 19.3309 11.1486 17.7205C10.6818 16.647 10.6818 15.4567 11.1019 14.3598C11.5453 13.263 12.3621 12.4228 13.4357 11.9794C13.7391 11.8393 14.0658 11.746 14.3926 11.6993C14.6493 11.6526 14.906 11.6293 15.1627 11.6293C16.2129 11.6293 17.2398 12.026 18.0333 12.7262C19.0368 13.613 19.5736 14.8966 19.527 16.2269C19.527 16.2969 19.527 16.3669 19.5036 16.4369V16.5303L19.3869 16.3669C19.2936 16.2269 19.2002 16.0869 19.1069 15.9468C18.9902 15.7835 18.8268 15.6901 18.6401 15.6901C18.6168 15.6901 18.5701 15.6901 18.5234 15.6901C18.4534 15.7135 18.3834 15.7368 18.3134 15.7835C18.0566 15.9468 17.9633 16.2969 18.1267 16.5536C18.5001 17.1371 18.8735 17.6972 19.2469 18.2573C19.3636 18.4207 19.527 18.514 19.7137 18.514C19.737 18.514 19.7837 18.514 19.807 18.514C19.877 18.514 19.947 18.4674 19.9937 18.444C20.6005 18.0473 21.1606 17.6739 21.6974 17.3238C21.8374 17.2304 21.9074 17.1137 21.9308 16.9504C22.0475 16.8337 22.0008 16.6703 21.9074 16.5536ZM3.70368 7.33506C3.9604 6.72827 4.21713 6.14482 4.47385 5.56136C4.82392 4.72119 5.45404 4.32444 6.36423 4.32444C6.83099 4.32444 7.27442 4.32444 7.71785 4.32444H11.662C12.6189 4.32444 13.5757 4.3011 14.5326 4.34778C15.2327 4.37112 15.7928 4.74453 16.1196 5.37466C16.4463 6.05146 16.7264 6.77495 17.0298 7.45175C17.0298 7.47509 17.0298 7.49843 17.0298 7.52177C15.5128 7.52177 13.9725 7.52177 12.4321 7.52177C11.942 7.52177 11.4753 7.52177 10.9852 7.52177H7.71785H7.69451C6.34089 7.52177 4.98728 7.52177 3.63367 7.52177C3.68035 7.45175 3.68034 7.38174 3.70368 7.33506ZM15.1861 10.5091C14.8593 10.5091 14.5326 10.5324 14.2058 10.6024C13.0156 10.8124 11.9421 11.3959 11.1252 12.3061C10.1217 13.3796 9.60824 14.8033 9.67825 16.2969C9.70159 17.0204 9.8883 17.7205 10.1917 18.374C10.1917 18.3973 10.215 18.3973 10.215 18.4207C8.83807 18.4207 7.2044 18.4207 5.92081 18.4207C4.42716 18.4207 3.30694 17.3005 3.30694 15.8068C3.30694 14.8733 3.30694 13.9631 3.30694 13.0529V10.0423C3.30694 9.99561 3.30694 9.94893 3.2836 9.90226C3.2836 9.85558 3.30694 9.80891 3.30694 9.76223C3.30694 9.4355 3.30694 9.1321 3.30694 8.80537C8.02124 8.80537 12.7122 8.80537 17.4032 8.80537C17.4032 9.52885 17.4032 10.2523 17.4032 10.9525C17.3565 10.9291 17.3332 10.9291 17.2865 10.9058C16.6097 10.6491 15.9095 10.5091 15.1861 10.5091Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('updated_products')}} </span>
                                    </a>
                                </li>
                                @endif
                                <li
                                    class="nav-item {{str_contains(url()->current().'?status='.request()->get('status'),'/admin/product/list/seller?status=0')==1?'active':''}}">
                                    <a class="nav-link"
                                        title="{{\App\CPU\translate('New')}} {{\App\CPU\translate('Products')}}"
                                        href="{{route('admin.product.list',['seller', 'status'=>'0'])}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M21.0925 14.6502C20.9105 13.9904 20.3416 13.5581 19.6135 13.5353H19.5453C19.022 13.5353 18.4759 13.6946 17.8843 14.0359C17.1562 14.4455 16.4281 14.8778 15.7 15.3101C15.7 15.3101 14.4486 16.0382 14.4258 16.0609C14.4713 15.7196 14.4031 15.4011 14.2438 15.1281C14.0163 14.6957 13.5612 14.4 13.0834 14.3317C13.0379 14.3317 13.0151 14.3089 12.9696 14.3089H12.9241C11.923 14.0814 10.9218 13.7174 10.1027 13.3988C9.78419 13.2851 9.4429 13.2168 9.12435 13.2168C8.69204 13.2168 8.25974 13.3306 7.85018 13.5353C7.28135 13.8084 6.73527 14.1497 6.1892 14.4682L6.12094 14.5137C5.82515 14.6957 5.52935 14.8778 5.21081 15.037C5.1653 15.0598 5.1198 15.0826 5.0743 15.1281C5.0743 15.1281 5.0743 15.1281 5.05154 15.1281L5.00603 15.0598C4.98328 15.037 4.98329 14.9915 4.96053 14.9688C4.733 14.6047 4.41446 14.4 4.02765 14.4C3.84563 14.4 3.64085 14.4455 3.45882 14.5592C3.16303 14.7185 2.86724 14.9005 2.57145 15.0598C2.00263 15.3783 1.84335 15.9927 2.16189 16.5388C3.07202 18.177 4.0049 19.8152 4.93778 21.4534C5.14256 21.7947 5.48385 21.9995 5.8479 21.9995C6.02993 21.9995 6.21195 21.954 6.37122 21.863C6.73527 21.681 7.05382 21.4989 7.34961 21.2942C7.73641 21.0666 7.91844 20.7026 7.87293 20.2475C7.85018 20.0427 7.75916 19.8835 7.66815 19.7242L7.6454 19.6559C7.62265 19.6332 7.59989 19.5877 7.59989 19.5649C7.62264 19.5422 7.6454 19.5194 7.69091 19.5194C7.78192 19.4511 7.85018 19.4512 7.87293 19.4512C7.89568 19.4512 7.91844 19.4512 7.96395 19.4739C9.26088 19.838 10.5123 20.1793 12.0367 20.5661C12.3098 20.6343 12.5828 20.6798 12.8559 20.6798C13.3564 20.6798 13.8342 20.5433 14.2666 20.2703C16.3143 19.0188 18.3849 17.7447 20.4327 16.4705C21.0242 16.1292 21.2973 15.3556 21.0925 14.6502ZM5.8934 20.8163C5.25632 19.7014 4.61923 18.5865 4.0049 17.4716L3.18578 16.0154C3.45882 15.8562 3.73186 15.6969 4.0049 15.5604L5.27907 17.8129C5.75689 18.6548 6.2347 19.4967 6.71252 20.3385C6.71252 20.3613 6.73527 20.3613 6.73527 20.384L6.68976 20.4068C6.59875 20.4523 6.50774 20.4978 6.41673 20.5661L6.39398 20.5888C6.21195 20.6343 6.05268 20.7253 5.8934 20.8163ZM7.03106 18.632C7.00831 18.6093 6.98556 18.541 6.9628 18.4955C6.94005 18.4273 6.9173 18.3818 6.87179 18.3135C6.73527 18.086 6.62151 17.8584 6.48499 17.6309L6.32572 17.3351L5.93891 16.607C5.8479 16.4477 5.73414 16.2657 5.64313 16.0837C5.8024 15.9927 6.50774 15.5831 6.50774 15.5831C7.09932 15.2418 7.69091 14.9005 8.30524 14.5592C8.57828 14.4 8.85131 14.3317 9.1471 14.3317C9.35188 14.3317 9.55667 14.3772 9.7842 14.4682C9.98898 14.5592 10.2165 14.6275 10.4213 14.7185L10.535 14.764C10.6261 14.8095 10.7398 14.8323 10.8536 14.8778C11.0356 14.9233 11.1949 14.9915 11.3769 15.037C11.7865 15.1736 12.1733 15.2873 12.5828 15.3783L12.9924 15.4694C13.2427 15.5376 13.3792 15.7196 13.3337 15.9472C13.2882 16.1292 13.1517 16.243 12.9469 16.243C12.9014 16.243 12.8786 16.243 12.8331 16.2202C12.378 16.1292 11.9457 16.0154 11.4907 15.8789L10.9219 15.7196C10.8308 15.6969 10.7626 15.6741 10.6716 15.6514C10.6261 15.6514 10.5805 15.6286 10.5123 15.6286C10.2392 15.6286 10.0345 15.7879 9.96622 16.0382C9.92071 16.1747 9.94347 16.334 10.0117 16.4705C10.08 16.607 10.2165 16.698 10.3758 16.7435C10.5578 16.789 10.7171 16.8345 10.8991 16.9028L11.0129 16.9256C11.6955 17.1303 12.4918 17.3579 13.2882 17.4489C13.3792 17.4489 13.4929 17.4716 13.584 17.4716C14.0618 17.4716 14.5396 17.3351 14.9719 17.0848L16.2916 16.3112C17.0424 15.8789 17.7705 15.4466 18.5214 15.0143C18.7944 14.855 19.1585 14.673 19.568 14.673H19.5908C19.8638 14.673 19.9776 14.764 20.0459 14.9688C20.0914 15.1963 20.0231 15.4011 19.8411 15.5149L18.1118 16.5843C16.7011 17.4489 15.3132 18.3135 13.9025 19.1781C13.5612 19.3829 13.2199 19.4967 12.8559 19.4967C12.6511 19.4967 12.4463 19.4739 12.2415 19.4056C11.3769 19.1554 10.535 18.9506 9.76144 18.723L9.67043 18.7003C9.12436 18.5638 8.60103 18.4045 8.05495 18.268C7.98669 18.2452 7.91844 18.2452 7.85018 18.2452C7.6454 18.2452 7.48612 18.3135 7.37236 18.4045C7.32685 18.4273 7.2586 18.4728 7.21309 18.5183C7.12208 18.5638 7.07657 18.6093 7.03106 18.632Z"
                                                fill="white" />
                                            <path
                                                d="M9.42127 11.3515C10.8775 12.1706 12.4019 13.058 13.9719 13.9681C14.1084 14.0364 14.2449 14.0819 14.3587 14.0819C14.4952 14.0819 14.609 14.0364 14.7455 13.9681C16.3837 13.0125 17.8627 12.1479 19.2961 11.3515C19.5692 11.1923 19.6829 10.9647 19.6829 10.6689C19.6829 10.0319 19.6829 9.39477 19.6829 8.75768V7.1422C19.6829 6.57338 19.6829 5.9818 19.6829 5.41297C19.6829 5.11718 19.5692 4.9124 19.3189 4.77588C17.7944 3.88851 16.2472 3.00114 14.7227 2.11377C14.5862 2.04551 14.4725 2 14.3587 2C14.2449 2 14.1084 2.04551 13.9947 2.11377C12.0151 3.27418 10.6499 4.04778 9.42127 4.75313C9.14823 4.9124 9.01172 5.13993 9.01172 5.45847C9.01172 7.14221 9.01172 8.84869 9.01172 10.6917C9.03447 10.9647 9.17099 11.1923 9.42127 11.3515ZM13.7899 11.8749V12.5347L11.9469 11.4653C11.3553 11.124 10.7637 10.7827 10.1721 10.4414C10.1721 9.281 10.1721 8.09784 10.1721 6.93743V6.25483L13.7899 8.34812C13.7899 9.53128 13.7899 10.6917 13.7899 11.8749ZM18.568 10.4642C17.5896 11.033 16.5885 11.6018 15.6101 12.1706L14.9503 12.5575V11.306C14.9503 10.3276 14.9503 9.37201 14.9503 8.41638C14.9503 8.39363 14.9503 8.39363 14.9503 8.39363C14.9503 8.39363 14.9503 8.39363 14.973 8.37088C15.2006 8.25711 15.4281 8.12059 15.6101 8.00682L16.5885 7.438L18.022 6.61889C18.204 6.52787 18.386 6.41411 18.568 6.32309C18.568 6.27759 18.568 10.4414 18.568 10.4642ZM11.3553 4.93515L13.8126 3.52446C13.9946 3.41069 14.1767 3.31968 14.3587 3.20592C14.9275 3.52446 17.6807 5.11718 17.9765 5.2992L14.9275 7.07395C14.7455 7.18771 14.5635 7.27872 14.3815 7.39249C14.1994 7.27872 13.4941 6.89192 10.7637 5.2992L10.8775 5.23094C11.0367 5.11718 11.196 5.02616 11.3553 4.93515Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('New')}}
                                            {{\App\CPU\translate('Products')}} </span>
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{str_contains(url()->current().'?status='.request()->get('status'),'/admin/product/list/seller?status=1')==1?'active':''}}">
                                    <a class="nav-link"
                                        title="{{\App\CPU\translate('Approved')}} {{\App\CPU\translate('Products')}}"
                                        href="{{route('admin.product.list',['seller', 'status'=>'1'])}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M21.865 15.5711C21.8419 15.5248 21.8419 15.4554 21.8419 15.386C21.6569 13.7441 20.7781 12.3103 19.5293 11.339C19.5524 11.2928 19.5524 11.2465 19.5524 11.1772C19.5524 10.3678 19.5524 8.00893 19.5524 7.19953C19.5524 7.13016 19.5524 7.08391 19.5524 7.01453C19.9456 6.99141 20.1537 6.78327 20.2693 6.43639C20.2693 5.74262 20.2693 5.07197 20.2925 4.40133C20.3156 3.80006 20.1306 3.19879 19.7374 2.7594C18.928 1.81124 17.6561 2.01937 16.5461 2.01937C12.3141 2.01937 8.08207 2.01937 3.85006 2.01937C3.82693 2.01937 3.80381 2.0425 3.78068 2.0425C2.7169 2.25063 2 3.10628 2 4.19319C2 4.86383 2 5.55761 2 6.22826C2 6.32076 2 6.39014 2.02313 6.48264C2.11563 6.82953 2.37001 6.99141 2.7169 7.03766C2.7169 7.13016 2.7169 7.19954 2.7169 7.26892C2.7169 10.6684 2.7169 14.0679 2.7169 17.4442C2.7169 18.7624 3.59568 19.8956 4.86759 20.2193C5.09885 20.2656 5.33011 20.3118 5.56136 20.3118C7.06454 20.3118 10.3253 20.3118 11.8284 20.3118C11.8516 20.3118 11.8747 20.3118 11.8747 20.3118C12.846 21.2369 14.141 21.8613 15.5979 21.9769C15.7367 21.9769 15.8754 22 16.0373 22C18.5118 22 20.7781 20.3581 21.5875 17.9761C21.68 17.6524 21.7494 17.3286 21.8188 17.0048C21.8419 16.8661 21.865 16.7042 21.9113 16.5423V15.5942L21.865 15.5711ZM3.41067 5.60386C3.41067 5.09509 3.38755 4.60945 3.41067 4.12381C3.4338 3.77693 3.7113 3.49942 4.08132 3.4763C4.15069 3.4763 4.19695 3.4763 4.26632 3.4763C8.84522 3.4763 13.4241 3.4763 18.003 3.4763C18.5811 3.4763 18.8587 3.7538 18.8587 4.33194C18.8587 4.74821 18.8587 5.14135 18.8587 5.55761C18.8587 5.55761 18.8587 5.58073 18.8587 5.60386C13.6785 5.60386 8.54458 5.60386 3.41067 5.60386ZM10.1171 17.0511C10.2328 17.7217 10.4409 18.3461 10.7647 18.9243C9.14585 18.9243 6.83328 18.9243 5.63074 18.9243C4.70571 18.9243 4.10444 18.323 4.10444 17.398C4.10444 14.0216 4.10444 10.6453 4.10444 7.24579C4.10444 7.17641 4.10444 7.13016 4.10444 7.06079C8.77584 7.06079 13.4472 7.06079 18.1186 7.06079C18.1186 7.13016 18.1186 7.19954 18.1186 7.26892C18.1186 7.89331 18.1186 9.58149 18.1186 10.599C17.448 10.3215 16.708 10.1828 15.9679 10.1828C15.6904 10.1828 15.4129 10.2059 15.1354 10.229C13.5397 10.4603 12.1291 11.2928 11.1809 12.5878C10.2328 13.8597 9.86275 15.4554 10.1171 17.0511ZM15.9448 20.705C14.7192 20.705 13.5629 20.2193 12.6841 19.3405C11.8053 18.4618 11.3197 17.3055 11.3197 16.0567C11.3197 13.5129 13.401 11.4315 15.9448 11.4315H15.9679C17.1936 11.4315 18.3499 11.9172 19.2287 12.796C20.1074 13.6747 20.5931 14.8542 20.57 16.0798C20.57 18.6468 18.4886 20.705 15.9448 20.705Z"
                                                fill="white" />
                                            <path
                                                d="M18.5571 13.5368C18.5108 13.5137 18.4645 13.5137 18.4183 13.5137C18.2564 13.5137 18.1177 13.583 18.002 13.6987L14.9726 16.7281L13.955 15.7106C13.9319 15.6875 13.9088 15.6644 13.8625 15.6181C13.77 15.5487 13.6544 15.5025 13.5156 15.5025C13.3769 15.5025 13.2613 15.5487 13.1456 15.6412C13.0763 15.6875 13.0531 15.7569 13.0069 15.8262L12.9838 15.8494L12.9606 15.8956L12.9375 15.9419V16.1731L12.9606 16.2194L12.9838 16.2656C12.9838 16.2656 13.0069 16.2888 13.0069 16.3119C13.03 16.3581 13.0763 16.4275 13.1456 16.4969C13.5619 16.9132 13.9782 17.3294 14.3944 17.7457L14.5563 17.9076C14.5794 17.9307 14.6488 17.9769 14.6951 18.0232C14.7876 18.0694 14.8801 18.0926 14.9726 18.0926C15.1344 18.0926 15.2732 18.0232 15.412 17.8844L18.8114 14.4849C18.8346 14.4618 18.8577 14.4387 18.8808 14.3924C18.9964 14.2537 19.0427 14.0456 18.9733 13.8606C18.9039 13.7218 18.7421 13.5831 18.5571 13.5368Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('Approved')}}
                                            {{\App\CPU\translate('Products')}}</span>
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{str_contains(url()->current().'?status='.request()->get('status'),'/admin/product/list/seller?status=2')==1?'active':''}}">
                                    <a class="nav-link"
                                        title="{{\App\CPU\translate('Denied')}} {{\App\CPU\translate('Products')}}"
                                        href="{{route('admin.product.list',['seller', 'status'=>'2'])}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M21.865 15.5711C21.8419 15.5248 21.8419 15.4554 21.8419 15.386C21.6569 13.7441 20.7781 12.3103 19.5293 11.339C19.5293 11.2928 19.5524 11.2465 19.5524 11.1772C19.5524 10.3678 19.5524 8.00893 19.5524 7.19953C19.5524 7.13016 19.5524 7.08391 19.5524 7.01453C19.9456 6.99141 20.1537 6.78327 20.2693 6.43639C20.2693 5.74262 20.2693 5.07197 20.2925 4.40133C20.3156 3.80006 20.1306 3.19879 19.7374 2.7594C18.928 1.81124 17.6561 2.01937 16.5461 2.01937C12.3141 2.01937 8.08207 2.01937 3.85006 2.01937C3.82693 2.01937 3.80381 2.0425 3.78068 2.0425C2.7169 2.25063 2 3.10628 2 4.19319C2 4.86383 2 5.55761 2 6.22826C2 6.32076 2 6.39014 2.02313 6.48264C2.11563 6.82953 2.37001 6.99141 2.7169 7.03766C2.7169 7.13016 2.7169 7.19954 2.7169 7.26892C2.7169 10.6684 2.7169 14.0679 2.7169 17.4442C2.7169 18.7624 3.59567 19.8956 4.86759 20.2193C5.09885 20.2656 5.3301 20.3118 5.56136 20.3118C7.06453 20.3118 10.3253 20.3118 11.8284 20.3118C11.8516 20.3118 11.8747 20.3118 11.8747 20.3118C12.846 21.2369 14.1641 21.8613 15.5979 21.9769C15.7367 21.9769 15.8754 22 16.0373 22C18.5118 22 20.7781 20.3581 21.5875 17.9761C21.7031 17.6524 21.7494 17.3286 21.8188 17.0048C21.8419 16.8661 21.865 16.7042 21.9113 16.5423V15.5942L21.865 15.5711ZM3.38754 5.60386C3.38754 5.09509 3.36442 4.60945 3.38754 4.12381C3.41067 3.77693 3.7113 3.49942 4.05819 3.4763C4.12757 3.4763 4.17382 3.4763 4.2432 3.4763C8.82209 3.4763 13.401 3.4763 17.9799 3.4763C18.558 3.4763 18.8355 3.7538 18.8355 4.33194C18.8355 4.74821 18.8355 5.14135 18.8355 5.55761C18.8355 5.55761 18.8355 5.58073 18.8355 5.60386C13.6785 5.60386 8.54458 5.60386 3.38754 5.60386ZM10.1171 17.0511C10.2328 17.7217 10.4409 18.3461 10.7647 18.9243C9.14585 18.9243 6.83328 18.9243 5.65387 18.9243C4.72884 18.9243 4.12757 18.323 4.12757 17.398C4.12757 14.0216 4.12757 10.6453 4.12757 7.24579C4.12757 7.17641 4.12757 7.13016 4.12757 7.06079C8.79896 7.06079 13.4704 7.06079 18.1418 7.06079C18.1418 7.13016 18.1418 7.19954 18.1418 7.26892C18.1418 7.89331 18.1418 9.58149 18.1418 10.599C17.4711 10.3215 16.7311 10.1828 15.9911 10.1828C15.7136 10.1828 15.436 10.2059 15.1585 10.229C13.5629 10.4603 12.1522 11.2928 11.204 12.5878C10.2328 13.8597 9.83962 15.4554 10.1171 17.0511ZM15.9448 20.705C14.7191 20.705 13.5397 20.2193 12.6841 19.3405C11.8053 18.4618 11.3197 17.3055 11.3197 16.0567C11.3197 13.5129 13.401 11.4315 15.9448 11.4315H15.9679C17.1936 11.4315 18.3499 11.9172 19.2287 12.796C20.1074 13.6747 20.5931 14.8542 20.57 16.0798C20.57 18.6468 18.4886 20.705 15.9448 20.705Z"
                                                fill="white" />
                                            <path
                                                d="M17.9095 18.5303C17.7708 18.5303 17.632 18.4841 17.5395 18.3916C17.5164 18.3684 17.4933 18.3453 17.4933 18.3453L17.4701 18.3222C17.3545 18.2065 17.0539 17.9059 16.0132 16.8653L14.51 18.3684C14.3944 18.4841 14.2556 18.5534 14.0938 18.5534C13.8394 18.5303 13.6544 18.4147 13.5619 18.2065L13.5388 18.1372V18.114L13.5156 17.9753C13.5388 17.744 13.6313 17.6284 13.7006 17.559L15.2038 16.0559L13.7238 14.5758C13.6544 14.5064 13.5619 14.3908 13.5388 14.2058V14.0902C13.585 13.7895 13.7469 13.6045 14.0244 13.5583H14.1631C14.3713 13.5814 14.4869 13.6739 14.5794 13.7433L16.0595 15.2233L17.5626 13.7202C17.632 13.6508 17.7476 13.5583 17.9326 13.5352H18.0483L18.1408 13.5583C18.2564 13.6045 18.2795 13.6045 18.3027 13.6277C18.4414 13.7202 18.5339 13.8589 18.5802 14.0208C18.6033 14.1827 18.557 14.3677 18.4645 14.4833C18.4414 14.5064 18.4183 14.5296 18.3952 14.5527L18.372 14.5758C18.2564 14.7146 17.9326 15.0152 17.1001 15.8708L16.7995 16.0327L18.3027 17.5359C18.4645 17.6978 18.5339 17.9059 18.4645 18.114C18.3952 18.3453 18.1639 18.5303 17.9095 18.5303Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('Denied')}}
                                            {{\App\CPU\translate('Products')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- vendor Product End  -->

                        @if(\App\CPU\Helpers::module_permission_check('user_section'))

                        <!-- <li
                            class="navbar-vertical-aside-has-menu {{Request::is('admin/report/seller-report') || (Request::is('admin/customer/wallet*') || Request::is('admin/customer/list') || Request::is('admin/customer/view*') || Request::is('admin/seller*') || Request::is('admin/reviews*') || Request::is('admin/customer/loyalty/report'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:"
                                title="{{\App\CPU\translate('Customer_&_vendor_Management')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none">
                                        <path
                                            d="M21.5999 7.41667C21.3655 6.27084 21.079 5.07292 20.8446 4.03125L20.8186 3.92709C20.7144 3.45834 20.5842 2.98959 20.4801 2.4948C20.454 2.33855 20.3499 2.10417 20.0634 2.02604C20.0374 2.02604 19.9853 2.02604 19.9332 2.02604C19.829 2.02604 19.7509 2 19.6467 2C17.1207 2 14.5686 2 12.0426 2H4.95923C4.85506 2 4.77695 2 4.67278 2.02604L4.54257 2.05209C4.23007 2.13021 4.15194 2.41667 4.1259 2.62501L4.09986 2.70313C3.91757 3.45834 3.76132 4.21355 3.57903 4.96876L3.55298 5.09896C3.39673 5.80209 3.24048 6.53125 3.05819 7.23438C2.90194 7.9375 3.05819 8.5625 3.55298 8.92708C3.99569 9.26562 4.02174 9.65625 3.99569 10.2031C3.99569 10.4375 3.99569 10.6458 3.99569 10.8802C3.99569 10.9844 3.99569 11.0885 3.99569 11.1927V21.1406C3.99569 21.4271 3.99569 21.8177 4.46444 21.974H4.59465C4.69882 21.974 4.77694 22 4.85507 22H12.303H19.7509C19.829 22 19.9332 22 20.0374 21.974L20.1676 21.9479C20.6103 21.7917 20.6103 21.4271 20.6103 21.1146V19.2135C20.6103 16.0625 20.6103 12.9115 20.6103 9.76042C20.6103 9.47396 20.6624 9.29167 20.8967 9.10938C21.4697 8.66667 21.7301 8.06771 21.5999 7.41667ZM9.95923 5.56771V4.96876C9.95923 4.44792 9.95923 3.95312 9.95923 3.43229C9.95923 3.25 9.98528 3.19792 10.2197 3.19792C10.4801 3.19792 10.6884 3.17187 10.8707 3.17187C11.2092 3.17187 11.3655 3.19792 11.4696 3.30208C11.5999 3.45833 11.5999 3.82292 11.5738 4.52604C11.5478 4.99479 11.5478 5.4375 11.5217 5.90625V5.95834C11.5217 6.47917 11.4957 7 11.4696 7.49479C11.4436 8.01562 11.1051 8.40625 10.6624 8.40625C10.5061 8.40625 10.3499 8.35417 10.2197 8.27604C9.95923 8.11979 9.85507 7.88542 9.85507 7.59896C9.85507 7.3125 9.88111 7 9.90715 6.6875C9.93319 6.29687 9.95923 5.93229 9.95923 5.56771ZM17.5634 7.57292C17.5634 8.04167 17.303 8.35417 16.8863 8.38021C16.8603 8.38021 16.8342 8.38021 16.8082 8.38021C16.3915 8.38021 16.053 8.06771 16.0269 7.65104C16.0009 7.15625 16.0009 6.66146 15.9749 6.14063V6.11459C15.9749 5.77605 15.9488 5.4375 15.9488 5.07292C15.9228 4.52605 15.9228 3.97917 15.8707 3.43229C15.8707 3.30208 15.8707 3.25 15.8967 3.22396C15.9228 3.19792 15.9749 3.19792 16.053 3.19792C16.079 3.19792 16.079 3.19792 16.1051 3.19792C16.2874 3.19792 16.4436 3.22396 16.6259 3.22396C16.8082 3.22396 16.9644 3.22396 17.1467 3.19792C17.1728 3.19792 17.1728 3.19792 17.1988 3.19792C17.2769 3.19792 17.329 3.19792 17.3551 3.22396C17.3811 3.25 17.4072 3.32812 17.4072 3.43229C17.4072 3.82292 17.4332 4.21355 17.4592 4.57813C17.4853 4.91667 17.5113 5.25521 17.5113 5.56771C17.5113 5.77604 17.5113 5.95833 17.5113 6.19271C17.5634 6.63542 17.5634 7.10417 17.5634 7.57292ZM14.803 6.16667C14.803 6.63542 14.803 7.10417 14.803 7.57292C14.803 7.96355 14.5426 8.27605 14.1519 8.35417C14.0999 8.35417 14.0478 8.35417 14.0217 8.35417C13.6832 8.35417 13.3967 8.14584 13.2665 7.83334C13.2144 7.72917 13.2144 7.57292 13.1884 7.44271C13.1884 7.02605 13.1624 6.60938 13.1624 6.19271V6.08854C13.1363 5.25521 13.1103 4.3698 13.0842 3.51042C13.0842 3.25 13.1624 3.19792 13.2405 3.17187C13.3186 3.17187 13.3707 3.14584 13.4488 3.14584L14.178 3.09375C14.204 3.09375 14.2301 3.09375 14.2561 3.09375C14.4384 3.09375 14.5686 3.14584 14.6728 3.22396C14.7249 3.27604 14.7769 3.38021 14.803 3.48438C14.829 3.58854 14.829 3.69271 14.829 3.82292V4.39584C14.829 4.76042 14.829 5.15104 14.829 5.51562V5.54167L14.803 6.16667ZM12.329 8.71875C12.8759 9.34375 13.3707 9.60417 13.9176 9.60417C14.3342 9.60417 14.7769 9.44792 15.3238 9.10938C15.3499 9.08334 15.4019 9.08333 15.4019 9.08333C15.428 9.08333 15.454 9.10937 15.5321 9.16146C16.0009 9.5 16.4176 9.65625 16.8082 9.65625C17.1988 9.65625 17.6155 9.5 18.1103 9.1875C18.1363 9.16146 18.1624 9.16146 18.1624 9.13542C18.2665 9.1875 18.3707 9.23958 18.4749 9.29166C18.7613 9.44791 19.0478 9.60417 19.3863 9.65625C19.4124 9.65625 19.4124 9.76042 19.3863 9.86458C19.3863 9.91667 19.3863 9.96875 19.3863 9.99479C19.3863 10.75 19.3863 11.5052 19.3863 12.2604C19.3863 13.5885 19.3863 14.9167 19.3863 16.2448C19.3863 16.401 19.3603 16.5052 19.3342 16.5313C19.2821 16.5833 19.204 16.5833 19.0478 16.5833C16.8082 16.5833 14.5686 16.5833 12.329 16.5833C10.0894 16.5833 7.84986 16.5833 5.61027 16.5833C5.45402 16.5833 5.34986 16.5573 5.32382 16.5313C5.27174 16.4792 5.27173 16.401 5.27173 16.2448C5.29778 14.1615 5.27173 12.1823 5.27173 9.96875C5.27173 9.83854 5.27173 9.70834 5.34986 9.63021C5.3759 9.60417 5.45402 9.60417 5.50611 9.57812C5.53215 9.57812 5.5582 9.57812 5.58424 9.57812C5.6884 9.55208 5.79257 9.5 5.89673 9.44792C5.94882 9.42187 6.0009 9.36979 6.05298 9.34375C6.18319 9.23958 6.3134 9.16146 6.46965 9.16146C6.57382 9.16146 6.70403 9.26563 6.83423 9.34375C6.96444 9.44792 7.12069 9.55208 7.30298 9.60417C7.48527 9.65625 7.66756 9.68229 7.82381 9.68229C8.26652 9.68229 8.65715 9.55209 9.07382 9.26563C9.09986 9.23959 9.1259 9.23958 9.15194 9.21354C9.20402 9.1875 9.20403 9.16146 9.23007 9.16146C9.23007 9.16146 9.25611 9.16146 9.30819 9.1875C9.88111 9.52604 10.3238 9.70833 10.7405 9.70833C11.3134 9.60417 11.7822 9.34375 12.329 8.71875ZM8.73527 4.42188C8.73527 5.09896 8.70924 5.80209 8.68319 6.50521C8.68319 6.58334 8.68319 6.66146 8.68319 6.76563C8.65715 7.39063 8.65715 8.27604 8.00611 8.38021C7.95403 8.38021 7.90194 8.38021 7.8759 8.38021C7.84986 8.38021 7.82382 8.38021 7.79778 8.38021C7.35507 8.35417 7.09465 8.01563 7.12069 7.52083C7.17278 6.29688 7.22486 4.89063 7.2509 3.48438C7.2509 3.27604 7.35507 3.17187 7.58944 3.17187H7.66757C7.77174 3.17187 7.8759 3.17187 7.98007 3.17187C8.08423 3.17187 8.21445 3.17187 8.31861 3.17187C8.39674 3.17187 8.47486 3.17187 8.57903 3.17187H8.63111C8.70923 3.17187 8.73527 3.17188 8.76132 3.19792C8.78736 3.22396 8.78736 3.30209 8.78736 3.40626C8.73528 3.7448 8.73527 4.08334 8.73527 4.42188ZM5.16757 3.66667C5.16757 3.61459 5.19361 3.5625 5.19361 3.51042C5.21965 3.32813 5.21965 3.22396 5.45403 3.19792C5.50611 3.19792 5.53215 3.19792 5.58424 3.19792C5.74049 3.19792 5.84465 3.22396 5.89673 3.27605C5.94882 3.32813 5.97486 3.45834 5.94882 3.64063C5.89674 4.08334 5.89673 4.55208 5.87069 4.99479V5.04688C5.87069 5.22917 5.8707 5.41146 5.84465 5.59375C5.84465 5.9323 5.84465 6.27084 5.84465 6.60938V6.63542C5.84465 6.94792 5.84465 7.26042 5.84465 7.57292C5.84465 8.04167 5.53215 8.38021 5.08944 8.38021H5.01132C4.77694 8.38021 4.54257 8.25 4.41236 8.09375C4.28215 7.9375 4.23007 7.70312 4.28215 7.49479C4.51653 6.40104 4.77694 5.30729 5.01132 4.26563L5.16757 3.66667ZM5.29778 17.8333C5.32382 17.8073 5.40194 17.7812 5.53215 17.7812C7.79778 17.7812 10.0634 17.7812 12.329 17.7812C14.5947 17.7812 16.8603 17.7812 19.1259 17.7812C19.2561 17.7812 19.3082 17.7813 19.3603 17.8333C19.3863 17.8594 19.4124 17.9375 19.3863 18.0156C19.3603 18.7187 19.3603 19.4479 19.3603 20.0729V20.776H5.27173V20.099C5.27173 19.4479 5.27173 18.7187 5.24569 18.0156C5.24569 17.9115 5.27174 17.8594 5.29778 17.8333ZM18.8394 7.65104C18.8134 6.53125 18.7874 5.04687 18.7353 3.5625C18.7353 3.45833 18.7353 3.32813 18.7613 3.25C18.7874 3.19792 18.8134 3.17187 18.9697 3.17187C19.0217 3.17187 19.0478 3.17187 19.0999 3.17187C19.1259 3.17187 19.178 3.17187 19.204 3.17187C19.3603 3.17187 19.4384 3.25 19.4644 3.43229C19.6728 4.36979 19.8811 5.28125 20.0894 6.19271L20.3499 7.33854C20.454 7.78125 20.3238 8.09375 19.9853 8.25C19.8551 8.32812 19.7249 8.35417 19.5946 8.35417C19.3863 8.35417 19.204 8.27604 19.0478 8.11979C18.8915 8.01562 18.8394 7.85938 18.8394 7.65104Z"
                                            fill="white" />
                                    </svg>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('Customer_&_vendor_Management')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/report/seller-report') || Request::is('admin/seller*')?'block':'none'}}"> -->
                                <li
                                    class="navbar-vertical-aside-has-menu {{(Request::is('admin/customer/wallet*') || Request::is('admin/customer/list') || Request::is('admin/customer/view*') || Request::is('admin/reviews*') || Request::is('admin/customer/loyalty/report'))?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:"
                                        title="{{\App\CPU\translate('Customers')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M5.95006 22.0005C5.76844 21.9324 5.63224 21.7962 5.51873 21.5692C4.83768 20.1844 4.15664 18.8223 3.45289 17.4375L3.40749 17.324C3.08967 16.6883 2.77185 16.0527 2.45403 15.4171L2 14.509V14.2139C2.09081 14.0323 2.24971 13.8961 2.49943 13.7825C2.83995 13.6236 3.18048 13.4647 3.4983 13.2831L3.52099 13.2604C3.81611 13.1015 4.08854 13.0334 4.38365 13.0334C4.61067 13.0334 4.86039 13.0788 5.0874 13.1696C5.1101 13.1696 5.1328 13.1696 5.1555 13.1696C5.22361 13.1696 5.29171 13.1469 5.35982 13.1015C5.54143 12.9653 5.70034 12.8064 5.88195 12.6475C6.19977 12.3751 6.54029 12.0799 6.88082 11.8302C7.51646 11.3989 8.26561 11.1719 9.06016 11.1719C9.96822 11.1719 10.8763 11.467 11.5573 12.0345C12.2611 12.6021 12.2838 12.6021 13.1691 12.6021H14.5766C14.849 12.6021 15.1442 12.6021 15.4166 12.6021C16.3019 12.6021 16.8695 12.9653 17.1873 13.7371C17.1873 13.7598 17.21 13.7598 17.21 13.7825L17.2554 13.8052H17.3235L17.3689 13.7598C17.3916 13.7144 17.4143 13.6917 17.4597 13.6463L17.8456 13.2377C18.2316 12.8291 18.6175 12.4205 19.0034 12.0118C19.3666 11.6259 19.8434 11.4216 20.3201 11.4216C20.6379 11.4216 20.9557 11.5124 21.2281 11.694C21.6595 11.9664 21.9319 12.3751 22.0227 12.8291C22.1135 13.2831 22 13.7371 21.7049 14.1458C20.6833 15.5533 19.7071 16.8472 18.7537 18.0731C17.9818 19.072 16.8922 19.5714 15.5301 19.5714C14.622 19.5714 13.6913 19.5714 12.7832 19.5714H8.69693L8.65153 19.5941C8.62883 19.6849 8.60613 19.753 8.60613 19.8438C8.56073 20.0255 8.53802 20.1844 8.46992 20.3433C8.33371 20.7292 8.03859 21.0243 7.58456 21.2513C7.26674 21.4102 6.97162 21.5691 6.6538 21.7281L6.26787 21.9324L5.95006 22.0005ZM4.38365 14.1685C4.31555 14.1685 4.24745 14.1912 4.15664 14.2139C3.95233 14.3047 3.77071 14.3955 3.5664 14.4863C3.47559 14.5317 3.38479 14.5771 3.29398 14.6225L3.27128 14.6906L6.26787 20.7065L6.33598 20.7292C6.33598 20.7292 6.83541 20.4795 6.99432 20.4114C7.47106 20.1617 7.58456 19.8665 7.33485 19.3671L5.72304 16.1208C5.47332 15.5987 5.22361 15.0992 4.95119 14.5771C4.81498 14.3047 4.61067 14.1685 4.38365 14.1685ZM9.06016 12.3297C8.58343 12.3297 8.1294 12.4659 7.65267 12.7156C7.22134 12.9653 6.83542 13.2831 6.44949 13.6009C6.26788 13.7371 6.10896 13.8961 5.92735 14.0323L5.90465 14.0777L5.95006 14.1912C5.97276 14.2366 5.99546 14.282 5.99546 14.3047L6.49489 15.2808C7.01702 16.3024 7.51646 17.3467 8.03859 18.3682C8.1294 18.5499 8.22021 18.5726 8.37912 18.5726C9.76391 18.5726 11.1714 18.5726 12.5562 18.5726C13.5096 18.5726 14.4631 18.5726 15.3939 18.5726C16.4835 18.5726 17.3689 18.1412 18.0045 17.2559C18.3905 16.7337 18.7991 16.2116 19.185 15.6668L20.0023 14.5544C20.252 14.2366 20.479 13.9188 20.706 13.6009C20.8422 13.3966 20.8876 13.1696 20.7968 12.9653C20.7287 12.761 20.5471 12.6248 20.3201 12.5794C20.2747 12.5794 20.2293 12.5794 20.2066 12.5794C20.025 12.5794 19.8661 12.6702 19.6844 12.8518C19.3212 13.2377 18.958 13.6236 18.5948 14.0096C18.1407 14.4863 17.6867 14.9857 17.2327 15.4852C16.8468 15.9165 16.37 16.1208 15.8025 16.1208H15.8252C15.6663 16.1208 15.5074 16.1208 15.3712 16.1208H13.4188C13.1464 16.1208 12.874 16.1208 12.6016 16.1208C12.3973 16.1208 12.2384 16.03 12.1249 15.8938C12.0341 15.7576 11.9886 15.5987 12.034 15.4171C12.1022 15.1673 12.3065 15.0084 12.6243 15.0084H14.0091C14.5085 15.0084 14.9852 15.0084 15.4847 15.0084C15.7344 15.0084 15.8933 14.9403 16.0295 14.8041C16.2111 14.5998 16.2565 14.3728 16.1657 14.1458C16.0522 13.8734 15.8479 13.7371 15.5528 13.7371C15.3258 13.7371 15.076 13.7371 14.849 13.7371C14.5766 13.7371 14.3042 13.7371 14.0318 13.7371C13.6459 13.7371 13.328 13.7371 13.0329 13.7598C12.9648 13.7598 12.8967 13.7598 12.8286 13.7598C12.1022 13.7598 11.4892 13.5328 10.9898 13.0334C10.9671 13.0107 10.9444 12.988 10.9217 12.988C10.3087 12.5567 9.6731 12.3297 9.06016 12.3297Z"
                                                fill="white" />
                                            <path
                                                d="M12.6708 10.2179C11.6492 10.2179 10.6504 10.2179 9.62882 10.2179C9.3337 10.2179 9.10668 10.0136 9.08397 9.7185C9.06127 9.03746 9.08397 8.1748 9.538 7.38025C9.85582 6.83542 10.2645 6.40409 10.7866 6.08627C10.8093 6.06356 10.832 6.06357 10.8547 6.04087L10.9001 6.01816V5.95006C9.90123 4.83768 10.1736 3.4756 10.9455 2.68104C11.3995 2.22701 11.9898 2 12.6027 2C13.1021 2 13.5789 2.15891 13.9875 2.47673C14.5323 2.90806 14.8502 3.4756 14.8956 4.15664C14.941 4.81498 14.7593 5.40523 14.2826 5.92736V5.99546C14.3734 6.06357 14.4642 6.15437 14.555 6.22248C14.7593 6.38139 14.941 6.5176 15.0999 6.69921C15.7355 7.33485 16.0533 8.1521 16.076 9.10557C16.076 9.28718 16.076 9.49149 16.076 9.6731C16.076 10.0136 15.849 10.2406 15.5085 10.2406C14.5777 10.2179 13.6243 10.2179 12.6708 10.2179ZM12.6027 6.6538C12.126 6.6538 11.6492 6.81272 11.1952 7.15324C10.5596 7.62997 10.2191 8.26561 10.1964 9.08286L10.2418 9.12826H14.9864L15.0318 9.08286C14.9637 8.084 14.5323 7.35755 13.7378 6.92622C13.3519 6.74461 12.9659 6.6538 12.6027 6.6538ZM12.6254 3.08967C12.3076 3.08967 11.9898 3.22588 11.74 3.4756C11.4903 3.72531 11.3541 4.04313 11.3541 4.36095C11.3541 5.01929 11.9217 5.56414 12.6027 5.58684C13.2837 5.58684 13.8286 5.042 13.8286 4.38365C13.8286 4.04313 13.6924 3.72531 13.4654 3.4756C13.2383 3.22588 12.9205 3.08967 12.6254 3.08967Z"
                                                fill="white" />
                                        </svg>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('Customers')}}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{(Request::is('admin/customer/*') || Request::is('admin/customer/list') || Request::is('admin/customer/bulk-import') || Request::is('admin/customer/view*') || Request::is('admin/reviews*') || Request::is('admin/customer/loyalty/report'))?'block':'none'}}">
                                        <li
                                            class="nav-item {{Request::is('admin/customer/list') || Request::is('admin/customer/view*')?'active':''}}">
                                            <a class="nav-link " href="{{route('admin.customer.list')}}"
                                                title="{{\App\CPU\translate('Customers_List')}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path d="M5.87389 21.9984C5.61804 21.9092 5.34404 21.8531 5.11131 21.7227C4.36854 21.3034 4.00045 20.6498 4.00045 19.7964C3.9988 17.8438 4.0021 15.8911 4.00375 13.9384C4.00705 11.106 4.01035 8.2752 4.01365 5.44276C4.01531 4.49696 4.4098 3.77729 5.27802 3.37619C5.55697 3.24745 5.88874 3.21113 6.20071 3.17812C6.50112 3.14511 6.80813 3.17152 7.1234 3.17152C7.12835 3.07083 7.12835 2.98665 7.13661 2.90412C7.19108 2.37428 7.59547 2.00124 8.13357 2.00124C10.4576 1.99959 12.7817 1.99959 15.1057 2.00124C15.6934 2.00124 16.0994 2.40399 16.1159 2.9883C16.1176 3.03947 16.1159 3.09064 16.1159 3.17152C16.2678 3.17152 16.4081 3.16492 16.5484 3.17317C16.9049 3.19298 17.2697 3.17317 17.613 3.25075C18.5539 3.46203 19.2124 4.30549 19.2306 5.27274C19.2405 5.73491 19.2339 6.19708 19.2323 6.65925C19.229 11.0136 19.224 15.3695 19.2191 19.7238C19.2174 20.696 18.7981 21.4107 17.9085 21.825C17.7352 21.9059 17.542 21.9439 17.3572 22C13.5294 21.9984 9.70165 21.9984 5.87389 21.9984ZM7.10194 4.3418C6.83784 4.3418 6.56549 4.3418 6.29479 4.3418C5.57348 4.3418 5.18394 4.72804 5.18394 5.44936C5.17898 10.2213 5.17568 14.9932 5.17238 19.7651C5.17238 20.4237 5.57348 20.8264 6.22712 20.8264C9.81389 20.8264 13.4007 20.8264 16.9874 20.8264C17.0782 20.8264 17.1706 20.8248 17.2581 20.805C17.7632 20.6993 18.0488 20.3098 18.0488 19.7288C18.0537 15.1005 18.057 10.4722 18.0603 5.84385C18.0603 5.65568 18.0653 5.46586 18.0587 5.27769C18.0438 4.86999 17.7731 4.4689 17.3786 4.40122C16.9693 4.33025 16.5451 4.3484 16.1308 4.32694C16.1209 4.39462 16.1159 4.41278 16.1143 4.43093C16.0845 5.1704 15.7247 5.51538 14.9852 5.51538C13.9305 5.51538 12.8758 5.51538 11.821 5.51538C10.5979 5.51538 9.37318 5.51703 8.15008 5.51538C7.69121 5.51538 7.32312 5.26614 7.19273 4.85514C7.14321 4.70163 7.13495 4.53657 7.10194 4.3418ZM8.30854 4.3319C10.5253 4.3319 12.7256 4.3319 14.9291 4.3319C14.9291 3.94235 14.9291 3.56271 14.9291 3.18637C12.7157 3.18637 10.5154 3.18637 8.30854 3.18637C8.30854 3.57097 8.30854 3.93905 8.30854 4.3319Z" fill="white"/>
                                                        <path d="M13.9204 16.4509C14.5905 16.4509 15.259 16.4492 15.9291 16.4509C16.3121 16.4509 16.5729 16.6869 16.5762 17.0286C16.5795 17.3802 16.317 17.6212 15.9242 17.6212C14.579 17.6228 13.2337 17.6228 11.8868 17.6212C11.4956 17.6212 11.2249 17.3769 11.2266 17.0319C11.2282 16.6869 11.4989 16.4492 11.8918 16.4492C12.5685 16.4509 13.2453 16.4509 13.9204 16.4509Z" fill="white"/>
                                                        <path d="M13.8924 9.81214C13.2222 9.81214 12.5521 9.81379 11.8836 9.81214C11.494 9.81214 11.2217 9.5662 11.2266 9.22123C11.2299 8.88285 11.4973 8.64186 11.8753 8.64186C13.2222 8.64021 14.5675 8.64021 15.9144 8.64186C16.2923 8.64186 16.5597 8.8845 16.563 9.22288C16.5663 9.5662 16.2923 9.81214 15.9028 9.81214C15.2327 9.81379 14.5625 9.81214 13.8924 9.81214Z" fill="white"/>
                                                        <path d="M13.8874 13.7179C13.2239 13.7179 12.5603 13.7179 11.8968 13.7179C11.5006 13.7179 11.2316 13.4835 11.2266 13.1402C11.2217 12.7886 11.4957 12.5476 11.9017 12.5476C13.242 12.5476 14.5807 12.546 15.921 12.5476C16.3699 12.5476 16.6571 12.9091 16.5333 13.3069C16.4541 13.5594 16.2313 13.7162 15.9358 13.7179C15.2525 13.7195 14.5691 13.7179 13.8874 13.7179Z" fill="white"/>
                                                        <path d="M7.944 13.2184C8.41113 12.7496 8.83533 12.3188 9.26614 11.8963C9.35197 11.8121 9.45761 11.7345 9.56985 11.6949C9.81249 11.6074 10.0815 11.7131 10.2218 11.9227C10.3704 12.1439 10.3704 12.4327 10.1806 12.6275C9.58141 13.2415 8.97398 13.849 8.35831 14.4481C8.13878 14.6611 7.82186 14.6611 7.59573 14.4547C7.31677 14.2005 7.04938 13.9348 6.79518 13.6558C6.58391 13.4247 6.61527 13.0715 6.8348 12.8619C7.04772 12.6572 7.38775 12.6391 7.61554 12.8404C7.73438 12.9444 7.82681 13.0814 7.944 13.2184Z" fill="white"/>
                                                        <path d="M7.97893 17.142C8.08952 17.0067 8.1704 16.8961 8.26448 16.7987C8.61606 16.4422 8.96764 16.084 9.32747 15.7374C9.56681 15.5063 9.92829 15.5195 10.1528 15.7489C10.3723 15.975 10.3839 16.325 10.1544 16.5594C9.56681 17.1585 8.97424 17.7527 8.37342 18.3387C8.13408 18.5714 7.81056 18.5681 7.56462 18.3404C7.30383 18.0961 7.04963 17.8435 6.80534 17.5811C6.58416 17.3434 6.60232 16.9902 6.82515 16.7706C7.04798 16.5528 7.39626 16.5462 7.63725 16.7657C7.74619 16.8647 7.83862 16.9852 7.97893 17.142Z" fill="white"/>
                                                        <path d="M7.96953 9.0408C8.10323 8.884 8.19401 8.7635 8.298 8.65786C8.63968 8.30959 8.983 7.96296 9.33458 7.62458C9.56732 7.40175 9.92385 7.41 10.1467 7.62788C10.3712 7.84741 10.3926 8.2056 10.1665 8.43668C9.57557 9.04246 8.97805 9.63997 8.37393 10.2309C8.13624 10.4636 7.80942 10.457 7.56513 10.2276C7.30929 9.9866 7.0617 9.73736 6.81906 9.48317C6.59457 9.24878 6.59953 8.89885 6.81741 8.67272C7.04024 8.43998 7.39512 8.43008 7.64106 8.65951C7.74835 8.7602 7.83583 8.88234 7.96953 9.0408Z" fill="white"/>
                                                </svg>
                                                <span class="text-truncate">{{\App\CPU\translate('Customers_List')}}
                                                </span>
                                            </a>
                                        </li>
                                        

                                        <li class="nav-item {{ Request::is('admin/customer/bulk-import') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('admin.customer.bulk-import') }}"
                                                title="{{ \App\CPU\translate('customer_bulk_import') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M14.4869 3.01175H7.83489C5.75489 3.00378 4.05089 4.66078 4.00089 6.74078V17.4778C3.95589 19.5798 5.62389 21.3198 7.72489 21.3648C7.76189 21.3648 7.79889 21.3658 7.83489 21.3648H15.8229C17.9129 21.2908 19.5649 19.5688 19.553 17.4778V8.28778L14.4869 3.01175Z"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M14.2266 3V5.909C14.2266 7.329 15.3756 8.48 16.7956 8.484H19.5496"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M11.3906 10.1582V16.1992" stroke="white" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M13.7369 12.5152L11.3919 10.1602L9.04688 12.5152" stroke="white"
                                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <span class="text-truncate">{{ \App\CPU\translate('Upload_customers') }}</span>
                                            </a>
                                        </li>

                                        
                                        
                                        <!-- <li class="nav-item d-none {{Request::is('admin/customer/user-chat')?'active':''}}">
                                            <a class="nav-link" href="{{route('admin.user-chat')}}"
                                                title="{{\App\CPU\translate('Customer')}} {{\App\CPU\translate('Reviews')}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path d="M3.8757 2C9.29188 2 14.7077 2 20.1238 2C20.1528 2.01174 20.181 2.02896 20.2111 2.03444C20.9598 2.17767 21.4947 2.59797 21.8101 3.2883C21.8947 3.47379 21.9369 3.67847 21.9988 3.87453C21.9988 7.88461 21.9988 11.8943 21.9988 15.9044C21.9111 16.1607 21.8579 16.4358 21.7291 16.6699C21.2967 17.4557 20.6021 17.8063 19.7165 17.8055C18.1675 17.8044 16.619 17.8079 15.07 17.8012C14.9159 17.8004 14.8419 17.8505 14.7754 17.9852C14.2725 19.005 13.7661 20.0233 13.2491 21.0361C13.1443 21.2419 13.0132 21.445 12.8535 21.6102C12.5169 21.9585 12.1045 22.0978 11.627 21.9275C11.2001 21.7753 10.948 21.4415 10.7539 21.0537C10.2421 20.0311 9.72862 19.0093 9.2234 17.9832C9.15648 17.847 9.08017 17.8004 8.92793 17.8012C7.379 17.8075 5.83046 17.8075 4.28153 17.8044C2.91848 17.8012 2.00117 16.8827 2.00117 15.5264C2.00078 11.7652 2.00235 8.00358 2 4.24239C1.99961 3.46519 2.28646 2.82417 2.93531 2.39447C3.21395 2.21054 3.56028 2.12836 3.8757 2ZM11.9976 20.86C12.0966 20.6921 12.1714 20.5809 12.2309 20.4624C12.7964 19.3353 13.3642 18.2098 13.9191 17.0776C14.0721 16.7653 14.2889 16.6276 14.6404 16.6295C16.3325 16.6393 18.0247 16.635 19.7172 16.6335C20.4334 16.6327 20.8271 16.2362 20.8271 15.5209C20.8271 11.7785 20.8271 8.03606 20.8271 4.29366C20.8271 3.55989 20.4412 3.17207 19.7106 3.17207C14.5687 3.17207 9.42689 3.17207 4.28505 3.17207C3.5638 3.17207 3.17285 3.56067 3.17285 4.278C3.17246 6.2175 3.17285 8.15699 3.17285 10.0969C3.17285 11.9256 3.17442 13.7547 3.17168 15.5835C3.17129 15.8934 3.26638 16.1572 3.49375 16.3705C3.7176 16.5802 3.99115 16.6354 4.28661 16.635C5.96586 16.6335 7.64511 16.6409 9.32436 16.6288C9.6977 16.626 9.92781 16.7606 10.0918 17.098C10.6404 18.2258 11.2083 19.3443 11.7706 20.4655C11.8278 20.5798 11.8982 20.6882 11.9976 20.86Z" fill="white"/>
                                                        <path d="M15.1976 12.7415C15.2015 13.8655 14.4274 14.4286 13.507 14.0795C13.0601 13.9101 12.6402 13.6655 12.2191 13.4342C12.0559 13.3446 11.935 13.3466 11.7765 13.4373C11.4384 13.6311 11.0901 13.8076 10.7418 13.9825C10.453 14.1273 10.1469 14.2177 9.81939 14.1735C9.2038 14.0901 8.78898 13.6123 8.8062 12.9423C8.81677 12.5361 8.87273 12.122 8.97565 11.7295C9.07897 11.3347 9.00148 11.0631 8.68097 10.8079C8.39803 10.5825 8.14953 10.3093 7.90924 10.0366C7.66113 9.75521 7.5179 9.41748 7.56291 9.03279C7.62122 8.53461 7.92255 8.20392 8.38159 8.06108C8.76902 7.94055 9.17876 7.85602 9.58262 7.83332C10.0323 7.80828 10.3047 7.64235 10.4514 7.20718C10.552 6.90858 10.7163 6.62799 10.8772 6.35405C11.1069 5.96349 11.4215 5.66724 11.9013 5.62928C12.4731 5.58428 12.8492 5.89853 13.1188 6.35522C13.3 6.66204 13.4663 6.98215 13.5978 7.31284C13.7238 7.62904 13.903 7.79028 14.2607 7.80671C14.6349 7.82354 15.0094 7.89711 15.3768 7.9789C15.9979 8.11666 16.3885 8.51582 16.435 9.05353C16.4749 9.51531 16.2562 9.88396 15.947 10.2013C15.6746 10.4808 15.398 10.7574 15.1064 11.0165C14.9644 11.1429 14.9409 11.2666 14.9741 11.4415C15.0653 11.9201 15.1401 12.4015 15.1976 12.7415ZM9.9497 13.0417C10.1211 12.9673 10.2413 12.9235 10.3536 12.8652C10.7864 12.6414 11.2208 12.4191 11.6473 12.1843C11.8876 12.052 12.1099 12.0528 12.349 12.1831C12.7881 12.4226 13.2327 12.6527 13.6768 12.8832C13.7778 12.9356 13.8866 12.9732 14.0255 13.0319C14.0197 12.9036 14.024 12.8139 14.0099 12.7275C13.9234 12.2015 13.8389 11.6751 13.7402 11.1515C13.6917 10.8948 13.7629 10.6952 13.9469 10.5187C14.3175 10.1626 14.6842 9.80217 15.0497 9.44096C15.1252 9.36621 15.1894 9.27972 15.2849 9.16819C15.1354 9.13062 15.0325 9.09579 14.926 9.08014C14.4439 9.00774 13.9617 8.93417 13.4777 8.87468C13.1806 8.83829 12.9748 8.70915 12.846 8.42855C12.6402 7.97969 12.4136 7.5406 12.1917 7.09956C12.1412 6.99937 12.0743 6.90741 11.9945 6.77866C11.9142 6.91249 11.8516 7.00211 11.8035 7.09916C11.5769 7.55234 11.3503 8.00591 11.1312 8.46299C11.0204 8.69428 10.8478 8.82655 10.5946 8.86177C10.1117 8.92908 9.62919 8.99835 9.14706 9.07114C9.01244 9.09149 8.88016 9.12827 8.70406 9.16702C8.81442 9.29186 8.88212 9.37795 8.95961 9.45426C9.32121 9.81038 9.68437 10.1653 10.0495 10.5175C10.2319 10.6936 10.3062 10.8952 10.2549 11.1515C10.1775 11.5401 10.116 11.9322 10.0518 12.3236C10.0162 12.5443 9.98884 12.7662 9.9497 13.0417Z" fill="white"/>
                                                </svg>
                                                <span
                                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                                    {{\App\CPU\translate('Customer')}} {{\App\CPU\translate('Chats')}}
                                                </span>
                                            </a>
                                        </li> -->

                                        <li class="nav-item {{Request::is('admin/reviews*')?'active':''}}">
                                            <a class="nav-link" href="{{route('admin.reviews.list')}}"
                                                title="{{\App\CPU\translate('Customer')}} {{\App\CPU\translate('Reviews')}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path d="M3.8757 2C9.29188 2 14.7077 2 20.1238 2C20.1528 2.01174 20.181 2.02896 20.2111 2.03444C20.9598 2.17767 21.4947 2.59797 21.8101 3.2883C21.8947 3.47379 21.9369 3.67847 21.9988 3.87453C21.9988 7.88461 21.9988 11.8943 21.9988 15.9044C21.9111 16.1607 21.8579 16.4358 21.7291 16.6699C21.2967 17.4557 20.6021 17.8063 19.7165 17.8055C18.1675 17.8044 16.619 17.8079 15.07 17.8012C14.9159 17.8004 14.8419 17.8505 14.7754 17.9852C14.2725 19.005 13.7661 20.0233 13.2491 21.0361C13.1443 21.2419 13.0132 21.445 12.8535 21.6102C12.5169 21.9585 12.1045 22.0978 11.627 21.9275C11.2001 21.7753 10.948 21.4415 10.7539 21.0537C10.2421 20.0311 9.72862 19.0093 9.2234 17.9832C9.15648 17.847 9.08017 17.8004 8.92793 17.8012C7.379 17.8075 5.83046 17.8075 4.28153 17.8044C2.91848 17.8012 2.00117 16.8827 2.00117 15.5264C2.00078 11.7652 2.00235 8.00358 2 4.24239C1.99961 3.46519 2.28646 2.82417 2.93531 2.39447C3.21395 2.21054 3.56028 2.12836 3.8757 2ZM11.9976 20.86C12.0966 20.6921 12.1714 20.5809 12.2309 20.4624C12.7964 19.3353 13.3642 18.2098 13.9191 17.0776C14.0721 16.7653 14.2889 16.6276 14.6404 16.6295C16.3325 16.6393 18.0247 16.635 19.7172 16.6335C20.4334 16.6327 20.8271 16.2362 20.8271 15.5209C20.8271 11.7785 20.8271 8.03606 20.8271 4.29366C20.8271 3.55989 20.4412 3.17207 19.7106 3.17207C14.5687 3.17207 9.42689 3.17207 4.28505 3.17207C3.5638 3.17207 3.17285 3.56067 3.17285 4.278C3.17246 6.2175 3.17285 8.15699 3.17285 10.0969C3.17285 11.9256 3.17442 13.7547 3.17168 15.5835C3.17129 15.8934 3.26638 16.1572 3.49375 16.3705C3.7176 16.5802 3.99115 16.6354 4.28661 16.635C5.96586 16.6335 7.64511 16.6409 9.32436 16.6288C9.6977 16.626 9.92781 16.7606 10.0918 17.098C10.6404 18.2258 11.2083 19.3443 11.7706 20.4655C11.8278 20.5798 11.8982 20.6882 11.9976 20.86Z" fill="white"/>
                                                        <path d="M15.1976 12.7415C15.2015 13.8655 14.4274 14.4286 13.507 14.0795C13.0601 13.9101 12.6402 13.6655 12.2191 13.4342C12.0559 13.3446 11.935 13.3466 11.7765 13.4373C11.4384 13.6311 11.0901 13.8076 10.7418 13.9825C10.453 14.1273 10.1469 14.2177 9.81939 14.1735C9.2038 14.0901 8.78898 13.6123 8.8062 12.9423C8.81677 12.5361 8.87273 12.122 8.97565 11.7295C9.07897 11.3347 9.00148 11.0631 8.68097 10.8079C8.39803 10.5825 8.14953 10.3093 7.90924 10.0366C7.66113 9.75521 7.5179 9.41748 7.56291 9.03279C7.62122 8.53461 7.92255 8.20392 8.38159 8.06108C8.76902 7.94055 9.17876 7.85602 9.58262 7.83332C10.0323 7.80828 10.3047 7.64235 10.4514 7.20718C10.552 6.90858 10.7163 6.62799 10.8772 6.35405C11.1069 5.96349 11.4215 5.66724 11.9013 5.62928C12.4731 5.58428 12.8492 5.89853 13.1188 6.35522C13.3 6.66204 13.4663 6.98215 13.5978 7.31284C13.7238 7.62904 13.903 7.79028 14.2607 7.80671C14.6349 7.82354 15.0094 7.89711 15.3768 7.9789C15.9979 8.11666 16.3885 8.51582 16.435 9.05353C16.4749 9.51531 16.2562 9.88396 15.947 10.2013C15.6746 10.4808 15.398 10.7574 15.1064 11.0165C14.9644 11.1429 14.9409 11.2666 14.9741 11.4415C15.0653 11.9201 15.1401 12.4015 15.1976 12.7415ZM9.9497 13.0417C10.1211 12.9673 10.2413 12.9235 10.3536 12.8652C10.7864 12.6414 11.2208 12.4191 11.6473 12.1843C11.8876 12.052 12.1099 12.0528 12.349 12.1831C12.7881 12.4226 13.2327 12.6527 13.6768 12.8832C13.7778 12.9356 13.8866 12.9732 14.0255 13.0319C14.0197 12.9036 14.024 12.8139 14.0099 12.7275C13.9234 12.2015 13.8389 11.6751 13.7402 11.1515C13.6917 10.8948 13.7629 10.6952 13.9469 10.5187C14.3175 10.1626 14.6842 9.80217 15.0497 9.44096C15.1252 9.36621 15.1894 9.27972 15.2849 9.16819C15.1354 9.13062 15.0325 9.09579 14.926 9.08014C14.4439 9.00774 13.9617 8.93417 13.4777 8.87468C13.1806 8.83829 12.9748 8.70915 12.846 8.42855C12.6402 7.97969 12.4136 7.5406 12.1917 7.09956C12.1412 6.99937 12.0743 6.90741 11.9945 6.77866C11.9142 6.91249 11.8516 7.00211 11.8035 7.09916C11.5769 7.55234 11.3503 8.00591 11.1312 8.46299C11.0204 8.69428 10.8478 8.82655 10.5946 8.86177C10.1117 8.92908 9.62919 8.99835 9.14706 9.07114C9.01244 9.09149 8.88016 9.12827 8.70406 9.16702C8.81442 9.29186 8.88212 9.37795 8.95961 9.45426C9.32121 9.81038 9.68437 10.1653 10.0495 10.5175C10.2319 10.6936 10.3062 10.8952 10.2549 11.1515C10.1775 11.5401 10.116 11.9322 10.0518 12.3236C10.0162 12.5443 9.98884 12.7662 9.9497 13.0417Z" fill="white"/>
                                                </svg>
                                                <span
                                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                                    {{\App\CPU\translate('Customer')}} {{\App\CPU\translate('Reviews')}}
                                                </span>
                                            </a>
                                        </li>
                                        <li
                                            class="nav-item {{Request::is('admin/customer/wallet/report')?'active':''}}">
                                            <a class="nav-link" title="{{\App\CPU\translate('wallet')}}"
                                                href="{{route('admin.customer.wallet.report')}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M21.6389 14.3943H17.5906C16.1042 14.3934 14.8993 13.1894 14.8984 11.703C14.8984 10.2165 16.1042 9.01263 17.5906 9.01172H21.6389" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M18.05 11.6445H17.7383" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.74766 3H16.3911C19.2892 3 21.6388 5.34951 21.6388 8.24766V15.4247C21.6388 18.3229 19.2892 20.6724 16.3911 20.6724H7.74766C4.84951 20.6724 2.5 18.3229 2.5 15.4247V8.24766C2.5 5.34951 4.84951 3 7.74766 3Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M7.03516 7.53906H12.4341" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                 </svg>
                                                <span class="text-truncate">
                                                    {{\App\CPU\translate('wallet')}}
                                                </span>
                                            </a>
                                        </li>
                                        <li
                                            class="d-none nav-item {{Request::is('admin/customer/loyalty/report')?'active':''}}">
                                            <a class="nav-link" title="{{\App\CPU\translate('Loyalty_Points')}}"
                                                href="{{route('admin.customer.loyalty.report')}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M19.0984 4.93909C17.2332 3.05394 14.7383 2.00995 12.0742 2H12.0306C6.53074 2 2.03002 6.42605 2.00016 11.8675C1.98523 14.5988 3.01428 17.1509 4.89819 19.0522C6.77089 20.9423 9.29189 21.99 11.9958 22H12.0356C17.5019 22 21.9753 17.5739 22.0076 12.1325C22.0226 9.40496 20.9898 6.85037 19.0984 4.93909ZM11.9821 20.8079H11.956C7.12304 20.7942 3.20218 16.8224 3.21462 11.9533C3.22706 7.12039 7.18525 3.18957 12.0356 3.18957H12.0481C14.3849 3.1933 16.5824 4.1166 18.2348 5.79021C19.8873 7.46507 20.7957 9.68742 20.7907 12.0479C20.782 16.8771 16.83 20.8079 11.9821 20.8079Z" fill="white"/>
                                                    <path d="M16.8741 9.51283L16.7534 9.49293C16.166 9.39587 15.5588 9.29508 14.9491 9.24531C14.3941 9.19927 14.1266 9.01386 13.9611 8.55844C13.8155 8.15652 13.6164 7.77701 13.4198 7.39998C13.3277 7.22328 13.2319 7.04036 13.1461 6.85994C12.8947 6.33732 12.5189 6.0673 12.0274 6.05859H12.005C11.4986 6.05859 11.1104 6.33359 10.8515 6.87611C10.7321 7.12622 10.6076 7.37384 10.4832 7.62146C10.3053 7.97734 10.1211 8.34441 9.95064 8.71896C9.81626 9.01635 9.6856 9.17189 9.34715 9.19678C8.94897 9.22664 8.55203 9.29134 8.16753 9.35605C7.88756 9.40209 7.59887 9.45062 7.31268 9.48297C7.08621 9.5091 6.39437 9.5875 6.14302 10.3017C5.87549 11.0583 6.41926 11.5573 6.5972 11.7215C6.78509 11.8945 6.968 12.0799 7.15092 12.264C7.40352 12.5191 7.66358 12.7829 7.94604 13.0206C8.29196 13.3105 8.37284 13.5693 8.25588 14.0061C8.15509 14.3819 8.10034 14.7689 8.04683 15.1434C8.01075 15.3985 7.97342 15.6623 7.9224 15.9136C7.81041 16.4636 7.95475 16.9004 8.35293 17.2115C8.56945 17.3807 8.80711 17.4665 9.05971 17.4665C9.28867 17.4665 9.52509 17.3981 9.78515 17.2563C10.034 17.1206 10.2903 16.9875 10.5479 16.8531C10.9287 16.6552 11.3219 16.4499 11.7039 16.2297C11.8159 16.165 11.9092 16.1351 12 16.1351C12.0921 16.1351 12.1929 16.1675 12.3149 16.2372C12.7877 16.5059 13.2755 16.7598 13.7583 17.0124C13.9362 17.1044 14.1129 17.1978 14.2908 17.2911C14.5148 17.4093 14.7351 17.469 14.9454 17.469C15.1805 17.469 15.402 17.3944 15.6061 17.2488C16.0267 16.9477 16.1947 16.5034 16.0926 15.9659C16.0404 15.6872 15.9943 15.3997 15.9495 15.1173C15.8861 14.7116 15.8201 14.2923 15.728 13.8816C15.6509 13.5357 15.7056 13.3491 15.9421 13.14C16.3178 12.809 16.675 12.4519 17.0196 12.106L17.0545 12.0712C17.1914 11.9343 17.3282 11.7974 17.4664 11.6618C17.816 11.3196 17.9666 11.0533 17.9404 10.8281C17.8882 10.0541 17.5398 9.62358 16.8741 9.51283ZM16.5916 10.8306C16.563 10.8555 16.5356 10.8803 16.5107 10.9052L16.3017 11.1118C15.8525 11.556 15.3871 12.0164 14.9167 12.4656C14.5235 12.8402 14.3867 13.2819 14.4986 13.8157C14.5957 14.2798 14.6728 14.7589 14.7463 15.223C14.7848 15.4644 14.8234 15.7058 14.8657 15.9472C14.8732 15.9895 14.8831 16.0331 14.8943 16.0766C14.903 16.114 14.9279 16.211 14.9155 16.2372C14.9055 16.2446 14.9006 16.2471 14.9006 16.2484C14.8719 16.2471 14.7923 16.1973 14.75 16.1712C14.7064 16.1438 14.6629 16.1177 14.6206 16.0965C13.9549 15.7593 13.3576 15.4532 12.769 15.1173C12.509 14.9692 12.2601 14.897 12.0088 14.897C11.7549 14.897 11.5036 14.9704 11.2385 15.1198C10.8229 15.3562 10.3949 15.5777 9.97926 15.7904L9.93447 15.8128C9.71298 15.9273 9.49149 16.0418 9.27124 16.1588C9.19036 16.2023 9.13313 16.2235 9.09455 16.2347C9.14059 15.9634 9.18165 15.7046 9.22272 15.4532C9.31604 14.8796 9.40314 14.3383 9.5114 13.8107C9.61965 13.2819 9.48029 12.8414 9.08584 12.4644C8.56447 11.9642 8.05928 11.4689 7.58394 10.9898C7.54164 10.9463 7.4956 10.909 7.44831 10.8716C7.30148 10.7522 7.30521 10.741 7.31641 10.7124C7.32761 10.6837 7.34379 10.6676 7.5491 10.6576C7.60634 10.6551 7.66482 10.6514 7.72455 10.6427C8.34547 10.5456 9.01118 10.4461 9.68063 10.3689C10.2742 10.3005 10.6823 10.0093 10.9274 9.47675C11.1464 9.00142 11.3866 8.52235 11.6193 8.05698L11.6317 8.03209C11.7238 7.84918 11.8159 7.66501 11.9067 7.48085C11.9179 7.45721 11.9291 7.43357 11.9403 7.40869C11.9677 7.34896 11.9876 7.30914 12.0013 7.29048C12.0212 7.31785 12.0535 7.38629 12.066 7.41491C12.0772 7.43979 12.0884 7.46468 12.0996 7.48708C12.1954 7.6812 12.2925 7.87406 12.3883 8.06693C12.6197 8.52982 12.8599 9.00764 13.0789 9.48422C13.3215 10.0106 13.7309 10.3005 14.3307 10.3714C14.9752 10.4461 15.6559 10.5406 16.4099 10.6589C16.4497 10.6651 16.492 10.6688 16.5344 10.6725C16.568 10.675 16.6687 10.6837 16.6911 10.7086C16.6949 10.7186 16.6949 10.7223 16.6949 10.7223C16.6911 10.741 16.619 10.8057 16.5916 10.8306Z" fill="white"/>
                                                 </svg>
                                                <span class="text-truncate">
                                                    {{\App\CPU\translate('Loyalty_Points')}}
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li
                                    class="navbar-vertical-aside-has-menu {{Request::is('admin/report/seller-report') || Request::is('admin/seller*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:"
                                        title="{{\App\CPU\translate('Sellers')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M5.95006 22.0005C5.76844 21.9324 5.63224 21.7962 5.51873 21.5692C4.83768 20.1844 4.15664 18.8223 3.45289 17.4375L3.40749 17.324C3.08967 16.6883 2.77185 16.0527 2.45403 15.4171L2 14.509V14.2139C2.09081 14.0323 2.24971 13.8961 2.49943 13.7825C2.83995 13.6236 3.18048 13.4647 3.4983 13.2831L3.52099 13.2604C3.81611 13.1015 4.08854 13.0334 4.38365 13.0334C4.61067 13.0334 4.86039 13.0788 5.0874 13.1696C5.1101 13.1696 5.1328 13.1696 5.1555 13.1696C5.22361 13.1696 5.29171 13.1469 5.35982 13.1015C5.54143 12.9653 5.70034 12.8064 5.88195 12.6475C6.19977 12.3751 6.54029 12.0799 6.88082 11.8302C7.51646 11.3989 8.26561 11.1719 9.06016 11.1719C9.96822 11.1719 10.8763 11.467 11.5573 12.0345C12.2611 12.6021 12.2838 12.6021 13.1691 12.6021H14.5766C14.849 12.6021 15.1442 12.6021 15.4166 12.6021C16.3019 12.6021 16.8695 12.9653 17.1873 13.7371C17.1873 13.7598 17.21 13.7598 17.21 13.7825L17.2554 13.8052H17.3235L17.3689 13.7598C17.3916 13.7144 17.4143 13.6917 17.4597 13.6463L17.8456 13.2377C18.2316 12.8291 18.6175 12.4205 19.0034 12.0118C19.3666 11.6259 19.8434 11.4216 20.3201 11.4216C20.6379 11.4216 20.9557 11.5124 21.2281 11.694C21.6595 11.9664 21.9319 12.3751 22.0227 12.8291C22.1135 13.2831 22 13.7371 21.7049 14.1458C20.6833 15.5533 19.7071 16.8472 18.7537 18.0731C17.9818 19.072 16.8922 19.5714 15.5301 19.5714C14.622 19.5714 13.6913 19.5714 12.7832 19.5714H8.69693L8.65153 19.5941C8.62883 19.6849 8.60613 19.753 8.60613 19.8438C8.56073 20.0255 8.53802 20.1844 8.46992 20.3433C8.33371 20.7292 8.03859 21.0243 7.58456 21.2513C7.26674 21.4102 6.97162 21.5691 6.6538 21.7281L6.26787 21.9324L5.95006 22.0005ZM4.38365 14.1685C4.31555 14.1685 4.24745 14.1912 4.15664 14.2139C3.95233 14.3047 3.77071 14.3955 3.5664 14.4863C3.47559 14.5317 3.38479 14.5771 3.29398 14.6225L3.27128 14.6906L6.26787 20.7065L6.33598 20.7292C6.33598 20.7292 6.83541 20.4795 6.99432 20.4114C7.47106 20.1617 7.58456 19.8665 7.33485 19.3671L5.72304 16.1208C5.47332 15.5987 5.22361 15.0992 4.95119 14.5771C4.81498 14.3047 4.61067 14.1685 4.38365 14.1685ZM9.06016 12.3297C8.58343 12.3297 8.1294 12.4659 7.65267 12.7156C7.22134 12.9653 6.83542 13.2831 6.44949 13.6009C6.26788 13.7371 6.10896 13.8961 5.92735 14.0323L5.90465 14.0777L5.95006 14.1912C5.97276 14.2366 5.99546 14.282 5.99546 14.3047L6.49489 15.2808C7.01702 16.3024 7.51646 17.3467 8.03859 18.3682C8.1294 18.5499 8.22021 18.5726 8.37912 18.5726C9.76391 18.5726 11.1714 18.5726 12.5562 18.5726C13.5096 18.5726 14.4631 18.5726 15.3939 18.5726C16.4835 18.5726 17.3689 18.1412 18.0045 17.2559C18.3905 16.7337 18.7991 16.2116 19.185 15.6668L20.0023 14.5544C20.252 14.2366 20.479 13.9188 20.706 13.6009C20.8422 13.3966 20.8876 13.1696 20.7968 12.9653C20.7287 12.761 20.5471 12.6248 20.3201 12.5794C20.2747 12.5794 20.2293 12.5794 20.2066 12.5794C20.025 12.5794 19.8661 12.6702 19.6844 12.8518C19.3212 13.2377 18.958 13.6236 18.5948 14.0096C18.1407 14.4863 17.6867 14.9857 17.2327 15.4852C16.8468 15.9165 16.37 16.1208 15.8025 16.1208H15.8252C15.6663 16.1208 15.5074 16.1208 15.3712 16.1208H13.4188C13.1464 16.1208 12.874 16.1208 12.6016 16.1208C12.3973 16.1208 12.2384 16.03 12.1249 15.8938C12.0341 15.7576 11.9886 15.5987 12.034 15.4171C12.1022 15.1673 12.3065 15.0084 12.6243 15.0084H14.0091C14.5085 15.0084 14.9852 15.0084 15.4847 15.0084C15.7344 15.0084 15.8933 14.9403 16.0295 14.8041C16.2111 14.5998 16.2565 14.3728 16.1657 14.1458C16.0522 13.8734 15.8479 13.7371 15.5528 13.7371C15.3258 13.7371 15.076 13.7371 14.849 13.7371C14.5766 13.7371 14.3042 13.7371 14.0318 13.7371C13.6459 13.7371 13.328 13.7371 13.0329 13.7598C12.9648 13.7598 12.8967 13.7598 12.8286 13.7598C12.1022 13.7598 11.4892 13.5328 10.9898 13.0334C10.9671 13.0107 10.9444 12.988 10.9217 12.988C10.3087 12.5567 9.6731 12.3297 9.06016 12.3297Z"
                                                fill="white" />
                                            <path
                                                d="M12.6708 10.2179C11.6492 10.2179 10.6504 10.2179 9.62882 10.2179C9.3337 10.2179 9.10668 10.0136 9.08397 9.7185C9.06127 9.03746 9.08397 8.1748 9.538 7.38025C9.85582 6.83542 10.2645 6.40409 10.7866 6.08627C10.8093 6.06356 10.832 6.06357 10.8547 6.04087L10.9001 6.01816V5.95006C9.90123 4.83768 10.1736 3.4756 10.9455 2.68104C11.3995 2.22701 11.9898 2 12.6027 2C13.1021 2 13.5789 2.15891 13.9875 2.47673C14.5323 2.90806 14.8502 3.4756 14.8956 4.15664C14.941 4.81498 14.7593 5.40523 14.2826 5.92736V5.99546C14.3734 6.06357 14.4642 6.15437 14.555 6.22248C14.7593 6.38139 14.941 6.5176 15.0999 6.69921C15.7355 7.33485 16.0533 8.1521 16.076 9.10557C16.076 9.28718 16.076 9.49149 16.076 9.6731C16.076 10.0136 15.849 10.2406 15.5085 10.2406C14.5777 10.2179 13.6243 10.2179 12.6708 10.2179ZM12.6027 6.6538C12.126 6.6538 11.6492 6.81272 11.1952 7.15324C10.5596 7.62997 10.2191 8.26561 10.1964 9.08286L10.2418 9.12826H14.9864L15.0318 9.08286C14.9637 8.084 14.5323 7.35755 13.7378 6.92622C13.3519 6.74461 12.9659 6.6538 12.6027 6.6538ZM12.6254 3.08967C12.3076 3.08967 11.9898 3.22588 11.74 3.4756C11.4903 3.72531 11.3541 4.04313 11.3541 4.36095C11.3541 5.01929 11.9217 5.56414 12.6027 5.58684C13.2837 5.58684 13.8286 5.042 13.8286 4.38365C13.8286 4.04313 13.6924 3.72531 13.4654 3.4756C13.2383 3.22588 12.9205 3.08967 12.6254 3.08967Z"
                                                fill="white" />
                                        </svg>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('Sellers')}}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{Request::is('admin/report/seller-report') || Request::is('admin/seller*')?'block':'none'}}">
                                        <li
                                            class="d-none nav-item {{Request::is('admin/sellers/seller-add')?'active':''}}">
                                            <a class="nav-link" title="{{\App\CPU\translate('Add_New_Seller')}}"
                                                href="{{route('admin.sellers.seller-add')}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path d="M12 8.32812V15.6545" stroke="white" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M15.6654 11.9902H8.33203" stroke="white" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M16.6857 2H7.31429C4.04762 2 2 4.31208 2 7.58516V16.4148C2 19.6879 4.0381 22 7.31429 22H16.6857C19.9619 22 22 19.6879 22 16.4148V7.58516C22 4.31208 19.9619 2 16.6857 2Z"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                <span class="text-truncate">
                                                    {{\App\CPU\translate('Add_New_Seller')}}
                                                </span>
                                            </a>
                                        </li>
                                        <li class="nav-item {{Request::is('admin/sellers/seller-list')?'active':''}}">
                                            <a class="nav-link" title="{{\App\CPU\translate('Seller_List')}}"
                                                href="{{route('admin.sellers.seller-list')}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path d="M10.332 16.5938H4.03125" stroke="white" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M13.1406 6.90039H19.4413" stroke="white" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M8.72629 6.84625C8.72629 5.5506 7.66813 4.5 6.36314 4.5C5.05816 4.5 4 5.5506 4 6.84625C4 8.14191 5.05816 9.19251 6.36314 9.19251C7.66813 9.19251 8.72629 8.14191 8.72629 6.84625Z"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M19.9997 16.5533C19.9997 15.2576 18.9424 14.207 17.6374 14.207C16.3316 14.207 15.2734 15.2576 15.2734 16.5533C15.2734 17.8489 16.3316 18.8995 17.6374 18.8995C18.9424 18.8995 19.9997 17.8489 19.9997 16.5533Z"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                <span class="text-truncate">
                                                    {{\App\CPU\translate('Seller_List')}}
                                                </span>
                                            </a>
                                        </li>
                                        <li class="nav-item {{Request::is('admin/sellers/withdraw_list')?'active':''}}">
                                            <a class="nav-link " href="{{route('admin.sellers.withdraw_list')}}"
                                                title="{{\App\CPU\translate('withdraws')}}">
                                             
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M8.37526 21.1404C6.82271 21.1404 5.73793 20.0521 5.73793 18.4937V11.7476C5.73793 11.7476 5.58396 11.7112 5.5299 11.6982C5.41825 11.6724 5.31012 11.6465 5.202 11.6171C3.19227 11.0636 1.87948 9.17022 2.00876 7.01123C2.12394 5.08025 3.74818 3.342 5.70502 3.05406C5.92832 3.02115 6.15985 3.0047 6.39608 3.0047L7.30105 3.00352C9.23909 3.00235 11.1783 3 13.1163 3C14.6125 3 16.1074 3.00117 17.6036 3.00352C19.8542 3.00705 21.777 4.74294 21.9803 6.95364C22.1907 9.24191 20.6969 11.2399 18.4263 11.7029L18.2558 11.7382V13.809C18.2558 15.3722 18.2558 16.9353 18.2558 18.4996C18.2558 20.0533 17.1687 21.1393 15.6115 21.1393H11.9928L8.37526 21.1404ZM7.02486 18.5066C7.02486 19.3505 7.5314 19.8535 8.37995 19.8535H15.6162C16.4635 19.8535 16.9701 19.3493 16.9701 18.5055V8.0443H7.02486V18.5066ZM11.7636 4.28458C9.77149 4.28458 7.99917 4.2881 6.3432 4.29633C5.13383 4.30221 4.19713 4.88044 3.63417 5.96875C3.08179 7.03708 3.15348 8.14302 3.8422 9.16434C4.26412 9.78959 4.87645 10.208 5.66388 10.4066L5.73675 10.4254V7.54598C5.73675 6.96892 5.96476 6.74444 6.54887 6.74444H17.4508C18.029 6.74444 18.2547 6.97127 18.2547 7.55538V10.4195C18.2547 10.4195 18.3839 10.389 18.4074 10.3843C18.4545 10.3737 18.4921 10.3655 18.5285 10.3537C19.9788 9.90947 20.9061 8.45565 20.684 6.97245C20.4536 5.42343 19.1808 4.2975 17.66 4.29398C15.6973 4.28928 13.7298 4.28458 11.7636 4.28458Z" fill="white"/>
                                                    <path d="M11.9947 17.0122C11.5799 17.0122 11.1862 16.843 10.8559 16.5221C10.4775 16.1554 10.1014 15.7735 9.73821 15.4056L9.70882 15.3762C9.56779 15.2329 9.4867 15.0589 9.48082 14.8861C9.47494 14.7157 9.54311 14.5512 9.67239 14.4243C9.7958 14.3032 9.94623 14.2386 10.1096 14.2386C10.2859 14.2386 10.4634 14.3173 10.6091 14.4595C10.7478 14.5959 10.8841 14.7404 11.0169 14.8803C11.0769 14.9437 11.138 15.0084 11.1979 15.0718L11.2296 15.1047L11.353 15.0331V14.3784C11.353 13.343 11.353 12.3076 11.3542 11.271V11.2369C11.3542 11.157 11.3542 11.0747 11.3695 11.0007C11.4341 10.701 11.6892 10.5 12.003 10.5C12.0253 10.5 12.0476 10.5012 12.0711 10.5035C12.3932 10.5329 12.6364 10.8103 12.6388 11.1487C12.6423 11.9162 12.6411 12.6837 12.6411 13.4511V15.2211L12.9561 14.8967C13.0983 14.7498 13.2276 14.6158 13.3581 14.4854C13.5179 14.3267 13.7071 14.2386 13.8905 14.2386C14.0515 14.2386 14.2007 14.3032 14.3241 14.4266C14.5945 14.6981 14.571 15.0871 14.2654 15.3962C13.8916 15.7735 13.525 16.1437 13.1489 16.5116C12.8174 16.8348 12.4084 17.0122 11.9947 17.0122Z" fill="white"/>
                                                 </svg>
                                                <span class="text-truncate">{{\App\CPU\translate('withdraws')}}</span>
                                            </a>
                                        </li>

                                        <li class="nav-item {{Request::is('admin/report/seller-report')?'active':''}}">
                                            <a class="nav-link" href="{{route('admin.report.seller-report')}}"
                                                title="{{\App\CPU\translate('seller')}} {{\App\CPU\translate('sales')}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M15.7161 16.2227H8.49609" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M15.7161 12.0352H8.49609" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M11.2511 7.85938H8.49609" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.908 2.75C15.908 2.75 8.231 2.754 8.219 2.754C5.459 2.771 3.75 4.587 3.75 7.357V16.553C3.75 19.337 5.472 21.16 8.256 21.16C8.256 21.16 15.932 21.157 15.945 21.157C18.705 21.14 20.415 19.323 20.415 16.553V7.357C20.415 4.573 18.692 2.75 15.908 2.75Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                 </svg>
                                                <span class="text-truncate text-capitalize">
                                                    {{\App\CPU\translate('seller')}} {{\App\CPU\translate('report')}}
                                                </span>
                                            </a>
                                        </li>

                                        <li
                                            class="d-none nav-item {{(Request::is('admin/sellers/withdraw-method/list') || Request::is('admin/sellers/withdraw-method/*'))?'active':''}}">
                                            <a class="nav-link " href="{{route('admin.sellers.withdraw-method.list')}}"
                                                title="{{\App\CPU\translate('Withdrawal_Methods')}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                         <path d="M12.0052 5.80078C14.2867 5.80078 16.5682 5.80078 18.8497 5.80078C19.4081 5.80078 19.6784 6.07105 19.6784 6.62805C19.6784 10.8865 19.6784 15.145 19.6784 19.4034C19.6784 20.4612 18.991 21.1499 17.9333 21.1499C13.9808 21.1512 10.0283 21.1512 6.07574 21.1499C5.01936 21.1499 4.33203 20.4598 4.33203 19.402C4.33203 15.1436 4.33203 10.8851 4.33203 6.62668C4.33203 6.06968 4.6023 5.80078 5.16204 5.80078C7.44218 5.80078 9.72369 5.80078 12.0052 5.80078ZM5.73276 7.20563C5.73002 7.26737 5.72728 7.30304 5.72728 7.34008C5.72728 11.3653 5.72728 15.3919 5.72728 19.4171C5.72728 19.6956 5.7945 19.7546 6.10455 19.7546C10.0351 19.7546 13.967 19.7546 17.8976 19.7546C18.2351 19.7546 18.2845 19.7039 18.2845 19.3609C18.2845 15.3864 18.2845 11.4106 18.2845 7.43611C18.2845 7.35929 18.2845 7.28246 18.2845 7.20563C14.085 7.20563 9.91713 7.20563 5.73276 7.20563Z" fill="white"/>
                                                         <path d="M12.0112 3.0097C14.7797 3.0097 17.5469 3.00832 20.3154 3.01107C21.1454 3.01107 21.7985 3.52829 21.9658 4.31577C21.9946 4.44885 21.9988 4.59015 21.9988 4.72735C22.0001 6.83462 22.0015 8.9419 21.996 11.0492C21.996 11.196 21.9645 11.3551 21.9014 11.4868C21.7806 11.7392 21.4912 11.8778 21.2332 11.8421C20.941 11.8024 20.6996 11.5952 20.6365 11.3126C20.6118 11.2001 20.6076 11.0821 20.6063 10.9669C20.6049 8.91034 20.6049 6.85383 20.6049 4.79731C20.6049 4.44885 20.561 4.40494 20.2208 4.40494C14.7427 4.40494 9.26458 4.40494 3.78649 4.40494C3.44625 4.40494 3.40509 4.44747 3.40372 4.80006C3.40372 6.87852 3.40372 8.95561 3.40372 11.0341C3.40372 11.4827 3.19656 11.7626 2.82065 11.8339C2.43377 11.908 2.05237 11.6226 2.01533 11.2289C2.0071 11.1493 2.00985 11.0697 2.00985 10.9888C2.00985 8.92543 2.03317 6.86069 2.00024 4.79869C1.98378 3.70801 2.80556 2.99049 3.79609 3.0001C6.53308 3.02753 9.27144 3.0097 12.0112 3.0097Z" fill="white"/>
                                                         <path d="M11.3042 15.0347C11.3042 13.4954 11.3042 12.0055 11.3042 10.5156C11.3042 10.414 11.3014 10.3125 11.3056 10.211C11.3207 9.81861 11.6252 9.51816 12.0039 9.51954C12.3811 9.52091 12.6926 9.8241 12.6967 10.2165C12.7036 10.9655 12.6994 11.7132 12.6994 12.4623C12.6994 13.3047 12.6994 14.147 12.6994 15.0251C12.7694 14.9647 12.8188 14.9277 12.8613 14.8851C13.3141 14.4338 13.7654 13.9797 14.2182 13.5283C14.5515 13.1963 14.9645 13.1702 15.2622 13.4597C15.5613 13.7519 15.5366 14.1827 15.1991 14.5216C14.5419 15.1801 13.8834 15.8372 13.2263 16.4944C13.1645 16.5561 13.1028 16.6192 13.0328 16.67C12.8394 16.8058 12.6706 16.9512 12.5307 17.157C12.2481 17.5714 11.6444 17.4959 11.3879 17.0541C11.3303 16.954 11.2342 16.8744 11.1464 16.7948C11.049 16.7084 10.9324 16.6426 10.8405 16.5506C10.1435 15.8592 9.4521 15.165 8.7579 14.4708C8.55486 14.2678 8.48763 14.0236 8.56858 13.7505C8.6454 13.4926 8.82787 13.3335 9.08991 13.2759C9.35469 13.2182 9.57282 13.3102 9.76078 13.4981C10.2217 13.9618 10.6855 14.4228 11.1492 14.8851C11.1862 14.9263 11.2287 14.9633 11.3042 15.0347Z" fill="white"/>
                                                 </svg>
                                                <span
                                                    class="text-truncate">{{\App\CPU\translate('Withdrawal_Methods')}}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li
                                    class="d-none navbar-vertical-aside-has-menu {{Request::is('admin/customer/subscriber-list')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.customer.subscriber-list')}}"
                                        title="{{\App\CPU\translate('subscribers')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{\App\CPU\translate('subscribers')}} </span>
                                    </a>
                                </li>

                                <li
                                    class="navbar-vertical-aside-has-menu  {{Request::is('admin/delivery-man*')?'active':''}}  ">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link its-drop " href="javascript:"
                                        title="{{\App\CPU\translate('delivery-man')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M19.5616 20.7743C19.5421 20.7354 19.5227 20.716 19.5227 20.6965C19.2698 20.0934 18.9779 19.5876 18.6083 19.1595C17.8495 18.2646 16.8962 17.7393 15.7678 17.6031C15.5538 17.5837 15.3398 17.5642 15.1258 17.5642C15.0091 17.5642 14.8729 17.5642 14.7561 17.5448C14.6589 17.5448 14.5811 17.5058 14.5811 17.3891C14.5421 17.0973 14.4838 16.8054 14.4449 16.5914C14.4254 16.5136 14.4449 16.4747 14.5227 16.4358C15.1453 16.0856 15.69 15.5992 16.118 14.9572L16.3709 14.5486C16.7211 13.9261 16.9351 13.2841 17.0519 12.6226C17.0713 12.5448 17.0908 12.4669 17.1686 12.4086C17.5577 12.0389 17.8301 11.6304 18.0052 11.1634C18.2581 10.4241 18.2386 9.68483 17.9274 8.88717L17.9079 8.84825C17.8884 8.78989 17.869 8.73152 17.869 8.67315C17.9468 7.95331 17.9468 7.33074 17.8495 6.72762C17.655 5.36576 17.0519 4.27626 16.0596 3.45914C14.9312 2.52529 13.5305 2.03891 11.8962 2C11.8379 2 11.799 2 11.7406 2C10.69 2 9.61996 2.27238 8.58883 2.81712C7.01296 3.6537 6.09856 4.9572 5.82619 6.74709C5.74836 7.29183 5.72891 7.87549 5.78728 8.49806C5.80673 8.67316 5.78728 8.8288 5.72891 8.98444C5.59272 9.31518 5.53436 9.66538 5.5149 10.0545C5.47599 10.9689 5.80673 11.7665 6.50712 12.428C6.56549 12.4864 6.6044 12.5448 6.62385 12.6226C6.74058 13.323 6.97405 13.9844 7.30479 14.5486L7.53825 14.9183C7.96626 15.5603 8.51101 16.0662 9.15304 16.4358C9.23086 16.4747 9.25031 16.5136 9.23086 16.6109C9.17249 16.8833 9.11412 17.1751 9.07521 17.4669C9.05576 17.5447 9.0363 17.5837 8.93903 17.5837C8.86121 17.5837 8.80284 17.5837 8.72502 17.5837C8.60829 17.5837 8.4721 17.5837 8.35537 17.5837C7.30479 17.6031 6.39039 17.9533 5.63163 18.5953C5.02852 19.1012 4.5616 19.7627 4.19195 20.6187C3.97794 21.144 3.93903 21.572 4.09467 21.8054C4.19195 21.9416 4.32813 22 4.54214 22C4.83397 22 6.40984 22 8.51101 22H8.88066C9.07521 22 9.28922 22 9.46432 22H9.50323H11.3904H12.013H19.3281C19.5227 22 19.6589 21.9416 19.7172 21.8249C19.834 21.6109 19.7756 21.2412 19.5616 20.7743ZM11.4877 3.18677C11.5071 3.18677 11.5266 3.18677 11.5655 3.16732H11.6044H11.9935C12.7912 3.24514 13.4137 3.38133 13.9585 3.59533C14.9312 3.98444 15.6316 4.50973 16.0791 5.24903C16.4488 5.83269 16.6433 6.53308 16.7017 7.36966L16.3515 7.19456L16.0013 7.03892C15.3009 6.74709 14.5227 6.53308 13.6277 6.39689C13.0052 6.31907 12.4021 6.26071 11.7989 6.26071C10.8456 6.26071 9.91179 6.37744 8.99739 6.59145C8.49156 6.70818 8.04409 6.86382 7.65498 7.03892C7.51879 7.07783 7.38261 7.15565 7.22696 7.23347L6.97405 7.36966C6.97405 7.13619 7.01296 6.92219 7.05187 6.68872C7.32424 5.21012 8.21918 4.17899 9.71724 3.59533C10.262 3.38133 10.8651 3.24514 11.4877 3.18677ZM8.54992 7.95332C9.54214 7.62258 10.5927 7.44747 11.7795 7.44747C12.0713 7.44747 12.3631 7.44748 12.655 7.46693C13.6472 7.5253 14.5227 7.71985 15.3398 8.03113C15.7484 8.18677 16.0791 8.36187 16.3904 8.57587C16.3126 8.6537 16.2347 8.71207 16.1569 8.78989C16.0013 8.94553 15.8262 9.10118 15.6316 9.21791C15.4371 9.33464 15.2036 9.393 14.9702 9.393C14.8729 9.393 14.7561 9.37354 14.6394 9.35408C13.6277 9.14008 12.6939 9.02335 11.8379 9.02335C11.7795 9.02335 11.7406 9.02335 11.6822 9.02335C10.8456 9.04281 9.97016 9.14008 9.0363 9.33463C8.91957 9.35409 8.80284 9.37355 8.70556 9.37355C8.35537 9.37355 8.04409 9.23736 7.77171 8.98444C7.7328 8.94553 7.69389 8.90661 7.63552 8.8677C7.5577 8.78988 7.46043 8.69261 7.36315 8.61479C7.32424 8.57588 7.30479 8.55643 7.30479 8.53697C7.30479 8.51752 7.3437 8.4786 7.38261 8.45915C7.7328 8.28405 8.12191 8.0895 8.54992 7.95332ZM6.74058 9.72374C6.79895 9.74319 6.83786 9.76265 6.85731 9.80156L6.87677 9.82101C7.42152 10.3463 8.00517 10.5992 8.68611 10.5992C8.90012 10.5992 9.13358 10.5798 9.36704 10.5214C10.1842 10.3463 11.0013 10.249 11.8184 10.249C12.6161 10.249 13.4332 10.3463 14.2503 10.5214C14.4838 10.5798 14.7172 10.5992 14.9507 10.5992C15.5344 10.5992 16.0596 10.4047 16.546 10.035C16.6239 9.97665 16.7017 9.89884 16.7795 9.82101C16.8184 9.7821 16.8573 9.7432 16.8962 9.70429C16.9935 10.1518 16.974 10.5409 16.7795 10.9494C16.6433 11.2412 16.4877 11.4553 16.2931 11.5914C16.0013 11.8054 15.904 12.0973 15.8456 12.4475C15.69 13.4202 15.3009 14.2374 14.6783 14.8599C14.0947 15.4436 13.3359 15.7938 12.4215 15.8716C12.1686 15.8911 11.9546 15.9105 11.7406 15.9105C11.2347 15.9105 10.7873 15.8521 10.3593 15.716C9.36704 15.4047 8.62774 14.7237 8.14136 13.7121C7.92736 13.2451 7.79117 12.7393 7.7328 12.2724C7.71335 12.0389 7.61607 11.8444 7.42152 11.6887C6.8184 11.144 6.58494 10.5019 6.74058 9.72374ZM11.76 17.0778C12.013 17.0778 12.2659 17.0584 12.5383 17.0389C12.7717 17.0195 12.9857 16.9805 13.1803 16.9222C13.1997 16.9222 13.2192 16.9222 13.2192 16.9222C13.2581 16.9222 13.2775 16.9222 13.297 17.0195C13.3165 17.1167 13.3359 17.214 13.3359 17.2918C13.3748 17.5253 13.3943 17.7393 13.4721 17.9533C13.4916 18.0117 13.4721 18.0506 13.4137 18.0895C13.3943 18.109 13.3748 18.1284 13.3748 18.1284L13.3554 18.1479V18.1673L13.1025 18.3813L13.0246 18.4397L12.8106 18.5759H12.7912H12.7523L12.7328 18.5953L12.4993 18.6926C12.3242 18.7704 12.1491 18.8093 11.8962 18.8093C11.7795 18.8093 11.6822 18.8093 11.5655 18.7899H11.4098C11.332 18.7899 11.2542 18.7704 11.2153 18.7315L10.9235 18.6148L10.8456 18.5953L10.7873 18.5564C10.7289 18.5175 10.69 18.4981 10.6122 18.4592L10.5344 18.4202L10.3593 18.2646C10.1647 18.1284 10.1258 17.9728 10.2036 17.7393C10.262 17.5642 10.2814 17.3697 10.3009 17.1946C10.3009 17.1362 10.3204 17.0973 10.3204 17.0389C10.3398 16.9611 10.3593 16.9611 10.3982 16.9611C10.4176 16.9611 10.4371 16.9611 10.4565 16.9611C10.8846 17.0389 11.2931 17.0778 11.76 17.0778ZM11.8184 19.9572C11.9157 19.9572 12.0324 19.9572 12.1297 19.9377C12.9857 19.8794 13.7056 19.5097 14.3087 18.8677C14.406 18.7704 14.4838 18.7315 14.62 18.7315H14.8145C15.2231 18.7315 15.6705 18.7315 16.0791 18.8483C16.8768 19.0623 17.5188 19.5486 18.0052 20.3074C18.0441 20.3852 18.1025 20.463 18.1414 20.5409C18.1608 20.5798 18.1997 20.6381 18.2192 20.677C18.2386 20.6965 18.2581 20.7549 18.2386 20.7743C18.2386 20.7743 18.2192 20.7938 18.1803 20.7938C18.1803 20.7938 18.1608 20.7938 18.1414 20.7938C18.1025 20.7938 18.083 20.7938 18.0441 20.7938H17.8884H5.35926C5.57327 20.3463 5.84564 19.9767 6.15692 19.6459C6.74058 19.0623 7.47988 18.751 8.33592 18.7315C8.4721 18.7315 8.60829 18.7315 8.76393 18.7315C8.88066 18.7315 9.01685 18.7315 9.13358 18.7315C9.19194 18.7315 9.23086 18.751 9.25031 18.8093C9.97016 19.5681 10.8262 19.9572 11.8184 19.9572Z"
                                                fill="white" />
                                        </svg>

                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('delivery-man')}}
                                        </span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{Request::is('admin/delivery-man*')?'block':'none'}}">
                                        <li class="nav-item {{Request::is('admin/delivery-man/add')?'active':''}}">
                                            <a class="nav-link " href="{{route('admin.delivery-man.add')}}"
                                                title="{{\App\CPU\translate('add_new')}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path d="M12 8.32812V15.6545" stroke="white" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M15.6654 11.9902H8.33203" stroke="white" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M16.6857 2H7.31429C4.04762 2 2 4.31208 2 7.58516V16.4148C2 19.6879 4.0381 22 7.31429 22H16.6857C19.9619 22 22 19.6879 22 16.4148V7.58516C22 4.31208 19.9619 2 16.6857 2Z"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                <span class="text-truncate">{{\App\CPU\translate('add_new')}}</span>
                                            </a>
                                        </li>
                                        <li
                                            class="nav-item {{Request::is('admin/delivery-man/list') || Request::is('admin/delivery-man/earning-statement*') || Request::is('admin/delivery-man/order-history-log*') || Request::is('admin/delivery-man/order-wise-earning*')?'active':''}}">
                                            <a class="nav-link" href="{{route('admin.delivery-man.list')}}"
                                                title="{{\App\CPU\translate('List')}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path d="M10.332 16.5938H4.03125" stroke="white" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M13.1406 6.90039H19.4413" stroke="white" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M8.72629 6.84625C8.72629 5.5506 7.66813 4.5 6.36314 4.5C5.05816 4.5 4 5.5506 4 6.84625C4 8.14191 5.05816 9.19251 6.36314 9.19251C7.66813 9.19251 8.72629 8.14191 8.72629 6.84625Z"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M19.9997 16.5533C19.9997 15.2576 18.9424 14.207 17.6374 14.207C16.3316 14.207 15.2734 15.2576 15.2734 16.5533C15.2734 17.8489 16.3316 18.8995 17.6374 18.8995C18.9424 18.8995 19.9997 17.8489 19.9997 16.5533Z"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                <span class="text-truncate">{{\App\CPU\translate('List')}}</span>
                                            </a>
                                        </li>
                                        <li class="d-none nav-item {{Request::is('admin/delivery-man/chat')?'active':''}}">
                                            <a class="nav-link" href="{{route('admin.delivery-man.chat')}}"
                                                title="{{\App\CPU\translate('Chat')}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M21.6407 10.1437C21.3669 8.86624 20.3176 8.0222 19.0174 8.0222C17.3521 8.0222 15.6869 8.0222 14.0216 8.0222C13.862 8.0222 13.7935 7.99941 13.7935 7.81692C13.7935 6.67635 13.7935 5.51294 13.7935 4.37237C13.7935 2.98087 12.8126 2 11.4211 2C9.04874 2 6.67635 2 4.32677 2C3.00371 2 2 3.00369 2 4.32676C2 5.90075 2 7.49756 2 9.07155C2 9.25405 2.02281 9.43651 2.06844 9.61901C2.31936 10.6683 3.1862 11.3755 4.25834 11.3983C4.3952 11.3983 4.44083 11.4439 4.41802 11.5808C4.41802 11.9458 4.41802 12.3336 4.41802 12.6986C4.41802 12.8126 4.41802 12.9267 4.46364 13.0179C4.69175 13.8163 5.64984 14.0672 6.24293 13.497C6.74479 12.9951 7.22383 12.4933 7.72568 11.9914C7.74849 12.0142 7.7713 12.0142 7.7713 12.037C7.7713 12.1055 7.7713 12.1739 7.7713 12.2423C7.7713 13.6338 7.7713 15.0025 7.7713 16.394C7.7713 16.6678 7.79411 16.9187 7.86255 17.1696C8.18191 18.333 9.20842 19.1086 10.4402 19.1086C11.6493 19.1086 12.8583 19.1086 14.0445 19.1086C14.1813 19.1086 14.2726 19.1542 14.3638 19.2455C15.1622 20.0439 15.9378 20.8195 16.7362 21.6179C17.1012 21.9828 17.5574 22.0969 18.0593 21.9144C18.5155 21.7319 18.7892 21.3213 18.7892 20.7738C18.7892 20.272 18.7892 19.7929 18.7892 19.2911C18.7892 19.1314 18.8349 19.1086 18.9717 19.1086C20.5457 19.0858 21.6863 17.9452 21.6863 16.3712C21.6863 14.5235 21.6863 12.6529 21.6863 10.8052C21.6863 10.5543 21.6863 10.349 21.6407 10.1437ZM7.79411 10.3262C7.7713 10.5315 7.70287 10.6912 7.54319 10.8508C6.90447 11.4667 6.28856 12.1055 5.64983 12.7214C5.5814 12.7898 5.53578 12.8811 5.42172 12.8354C5.33047 12.7898 5.35329 12.6986 5.35329 12.6073C5.35329 12.0827 5.35329 11.558 5.35329 11.0105C5.35329 10.5999 5.17079 10.4402 4.783 10.4402C4.53207 10.4402 4.30396 10.463 4.05303 10.4174C3.41431 10.3034 2.93527 9.7787 2.93527 9.11717C2.93527 7.49755 2.93527 5.87793 2.93527 4.25831C2.93527 3.52835 3.52837 2.95807 4.25834 2.95807C5.46734 2.95807 6.69916 2.95807 7.90817 2.95807C9.07155 2.95807 10.2349 2.95807 11.3983 2.95807C12.3108 2.95807 12.8583 3.50555 12.8583 4.41801C12.8583 5.55858 12.8583 6.69915 12.8583 7.86253C12.8583 8.04503 12.7898 8.06784 12.6301 8.06784C11.8089 8.06784 10.9877 8.04503 10.1665 8.09065C8.9575 8.11346 7.93098 9.11718 7.79411 10.3262ZM20.751 16.3712C20.751 17.3977 20.0211 18.1277 18.9945 18.1505C18.7892 18.1505 18.5839 18.1505 18.3786 18.1505C18.0365 18.1505 17.854 18.333 17.854 18.6752C17.854 19.3595 17.854 20.0211 17.854 20.7054C17.854 20.7966 17.854 20.9107 17.7627 20.9791C17.6259 21.0932 17.489 21.0247 17.3749 20.9107C16.7362 20.272 16.0747 19.6104 15.436 18.9717C15.2307 18.7892 15.0482 18.5839 14.8429 18.3786C14.6832 18.1961 14.5007 18.1277 14.2726 18.1277C12.9951 18.1277 11.7405 18.1277 10.4631 18.1277C9.59622 18.1277 8.93469 17.6259 8.72938 16.8275C8.68376 16.6678 8.68376 16.5081 8.68376 16.3484C8.68376 14.4778 8.68376 12.6073 8.68376 10.7368C8.68376 9.68745 9.41373 8.9803 10.4402 8.9803C11.8546 8.9803 13.2689 8.9803 14.706 8.9803C16.1203 8.9803 17.5346 8.9803 18.9717 8.9803C19.8158 8.9803 20.4773 9.48215 20.6826 10.2577C20.7282 10.4174 20.7282 10.5771 20.7282 10.714C20.751 12.6073 20.751 14.4779 20.751 16.3712Z"
                                                        fill="white" />
                                                    <path
                                                        d="M14.043 13.5418C14.043 13.154 14.3623 12.8574 14.7501 12.8574C15.1379 12.8574 15.4345 13.154 15.4345 13.5418C15.4345 13.9296 15.1379 14.2489 14.7501 14.2489C14.3395 14.2489 14.043 13.9296 14.043 13.5418Z"
                                                        fill="white" />
                                                    <path
                                                        d="M11.125 13.5652C11.125 13.1774 11.4216 12.8809 11.8322 12.8809C12.22 12.8809 12.5393 13.2002 12.5165 13.588C12.5165 13.953 12.1971 14.2724 11.8322 14.2724C11.4444 14.2495 11.125 13.953 11.125 13.5652Z"
                                                        fill="white" />
                                                    <path
                                                        d="M18.3329 13.5646C18.3329 13.9524 18.0135 14.2489 17.6258 14.2489C17.238 14.2489 16.9414 13.9296 16.9414 13.5418C16.9414 13.1768 17.2608 12.8574 17.6486 12.8574C18.0364 12.8574 18.3329 13.1768 18.3329 13.5646Z"
                                                        fill="white" />
                                                </svg>
                                                <span class="text-truncate">{{\App\CPU\translate('chat')}}</span>
                                            </a>
                                        </li>
                                        <li
                                            class="d-none nav-item {{Request::is('admin/delivery-man/withdraw-list') || Request::is('admin/delivery-man/withdraw-view*')?'active':''}}">
                                            <a class="nav-link " href="{{route('admin.delivery-man.withdraw-list')}}"
                                                title="{{\App\CPU\translate('withdraws')}}">
                                                <!-- <span class="tio-circle nav-indicator-icon"></span> -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M8.37526 21.1404C6.82271 21.1404 5.73793 20.0521 5.73793 18.4937V11.7476C5.73793 11.7476 5.58396 11.7112 5.5299 11.6982C5.41825 11.6724 5.31012 11.6465 5.202 11.6171C3.19227 11.0636 1.87948 9.17022 2.00876 7.01123C2.12394 5.08025 3.74818 3.342 5.70502 3.05406C5.92832 3.02115 6.15985 3.0047 6.39608 3.0047L7.30105 3.00352C9.23909 3.00235 11.1783 3 13.1163 3C14.6125 3 16.1074 3.00117 17.6036 3.00352C19.8542 3.00705 21.777 4.74294 21.9803 6.95364C22.1907 9.24191 20.6969 11.2399 18.4263 11.7029L18.2558 11.7382V13.809C18.2558 15.3722 18.2558 16.9353 18.2558 18.4996C18.2558 20.0533 17.1687 21.1393 15.6115 21.1393H11.9928L8.37526 21.1404ZM7.02486 18.5066C7.02486 19.3505 7.5314 19.8535 8.37995 19.8535H15.6162C16.4635 19.8535 16.9701 19.3493 16.9701 18.5055V8.0443H7.02486V18.5066ZM11.7636 4.28458C9.77149 4.28458 7.99917 4.2881 6.3432 4.29633C5.13383 4.30221 4.19713 4.88044 3.63417 5.96875C3.08179 7.03708 3.15348 8.14302 3.8422 9.16434C4.26412 9.78959 4.87645 10.208 5.66388 10.4066L5.73675 10.4254V7.54598C5.73675 6.96892 5.96476 6.74444 6.54887 6.74444H17.4508C18.029 6.74444 18.2547 6.97127 18.2547 7.55538V10.4195C18.2547 10.4195 18.3839 10.389 18.4074 10.3843C18.4545 10.3737 18.4921 10.3655 18.5285 10.3537C19.9788 9.90947 20.9061 8.45565 20.684 6.97245C20.4536 5.42343 19.1808 4.2975 17.66 4.29398C15.6973 4.28928 13.7298 4.28458 11.7636 4.28458Z" fill="white"/>
                                                    <path d="M11.9947 17.0122C11.5799 17.0122 11.1862 16.843 10.8559 16.5221C10.4775 16.1554 10.1014 15.7735 9.73821 15.4056L9.70882 15.3762C9.56779 15.2329 9.4867 15.0589 9.48082 14.8861C9.47494 14.7157 9.54311 14.5512 9.67239 14.4243C9.7958 14.3032 9.94623 14.2386 10.1096 14.2386C10.2859 14.2386 10.4634 14.3173 10.6091 14.4595C10.7478 14.5959 10.8841 14.7404 11.0169 14.8803C11.0769 14.9437 11.138 15.0084 11.1979 15.0718L11.2296 15.1047L11.353 15.0331V14.3784C11.353 13.343 11.353 12.3076 11.3542 11.271V11.2369C11.3542 11.157 11.3542 11.0747 11.3695 11.0007C11.4341 10.701 11.6892 10.5 12.003 10.5C12.0253 10.5 12.0476 10.5012 12.0711 10.5035C12.3932 10.5329 12.6364 10.8103 12.6388 11.1487C12.6423 11.9162 12.6411 12.6837 12.6411 13.4511V15.2211L12.9561 14.8967C13.0983 14.7498 13.2276 14.6158 13.3581 14.4854C13.5179 14.3267 13.7071 14.2386 13.8905 14.2386C14.0515 14.2386 14.2007 14.3032 14.3241 14.4266C14.5945 14.6981 14.571 15.0871 14.2654 15.3962C13.8916 15.7735 13.525 16.1437 13.1489 16.5116C12.8174 16.8348 12.4084 17.0122 11.9947 17.0122Z" fill="white"/>
                                                    </svg>
                                                <span class="text-truncate">{{\App\CPU\translate('withdraws')}}</span>
                                            </a>
                                        </li>

                                        <li
                                            class="d-none nav-item {{Request::is('admin/delivery-man/emergency-contact')?'active':''}}">
                                            <a class="nav-link "
                                                href="{{route('admin.delivery-man.emergency-contact.index')}}"
                                                title="{{\App\CPU\translate('emergency_contact')}}">
                                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M19.8047 15.7143C18.6155 15.3863 17.4246 15.0645 16.2321 14.7489C15.2516 14.4892 14.2874 14.9 13.8094 15.782C13.7087 15.9678 13.5968 15.9857 13.4194 15.9129C10.8938 14.8787 9.11363 13.103 8.08504 10.573C8.00949 10.3872 8.04475 10.2887 8.22607 10.1896C9.06774 9.72851 9.50145 8.81856 9.27033 7.88566C8.94686 6.58062 8.60101 5.27949 8.21599 3.99124C7.87518 2.85184 6.60483 2.26423 5.50013 2.69738C4.77878 2.97999 4.12905 3.3874 3.57559 3.93415C2.4513 5.04557 1.99856 6.42113 2.00024 7.9724C1.99744 8.33 2.01871 8.68649 2.06964 9.03905C2.1172 9.37035 2.17373 9.70164 2.25487 10.0257C2.95049 12.8059 4.35906 15.1966 6.31495 17.2622C7.95017 18.9886 9.91445 20.2556 12.1138 21.1521C13.463 21.7022 14.8582 22.0582 16.33 21.9932C18.7112 21.888 20.3537 20.7095 21.2944 18.532C21.3834 18.3255 21.4354 18.1083 21.4438 17.8828C21.4813 16.8369 20.8579 16.0047 19.8047 15.7143ZM20.131 18.1833C19.5478 19.4649 18.625 20.3468 17.2226 20.6697C16.8118 20.7643 16.3943 20.7934 16.0082 20.8001C14.4424 20.7671 13.0248 20.3032 11.665 19.6406C7.91435 17.8145 5.30371 14.9392 3.81399 11.0481C3.40658 9.98425 3.1609 8.88235 3.20679 7.734C3.28066 5.88333 4.17942 4.59059 5.87228 3.84629C6.37035 3.62748 6.93333 3.86476 7.08275 4.39249C7.43308 5.63373 7.76717 6.87946 8.09399 8.12686C8.20536 8.55274 8.01285 8.92936 7.62167 9.14762C6.89304 9.5539 6.64569 10.2137 6.9574 10.9916C8.1147 13.8776 10.1439 15.8972 13.0293 17.0523C13.7859 17.3551 14.4603 17.0965 14.8548 16.3796C15.0809 15.9694 15.4598 15.7937 15.9119 15.914C17.1241 16.2375 18.3357 16.5643 19.5445 16.9007C20.1494 17.0685 20.3895 17.6147 20.131 18.1833Z" fill="#fff"/>
                                                <path d="M21.955 6.33461C21.5773 3.47213 18.745 1.48658 15.9329 2.11728C12.7515 2.8308 11.0849 6.21037 12.456 9.17134C12.5035 9.27376 12.5069 9.36162 12.4761 9.47018C12.3653 9.85856 12.2651 10.2509 12.1639 10.642C11.9574 11.4417 12.5752 12.0607 13.3698 11.8519C13.7677 11.7473 14.1662 11.6466 14.5613 11.533C14.6637 11.5039 14.746 11.5106 14.8394 11.5553C15.6979 11.9667 16.5994 12.1127 17.5474 12.0126C20.0725 11.7445 22.0149 9.5726 21.9993 6.99776C21.9925 6.79406 21.9853 6.56461 21.955 6.33461ZM17.7265 10.7629C16.8418 10.9459 15.9889 10.8166 15.1937 10.3801C14.9564 10.2497 14.7314 10.2229 14.4734 10.3046C14.1421 10.4092 13.8019 10.4876 13.4286 10.5872C13.5327 10.196 13.6228 9.83338 13.7263 9.47466C13.7879 9.26032 13.7627 9.06893 13.6575 8.87138C12.5629 6.817 13.4185 4.4039 15.5597 3.49507C17.7405 2.56945 20.3478 4.00657 20.7311 6.34468C21.0736 8.4276 19.7809 10.3387 17.7265 10.7629Z" fill="#fff"/>
                                                <path d="M16.3838 8.03838C16.3787 7.63489 16.3782 7.63713 15.9831 7.63321C15.8303 7.63153 15.677 7.65336 15.5247 7.60971C15.2293 7.52465 15.0384 7.25883 15.0698 6.96726C15.1017 6.67178 15.3378 6.44233 15.6423 6.42666C15.8443 6.41603 16.0474 6.41883 16.25 6.42498C16.3479 6.42778 16.3854 6.4026 16.381 6.29851C16.3726 6.11719 16.3737 5.93531 16.3798 5.75399C16.3933 5.36114 16.6451 5.10651 17.0044 5.11546C17.3519 5.12386 17.5791 5.38408 17.5881 5.77022C17.5931 5.984 17.4952 6.26941 17.6295 6.39029C17.747 6.4955 18.0274 6.42051 18.2361 6.42331C18.6284 6.42834 18.8987 6.67066 18.9021 7.02266C18.9054 7.38026 18.6284 7.63209 18.226 7.63489C18.0514 7.63601 17.8768 7.63937 17.7022 7.63377C17.6093 7.63098 17.5819 7.66455 17.5847 7.75465C17.5903 7.94324 17.5886 8.13184 17.5858 8.32043C17.5797 8.673 17.3413 8.93042 17.0128 8.94385C16.6792 8.95728 16.4213 8.72672 16.3854 8.3736C16.3742 8.26279 16.3838 8.14975 16.3838 8.03838Z" fill="#fff"/>
                                                </svg>
                                                <span
                                                    class="text-truncate">{{\App\CPU\translate('Emergency_Contact')}}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li
                                    class="d-none navbar-vertical-aside-has-menu {{Request::is('admin/support-ticket*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.support-ticket.view')}}"
                                        title="{{\App\CPU\translate('Customer_Support')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M21.3377 12.6702C21.3377 11.3474 20.6323 10.4832 19.3801 10.2011V10.1834C19.3801 10.1305 19.3801 10.06 19.3801 10.0071C19.3624 9.79542 19.3624 9.56614 19.3624 9.3545C19.3624 8.87831 19.3448 8.36685 19.2566 7.89066C18.6922 4.9806 16.9462 3.09348 14.0891 2.24692C13.8069 2.15873 13.5071 2.12346 13.2072 2.07055C13.0661 2.05291 12.9427 2.03527 12.7839 2C12.4489 2 12.1138 2 11.7787 2H11.761C11.761 2 11.7257 2.01764 11.7081 2.01764C11.6905 2.01764 11.6728 2.03528 11.6552 2.03528C10.9145 2.08819 10.2443 2.26455 9.62698 2.5291C7.29894 3.53439 5.85273 5.29806 5.32363 7.7672C5.20018 8.36684 5.20018 9.00177 5.20018 9.60142C5.20018 9.74251 5.20018 9.90124 5.20018 10.0423V10.2187H5.09436C5.05908 10.2187 5.04145 10.2187 5.00617 10.2187C3.84215 10.2187 3.03086 11.2416 3.01323 12.2116C2.99559 13.4638 2.99559 14.575 3.01323 15.5979C3.03086 16.6032 3.87742 17.4497 4.88272 17.485C5.12963 17.5026 5.39418 17.5026 5.71164 17.5026C5.87037 17.5026 6.04674 17.5026 6.20547 17.5026C6.3642 17.5026 6.54056 17.5026 6.69929 17.5026C7.14021 17.5026 7.33421 17.3086 7.33421 16.8501V10.9947C7.33421 10.9065 7.33421 10.836 7.31658 10.7478C7.26367 10.3951 7.05203 10.2187 6.69929 10.2187H6.32892C6.32892 10.0247 6.32892 9.83068 6.34656 9.63668C6.34656 9.17813 6.3642 8.73721 6.41711 8.31393C6.78748 5.5097 9.08025 3.35803 11.8845 3.1993C12.0256 3.1993 12.1667 3.18166 12.3078 3.18166C14.3536 3.18166 16.0291 4.08113 17.246 5.86244C18.1984 7.25573 18.269 8.84303 18.1984 10.2011C18.1279 10.2011 18.0397 10.2011 17.9691 10.2011H17.828C17.5459 10.2011 17.3695 10.3422 17.2813 10.5891C17.246 10.6949 17.2284 10.8183 17.2284 10.9594C17.2284 12.9171 17.2284 14.8924 17.2284 16.8501C17.2284 17.2734 17.44 17.485 17.8633 17.485H18.1455C17.6164 18.2257 16.3289 18.9312 15.4471 18.9312C15.3765 18.9312 15.306 18.9312 15.2354 18.9136C14.9003 17.5908 13.7363 16.9912 12.8016 16.9912C12.6958 16.9912 12.5899 16.9912 12.4841 17.0088C11.179 17.1852 10.2443 18.2434 10.2619 19.5132C10.2619 20.7654 11.2496 21.8236 12.537 21.9824C12.6252 22 12.7134 22 12.8016 22C13.7716 22 14.9356 21.4004 15.2531 20.06C15.2707 20.06 15.306 20.06 15.3413 20.06C16.1702 20.0247 16.9462 19.813 17.6693 19.4074C18.41 18.9841 19.0273 18.3668 19.5212 17.5379C19.5741 17.4674 19.6093 17.4321 19.7152 17.4145C20.791 17.2557 21.3554 16.6032 21.3554 15.5273V14.6984C21.3377 14.0459 21.3377 13.358 21.3377 12.6702ZM18.41 11.3651C18.4982 11.3651 18.5864 11.3651 18.657 11.3651C18.7628 11.3651 18.8862 11.3651 18.9921 11.3651C19.1684 11.3651 19.3095 11.3827 19.4506 11.4003C19.5741 11.418 19.7328 11.5414 19.8915 11.7354C20.0855 11.9824 20.1914 12.2822 20.1914 12.6526V15.5802C20.1914 16.0917 19.9797 16.321 19.4859 16.3563C19.3624 16.3563 19.2213 16.3739 19.045 16.3739C18.9215 16.3739 18.8157 16.3739 18.6922 16.3739C18.6041 16.3739 18.4982 16.3739 18.41 16.3739C18.41 14.6631 18.41 13.0229 18.41 11.3651ZM4.15961 15.4744C4.15961 14.5397 4.15961 13.4462 4.15961 12.3351C4.15961 11.7355 4.54762 11.3474 5.14727 11.3474H6.18783C6.18783 13.0053 6.18783 14.6631 6.18783 16.3034C6.06437 16.3034 5.94092 16.3034 5.81746 16.3034C5.67637 16.3034 5.51764 16.3034 5.37654 16.3034C5.1649 16.3034 4.98854 16.3034 4.81217 16.2681C4.4418 16.2504 4.15961 15.9153 4.15961 15.4744ZM12.7839 18.0494V18.1376C13.56 18.1376 14.142 18.7372 14.142 19.5309C14.142 19.8836 14.0009 20.2011 13.754 20.4656C13.5071 20.7302 13.172 20.8712 12.8016 20.8712C12.0608 20.8712 11.4259 20.2187 11.4083 19.4956C11.4083 18.7548 12.0079 18.1552 12.7487 18.1552L12.7839 18.0494Z"
                                                fill="white" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            <span class="position-relative">
                                                {{\App\CPU\translate('Customer_Support')}}
                                                @if(\App\Model\SupportTicket::where('status','open')->count()>0)
                                                <span
                                                    class="btn-status btn-xs-status btn-status-danger position-absolute top-0 menu-status"></span>
                                                @endif
                                            </span>
                                        </span>
                                    </a>
                                </li>


                            <!-- </ul>
                        </li> -->
                        @endif
                        <!--User management-->
                        <!--User management end-->
                        @if(auth('admin')->user()->admin_role_id==1)
                        <li
                            class="navbar-vertical-aside-has-menu {{(Request::is('admin/employee*') || Request::is('admin/custom-role*'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link  its-drop" href="javascript:"
                                title="{{\App\CPU\translate('employees')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M11.9961 13.3906C12.4103 13.3906 12.7461 13.7264 12.7461 14.1406V16.6776C12.7461 17.0918 12.4103 17.4276 11.9961 17.4276C11.5819 17.4276 11.2461 17.0918 11.2461 16.6776V14.1406C11.2461 13.7264 11.5819 13.3906 11.9961 13.3906Z"
                                        fill="white" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M5.81 6.08008C4.53101 6.08008 3.5 7.10749 3.5 8.38008V11.3921C5.78555 12.6333 8.74542 13.3901 11.99 13.3901C15.235 13.3901 18.2038 12.6332 20.49 11.392V8.39008C20.49 7.11109 19.4626 6.08008 18.19 6.08008H5.81ZM2 8.38008C2 6.27267 3.70899 4.58008 5.81 4.58008H18.19C20.2974 4.58008 21.99 6.28907 21.99 8.39008V11.8301C21.99 12.0964 21.8487 12.3428 21.6189 12.4773C19.0303 13.9926 15.6465 14.8901 11.99 14.8901C8.33312 14.8901 4.95934 13.9924 2.37112 12.4773C2.14126 12.3428 2 12.0964 2 11.8301V8.38008Z"
                                        fill="white" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M7.74609 4.96C7.74609 3.32579 9.07188 2 10.7061 2H13.2861C14.9203 2 16.2461 3.32579 16.2461 4.96V5.326C16.2461 5.74021 15.9103 6.076 15.4961 6.076C15.0819 6.076 14.7461 5.74021 14.7461 5.326V4.96C14.7461 4.15421 14.0919 3.5 13.2861 3.5H10.7061C9.90031 3.5 9.24609 4.15421 9.24609 4.96V5.326C9.24609 5.74021 8.91031 6.076 8.49609 6.076C8.08188 6.076 7.74609 5.74021 7.74609 5.326V4.96Z"
                                        fill="white" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M2.71714 14.7346C3.13018 14.7035 3.49024 15.0131 3.52135 15.4261L3.71033 17.9348C3.80888 19.2349 4.89214 20.2395 6.19447 20.2395H17.7935C19.0958 20.2395 20.179 19.2352 20.2776 17.9351C20.2776 17.9352 20.2776 17.935 20.2776 17.9351L20.4666 15.4261C20.4977 15.0131 20.8578 14.7035 21.2708 14.7346C21.6839 14.7657 21.9935 15.1258 21.9624 15.5388L21.7734 18.0478C21.6158 20.1296 19.881 21.7395 17.7935 21.7395H6.19447C4.1069 21.7395 2.37219 20.1299 2.21461 18.0481L2.02559 15.5388C1.99448 15.1258 2.30409 14.7657 2.71714 14.7346Z"
                                        fill="white" />
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('employees')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/employee*') || Request::is('admin/custom-role*')?'block':'none'}}">
                                <li
                                    class="d-none navbar-vertical-aside-has-menu {{Request::is('admin/custom-role*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.custom-role.create')}}"
                                        title="{{\App\CPU\translate('Employee_Role_Setup')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M19.774 19.6423C19.6927 17.1423 18.6561 15.1707 16.6643 13.748C16.1358 13.3618 15.5667 13.0366 14.9976 12.7114L14.9773 12.6911C14.7944 12.5894 14.6114 12.4878 14.4285 12.3659C14.3878 12.3455 14.3675 12.3252 14.3675 12.3252C14.3675 12.3049 14.3878 12.3049 14.4285 12.2642C15.2822 11.4715 15.77 10.6585 15.9732 9.72358C16.2374 8.54472 16.3187 7.28455 16.2781 5.88211C16.2781 5.61788 16.1968 5.35366 16.1358 5.10975C15.5667 3.26016 13.8797 2 11.9082 2C11.5626 2 11.2374 2.04065 10.8919 2.12195C8.92036 2.5691 7.51792 4.27642 7.4976 6.24796C7.47727 7.28455 7.55858 8.28049 7.72118 9.23577C7.9041 10.3943 8.37158 11.3089 9.10329 12.0203C9.20491 12.122 9.30654 12.2439 9.42849 12.3455L9.48947 12.4065C9.34719 12.4878 9.20491 12.5488 9.06264 12.6301C8.67646 12.8333 8.26996 13.0366 7.88378 13.2602C5.7293 14.4593 4.44881 16.2886 4.08296 18.7073C3.98133 19.3171 4.00166 19.9268 4.00166 20.5366C4.00166 20.8008 4.00166 21.0447 4.00166 21.309C4.00166 21.5325 4.06264 21.6951 4.16426 21.8171C4.28622 21.939 4.44882 22 4.6724 22H14.2862H19.083C19.5098 22 19.7537 21.7561 19.7537 21.3293C19.7537 21.1667 19.7537 21.0244 19.7537 20.8618C19.774 20.4553 19.774 20.0285 19.774 19.6423ZM11.8878 12.3455C11.6846 12.3455 11.461 12.2846 11.2374 12.1626C9.87565 11.4106 9.12361 10.3943 8.90004 9.07317C8.73743 8.15854 8.67646 7.18293 8.69678 6.22764C8.71711 4.78455 9.77402 3.56504 11.2781 3.26016C11.4813 3.21951 11.7049 3.19919 11.9082 3.19919C13.3309 3.19919 14.6114 4.15447 14.9976 5.49593C15.1196 5.88211 15.0992 6.28861 15.0992 6.75609C15.0992 6.83739 15.0992 6.93903 15.0992 7.02033V7.18293C15.0586 8.01626 15.0179 8.95122 14.713 9.82521C14.3269 10.8821 13.5545 11.6951 12.396 12.2439C12.213 12.3049 12.0504 12.3455 11.8878 12.3455ZM12.1521 13.9309C12.1521 13.9309 12.1114 13.9309 12.0911 13.9309C11.9895 13.9309 11.9082 13.9309 11.8472 13.9309C11.6236 13.9309 11.6033 13.8903 11.4204 13.5244C11.4204 13.5041 11.4 13.4837 11.4 13.4634C11.5626 13.5041 11.7252 13.5041 11.9082 13.5041C12.0708 13.5041 12.2334 13.4837 12.396 13.4634C12.2943 13.6463 12.2334 13.7683 12.1521 13.9309ZM11.7659 15.1098C11.7659 15.1098 11.8065 15.1098 11.8472 15.1301H11.8878H11.9082C11.9285 15.1301 11.9691 15.1098 11.9895 15.1098C12.0098 15.1098 12.0504 15.1098 12.0708 15.2317C12.1927 15.9024 12.3147 16.5732 12.4366 17.2236L12.5383 17.7317C12.5586 17.813 12.5383 17.8537 12.4976 17.8943C12.274 18.1992 12.0708 18.4431 11.9082 18.687C11.9082 18.687 11.9082 18.687 11.9082 18.7073C11.9082 18.7073 11.9082 18.687 11.8878 18.687C11.6846 18.4024 11.4813 18.1382 11.2984 17.9146C11.2578 17.8537 11.2374 17.813 11.2578 17.7317C11.4204 16.9187 11.5626 16.0854 11.7049 15.2724C11.7252 15.1911 11.7456 15.1098 11.7659 15.1098ZM10.587 14.7642C10.5057 15.1911 10.4244 15.6179 10.3431 16.0447C10.2415 16.6138 10.1399 17.1829 10.0179 17.7724C9.95695 18.0569 10.0179 18.2805 10.1805 18.5041C10.4448 18.8496 10.709 19.1951 10.9529 19.5407C11.0748 19.7033 11.2171 19.8862 11.3391 20.0488C11.4813 20.252 11.6643 20.3537 11.8675 20.3537C12.0504 20.3537 12.2334 20.252 12.396 20.0488C12.5586 19.8455 12.7009 19.6423 12.8635 19.439C13.1074 19.1138 13.3513 18.7886 13.5952 18.4634C13.7374 18.2805 13.7781 18.0772 13.7374 17.8333L13.5139 16.6748C13.3919 16.065 13.2903 15.4553 13.1683 14.8455C13.148 14.6829 13.148 14.5813 13.209 14.4594C13.2903 14.2968 13.3716 14.1341 13.4529 13.9715C13.4935 13.8699 13.5545 13.7886 13.5952 13.687C13.6358 13.5854 13.6968 13.5041 13.7171 13.3618C14.0017 13.5447 14.3065 13.7276 14.6114 13.8902C15.1195 14.1748 15.587 14.4593 16.0342 14.7845C17.6399 15.9837 18.4935 17.6301 18.5342 19.7033C18.5342 19.8455 18.5342 20.0081 18.5342 20.1504C18.5342 20.3333 18.5342 20.5163 18.5342 20.6992C18.5342 20.7602 18.5342 20.7805 18.5342 20.7805C18.5342 20.7805 18.5139 20.7805 18.4529 20.7805C17.1114 20.7805 15.77 20.7805 14.4082 20.7805H9.02199C7.76183 20.7805 6.52199 20.7805 5.26182 20.7805C5.18052 20.7805 5.1602 20.7602 5.1602 20.7602C5.1602 20.7602 5.13987 20.7398 5.13987 20.6585V20.4146C5.13987 19.6423 5.13987 18.8496 5.36345 18.0772C5.83093 16.4106 6.84719 15.1504 8.35125 14.3171C8.75776 14.0935 9.16427 13.8699 9.55045 13.687C9.69272 13.6057 9.85532 13.5244 9.9976 13.4431L10.1195 13.6667C10.2618 13.9309 10.3838 14.1951 10.5261 14.4594C10.6074 14.5813 10.6074 14.6626 10.587 14.7642Z"
                                                fill="white" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('Employee_Role_Setup')}}</span>
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{(Request::is('admin/employee/list') || Request::is('admin/employee/add-new') || Request::is('admin/employee/update*'))?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.employee.list')}}"
                                        title="{{\App\CPU\translate('Employees')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M16.8098 19.6505C15.5003 19.6505 14.2067 19.6505 12.8972 19.6505C12.1625 19.6505 11.6675 19.1554 11.6675 18.4049C11.6675 18.1653 11.6675 17.9098 11.6675 17.6543V17.3508C11.6675 17.3508 7.70694 17.3508 7.02024 17.3508C5.45519 17.3508 4.2255 17.3508 3.07567 17.3668C2.70836 17.3668 2.42091 17.255 2.22927 17.0314C2.03763 16.8079 1.95778 16.5044 2.02166 16.1531C2.4209 13.534 3.85819 11.7294 6.31756 10.7872L6.3495 10.7712L6.33353 10.7553C5.29549 10.0047 4.76848 8.9826 4.73654 7.73695C4.72057 6.68294 5.10385 5.77266 5.88637 5.02207C6.57308 4.36731 7.48336 4 8.44156 4C9.55945 4 10.6135 4.49507 11.3161 5.35744C12.0028 6.17191 12.2903 7.25786 12.1146 8.31187C11.9549 9.28604 11.412 10.1484 10.5815 10.7553L10.5496 10.7712L10.5815 10.7872C11.2363 11.0268 11.8431 11.3621 12.4181 11.7933C12.4819 11.8412 12.5778 11.8732 12.6576 11.8732C12.977 11.8732 13.2964 11.8732 13.6158 11.8732H14.4941V11.8572C14.4941 11.7933 14.4941 11.7294 14.4941 11.6656C14.5101 10.9629 14.9413 10.5317 15.628 10.5317C16.0273 10.5317 16.4265 10.5317 16.8258 10.5317C17.225 10.5317 17.6402 10.5317 18.0395 10.5317C18.7262 10.5317 19.1574 10.9629 19.1733 11.6656V11.8732H20.3391C20.5627 11.8732 20.7863 11.8732 21.0099 11.8732C21.6007 11.8732 22 12.2564 22 12.8473C22 14.6998 22 16.5523 22 18.4049C22 19.1395 21.489 19.6665 20.7544 19.6665C19.4288 19.6505 18.1193 19.6505 16.8098 19.6505ZM12.6736 16.744C12.6736 17.3189 12.6736 17.8938 12.6736 18.4528C12.6736 18.5166 12.6736 18.6284 12.9131 18.6284C14.2067 18.6284 15.5003 18.6284 16.8098 18.6284C18.1033 18.6284 19.3969 18.6284 20.7064 18.6284C20.8981 18.6284 20.962 18.5645 20.962 18.3729C20.962 18.1493 20.962 17.9417 20.962 17.7181V16.2808H20.946C20.5308 16.3607 20.1475 16.3607 19.8441 16.3607C19.6684 16.3607 19.4927 16.3607 19.333 16.3607H19.3171C19.1574 16.3607 18.9817 16.3607 18.822 16.3607C18.6304 16.3607 18.4707 16.3607 18.3269 16.3767H18.311V16.3926C18.311 16.5044 18.311 17.0953 18.311 17.1911C18.295 17.6543 18.0075 17.9417 17.5604 17.9417C17.3208 17.9417 17.0653 17.9417 16.8258 17.9417C16.5862 17.9417 16.3467 17.9417 16.1071 17.9417C15.644 17.9417 15.3725 17.6543 15.3565 17.1752C15.3565 17.0634 15.3565 16.5204 15.3565 16.3767V16.3607H15.3406C15.1968 16.3447 15.0371 16.3447 14.8455 16.3447C14.6698 16.3447 14.5101 16.3447 14.3344 16.3447H14.3185C14.1588 16.3447 13.9991 16.3447 13.8234 16.3447C13.504 16.3447 13.1207 16.3288 12.7215 16.2649H12.7055L12.6736 16.744ZM16.3626 16.9196H17.2889V16.3767H16.3626V16.9196ZM8.42559 11.41C7.49933 11.41 6.57308 11.6496 5.74265 12.1127C3.95402 13.1188 3.07567 14.9873 3.02776 16.2968V16.3128H11.6675V12.512C10.7732 11.8093 9.62333 11.41 8.42559 11.41ZM12.6736 12.9112V13.3584C12.6736 13.8375 12.6736 14.3006 12.6736 14.7797C12.6736 15.163 12.8492 15.3386 13.2325 15.3386C14.4303 15.3386 15.612 15.3386 16.8098 15.3386C18.0075 15.3386 19.1893 15.3386 20.387 15.3386C20.7863 15.3386 20.962 15.163 20.962 14.7477C20.962 14.5721 20.962 14.4124 20.962 14.2367V14.1409C20.962 13.7416 20.962 13.3424 20.962 12.9431C20.962 12.8952 20.962 12.8793 20.962 12.8633V12.8473H20.946C20.93 12.8473 20.8981 12.8473 20.8502 12.8473C19.1893 12.8473 17.5444 12.8473 15.8995 12.8473H12.6736C12.6736 12.8633 12.6736 12.9112 12.6736 12.9112ZM15.8835 11.5218C15.7079 11.5218 15.5482 11.5218 15.5002 11.5538C15.4843 11.5697 15.4683 11.6176 15.4843 11.8253V11.8412H18.1353V11.7614C18.1353 11.7135 18.1353 11.5857 18.1193 11.5538V11.5378H18.1033C18.0554 11.5218 17.9436 11.5218 17.8957 11.5218H15.8835ZM8.44156 4.99013C6.94039 4.99013 5.74264 6.1719 5.7107 7.6571C5.69473 8.35978 5.96622 9.04649 6.47726 9.55752C6.98829 10.0845 7.64306 10.372 8.34574 10.388H8.39365C9.89482 10.388 11.0926 9.22215 11.1245 7.72098C11.1564 6.26772 9.94273 5.0061 8.48947 4.99013H8.44156Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('Employees')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @endif
                        <!--Product Management Ends-->

                        @if(\App\CPU\Helpers::module_permission_check('promotion_management'))
                        <!--promotion management start-->
                        <li
                            class="nav-item{{(Request::is('admin/banner*') || (Request::is('admin/coupon*')) || (Request::is('admin/notification*')) || (Request::is('admin/deal*')))?'scroll-here':''}}">
                            <small class="nav-subtitle" title="">{{\App\CPU\translate('promotion_management')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>
                        <li
                            class="navbar-vertical-aside-has-menu {{(Request::is('admin/coupon*') || Request::is('admin/deal*'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:"
                                title="{{\App\CPU\translate('Promotion_&_Discounts')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.79476 7.05589C4.79476 5.80689 5.80676 4.79489 7.05576 4.79389H8.08476C8.68176 4.79389 9.25376 4.55689 9.67776 4.13689L10.3968 3.41689C11.2778 2.53089 12.7098 2.52689 13.5958 3.40789L13.5968 3.40889L13.6058 3.41689L14.3258 4.13689C14.7498 4.55789 15.3218 4.79389 15.9188 4.79389H16.9468C18.1958 4.79389 19.2088 5.80589 19.2088 7.05589V8.08289C19.2088 8.67989 19.4448 9.25289 19.8658 9.67689L20.5858 10.3969C21.4718 11.2779 21.4768 12.7099 20.5958 13.5959L20.5948 13.5969L20.5858 13.6059L19.8658 14.3259C19.4448 14.7489 19.2088 15.3209 19.2088 15.9179V16.9469C19.2088 18.1959 18.1968 19.2079 16.9478 19.2079H15.9168C15.3198 19.2079 14.7468 19.4449 14.3238 19.8659L13.6038 20.5849C12.7238 21.4709 11.2928 21.4759 10.4068 20.5969C10.4058 20.5959 10.4048 20.5949 10.4038 20.5939L10.3948 20.5849L9.67576 19.8659C9.25276 19.4449 8.67976 19.2089 8.08276 19.2079H7.05576C5.80676 19.2079 4.79476 18.1959 4.79476 16.9469V15.9159C4.79476 15.3189 4.55776 14.7469 4.13676 14.3239L3.41776 13.6039C2.53176 12.7239 2.52676 11.2929 3.40676 10.4069C3.40676 10.4059 3.40776 10.4049 3.40876 10.4039L3.41776 10.3949L4.13676 9.67489C4.55776 9.25089 4.79476 8.67889 4.79476 8.08089V7.05589Z"
                                        stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M9.42969 14.5714L14.5697 9.4314" stroke="#fff" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M14.4961 14.4998H14.5051" stroke="#fff" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9.49609 9.49976H9.50509" stroke="#fff" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('Promotion_&_Discounts')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{(Request::is('admin/coupon*') || Request::is('admin/deal*'))?'block':'none'}}">
                                <li class="navbar-vertical-aside-has-menu {{Request::is('admin/coupon*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.coupon.add-new')}}"
                                        title="{{\App\CPU\translate('coupon')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M21.326 14.3625C20.306 14.3261 19.5046 13.5064 19.5046 12.5046C19.5046 11.4845 20.306 10.6648 21.326 10.6284C21.7632 10.6102 22 10.3734 22 9.93625C22 8.93443 22 7.93261 22 6.93079C22 6.76686 21.9818 6.58471 21.9454 6.42077C21.7268 5.56467 20.9982 5 20.0874 5C14.6958 5 9.3224 5 3.93078 5C3.89435 5 3.85792 5 3.83971 5C3.03825 5.01821 2.32787 5.58288 2.09107 6.34791C2.07286 6.40255 2.09108 6.47542 2.01822 6.51185C2.01822 7.71403 2.01822 8.93443 2.01822 10.1366C2.10929 10.4827 2.30965 10.6466 2.67395 10.6466C2.69217 10.6466 2.7286 10.6466 2.74681 10.6466C3.40255 10.7013 3.91257 11.0109 4.22222 11.5756C4.89617 12.796 4.05829 14.2168 2.65574 14.2896C2.29144 14.3078 2.09107 14.4718 2 14.7996C2 16.0018 2 17.1858 2 18.388C2.05464 18.4062 2.03643 18.4426 2.03643 18.4791C2.27322 19.3898 2.98361 19.9362 3.93078 19.9362C9.30419 19.9362 14.6776 19.9362 20.051 19.9362C21.1621 19.9362 21.9818 19.1166 21.9818 18.0055C21.9818 17.0036 21.9818 16.02 21.9818 15.0182C22.0182 14.6175 21.7814 14.3807 21.326 14.3625ZM20.7614 15.8197C20.7432 16.5665 20.7614 17.2951 20.7614 18.0419C20.7614 18.4791 20.5064 18.7341 20.0692 18.7341C17.3916 18.7341 14.6958 18.7341 12.0182 18.7341C9.35883 18.7341 6.68124 18.7341 4.02186 18.7341C3.53005 18.7341 3.29326 18.4973 3.29326 17.9873C3.29326 17.2404 3.29326 16.4754 3.29326 15.7286C3.29326 15.6011 3.31148 15.5464 3.4572 15.51C4.85975 15.1275 5.78871 13.9071 5.78871 12.4863C5.78871 11.0656 4.84153 9.84517 3.4572 9.46266C3.32969 9.42623 3.29326 9.37159 3.29326 9.24409C3.29326 8.47906 3.29326 7.69582 3.29326 6.93079C3.29326 6.49363 3.54827 6.23862 3.98543 6.23862C9.35883 6.23862 14.714 6.23862 20.0874 6.23862C20.5428 6.23862 20.7796 6.49363 20.7796 6.949C20.7796 7.71403 20.7796 8.47906 20.7796 9.24409C20.7796 9.37159 20.7614 9.42623 20.6157 9.46266C19.3042 9.80874 18.3752 10.9381 18.2842 12.2678C18.1931 13.5975 18.9581 14.8361 20.2149 15.3643C20.3242 15.4007 20.4153 15.4554 20.5246 15.4736C20.725 15.51 20.7796 15.6193 20.7614 15.8197Z"
                                                fill="white" />
                                            <path
                                                d="M14.4599 7.7499C14.4417 7.78633 14.4235 7.84098 14.4052 7.87741C13.2031 11.1015 12.0009 14.3073 10.7987 17.5313C10.744 17.6588 10.7076 17.7135 10.5619 17.6406C10.2887 17.5131 9.99722 17.422 9.70578 17.3127C9.57828 17.2763 9.57828 17.2217 9.61471 17.1124C10.1612 15.6552 10.7076 14.198 11.2541 12.7226C11.9098 10.9739 12.5655 9.22531 13.2213 7.45846C13.2759 7.31274 13.3306 7.2581 13.4945 7.33096C13.7677 7.45847 14.0592 7.54954 14.3506 7.65883C14.387 7.67704 14.4599 7.67704 14.4599 7.7499Z"
                                                fill="white" />
                                            <path
                                                d="M15.5145 12.832C14.4762 12.832 13.6383 13.6699 13.6565 14.7082C13.6565 15.7464 14.4944 16.5843 15.5327 16.5843C16.5527 16.5843 17.4088 15.7282 17.4088 14.7082C17.3906 13.6699 16.5527 12.832 15.5145 12.832ZM15.5145 15.3275C15.1684 15.3275 14.8952 15.036 14.8952 14.69C14.8952 14.3439 15.1684 14.0707 15.5145 14.0889C15.8605 14.0889 16.1338 14.3803 16.1338 14.7082C16.1338 15.036 15.8605 15.3275 15.5145 15.3275Z"
                                                fill="white" />
                                            <path
                                                d="M9.06754 8.95312C8.04751 8.95312 7.19141 9.80922 7.19141 10.8293C7.19141 11.8675 8.02929 12.6872 9.06754 12.6872C10.1058 12.6872 10.9255 11.8493 10.9255 10.8111C10.9255 9.79101 10.0876 8.95312 9.06754 8.95312ZM9.06754 11.4486C8.72146 11.4486 8.43002 11.1571 8.43002 10.8293C8.43002 10.4832 8.72146 10.21 9.04933 10.21C9.39541 10.21 9.66864 10.4832 9.66864 10.8293C9.68685 11.1753 9.41363 11.4486 9.06754 11.4486Z"
                                                fill="white" />
                                        </svg>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('coupon')}}</span>
                                    </a>
                                </li>
                                <li
                                    class="navbar-vertical-aside-has-menu {{(Request::is('admin/deal/flash') || (Request::is('admin/deal/update*')))?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.deal.flash')}}"
                                        title="{{\App\CPU\translate('Flash_Deals')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M6.85366 21.9321C6.06181 21.9321 5.24733 21.5701 4.70434 20.9593C4.31973 20.5294 3.88986 19.7602 4.02561 18.6063C4.09348 18.0181 4.16136 17.4072 4.2066 16.819C4.22923 16.5475 4.25185 16.2534 4.27448 15.9819L4.3876 14.6923C4.45547 14.0815 4.50072 13.4932 4.5686 12.8824C4.61385 12.4977 4.63647 12.1358 4.68172 11.7511C4.72697 11.276 4.77221 10.8009 4.81746 10.3258C4.84009 10.0543 4.86271 9.76019 4.88534 9.48869C4.95321 8.76471 5.02109 7.99548 5.13421 7.2715C5.26995 6.29865 6.15231 5.57467 7.17041 5.57467C7.17041 5.57467 7.66814 5.57467 7.89439 5.57467C8.14326 5.57467 8.3695 5.57467 8.61837 5.57467C8.77674 5.57467 8.82199 5.50679 8.82199 5.37105C8.93511 3.44797 10.4509 2 12.374 2H12.3966C14.2292 2.02262 15.7903 3.51585 15.8808 5.34843C15.8808 5.52942 15.9487 5.57467 16.1297 5.57467H16.1749C16.3333 5.57467 16.4917 5.57467 16.65 5.57467C16.8084 5.57467 16.9668 5.57467 17.1252 5.57467C17.3966 5.57467 17.6229 5.59728 17.8265 5.61991C18.822 5.77828 19.5233 6.54752 19.6138 7.56562C19.727 8.76471 19.8401 9.96381 19.9532 11.1855C20.0211 11.819 20.0663 12.4751 20.1342 13.1086C20.1795 13.6968 20.2473 14.2851 20.2926 14.8733C20.4057 16.095 20.5188 17.3167 20.6319 18.5385C20.6998 19.1041 20.6998 19.7149 20.4283 20.2806C19.908 21.3665 19.0482 21.9321 17.8718 21.9547C17.1252 21.9547 16.3785 21.9774 15.6093 21.9774C15.0663 21.9774 14.5007 21.9774 13.9577 21.9774C13.4147 21.9774 12.8491 21.9774 12.3062 21.9774C11.7405 21.9774 11.1976 21.9774 10.6772 21.9774C10.1342 21.9774 9.59122 21.9774 9.04823 21.9774C8.23375 21.9774 7.55502 21.9774 6.92154 22L6.85366 21.9321ZM7.23828 6.88688C6.80842 6.88688 6.5143 7.15838 6.46905 7.56562C6.40117 8.26697 6.3333 8.99095 6.26543 9.69231L6.10706 11.2986C6.06181 11.7059 6.03919 12.1131 5.99394 12.5204C5.94869 13.0633 5.88081 13.6063 5.83556 14.1493L5.67719 15.7783C5.63194 16.2986 5.56407 16.819 5.54145 17.3394C5.51882 17.543 5.4962 17.7466 5.47357 17.9729C5.42832 18.3575 5.38307 18.7647 5.38307 19.1493C5.38307 19.8959 6.01656 20.552 6.74054 20.5747C6.94416 20.5747 7.1704 20.5747 7.37402 20.5747C7.60027 20.5747 7.82651 20.5747 8.05276 20.5747C8.25638 20.5747 8.43737 20.5747 8.64099 20.5747H8.70887C8.75412 20.5747 8.82199 20.5747 8.86724 20.5747H17.736C18.2338 20.5747 18.6636 20.4163 18.9351 20.0995C19.2066 19.8054 19.3423 19.3756 19.2971 18.8778C19.2518 18.2896 19.184 17.7014 19.1387 17.1131C19.1161 16.8643 19.0935 16.6154 19.0709 16.3665L18.641 11.8643L18.5052 10.3937C18.4147 9.44345 18.3243 8.51584 18.2338 7.56562C18.1885 7.13575 17.8944 6.86425 17.4419 6.86425C17.3062 6.86425 17.1704 6.86425 17.0347 6.86425C16.8537 6.86425 16.6953 6.86425 16.5143 6.86425C16.4464 6.86425 16.4012 6.86425 16.3333 6.86425C16.2654 6.86425 16.2202 6.86425 16.1523 6.86425C16.0844 6.86425 15.9713 6.86426 15.9034 6.93214C15.8129 7.02263 15.8129 7.18099 15.8356 7.31674C15.8356 7.38461 15.8582 7.45249 15.8582 7.52037C15.8582 7.54299 15.8582 7.56561 15.8582 7.58824V7.63349C15.8582 8.04073 15.5867 8.31222 15.2021 8.31222C14.8175 8.31222 14.546 8.04073 14.5233 7.63349C14.5233 7.42987 14.5233 7.24888 14.5233 7.04526C14.5233 7.00001 14.5233 6.95475 14.4781 6.9095C14.4328 6.86425 14.3876 6.86425 14.3423 6.86425C13.6636 6.86425 12.9623 6.86425 12.2835 6.86425C11.6274 6.86425 10.2926 6.86425 10.2926 6.86425C10.2473 6.86425 10.1795 6.86425 10.1568 6.9095C10.1116 6.95475 10.1116 7.00001 10.1116 7.04526C10.1116 7.20363 10.1116 7.362 10.1116 7.52037V7.58824C10.1116 8.0181 9.86271 8.26697 9.45547 8.26697C9.41022 8.26697 9.36497 8.26697 9.31973 8.26697C8.95774 8.1991 8.77674 7.97285 8.77674 7.58824V7.4525C8.77674 7.31675 8.77674 7.20362 8.77674 7.06788C8.77674 7 8.77674 6.93213 8.73149 6.9095C8.68624 6.86425 8.61837 6.86425 8.55049 6.86425C8.3695 6.86425 8.21113 6.86425 8.05276 6.86425H7.53239C7.46452 6.88688 7.3514 6.88688 7.23828 6.88688ZM12.3514 3.31223C11.2428 3.31223 10.27 4.19458 10.1568 5.30317C10.1568 5.39367 10.1568 5.46154 10.2021 5.50679C10.2473 5.55204 10.3152 5.55204 10.3831 5.55204C10.6998 5.55204 10.9939 5.55204 11.2881 5.55204H12.3288H13.3695C13.6636 5.55204 13.9804 5.55204 14.2745 5.55204C14.365 5.55204 14.4328 5.55204 14.4781 5.50679C14.5233 5.46154 14.5233 5.4163 14.5233 5.30317C14.4328 4.17195 13.4826 3.31223 12.3514 3.31223Z"
                                                fill="white" />
                                            <path
                                                d="M9.04773 19.0597C8.84411 19.0597 8.64049 18.9013 8.52737 18.6525C8.41425 18.4036 8.43687 18.1774 8.66311 17.9285C9.68121 16.6841 10.6993 15.4398 11.7174 14.1955L13.2332 12.3402C13.8441 11.6163 14.4323 10.8697 15.0432 10.1457C15.2016 9.96468 15.3826 9.85156 15.5862 9.85156C15.7898 9.85156 15.9934 9.96469 16.1292 10.1683C16.3102 10.4398 16.2875 10.7113 16.0613 11.0054C15.4504 11.7746 14.817 12.5212 14.2061 13.2905L12.5093 15.3493C11.5817 16.4805 10.6541 17.6117 9.72646 18.743C9.56809 18.924 9.43234 19.0823 9.07035 19.0823L9.04773 19.0597Z"
                                                fill="white" />
                                            <path
                                                d="M15.2017 19.0597C14.0479 19.0597 13.0977 18.1095 13.0977 16.9556C13.0977 15.8018 14.0479 14.8516 15.2017 14.8516C16.3556 14.8516 17.3058 15.8018 17.3058 16.9556C17.3058 18.1095 16.3782 19.0597 15.2017 19.0597ZM15.2017 16.1864C14.7945 16.1864 14.4551 16.5484 14.4551 16.9556C14.4551 17.3629 14.8171 17.7022 15.2244 17.7022C15.428 17.7022 15.609 17.6117 15.7673 17.476C15.9031 17.3176 15.9936 17.1366 15.9936 16.933C15.971 16.5258 15.6316 16.1864 15.2017 16.1864Z"
                                                fill="white" />
                                            <path
                                                d="M9.52503 14.0359C9.27616 14.0359 9.0273 13.9907 8.7558 13.9228C8.07707 13.6287 7.64721 13.1536 7.46621 12.4975C7.30784 11.9319 7.39834 11.2984 7.7377 10.8006C8.07707 10.3029 8.59743 9.96354 9.18567 9.89567C9.29879 9.87304 9.41191 9.87305 9.50241 9.87305C10.4979 9.87305 11.3802 10.6196 11.5386 11.6151C11.7422 12.7463 10.9956 13.8323 9.88702 14.0359C9.7739 14.0359 9.66078 14.0359 9.52503 14.0359ZM9.52503 11.1853C9.32141 11.1853 9.14042 11.2758 8.98205 11.4341C8.71055 11.7056 8.68793 12.0224 8.89155 12.3617C9.04992 12.588 9.29879 12.7011 9.52503 12.7011C9.6834 12.7011 9.84177 12.6332 9.97752 12.5427C10.1359 12.407 10.249 12.226 10.2716 12.0224C10.2943 11.8187 10.2264 11.6151 10.0906 11.4568C9.90965 11.2758 9.72865 11.1853 9.52503 11.1853Z"
                                                fill="white" />
                                        </svg>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('Flash_Deals')}}</span>
                                    </a>
                                </li>
                                <li
                                    class="navbar-vertical-aside-has-menu {{(Request::is('admin/deal/day') || (Request::is('admin/deal/day-update*')))?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.deal.day')}}"
                                        title="{{\App\CPU\translate('deal_of_the_day')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M4.07219 16.8175C3.80213 16.8175 3.55284 16.8174 3.28278 16.7967C2.59725 16.7759 2.01558 16.1735 2.01558 15.5087C1.99481 14.5531 1.99481 13.5975 2.01558 12.6627V12.6211C2.01558 12.268 2.28564 12.0187 2.65957 11.9979C3.46975 11.9564 4.09296 11.437 4.21761 10.6684C4.3007 10.2114 4.19683 9.77512 3.906 9.40119C3.61517 9.02727 3.17891 8.81953 2.72189 8.77798C2.20254 8.73643 2.01558 8.52869 2.01558 8.00935V7.26149C2.01558 6.65905 2.01558 6.05661 2.01558 5.45417C2.01558 4.58167 2.59725 4 3.46975 4H12.0078H20.5458C21.4183 4 22 4.58167 22 5.45417C22 6.18125 22 6.88757 22 7.61465V8.0509C22 8.52869 21.7923 8.7572 21.3145 8.79875C20.442 8.86107 19.7564 9.54661 19.7564 10.3983C19.7564 11.2501 20.4212 11.9564 21.2937 12.0187C21.7923 12.0602 22 12.268 22 12.7666V13.1613C22 13.8883 22 14.6154 22 15.3217C22 16.215 21.4183 16.7967 20.5458 16.7967C20.2965 16.7967 20.068 16.7967 19.8187 16.7967C19.5695 16.7967 19.341 16.7967 19.0917 16.7967C18.8839 16.7967 18.7177 16.7344 18.5931 16.6097C18.4685 16.4851 18.4061 16.3397 18.4061 16.1527C18.4061 15.7788 18.697 15.5295 19.1124 15.5087C19.3202 15.5087 19.5071 15.5087 19.7149 15.5087H20.7536V14.9894C20.7536 14.4492 20.7536 13.9091 20.7536 13.369C20.7536 13.2651 20.6497 13.1613 20.5666 13.1405C19.341 12.725 18.5308 11.6448 18.5308 10.3983C18.5308 9.15191 19.3202 8.07167 20.5666 7.63542C20.6289 7.61465 20.7743 7.51078 20.7743 7.40691C20.7743 6.86679 20.7743 6.34744 20.7743 5.78654V5.28798H3.36588L3.3451 5.39185C3.3451 5.4334 3.32433 5.47495 3.32433 5.53727V6.11893C3.32433 6.51364 3.32433 6.90833 3.30356 7.28226C3.30356 7.49 3.36588 7.59387 3.55284 7.65619C4.6954 8.03012 5.48481 9.06882 5.52636 10.2737C5.5679 11.4578 4.88237 12.5588 3.78136 13.0158L3.71904 13.0366C3.30356 13.2028 3.28278 13.2236 3.28278 13.7014V15.4879H4.17606C4.40457 15.4879 4.61231 15.4879 4.84082 15.4879C4.94469 15.4879 5.04856 15.4879 5.15243 15.5087C5.46404 15.5918 5.651 15.8619 5.63022 16.1942C5.58868 16.5058 5.33939 16.7344 5.02778 16.7551C4.6954 16.7967 4.3838 16.8175 4.07219 16.8175Z"
                                                fill="white" />
                                            <path
                                                d="M9.47399 20.0377C9.10006 20.0377 8.76768 19.8922 8.5184 19.6429C8.24834 19.3729 8.10292 18.999 8.12369 18.5627C8.12369 18.355 8.14447 18.1472 8.14447 17.9395C8.14447 17.8356 8.16524 17.5656 8.16524 17.5656V17.3786C8.16524 17.1501 8.18602 16.9424 8.20679 16.7138C8.22757 16.5269 8.16524 16.3607 8.01982 16.1945C7.66667 15.779 7.33429 15.3428 7.02268 14.9688L6.91881 14.8234C6.58643 14.4079 6.46179 13.9094 6.62798 13.4524C6.7734 12.9953 7.1681 12.6629 7.68745 12.5175C7.87441 12.476 8.0406 12.4137 8.22756 12.3721C8.60149 12.2682 9.03774 12.1644 9.43245 12.0397C9.55709 11.9982 9.68173 11.8943 9.76483 11.7904C9.99334 11.4581 10.2011 11.1257 10.4088 10.7933L10.4711 10.7102C10.575 10.5648 10.6581 10.4194 10.762 10.274C11.0736 9.81693 11.5098 9.54688 11.9876 9.54688C12.4654 9.54688 12.9017 9.81693 13.2133 10.274C13.3171 10.4401 13.4418 10.6271 13.5457 10.7933C13.7534 11.1257 13.9819 11.4581 14.1897 11.7904C14.2727 11.8943 14.4182 11.9982 14.5428 12.0397C14.8752 12.1436 15.1868 12.2267 15.5192 12.3098H15.5399C15.81 12.3929 16.0801 12.4552 16.3501 12.5383C16.8279 12.6837 17.2019 13.0161 17.3473 13.4524C17.4927 13.8886 17.3888 14.3872 17.0772 14.7819C16.911 14.9896 16.7241 15.2181 16.5579 15.4259L16.5371 15.4466C16.3294 15.7167 16.1216 15.966 15.9139 16.236C15.8308 16.3399 15.7892 16.4853 15.7892 16.61C15.7892 16.9216 15.81 17.2124 15.8308 17.524C15.8516 17.8564 15.8723 18.168 15.8723 18.5004C15.8931 18.9366 15.7477 19.3521 15.4776 19.6222C15.2283 19.8715 14.896 20.0169 14.522 20.0169C14.3143 20.0169 14.1066 19.9753 13.8988 19.8922C13.6703 19.8091 13.4418 19.726 13.2133 19.6222C12.8601 19.4975 12.5277 19.3521 12.1954 19.2275C12.133 19.2067 12.0707 19.1859 11.9876 19.1859C11.9045 19.1859 11.8422 19.2067 11.7799 19.2275C11.4475 19.3521 11.0944 19.4768 10.762 19.6014C10.5127 19.7053 10.2634 19.7884 10.0349 19.8715C9.84792 19.9961 9.66095 20.0377 9.47399 20.0377ZM11.9461 10.8972C11.8422 11.0634 11.7383 11.2088 11.6345 11.375C11.3852 11.7281 11.1567 12.0605 10.9489 12.4137C10.6373 12.933 10.2218 13.2238 9.61941 13.3485C9.22471 13.4316 8.85078 13.5355 8.45607 13.6393C8.28988 13.6809 8.10292 13.7432 7.93673 13.7847L7.81208 13.8263L8.78846 15.0727C9.55709 16.0491 9.55709 16.0491 9.45322 17.2955C9.43245 17.6071 9.41167 17.898 9.41167 18.2096C9.41167 18.355 9.3909 18.7704 9.3909 18.7704L10.1388 18.5004C10.5958 18.3342 11.032 18.1888 11.4683 18.0226C11.6552 17.9603 11.8422 17.9187 12.0292 17.9187C12.2161 17.9187 12.4031 17.9603 12.5901 18.0226C13.0263 18.1888 13.4626 18.355 13.878 18.5212L14.2312 18.6666C14.2935 18.6873 14.3351 18.7081 14.3766 18.7081L14.522 18.7497V18.6458C14.522 18.4381 14.522 18.2303 14.522 18.0434C14.522 17.5863 14.522 17.1293 14.522 16.6515C14.5013 16.1945 14.6259 15.8206 14.9167 15.4882C15.2076 15.135 15.4776 14.8026 15.7685 14.4495L16.2255 13.8886L16.1008 13.8471C15.9347 13.8055 15.7685 13.7432 15.6023 13.7016L15.5192 13.6809C15.0829 13.5562 14.6674 13.4316 14.252 13.3277C13.7534 13.2031 13.3795 12.933 13.1094 12.476C12.8809 12.1021 12.6524 11.7697 12.4239 11.3957C12.32 11.2295 12.2161 11.0634 12.1123 10.9179L12.0499 10.8141L11.9461 10.8972Z"
                                                fill="white" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('deal_of_the_day')}}
                                        </span>
                                    </a>
                                </li>
                                <li
                                    class="d-none navbar-vertical-aside-has-menu {{(Request::is('admin/deal/feature') || Request::is('admin/deal/edit*'))?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.deal.feature')}}"
                                        title="{{\App\CPU\translate('Featured_Deal')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M6.85366 21.9321C6.06181 21.9321 5.24733 21.5701 4.70434 20.9593C4.31973 20.5294 3.88986 19.7602 4.02561 18.6063C4.09348 18.0181 4.16136 17.4072 4.2066 16.819C4.22923 16.5475 4.25185 16.2534 4.27448 15.9819L4.3876 14.6923C4.45547 14.0815 4.50072 13.4932 4.5686 12.8824C4.61385 12.4977 4.63647 12.1358 4.68172 11.7511C4.72697 11.276 4.77221 10.8009 4.81746 10.3258C4.84009 10.0543 4.86271 9.76019 4.88534 9.48869C4.95321 8.76471 5.02109 7.99548 5.13421 7.2715C5.26995 6.29865 6.15231 5.57467 7.17041 5.57467C7.17041 5.57467 7.66814 5.57467 7.89439 5.57467C8.14326 5.57467 8.3695 5.57467 8.61837 5.57467C8.77674 5.57467 8.82199 5.50679 8.82199 5.37105C8.93511 3.44797 10.4509 2 12.374 2H12.3966C14.2292 2.02262 15.7903 3.51585 15.8808 5.34843C15.8808 5.52942 15.9487 5.57467 16.1297 5.57467H16.1749C16.3333 5.57467 16.4917 5.57467 16.65 5.57467C16.8084 5.57467 16.9668 5.57467 17.1252 5.57467C17.3966 5.57467 17.6229 5.59728 17.8265 5.61991C18.822 5.77828 19.5233 6.54752 19.6138 7.56562C19.727 8.76471 19.8401 9.96381 19.9532 11.1855C20.0211 11.819 20.0663 12.4751 20.1342 13.1086C20.1795 13.6968 20.2473 14.2851 20.2926 14.8733C20.4057 16.095 20.5188 17.3167 20.6319 18.5385C20.6998 19.1041 20.6998 19.7149 20.4283 20.2806C19.908 21.3665 19.0482 21.9321 17.8718 21.9547C17.1252 21.9547 16.3785 21.9774 15.6093 21.9774C15.0663 21.9774 14.5007 21.9774 13.9577 21.9774C13.4147 21.9774 12.8491 21.9774 12.3062 21.9774C11.7405 21.9774 11.1976 21.9774 10.6772 21.9774C10.1342 21.9774 9.59122 21.9774 9.04823 21.9774C8.23375 21.9774 7.55502 21.9774 6.92154 22L6.85366 21.9321ZM7.23828 6.88688C6.80842 6.88688 6.5143 7.15838 6.46905 7.56562C6.40117 8.26697 6.3333 8.99095 6.26543 9.69231L6.10706 11.2986C6.06181 11.7059 6.03919 12.1131 5.99394 12.5204C5.94869 13.0633 5.88081 13.6063 5.83556 14.1493L5.67719 15.7783C5.63194 16.2986 5.56407 16.819 5.54145 17.3394C5.51882 17.543 5.4962 17.7466 5.47357 17.9729C5.42832 18.3575 5.38307 18.7647 5.38307 19.1493C5.38307 19.8959 6.01656 20.552 6.74054 20.5747C6.94416 20.5747 7.1704 20.5747 7.37402 20.5747C7.60027 20.5747 7.82651 20.5747 8.05276 20.5747C8.25638 20.5747 8.43737 20.5747 8.64099 20.5747H8.70887C8.75412 20.5747 8.82199 20.5747 8.86724 20.5747H17.736C18.2338 20.5747 18.6636 20.4163 18.9351 20.0995C19.2066 19.8054 19.3423 19.3756 19.2971 18.8778C19.2518 18.2896 19.184 17.7014 19.1387 17.1131C19.1161 16.8643 19.0935 16.6154 19.0709 16.3665L18.641 11.8643L18.5052 10.3937C18.4147 9.44345 18.3243 8.51584 18.2338 7.56562C18.1885 7.13575 17.8944 6.86425 17.4419 6.86425C17.3062 6.86425 17.1704 6.86425 17.0347 6.86425C16.8537 6.86425 16.6953 6.86425 16.5143 6.86425C16.4464 6.86425 16.4012 6.86425 16.3333 6.86425C16.2654 6.86425 16.2202 6.86425 16.1523 6.86425C16.0844 6.86425 15.9713 6.86426 15.9034 6.93214C15.8129 7.02263 15.8129 7.18099 15.8356 7.31674C15.8356 7.38461 15.8582 7.45249 15.8582 7.52037C15.8582 7.54299 15.8582 7.56561 15.8582 7.58824V7.63349C15.8582 8.04073 15.5867 8.31222 15.2021 8.31222C14.8175 8.31222 14.546 8.04073 14.5233 7.63349C14.5233 7.42987 14.5233 7.24888 14.5233 7.04526C14.5233 7.00001 14.5233 6.95475 14.4781 6.9095C14.4328 6.86425 14.3876 6.86425 14.3423 6.86425C13.6636 6.86425 12.9623 6.86425 12.2835 6.86425C11.6274 6.86425 10.2926 6.86425 10.2926 6.86425C10.2473 6.86425 10.1795 6.86425 10.1568 6.9095C10.1116 6.95475 10.1116 7.00001 10.1116 7.04526C10.1116 7.20363 10.1116 7.362 10.1116 7.52037V7.58824C10.1116 8.0181 9.86271 8.26697 9.45547 8.26697C9.41022 8.26697 9.36497 8.26697 9.31973 8.26697C8.95774 8.1991 8.77674 7.97285 8.77674 7.58824V7.4525C8.77674 7.31675 8.77674 7.20362 8.77674 7.06788C8.77674 7 8.77674 6.93213 8.73149 6.9095C8.68624 6.86425 8.61837 6.86425 8.55049 6.86425C8.3695 6.86425 8.21113 6.86425 8.05276 6.86425H7.53239C7.46452 6.88688 7.3514 6.88688 7.23828 6.88688ZM12.3514 3.31223C11.2428 3.31223 10.27 4.19458 10.1568 5.30317C10.1568 5.39367 10.1568 5.46154 10.2021 5.50679C10.2473 5.55204 10.3152 5.55204 10.3831 5.55204C10.6998 5.55204 10.9939 5.55204 11.2881 5.55204H12.3288H13.3695C13.6636 5.55204 13.9804 5.55204 14.2745 5.55204C14.365 5.55204 14.4328 5.55204 14.4781 5.50679C14.5233 5.46154 14.5233 5.4163 14.5233 5.30317C14.4328 4.17195 13.4826 3.31223 12.3514 3.31223Z"
                                                fill="white" />
                                            <path
                                                d="M9.04773 19.0597C8.84411 19.0597 8.64049 18.9013 8.52737 18.6525C8.41425 18.4036 8.43687 18.1774 8.66311 17.9285C9.68121 16.6841 10.6993 15.4398 11.7174 14.1955L13.2332 12.3402C13.8441 11.6163 14.4323 10.8697 15.0432 10.1457C15.2016 9.96468 15.3826 9.85156 15.5862 9.85156C15.7898 9.85156 15.9934 9.96469 16.1292 10.1683C16.3102 10.4398 16.2875 10.7113 16.0613 11.0054C15.4504 11.7746 14.817 12.5212 14.2061 13.2905L12.5093 15.3493C11.5817 16.4805 10.6541 17.6117 9.72646 18.743C9.56809 18.924 9.43234 19.0823 9.07035 19.0823L9.04773 19.0597Z"
                                                fill="white" />
                                            <path
                                                d="M15.2017 19.0597C14.0479 19.0597 13.0977 18.1095 13.0977 16.9556C13.0977 15.8018 14.0479 14.8516 15.2017 14.8516C16.3556 14.8516 17.3058 15.8018 17.3058 16.9556C17.3058 18.1095 16.3782 19.0597 15.2017 19.0597ZM15.2017 16.1864C14.7945 16.1864 14.4551 16.5484 14.4551 16.9556C14.4551 17.3629 14.8171 17.7022 15.2244 17.7022C15.428 17.7022 15.609 17.6117 15.7673 17.476C15.9031 17.3176 15.9936 17.1366 15.9936 16.933C15.971 16.5258 15.6316 16.1864 15.2017 16.1864Z"
                                                fill="white" />
                                            <path
                                                d="M9.52503 14.0359C9.27616 14.0359 9.0273 13.9907 8.7558 13.9228C8.07707 13.6287 7.64721 13.1536 7.46621 12.4975C7.30784 11.9319 7.39834 11.2984 7.7377 10.8006C8.07707 10.3029 8.59743 9.96354 9.18567 9.89567C9.29879 9.87304 9.41191 9.87305 9.50241 9.87305C10.4979 9.87305 11.3802 10.6196 11.5386 11.6151C11.7422 12.7463 10.9956 13.8323 9.88702 14.0359C9.7739 14.0359 9.66078 14.0359 9.52503 14.0359ZM9.52503 11.1853C9.32141 11.1853 9.14042 11.2758 8.98205 11.4341C8.71055 11.7056 8.68793 12.0224 8.89155 12.3617C9.04992 12.588 9.29879 12.7011 9.52503 12.7011C9.6834 12.7011 9.84177 12.6332 9.97752 12.5427C10.1359 12.407 10.249 12.226 10.2716 12.0224C10.2943 11.8187 10.2264 11.6151 10.0906 11.4568C9.90965 11.2758 9.72865 11.1853 9.52503 11.1853Z"
                                                fill="white" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('Special_Offer')}}
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Promotion & Discounts End -->
                        <!-- Banner -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/banner*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{route('admin.banner.list')}}"
                                title="{{\App\CPU\translate('banners')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path d="M15.7161 16.2236H8.49609" stroke="#fff" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M15.7161 12.0371H8.49609" stroke="#fff" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M11.2511 7.86035H8.49609" stroke="#fff" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M15.908 2.75C15.908 2.75 8.231 2.754 8.219 2.754C5.459 2.771 3.75 4.587 3.75 7.357V16.553C3.75 19.337 5.472 21.16 8.256 21.16C8.256 21.16 15.932 21.157 15.945 21.157C18.705 21.14 20.415 19.323 20.415 16.553V7.357C20.415 4.573 18.692 2.75 15.908 2.75Z"
                                        stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('banners')}}</span>
                            </a>
                        </li>
                        <!-- Banner End -->
                        <li
                            class="d-none navbar-vertical-aside-has-menu {{Request::is('admin/email-templates/*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:void(0)"
                                title="{{\App\CPU\translate('Marketing')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M14.6338 7.33381C13.8029 6.30965 12.9333 5.57535 12.0058 5.07294C11.4068 4.74443 10.9043 4.58984 10.4019 4.58984C10.3053 4.58984 10.2087 4.58985 10.1121 4.60917C9.53237 4.68647 9.10725 4.97632 8.85604 5.47874L8.29565 6.63816C7.50338 8.24202 6.71111 9.84588 5.91884 11.4497C5.86087 11.5464 5.78357 11.643 5.66763 11.7203C5.3971 11.9135 5.12657 12.0874 4.85604 12.2807C4.62416 12.4353 4.37295 12.6092 4.14106 12.7638C3.40676 13.2855 3.02029 13.9812 3.00097 14.8507C2.98164 15.585 3.25217 16.2613 3.75459 16.7831C4.27633 17.3048 4.95266 17.5947 5.68696 17.5947C5.70628 17.5947 5.74493 17.5947 5.76425 17.5947C6.18937 17.5753 6.57585 17.4594 6.98165 17.2275C7.00097 17.2662 7.02029 17.2855 7.03961 17.3241L7.88985 18.5802C8.46956 19.4498 9.04928 20.3 9.64831 21.1696C9.99614 21.6913 10.5372 22.0005 11.1169 22.0005C11.2908 22.0005 11.4841 21.9618 11.658 21.9039C12.1797 21.7299 12.5662 21.3435 12.7208 20.8411C12.8947 20.3386 12.8174 19.7976 12.5082 19.3338C12.0831 18.6768 11.6386 18.0391 11.2135 17.4014C11.0203 17.1116 10.8077 16.8217 10.6145 16.5319L10.1894 15.8942L11.3295 15.7976C12.7401 15.6816 14.17 15.5657 15.5807 15.4691C16.0058 15.4304 16.3343 15.2758 16.6242 14.986C16.8947 14.7154 17.0686 14.3869 17.1459 13.9425C17.2812 13.3048 17.2425 12.6092 17.0106 11.7589C16.6048 10.2324 15.8319 8.78308 14.6338 7.33381ZM10.5179 20.6285C10.0155 19.8749 9.51305 19.1406 8.99131 18.3869L7.83189 16.6865L8.04445 16.5512C8.29566 16.3773 8.54686 16.2034 8.79807 16.0488C8.83672 16.0295 8.87537 16.0101 8.91401 16.0101C8.99131 16.0101 9.04928 16.0681 9.0686 16.1068L9.84155 17.2468C10.4406 18.1164 11.0203 18.986 11.6193 19.8749C11.7739 20.0874 11.8126 20.3386 11.7159 20.5512C11.6386 20.7444 11.4647 20.899 11.2522 20.957C11.1942 20.9763 11.1556 20.9763 11.0976 20.9763C10.885 20.9763 10.6725 20.8411 10.5179 20.6285ZM4.3343 15.8555C3.81256 15.0826 3.96715 14.1551 4.70145 13.614C5.02995 13.3821 5.37778 13.1502 5.72561 12.9184L6.09276 12.6671L7.9285 15.3724C7.90918 15.3724 7.90918 15.3918 7.88985 15.3918C7.75459 15.4884 7.61933 15.585 7.48406 15.6623C7.21353 15.8555 6.8657 16.0874 6.5372 16.3C6.26667 16.4739 5.95749 16.5705 5.64831 16.5705C5.1459 16.5898 4.6628 16.3193 4.3343 15.8555ZM9.0686 14.9667C9.04928 14.9667 9.02996 14.9667 9.01063 14.9667C8.91401 14.9667 8.87537 14.9473 8.81739 14.8507C8.23768 13.9812 7.63865 13.1116 7.11691 12.3193L6.88503 11.9715C6.84638 11.9135 6.84638 11.8942 6.8657 11.8556C7.32947 10.9087 7.81256 9.9425 8.27633 8.99564L8.70145 8.1454C9.43575 10.7734 10.9237 12.957 13.1072 14.6382L11.4068 14.7734L10.7498 14.8314C10.1894 14.87 9.62899 14.9087 9.0686 14.9667ZM13.3971 13.5754C11.5807 12.0681 10.3246 10.1937 9.70629 8.01014C9.62899 7.75893 9.59034 7.50772 9.57102 7.27584C9.55169 7.17922 9.53237 7.0826 9.53237 7.02463C9.53237 6.94734 9.55169 6.88936 9.55169 6.81207V6.77342C9.55169 6.63815 9.57102 6.54153 9.59034 6.42559C9.66764 5.92318 9.97681 5.63333 10.4213 5.63333C10.5179 5.63333 10.6145 5.65265 10.7304 5.67198C11.2329 5.78792 11.6966 6.01979 12.1604 6.38694C14.0541 7.85554 15.3488 9.76859 16.0251 12.0295C16.1797 12.5705 16.2184 13.0923 16.1217 13.5947C16.0638 13.8652 15.9672 14.0778 15.8126 14.213C15.6386 14.3869 15.4454 14.4642 15.2522 14.4642C15.1942 14.4642 15.1362 14.4642 15.0783 14.4449C14.4986 14.3097 13.9575 14.0391 13.3971 13.5754Z"
                                        fill="white" />
                                    <path
                                        d="M17.862 7.60334C17.9199 7.58402 17.9972 7.54537 18.0552 7.50672C18.8861 6.96566 19.717 6.42459 20.5479 5.86421C20.7025 5.74827 20.8185 5.61299 20.8378 5.4584C20.8571 5.32314 20.8378 5.2072 20.7605 5.07193C20.6639 4.93666 20.5286 4.85938 20.3547 4.85938C20.2388 4.85938 20.1035 4.89802 19.9876 4.97531L18.2098 6.13473C17.9393 6.30865 17.688 6.48256 17.4368 6.65647C17.205 6.81106 17.1277 7.04295 17.2243 7.27483C17.3016 7.50672 17.4948 7.62266 17.7847 7.62266H17.8233L17.862 7.60334Z"
                                        fill="white" />
                                    <path
                                        d="M21.4549 9.67192C21.1651 9.61395 20.8752 9.5753 20.566 9.51733L18.4018 9.13086H18.3631C18.0733 9.13086 17.8607 9.30477 17.8028 9.55598C17.7641 9.80719 17.9187 10.0584 18.1892 10.1357C18.2665 10.155 18.3245 10.1743 18.4018 10.1743L19.368 10.3482C19.9863 10.4642 20.624 10.5801 21.2424 10.6961C21.281 10.6961 21.3197 10.7154 21.3583 10.7154C21.6482 10.7154 21.8607 10.5415 21.8994 10.271C21.9767 9.96178 21.7834 9.72989 21.4549 9.67192Z"
                                        fill="white" />
                                    <path
                                        d="M15.7342 5.88405C15.7536 5.88405 15.7729 5.88405 15.7922 5.88405C16.0627 5.88405 16.2753 5.69081 16.2946 5.42028C16.3139 5.28501 16.3333 5.14975 16.3333 5.01449L16.5845 2.54106C16.5845 2.25121 16.4106 2.03865 16.1207 2C16.1014 2 16.082 2 16.0627 2C15.7922 2 15.5796 2.19323 15.5603 2.46376C15.4637 3.41062 15.3671 4.3768 15.2705 5.32366C15.2511 5.47825 15.2898 5.61352 15.3864 5.71014C15.483 5.80675 15.599 5.88405 15.7342 5.88405Z"
                                        fill="white" />
                                    <path
                                        d="M12.0234 8.31836C11.6949 8.31836 11.2891 8.47295 10.9413 8.70483C10.7094 8.85942 10.6514 9.16861 10.7867 9.40049C10.8833 9.55508 11.0379 9.6517 11.2312 9.6517C11.3085 9.6517 11.4051 9.63238 11.4824 9.59373C11.5017 9.57441 11.5403 9.55508 11.579 9.53576C11.5983 9.51644 11.6176 9.49711 11.6563 9.49711C11.8495 9.40049 12.0428 9.36184 12.2167 9.36184C12.4485 9.36184 12.6611 9.43913 12.8737 9.5744C13.2601 9.84493 13.4147 10.2121 13.3568 10.6952C13.3181 11.0237 13.1442 11.2942 12.8543 11.5068C12.6418 11.6614 12.5838 11.9319 12.6998 12.1638C12.7964 12.3377 12.9703 12.4343 13.1442 12.4343C13.2022 12.4343 13.2408 12.4343 13.2988 12.415C13.3954 12.3763 13.492 12.3184 13.5693 12.2604C14.3036 11.6807 14.5935 10.6952 14.2843 9.82562C13.9558 8.87876 13.0862 8.31836 12.0234 8.31836Z"
                                        fill="white" />
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Marketing')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/order*')?'block':'none'}}">

                                <li
                                    class="navbar-vertical-aside-has-menu {{Request::is('admin/email-templates/*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        title="{{\App\CPU\translate('Dashboard')}}"
                                        href="{{route('admin.email-templates.list')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M11.3752 21.9937C9.98523 21.9937 8.59522 22.0033 7.20522 21.9937C6.28522 21.9842 5.37523 21.8789 4.49523 21.5728C3.14523 21.104 2.36523 20.1761 2.12523 18.8368C2.05523 18.4446 2.00522 18.0428 2.00522 17.641C1.99522 16.5505 1.99523 15.4599 2.04523 14.3693C2.08523 13.5083 2.37523 12.7143 3.12523 12.1499C3.31523 12.0064 3.52523 11.8821 3.74523 11.7864C3.89523 11.7194 3.93523 11.6429 3.93523 11.4898C3.93523 9.56699 3.92523 7.65372 3.94523 5.73088C3.94523 5.31953 3.99523 4.89861 4.07523 4.49682C4.30523 3.32016 5.06523 2.60268 6.24523 2.26786C6.88523 2.0861 7.53522 2 8.20522 2C10.3852 2 12.5652 2 14.7452 2C15.4152 2 16.0752 2.0861 16.7252 2.26786C18.0852 2.66965 18.8452 3.57845 18.9552 4.94645C18.9952 5.45346 19.0152 5.96048 19.0152 6.4675C19.0252 8.14161 19.0152 9.81573 19.0152 11.4803C19.0152 11.5855 18.9952 11.6812 19.1452 11.7481C20.1552 12.1691 20.7252 12.9344 20.8552 13.9484C21.0452 15.4025 21.0952 16.8661 20.7952 18.3107C20.3652 20.377 18.9652 21.6015 16.7852 21.8885C16.2852 21.9555 15.7752 21.9841 15.2652 21.9841C13.9652 22.0033 12.6652 22.0033 11.3752 21.9937ZM11.3352 20.597V20.6066C12.3752 20.6066 13.4152 20.6162 14.4552 20.6066C15.0952 20.597 15.7352 20.6066 16.3652 20.5492C18.0552 20.4057 19.0652 19.5543 19.3652 17.9663C19.5952 16.7514 19.5552 15.5173 19.4152 14.2928C19.3152 13.4223 18.8752 12.9918 17.9652 12.8483C17.3052 12.743 16.6352 12.7239 15.9752 12.7048C15.5952 12.6952 15.2652 12.8865 14.9952 13.1544C14.7152 13.4318 14.4452 13.7188 14.1652 13.9962C12.6652 15.5173 10.2852 15.5268 8.77523 14.0058C8.49523 13.7188 8.21523 13.4318 7.93523 13.1448C7.64523 12.8578 7.28522 12.6761 6.86522 12.6952C6.31522 12.7048 5.75522 12.7239 5.20522 12.7909C4.06522 12.9344 3.53523 13.4318 3.49523 14.532C3.44523 15.7756 3.44523 17.0192 3.51523 18.2628C3.57523 19.5065 4.21523 20.1666 5.49523 20.4248C5.96523 20.5205 6.45523 20.5875 6.93523 20.597C8.40523 20.6066 9.87523 20.597 11.3352 20.597ZM5.40523 11.2985C5.89523 11.2985 6.34523 11.2985 6.80523 11.2985C7.67523 11.2985 8.41523 11.6046 9.02523 12.2073C9.29523 12.4847 9.56523 12.7622 9.83523 13.03C10.7652 13.9484 12.1952 13.9484 13.1252 13.03C13.3852 12.7717 13.6352 12.5134 13.8952 12.2552C14.5352 11.5951 15.3252 11.2794 16.2752 11.2985C16.6952 11.3081 17.1152 11.2985 17.5552 11.2985C17.5552 11.289 17.5652 11.2411 17.5652 11.1933C17.5652 9.35653 17.5652 7.51023 17.5552 5.67348C17.5552 5.36736 17.5152 5.06124 17.4652 4.76468C17.3652 4.2194 17.0452 3.81761 16.4852 3.65498C16.0352 3.52105 15.5552 3.41582 15.0852 3.40626C12.6952 3.37756 10.2952 3.37756 7.90523 3.40626C7.43523 3.40626 6.95522 3.53062 6.50522 3.65498C5.97522 3.79847 5.65522 4.18113 5.53522 4.69771C5.47522 4.956 5.43523 5.23343 5.43523 5.49172C5.42523 7.39543 5.42522 9.29914 5.42522 11.2028C5.40522 11.2507 5.40523 11.2985 5.40523 11.2985Z"
                                                fill="white" />
                                            <path
                                                d="M11.6964 7.31857C10.8964 7.31857 10.0864 7.31857 9.28637 7.31857C8.88637 7.31857 8.59638 7.06985 8.54638 6.70632C8.49638 6.36194 8.70638 6.04625 9.05638 5.95059C9.14638 5.92189 9.24637 5.92188 9.34637 5.92188C10.9064 5.92188 12.4764 5.92188 14.0364 5.92188C14.5264 5.92188 14.8464 6.1993 14.8564 6.61066C14.8564 7.03158 14.5264 7.30901 14.0264 7.31857C13.2464 7.31857 12.4764 7.31857 11.6964 7.31857Z"
                                                fill="white" />
                                            <path
                                                d="M11.694 8.71462C12.214 8.71462 12.744 8.71462 13.264 8.71462C13.714 8.71462 14.034 9.01118 14.034 9.4034C14.034 9.80519 13.714 10.1018 13.274 10.1018C12.214 10.1018 11.164 10.1018 10.104 10.1018C9.66398 10.1018 9.33397 9.78608 9.34397 9.39386C9.35397 9.00164 9.66398 8.71464 10.104 8.70508C10.624 8.71464 11.154 8.71462 11.694 8.71462Z"
                                                fill="white" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('Email_Templates')}}
                                        </span>
                                    </a>
                                </li>

                                <li
                                    class="navbar-vertical-aside-has-menu {{Request::is('admin/sms-templates/*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        title="{{\App\CPU\translate('Dashboard')}}"
                                        href="{{route('admin.sms-templates.list')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M10.5808 2.00926C11.66 2.00926 12.7478 2.00926 13.827 2.00926C14.1754 2.00926 14.4474 2.21269 14.5323 2.51783C14.6173 2.81449 14.5069 3.13657 14.2264 3.28914C14.0905 3.35694 13.9205 3.39933 13.7675 3.39933C11.8895 3.4078 10.0029 3.39086 8.1249 3.41628C7.52154 3.42476 6.90119 3.50103 6.31483 3.64513C4.74271 4.03503 3.85043 5.10302 3.5445 6.66261C3.45952 7.10336 3.40853 7.55258 3.40004 8.00181C3.38304 9.86654 3.38304 11.7228 3.40853 13.5875C3.41703 14.1724 3.5105 14.7742 3.64647 15.3506C3.9439 16.6559 4.8022 17.4272 6.11938 17.6136C6.90969 17.7238 7.70849 17.7662 8.5073 17.7747C9.22113 17.7832 9.765 18.0375 10.1814 18.6138C10.5893 19.1817 11.0227 19.7411 11.4476 20.3006C11.7535 20.7074 12.2379 20.7159 12.5438 20.309C12.9772 19.7327 13.4276 19.1563 13.844 18.5714C14.2349 18.029 14.7533 17.7662 15.4246 17.7832C16.164 17.8001 16.9033 17.7577 17.6256 17.5628C19.2147 17.1475 20.141 16.0964 20.4384 14.5114C20.5404 13.9605 20.5659 13.3926 20.5829 12.8247C20.6084 11.9516 20.5829 11.0701 20.5914 10.1971C20.5914 9.71397 20.9228 9.40037 21.3732 9.44275C21.6706 9.46818 21.9681 9.71397 21.9681 10.0106C21.9511 11.6635 22.138 13.3332 21.7641 14.9691C21.2372 17.2492 19.7841 18.5799 17.4981 18.9952C16.8353 19.1139 16.1555 19.1309 15.4841 19.1563C15.2377 19.1648 15.0677 19.2241 14.9233 19.436C14.4984 20.0293 14.0565 20.5972 13.6231 21.1736C12.7903 22.2755 11.2012 22.2755 10.3684 21.1736C9.91796 20.5803 9.46757 19.9785 9.02568 19.3851C8.9152 19.2326 8.77923 19.1648 8.58378 19.1648C7.55553 19.1648 6.53578 19.1309 5.53302 18.885C3.81643 18.4612 2.77119 17.3848 2.3123 15.6981C2.10835 14.9268 2.00637 14.1385 2.00637 13.3332C1.99788 11.5363 1.99788 9.73939 2.00637 7.94247C2.01487 6.9084 2.19332 5.90824 2.63521 4.9674C3.35754 3.44171 4.60675 2.58563 6.22986 2.21269C6.93519 2.05164 7.649 1.99231 8.37133 2.00079C9.11065 2.01774 9.84148 2.00926 10.5808 2.00926Z"
                                                fill="white" />
                                            <path
                                                d="M21.9867 5.02594C21.9867 6.68724 20.644 8.03494 18.9699 8.03494C17.3213 8.04341 15.9616 6.68724 15.9531 5.03441C15.9531 3.34768 17.2958 2 18.9699 2C20.644 2.01695 21.9867 3.35616 21.9867 5.02594ZM20.593 5.01747C20.5845 4.11901 19.8622 3.39854 18.9699 3.40701C18.0606 3.40701 17.3468 4.13596 17.3468 5.0429C17.3468 5.93288 18.0776 6.65335 18.9699 6.66182C19.8707 6.65335 20.6015 5.9244 20.593 5.01747Z"
                                                fill="white" />
                                            <path
                                                d="M15.7245 10.1309C16.2344 10.1309 16.6508 10.5462 16.6508 11.0547C16.6508 11.5718 16.2259 11.9871 15.699 11.9786C15.1892 11.9702 14.7812 11.5633 14.7812 11.0463C14.7897 10.5292 15.2061 10.1309 15.7245 10.1309Z"
                                                fill="white" />
                                            <path
                                                d="M8.27796 11.9786C7.76808 11.9786 7.34319 11.5633 7.35169 11.0547C7.36019 10.5377 7.76809 10.1309 8.28647 10.1309C8.80484 10.1309 9.21274 10.5462 9.21274 11.0547C9.21274 11.5633 8.79634 11.9786 8.27796 11.9786Z"
                                                fill="white" />
                                            <path
                                                d="M12.9149 11.0866C12.9064 11.5951 12.4815 11.985 11.9631 11.9765C11.4532 11.9681 11.0538 11.5443 11.0708 11.0272C11.0878 10.5102 11.5042 10.1203 12.0226 10.1373C12.5325 10.1457 12.9234 10.561 12.9149 11.0866Z"
                                                fill="white" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('SMS_Templates')}}
                                        </span>
                                    </a>
                                </li>
                                <!-- Notification -->
                                <li
                                    class="navbar-vertical-aside-has-menu {{Request::is('admin/notification*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.notification.add-new')}}"
                                        title="{{\App\CPU\translate('Push_Notification')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M12 17.8476C17.6392 17.8476 20.2481 17.1242 20.5 14.2205C20.5 11.3188 18.6812 11.5054 18.6812 7.94511C18.6812 5.16414 16.0452 2 12 2C7.95477 2 5.31885 5.16414 5.31885 7.94511C5.31885 11.5054 3.5 11.3188 3.5 14.2205C3.75295 17.1352 6.36177 17.8476 12 17.8476Z"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M14.3889 20.8574C13.0247 22.3721 10.8967 22.3901 9.51953 20.8574"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('Push_Notification')}}
                                        </span>
                                    </a>
                                </li> <span class="tio-circle nav-indicator-icon"></span>
                                <!-- Notification End -->

                                <!-- Announcement -->
                                <li
                                    class="d-none navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/announcement')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.business-settings.announcement')}}"
                                        title="{{\App\CPU\translate('announcement')}}">
                                        <i class="tio-mic-outlined nav-icon"></i>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('announcement')}}
                                        </span>
                                    </a>
                                </li>
                                <!-- Announcement End -->

                            </ul>
                        </li>

                        <li
                            class="nav-item{{(Request::is('admin/area*')) ? 'scroll-here':''}}">
                            <small class="nav-subtitle" title="">{{\App\CPU\translate('city_area_management')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{(Request::is('admin/area*'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:"
                                title="{{\App\CPU\translate('City_Area_Management')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.79476 7.05589C4.79476 5.80689 5.80676 4.79489 7.05576 4.79389H8.08476C8.68176 4.79389 9.25376 4.55689 9.67776 4.13689L10.3968 3.41689C11.2778 2.53089 12.7098 2.52689 13.5958 3.40789L13.5968 3.40889L13.6058 3.41689L14.3258 4.13689C14.7498 4.55789 15.3218 4.79389 15.9188 4.79389H16.9468C18.1958 4.79389 19.2088 5.80589 19.2088 7.05589V8.08289C19.2088 8.67989 19.4448 9.25289 19.8658 9.67689L20.5858 10.3969C21.4718 11.2779 21.4768 12.7099 20.5958 13.5959L20.5948 13.5969L20.5858 13.6059L19.8658 14.3259C19.4448 14.7489 19.2088 15.3209 19.2088 15.9179V16.9469C19.2088 18.1959 18.1968 19.2079 16.9478 19.2079H15.9168C15.3198 19.2079 14.7468 19.4449 14.3238 19.8659L13.6038 20.5849C12.7238 21.4709 11.2928 21.4759 10.4068 20.5969C10.4058 20.5959 10.4048 20.5949 10.4038 20.5939L10.3948 20.5849L9.67576 19.8659C9.25276 19.4449 8.67976 19.2089 8.08276 19.2079H7.05576C5.80676 19.2079 4.79476 18.1959 4.79476 16.9469V15.9159C4.79476 15.3189 4.55776 14.7469 4.13676 14.3239L3.41776 13.6039C2.53176 12.7239 2.52676 11.2929 3.40676 10.4069C3.40676 10.4059 3.40776 10.4049 3.40876 10.4039L3.41776 10.3949L4.13676 9.67489C4.55776 9.25089 4.79476 8.67889 4.79476 8.08089V7.05589Z"
                                        stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M9.42969 14.5714L14.5697 9.4314" stroke="#fff" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M14.4961 14.4998H14.5051" stroke="#fff" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9.49609 9.49976H9.50509" stroke="#fff" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('City_Area_Management')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{(Request::is('admin/area*'))?'block':'none'}}">
                                <li class="navbar-vertical-aside-has-menu {{Request::is('admin/area/city*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.area.city') }}"
                                        title="{{\App\CPU\translate('coupon')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M21.326 14.3625C20.306 14.3261 19.5046 13.5064 19.5046 12.5046C19.5046 11.4845 20.306 10.6648 21.326 10.6284C21.7632 10.6102 22 10.3734 22 9.93625C22 8.93443 22 7.93261 22 6.93079C22 6.76686 21.9818 6.58471 21.9454 6.42077C21.7268 5.56467 20.9982 5 20.0874 5C14.6958 5 9.3224 5 3.93078 5C3.89435 5 3.85792 5 3.83971 5C3.03825 5.01821 2.32787 5.58288 2.09107 6.34791C2.07286 6.40255 2.09108 6.47542 2.01822 6.51185C2.01822 7.71403 2.01822 8.93443 2.01822 10.1366C2.10929 10.4827 2.30965 10.6466 2.67395 10.6466C2.69217 10.6466 2.7286 10.6466 2.74681 10.6466C3.40255 10.7013 3.91257 11.0109 4.22222 11.5756C4.89617 12.796 4.05829 14.2168 2.65574 14.2896C2.29144 14.3078 2.09107 14.4718 2 14.7996C2 16.0018 2 17.1858 2 18.388C2.05464 18.4062 2.03643 18.4426 2.03643 18.4791C2.27322 19.3898 2.98361 19.9362 3.93078 19.9362C9.30419 19.9362 14.6776 19.9362 20.051 19.9362C21.1621 19.9362 21.9818 19.1166 21.9818 18.0055C21.9818 17.0036 21.9818 16.02 21.9818 15.0182C22.0182 14.6175 21.7814 14.3807 21.326 14.3625ZM20.7614 15.8197C20.7432 16.5665 20.7614 17.2951 20.7614 18.0419C20.7614 18.4791 20.5064 18.7341 20.0692 18.7341C17.3916 18.7341 14.6958 18.7341 12.0182 18.7341C9.35883 18.7341 6.68124 18.7341 4.02186 18.7341C3.53005 18.7341 3.29326 18.4973 3.29326 17.9873C3.29326 17.2404 3.29326 16.4754 3.29326 15.7286C3.29326 15.6011 3.31148 15.5464 3.4572 15.51C4.85975 15.1275 5.78871 13.9071 5.78871 12.4863C5.78871 11.0656 4.84153 9.84517 3.4572 9.46266C3.32969 9.42623 3.29326 9.37159 3.29326 9.24409C3.29326 8.47906 3.29326 7.69582 3.29326 6.93079C3.29326 6.49363 3.54827 6.23862 3.98543 6.23862C9.35883 6.23862 14.714 6.23862 20.0874 6.23862C20.5428 6.23862 20.7796 6.49363 20.7796 6.949C20.7796 7.71403 20.7796 8.47906 20.7796 9.24409C20.7796 9.37159 20.7614 9.42623 20.6157 9.46266C19.3042 9.80874 18.3752 10.9381 18.2842 12.2678C18.1931 13.5975 18.9581 14.8361 20.2149 15.3643C20.3242 15.4007 20.4153 15.4554 20.5246 15.4736C20.725 15.51 20.7796 15.6193 20.7614 15.8197Z"
                                                fill="white" />
                                            <path
                                                d="M14.4599 7.7499C14.4417 7.78633 14.4235 7.84098 14.4052 7.87741C13.2031 11.1015 12.0009 14.3073 10.7987 17.5313C10.744 17.6588 10.7076 17.7135 10.5619 17.6406C10.2887 17.5131 9.99722 17.422 9.70578 17.3127C9.57828 17.2763 9.57828 17.2217 9.61471 17.1124C10.1612 15.6552 10.7076 14.198 11.2541 12.7226C11.9098 10.9739 12.5655 9.22531 13.2213 7.45846C13.2759 7.31274 13.3306 7.2581 13.4945 7.33096C13.7677 7.45847 14.0592 7.54954 14.3506 7.65883C14.387 7.67704 14.4599 7.67704 14.4599 7.7499Z"
                                                fill="white" />
                                            <path
                                                d="M15.5145 12.832C14.4762 12.832 13.6383 13.6699 13.6565 14.7082C13.6565 15.7464 14.4944 16.5843 15.5327 16.5843C16.5527 16.5843 17.4088 15.7282 17.4088 14.7082C17.3906 13.6699 16.5527 12.832 15.5145 12.832ZM15.5145 15.3275C15.1684 15.3275 14.8952 15.036 14.8952 14.69C14.8952 14.3439 15.1684 14.0707 15.5145 14.0889C15.8605 14.0889 16.1338 14.3803 16.1338 14.7082C16.1338 15.036 15.8605 15.3275 15.5145 15.3275Z"
                                                fill="white" />
                                            <path
                                                d="M9.06754 8.95312C8.04751 8.95312 7.19141 9.80922 7.19141 10.8293C7.19141 11.8675 8.02929 12.6872 9.06754 12.6872C10.1058 12.6872 10.9255 11.8493 10.9255 10.8111C10.9255 9.79101 10.0876 8.95312 9.06754 8.95312ZM9.06754 11.4486C8.72146 11.4486 8.43002 11.1571 8.43002 10.8293C8.43002 10.4832 8.72146 10.21 9.04933 10.21C9.39541 10.21 9.66864 10.4832 9.66864 10.8293C9.68685 11.1753 9.41363 11.4486 9.06754 11.4486Z"
                                                fill="white" />
                                        </svg>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('City')}}</span>
                                    </a>
                                </li>
                                <li
                                    class="navbar-vertical-aside-has-menu {{(Request::is('admin/area/view*'))?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.area.view') }}"
                                        title="{{\App\CPU\translate('Flash_Deals')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M6.85366 21.9321C6.06181 21.9321 5.24733 21.5701 4.70434 20.9593C4.31973 20.5294 3.88986 19.7602 4.02561 18.6063C4.09348 18.0181 4.16136 17.4072 4.2066 16.819C4.22923 16.5475 4.25185 16.2534 4.27448 15.9819L4.3876 14.6923C4.45547 14.0815 4.50072 13.4932 4.5686 12.8824C4.61385 12.4977 4.63647 12.1358 4.68172 11.7511C4.72697 11.276 4.77221 10.8009 4.81746 10.3258C4.84009 10.0543 4.86271 9.76019 4.88534 9.48869C4.95321 8.76471 5.02109 7.99548 5.13421 7.2715C5.26995 6.29865 6.15231 5.57467 7.17041 5.57467C7.17041 5.57467 7.66814 5.57467 7.89439 5.57467C8.14326 5.57467 8.3695 5.57467 8.61837 5.57467C8.77674 5.57467 8.82199 5.50679 8.82199 5.37105C8.93511 3.44797 10.4509 2 12.374 2H12.3966C14.2292 2.02262 15.7903 3.51585 15.8808 5.34843C15.8808 5.52942 15.9487 5.57467 16.1297 5.57467H16.1749C16.3333 5.57467 16.4917 5.57467 16.65 5.57467C16.8084 5.57467 16.9668 5.57467 17.1252 5.57467C17.3966 5.57467 17.6229 5.59728 17.8265 5.61991C18.822 5.77828 19.5233 6.54752 19.6138 7.56562C19.727 8.76471 19.8401 9.96381 19.9532 11.1855C20.0211 11.819 20.0663 12.4751 20.1342 13.1086C20.1795 13.6968 20.2473 14.2851 20.2926 14.8733C20.4057 16.095 20.5188 17.3167 20.6319 18.5385C20.6998 19.1041 20.6998 19.7149 20.4283 20.2806C19.908 21.3665 19.0482 21.9321 17.8718 21.9547C17.1252 21.9547 16.3785 21.9774 15.6093 21.9774C15.0663 21.9774 14.5007 21.9774 13.9577 21.9774C13.4147 21.9774 12.8491 21.9774 12.3062 21.9774C11.7405 21.9774 11.1976 21.9774 10.6772 21.9774C10.1342 21.9774 9.59122 21.9774 9.04823 21.9774C8.23375 21.9774 7.55502 21.9774 6.92154 22L6.85366 21.9321ZM7.23828 6.88688C6.80842 6.88688 6.5143 7.15838 6.46905 7.56562C6.40117 8.26697 6.3333 8.99095 6.26543 9.69231L6.10706 11.2986C6.06181 11.7059 6.03919 12.1131 5.99394 12.5204C5.94869 13.0633 5.88081 13.6063 5.83556 14.1493L5.67719 15.7783C5.63194 16.2986 5.56407 16.819 5.54145 17.3394C5.51882 17.543 5.4962 17.7466 5.47357 17.9729C5.42832 18.3575 5.38307 18.7647 5.38307 19.1493C5.38307 19.8959 6.01656 20.552 6.74054 20.5747C6.94416 20.5747 7.1704 20.5747 7.37402 20.5747C7.60027 20.5747 7.82651 20.5747 8.05276 20.5747C8.25638 20.5747 8.43737 20.5747 8.64099 20.5747H8.70887C8.75412 20.5747 8.82199 20.5747 8.86724 20.5747H17.736C18.2338 20.5747 18.6636 20.4163 18.9351 20.0995C19.2066 19.8054 19.3423 19.3756 19.2971 18.8778C19.2518 18.2896 19.184 17.7014 19.1387 17.1131C19.1161 16.8643 19.0935 16.6154 19.0709 16.3665L18.641 11.8643L18.5052 10.3937C18.4147 9.44345 18.3243 8.51584 18.2338 7.56562C18.1885 7.13575 17.8944 6.86425 17.4419 6.86425C17.3062 6.86425 17.1704 6.86425 17.0347 6.86425C16.8537 6.86425 16.6953 6.86425 16.5143 6.86425C16.4464 6.86425 16.4012 6.86425 16.3333 6.86425C16.2654 6.86425 16.2202 6.86425 16.1523 6.86425C16.0844 6.86425 15.9713 6.86426 15.9034 6.93214C15.8129 7.02263 15.8129 7.18099 15.8356 7.31674C15.8356 7.38461 15.8582 7.45249 15.8582 7.52037C15.8582 7.54299 15.8582 7.56561 15.8582 7.58824V7.63349C15.8582 8.04073 15.5867 8.31222 15.2021 8.31222C14.8175 8.31222 14.546 8.04073 14.5233 7.63349C14.5233 7.42987 14.5233 7.24888 14.5233 7.04526C14.5233 7.00001 14.5233 6.95475 14.4781 6.9095C14.4328 6.86425 14.3876 6.86425 14.3423 6.86425C13.6636 6.86425 12.9623 6.86425 12.2835 6.86425C11.6274 6.86425 10.2926 6.86425 10.2926 6.86425C10.2473 6.86425 10.1795 6.86425 10.1568 6.9095C10.1116 6.95475 10.1116 7.00001 10.1116 7.04526C10.1116 7.20363 10.1116 7.362 10.1116 7.52037V7.58824C10.1116 8.0181 9.86271 8.26697 9.45547 8.26697C9.41022 8.26697 9.36497 8.26697 9.31973 8.26697C8.95774 8.1991 8.77674 7.97285 8.77674 7.58824V7.4525C8.77674 7.31675 8.77674 7.20362 8.77674 7.06788C8.77674 7 8.77674 6.93213 8.73149 6.9095C8.68624 6.86425 8.61837 6.86425 8.55049 6.86425C8.3695 6.86425 8.21113 6.86425 8.05276 6.86425H7.53239C7.46452 6.88688 7.3514 6.88688 7.23828 6.88688ZM12.3514 3.31223C11.2428 3.31223 10.27 4.19458 10.1568 5.30317C10.1568 5.39367 10.1568 5.46154 10.2021 5.50679C10.2473 5.55204 10.3152 5.55204 10.3831 5.55204C10.6998 5.55204 10.9939 5.55204 11.2881 5.55204H12.3288H13.3695C13.6636 5.55204 13.9804 5.55204 14.2745 5.55204C14.365 5.55204 14.4328 5.55204 14.4781 5.50679C14.5233 5.46154 14.5233 5.4163 14.5233 5.30317C14.4328 4.17195 13.4826 3.31223 12.3514 3.31223Z"
                                                fill="white" />
                                            <path
                                                d="M9.04773 19.0597C8.84411 19.0597 8.64049 18.9013 8.52737 18.6525C8.41425 18.4036 8.43687 18.1774 8.66311 17.9285C9.68121 16.6841 10.6993 15.4398 11.7174 14.1955L13.2332 12.3402C13.8441 11.6163 14.4323 10.8697 15.0432 10.1457C15.2016 9.96468 15.3826 9.85156 15.5862 9.85156C15.7898 9.85156 15.9934 9.96469 16.1292 10.1683C16.3102 10.4398 16.2875 10.7113 16.0613 11.0054C15.4504 11.7746 14.817 12.5212 14.2061 13.2905L12.5093 15.3493C11.5817 16.4805 10.6541 17.6117 9.72646 18.743C9.56809 18.924 9.43234 19.0823 9.07035 19.0823L9.04773 19.0597Z"
                                                fill="white" />
                                            <path
                                                d="M15.2017 19.0597C14.0479 19.0597 13.0977 18.1095 13.0977 16.9556C13.0977 15.8018 14.0479 14.8516 15.2017 14.8516C16.3556 14.8516 17.3058 15.8018 17.3058 16.9556C17.3058 18.1095 16.3782 19.0597 15.2017 19.0597ZM15.2017 16.1864C14.7945 16.1864 14.4551 16.5484 14.4551 16.9556C14.4551 17.3629 14.8171 17.7022 15.2244 17.7022C15.428 17.7022 15.609 17.6117 15.7673 17.476C15.9031 17.3176 15.9936 17.1366 15.9936 16.933C15.971 16.5258 15.6316 16.1864 15.2017 16.1864Z"
                                                fill="white" />
                                            <path
                                                d="M9.52503 14.0359C9.27616 14.0359 9.0273 13.9907 8.7558 13.9228C8.07707 13.6287 7.64721 13.1536 7.46621 12.4975C7.30784 11.9319 7.39834 11.2984 7.7377 10.8006C8.07707 10.3029 8.59743 9.96354 9.18567 9.89567C9.29879 9.87304 9.41191 9.87305 9.50241 9.87305C10.4979 9.87305 11.3802 10.6196 11.5386 11.6151C11.7422 12.7463 10.9956 13.8323 9.88702 14.0359C9.7739 14.0359 9.66078 14.0359 9.52503 14.0359ZM9.52503 11.1853C9.32141 11.1853 9.14042 11.2758 8.98205 11.4341C8.71055 11.7056 8.68793 12.0224 8.89155 12.3617C9.04992 12.588 9.29879 12.7011 9.52503 12.7011C9.6834 12.7011 9.84177 12.6332 9.97752 12.5427C10.1359 12.407 10.249 12.226 10.2716 12.0224C10.2943 11.8187 10.2264 11.6151 10.0906 11.4568C9.90965 11.2758 9.72865 11.1853 9.52503 11.1853Z"
                                                fill="white" />
                                        </svg>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('Area')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/area/bulk-import') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.area.bulk-import') }}"
                                        title="{{ \App\CPU\translate('area_bulk_import') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M14.4869 3.01175H7.83489C5.75489 3.00378 4.05089 4.66078 4.00089 6.74078V17.4778C3.95589 19.5798 5.62389 21.3198 7.72489 21.3648C7.76189 21.3648 7.79889 21.3658 7.83489 21.3648H15.8229C17.9129 21.2908 19.5649 19.5688 19.553 17.4778V8.28778L14.4869 3.01175Z"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M14.2266 3V5.909C14.2266 7.329 15.3756 8.48 16.7956 8.484H19.5496"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M11.3906 10.1582V16.1992" stroke="white" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M13.7369 12.5152L11.3919 10.1602L9.04688 12.5152" stroke="white"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span class="text-truncate">{{ \App\CPU\translate('Upload_areas') }}</span>
                                    </a>
                                </li>

                            </ul>
                        </li>        


                        <!-- Plan -->
                        <li class="d-none navbar-vertical-aside-has-menu {{Request::is('admin/plan*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:"
                                title="{{\App\CPU\translate('Membership')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M5.76828 19.0051C5.33127 19.0051 5.10236 18.8386 4.9775 18.4016L4.20754 15.842C3.5 13.5321 2.77165 11.1181 2.04331 8.76659C1.96007 8.51687 2.00169 8.24635 2.12655 8.05906C2.2306 7.89258 2.37626 7.80934 2.56355 7.78853C2.58436 7.78853 2.62598 7.78853 2.6676 7.78853C2.81327 7.78853 2.97975 7.83015 3.12542 7.89258C4.1243 8.37121 5.12317 8.84983 6.10124 9.32846L7.12092 9.8279C7.16254 9.84871 7.41226 9.97357 7.41226 9.97357L7.47469 9.86952C7.4955 9.84871 7.51631 9.80709 7.51631 9.78628C8.66085 8.47525 11.4078 5.29134 11.4078 5.29134C11.5742 5.10405 11.7823 5 11.9904 5C12.1985 5 12.4066 5.10405 12.5731 5.31215L16.4438 9.78628C16.4646 9.80709 16.4854 9.84871 16.527 9.86952L16.6102 9.95275L16.9016 9.80708L18.0461 9.24522C18.9826 8.78741 19.919 8.32958 20.8555 7.87176C20.9803 7.80933 21.126 7.74691 21.2925 7.74691C21.3549 7.74691 21.3965 7.7469 21.4589 7.76771C21.7087 7.83014 21.8751 7.99663 22 8.30878V8.60011L21.7711 9.32846C21.6046 9.86951 21.4381 10.4106 21.2717 10.9724C20.793 12.554 20.3144 14.1355 19.8358 15.7171L19.0242 18.36C18.8993 18.797 18.6704 18.9634 18.2126 18.9634H12.0112L5.76828 19.0051ZM11.0956 17.6108C13.2182 17.6108 15.3408 17.6108 17.4634 17.6108C17.6507 17.6108 17.7548 17.5484 17.8172 17.3611C18.3583 15.5714 18.8993 13.7818 19.4404 11.9921L19.9814 10.2233C20.0023 10.1817 20.0023 10.14 20.0231 10.0984L20.0855 9.8487L19.6693 10.0568L16.8391 11.4303C16.6519 11.5135 16.5062 11.5551 16.3813 11.5551C16.1732 11.5551 15.9859 11.4511 15.7986 11.2222L12.3442 7.22666C12.2818 7.16423 12.2193 7.08099 12.1569 7.01856L11.9904 6.83127L11.8864 6.93532C11.8656 6.95613 11.8448 6.97694 11.824 7.01856L8.16142 11.243C7.97413 11.4719 7.78684 11.5759 7.57874 11.5759C7.45388 11.5759 7.30821 11.5343 7.14173 11.4511L6.20529 10.9932L3.85377 9.8487L3.9162 10.0984C3.93701 10.14 3.93701 10.1609 3.93701 10.2025L4.37402 11.6384C4.95669 13.5737 5.53937 15.4882 6.12205 17.4235C6.18448 17.6524 6.33015 17.6524 6.45501 17.6524C7.99494 17.6108 9.55568 17.6108 11.0956 17.6108Z"
                                        fill="white" />
                                </svg>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('Membership')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/plan*')?'block':'none'}}">
                                <li class="nav-item {{Request::is('admin/plan/add-new')?'active':''}}"
                                    title="{{\App\CPU\translate('add_new')}}">
                                    <a class="nav-link " href="{{route('admin.plan.add-new')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M11.9985 7.88672C12.3838 7.88672 12.6961 8.19908 12.6961 8.58439V15.3996C12.6961 15.7849 12.3838 16.0973 11.9985 16.0973C11.6131 16.0973 11.3008 15.7849 11.3008 15.3996V8.58439C11.3008 8.19908 11.6131 7.88672 11.9985 7.88672Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M7.89062 11.9906C7.89062 11.6053 8.20298 11.293 8.5883 11.293H15.41C15.7953 11.293 16.1077 11.6053 16.1077 11.9906C16.1077 12.376 15.7953 12.6883 15.41 12.6883H8.5883C8.20298 12.6883 7.89062 12.376 7.89062 11.9906Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M3.51958 3.67792C4.5211 2.60357 5.94906 2 7.6412 2H16.3588C18.0549 2 19.4833 2.60329 20.4841 3.67825C21.4796 4.74758 22 6.22683 22 7.89317V16.1068C22 17.7732 21.4796 19.2524 20.4841 20.3217C19.4833 21.3967 18.0549 22 16.3588 22H7.6412C5.94508 22 4.51675 21.3967 3.51595 20.3217C2.5204 19.2524 2 17.7732 2 16.1068V7.89317C2 6.22597 2.52312 4.74684 3.51958 3.67792ZM4.54022 4.62938C3.82461 5.39703 3.39535 6.51565 3.39535 7.89317V16.1068C3.39535 17.4852 3.8229 18.6037 4.53721 19.3709C5.24627 20.1326 6.2897 20.6047 7.6412 20.6047H16.3588C17.7103 20.6047 18.7537 20.1326 19.4628 19.3709C20.1771 18.6037 20.6047 17.4852 20.6047 16.1068V7.89317C20.6047 6.51479 20.1771 5.39629 19.4628 4.62905C18.7537 3.86745 17.7103 3.39535 16.3588 3.39535H7.6412C6.29457 3.39535 5.25077 3.86717 4.54022 4.62938Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('add_new')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/plan/list')?'active':''}}"
                                    title="{{\App\CPU\translate('List')}}">
                                    <a class="nav-link " href="{{route('admin.plan.list')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path d="M10.332 16.5938H4.03125" stroke="white" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M13.1406 6.90039H19.4413" stroke="white" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M8.72629 6.84625C8.72629 5.5506 7.66813 4.5 6.36314 4.5C5.05816 4.5 4 5.5506 4 6.84625C4 8.14191 5.05816 9.19251 6.36314 9.19251C7.66813 9.19251 8.72629 8.14191 8.72629 6.84625Z"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M19.9997 16.5533C19.9997 15.2576 18.9424 14.207 17.6374 14.207C16.3316 14.207 15.2734 15.2576 15.2734 16.5533C15.2734 17.8489 16.3316 18.8995 17.6374 18.8995C18.9424 18.8995 19.9997 17.8489 19.9997 16.5533Z"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('List')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Plan End  -->

                        <!--Reports & Analytics section-->
                        @if(\App\CPU\Helpers::module_permission_check('report'))
                        <li
                            class="nav-item {{(Request::is('admin/report/earning') || Request::is('admin/report/inhoue-product-sale') || Request::is('admin/report/earning') || Request::is('admin/transaction/list') || Request::is('admin/refund-section/refund-list') || Request::is('admin/stock/product-in-wishlist') || Request::is('admin/reviews*') || Request::is('admin/stock/product-stock')) ? 'scroll-here':''}}">
                            <small class="nav-subtitle" title="">
                                {{\App\CPU\translate('Reports')}} & {{\App\CPU\translate('Analysis')}}
                            </small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{(Request::is('admin/report/admin-earning') || Request::is('admin/report/seller-earning') || Request::is('admin/report/inhoue-product-sale') || Request::is('admin/report/earning') || Request::is('admin/transaction/order-transaction-list') || Request::is('admin/transaction/expense-transaction-list') || Request::is('admin/transaction/refund-transaction-list')) ?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:"
                                title=" {{\App\CPU\translate('Revenue_&_Account_Management')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M19.2817 7.61779C19.1884 7.33791 18.9785 7.15134 18.6986 7.05805C18.582 7.03472 18.4654 6.98807 18.3254 6.96475H18.3021C18.1622 6.9181 18.0456 6.89478 17.9056 6.84813L17.8823 6.82482C17.789 6.68488 17.6957 6.54494 17.6024 6.40501L17.4625 6.17178C17.2992 5.91522 17.0194 5.75195 16.7395 5.75195C16.4596 5.75195 16.1797 5.91522 16.0165 6.17178L15.8532 6.40501C15.7599 6.54494 15.6666 6.68488 15.5733 6.82482L15.55 6.84813C15.3867 6.89478 15.2002 6.94143 15.0602 6.98808L14.8037 7.05805C14.5005 7.15134 14.2672 7.33791 14.174 7.61779C14.0807 7.89766 14.1506 8.17754 14.3372 8.43409L14.3839 8.50407C14.5238 8.66733 14.6637 8.8539 14.8037 9.01716C14.827 9.04048 14.827 9.06381 14.827 9.06381C14.827 9.1571 14.8037 9.27371 14.8037 9.36701V9.60023C14.8037 9.69353 14.8037 9.78682 14.7804 9.88011C14.7804 10.1367 14.8503 10.3466 15.0136 10.5098C15.1768 10.6731 15.3634 10.7431 15.5967 10.7431C15.7133 10.7431 15.8066 10.7197 15.9232 10.6731C16.0398 10.6265 16.1331 10.6031 16.2497 10.5565C16.3896 10.5098 16.5296 10.4399 16.6928 10.3932C16.8328 10.4399 16.9727 10.5098 17.136 10.5565C17.2293 10.6031 17.3226 10.6265 17.4392 10.6731C17.5558 10.7197 17.6724 10.7431 17.789 10.7431C18.0222 10.7431 18.2088 10.6498 18.3721 10.5098C18.5353 10.3466 18.6286 10.1133 18.6053 9.8568C18.6053 9.71686 18.6053 9.57692 18.582 9.43699C18.582 9.29705 18.5587 9.18043 18.582 9.04049C18.6753 8.92388 18.7686 8.83059 18.8619 8.69065C18.9318 8.59736 19.0251 8.50406 19.0951 8.41077C19.305 8.17754 19.375 7.89766 19.2817 7.61779ZM17.5791 9.06381C17.5791 9.25039 17.5791 9.4603 17.5791 9.64688C17.3925 9.57692 17.2293 9.50695 17.0427 9.43699C16.9261 9.39034 16.8328 9.36701 16.7162 9.36701C16.5995 9.36701 16.5062 9.39034 16.3896 9.43699C16.2264 9.50695 16.0398 9.5536 15.8532 9.62357L15.8066 9.64688C15.8066 9.55359 15.8066 9.4603 15.8299 9.36701C15.8765 8.76061 15.8532 8.71397 15.48 8.24751L15.2701 7.96763C15.4101 7.92098 15.55 7.89766 15.6899 7.85102C16.0165 7.78105 16.2497 7.6178 16.413 7.33792C16.5062 7.19799 16.5762 7.05805 16.6695 6.94143C16.7628 7.08137 16.8561 7.2213 16.9494 7.36124C17.0893 7.61779 17.2992 7.75773 17.5791 7.8277C17.7424 7.87434 17.9056 7.92098 18.0922 7.96763C17.9756 8.10757 17.859 8.24752 17.7424 8.38745C17.6491 8.59736 17.5791 8.83058 17.5791 9.06381Z"
                                        fill="white" />
                                    <path
                                        d="M2.02332 13.9619C2.18658 13.6354 2.41981 13.5188 2.79298 13.5188C3.37605 13.5421 3.98245 13.5188 4.56552 13.5188C4.65881 13.5188 4.75211 13.4721 4.82207 13.4255C5.80164 12.6325 6.94446 12.306 8.2039 12.3293C8.90359 12.3293 9.55663 12.5392 10.2097 12.7491C11.6324 13.2389 13.0551 13.7287 14.5011 14.1485C15.504 14.4517 16.1803 15.2913 15.8771 16.5041C16.1337 16.3642 16.3902 16.2709 16.5768 16.1309C17.4165 15.5245 18.2561 14.9181 19.0724 14.2884C19.6321 13.8686 20.2385 13.7287 20.8916 14.0085C22.081 14.475 22.4076 16.1309 21.4047 16.9239C20.1919 17.9035 18.9791 18.9063 17.5564 19.6294C15.2707 20.8188 12.8452 21.1454 10.303 20.6089C8.74033 20.2824 7.17769 19.9092 5.63838 19.5594C5.61505 19.5594 5.56841 19.5361 5.52176 19.5361C5.52176 19.606 5.52176 19.676 5.52176 19.746C5.56841 20.2824 5.28853 20.5856 4.70546 20.5623C4.07574 20.539 3.4227 20.539 2.79298 20.5623C2.41981 20.5623 2.16326 20.469 2 20.1191C2.02332 18.0667 2.02332 16.0143 2.02332 13.9619ZM5.52176 18.3233C5.59173 18.3466 5.63838 18.3699 5.70835 18.3699C7.17769 18.6964 8.67035 19.023 10.1397 19.3728C11.4924 19.6993 12.8452 19.7926 14.2212 19.5594C15.8072 19.2795 17.2299 18.6498 18.4893 17.6936C19.1657 17.1805 19.8654 16.6907 20.5417 16.1776C20.775 15.991 20.8682 15.7578 20.7983 15.5245C20.6817 15.1047 20.2152 14.9648 19.842 15.2447C19.0491 15.8277 18.2328 16.4341 17.4398 17.0172C16.4602 17.7402 15.364 18.0201 14.1746 17.7635C13.5448 17.6236 12.9151 17.3904 12.2854 17.1805C11.5391 16.9472 10.7927 16.6907 10.0464 16.4341C9.76653 16.3408 9.6266 16.1076 9.6266 15.8277C9.64992 15.5712 9.81318 15.3613 10.0697 15.3146C10.1863 15.2913 10.3496 15.3146 10.4662 15.3613C11.609 15.7344 12.7285 16.1076 13.8714 16.4808C13.988 16.5274 14.1279 16.5507 14.2445 16.5274C14.5244 16.5041 14.711 16.2709 14.7343 15.991C14.7576 15.6878 14.5944 15.4779 14.2679 15.3613C12.7519 14.8482 11.2126 14.3584 9.69656 13.8453C9.18346 13.682 8.64704 13.5188 8.11061 13.5188C7.20101 13.4954 6.36139 13.7053 5.61505 14.2651C5.52176 14.3351 5.47512 14.405 5.47512 14.545C5.47512 15.7578 5.47512 16.9706 5.47512 18.1833C5.52176 18.2067 5.52176 18.2766 5.52176 18.3233ZM4.35562 19.3728C4.35562 17.8102 4.35562 16.2709 4.35562 14.7082C3.95913 14.7082 3.58596 14.7082 3.21279 14.7082C3.21279 16.2709 3.21279 17.8102 3.21279 19.3728C3.58596 19.3728 3.95913 19.3728 4.35562 19.3728Z"
                                        fill="white" />
                                    <path
                                        d="M11.4688 8.27099C11.4688 5.35562 13.8477 3 16.7397 3C19.6318 3 21.9874 5.35562 21.9874 8.27099C21.9874 11.1864 19.6084 13.542 16.7164 13.542C13.8244 13.5186 11.4688 11.163 11.4688 8.27099ZM12.6349 8.24766C12.6349 10.51 14.4541 12.3525 16.7164 12.3525C18.9787 12.3525 20.8212 10.5333 20.8212 8.27099C20.8212 6.00867 19.0021 4.16615 16.7397 4.16615C14.4774 4.16615 12.6349 6.00866 12.6349 8.24766Z"
                                        fill="white" />
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Revenue_&_Account_Management')}}
                                </span>
                            </a>
                            <ul
                                class="js-navbar-vertical-aside-submenu nav nav-sub {{(Request::is('admin/report/admin-earning') || Request::is('admin/report/seller-earning') || Request::is('admin/report/inhoue-product-sale') || Request::is('admin/report/seller-report') || Request::is('admin/report/earning') || Request::is('admin/transaction/order-transaction-list') || Request::is('admin/transaction/expense-transaction-list') || Request::is('admin/transaction/refund-transaction-list')) ?'block':'none'}}">
                                <li
                                    class="navbar-vertical-aside-has-menu {{(Request::is('admin/report/admin-earning') || Request::is('admin/report/seller-earning'))?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.report.admin-earning')}}"
                                        title="{{\App\CPU\translate('Earning')}} {{\App\CPU\translate('reports')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M21.9113 11.812C21.6854 11.0663 21.03 10.5918 20.2617 10.5918C19.7872 10.5918 19.3353 10.7726 18.9737 11.1341C18.8155 11.2923 18.6574 11.4505 18.4766 11.6087C18.3184 11.7668 18.1602 11.925 18.0021 12.0832L16.9852 13.1001C16.8496 13.2356 16.6914 13.3938 16.5559 13.5294L16.5107 13.5746C16.4655 13.6198 16.4203 13.665 16.3751 13.7328L16.1943 13.9136C16.1039 14.0039 16.0135 14.0943 15.9231 14.2073C15.8327 14.2977 15.7424 14.4107 15.652 14.5011C15.6068 14.3881 15.539 14.2977 15.4486 14.2073L15.3582 14.0943C14.9967 13.7102 14.5221 13.5294 13.8216 13.5294C13.7764 13.5294 13.7312 13.5294 13.686 13.5294C13.4149 13.5294 13.1663 13.5294 12.8951 13.5294C12.6918 13.5294 12.4884 13.5294 12.285 13.5294C12.1043 13.5294 11.9461 13.5294 11.7653 13.5294H11.6523C11.6071 13.5294 11.5619 13.5294 11.5167 13.5294H11.4263C10.7484 13.5068 10.5225 13.3486 10.4999 13.326L10.4095 13.2808C10.3417 13.2356 10.2513 13.213 10.1835 13.1678C9.55079 12.8063 8.89548 12.6029 8.21758 12.5803C8.17238 12.5803 8.12719 12.5803 8.08199 12.5803C6.88435 12.5803 5.84489 13.0097 4.98621 13.8684C4.35349 14.5011 3.72078 15.1564 3.08806 15.7891L2.25197 16.6478C2.0712 16.8286 1.98082 17.0093 2.00341 17.1675C2.00341 17.3257 2.11639 17.4839 2.29717 17.6646L3.22365 18.4555C3.72078 18.8849 4.19532 19.2916 4.69245 19.721C5.0766 20.0599 5.48334 20.3989 5.86749 20.7152L6.43241 21.1898H6.77136L6.92955 21.0542C7.01993 20.9864 7.08773 20.9186 7.17811 20.8508C7.3137 20.7152 7.47187 20.5571 7.60745 20.4215C7.85602 20.1729 8.08199 19.9469 8.33056 19.6984C8.37575 19.6532 8.48874 19.608 8.55653 19.608C9.57339 19.608 10.5903 19.608 11.6071 19.608C12.2624 19.608 12.9177 19.608 13.5956 19.608H13.6182C14.7707 19.608 15.765 19.1786 16.601 18.3651C17.2338 17.7324 17.8891 17.0771 18.5218 16.4444L20.1488 14.8174C20.5555 14.4107 20.9849 14.0039 21.3916 13.5746C21.9791 13.1001 22.1147 12.4899 21.9113 11.812ZM13.7764 18.5233H11.0422C10.1835 18.5233 9.32483 18.5233 8.44354 18.5233C8.17238 18.5233 7.969 18.6137 7.78823 18.7945C7.51706 19.0882 7.22331 19.3594 6.95214 19.6306L6.68098 19.9017L3.44962 17.1223C3.54 17.0545 3.6078 17.0093 3.67559 16.9415C4.42129 16.1958 5.14438 15.4501 5.86749 14.7044C6.4776 14.0943 7.2911 13.7328 8.14978 13.7328C8.69211 13.7328 9.21184 13.8684 9.66378 14.1621C10.0705 14.4107 10.5225 14.5689 10.997 14.6366C11.2456 14.6592 11.4941 14.6818 11.7201 14.6818C11.9235 14.6818 12.1494 14.6818 12.3528 14.6818H13.2793C13.5279 14.6818 13.7538 14.6818 14.0024 14.6818C14.2736 14.6818 14.4995 14.7948 14.6351 14.9756C14.7481 15.1564 14.7933 15.3598 14.7255 15.5631C14.6351 15.8343 14.4091 16.0151 14.1606 16.0377C14.025 16.0377 13.912 16.0603 13.7764 16.0603H10.4321C10.3417 16.0603 10.2513 16.0603 10.1609 16.0829C9.91234 16.128 9.70897 16.354 9.70897 16.6252C9.70897 16.8963 9.91234 17.1223 10.1609 17.1675C10.2513 17.1901 10.3417 17.1901 10.4095 17.1901H12.511H13.912C14.8385 17.1901 15.4034 16.806 15.7424 15.9473C15.765 15.9021 15.7876 15.8569 15.8327 15.8343C17.1208 14.5463 18.4088 13.2582 19.6968 11.9702C19.855 11.8346 20.0132 11.7442 20.194 11.7442C20.2843 11.7442 20.3973 11.7668 20.4877 11.812C20.7589 11.9476 20.8719 12.1962 20.8267 12.4673C20.8041 12.6029 20.7137 12.7611 20.6233 12.8515C18.8381 14.6367 17.2338 16.241 15.7198 17.755C15.2452 18.2522 14.5673 18.5233 13.7764 18.5233Z"
                                                fill="white" />
                                            <path
                                                d="M13.3258 4.58203C12.196 4.58203 11.2695 5.53111 11.2695 6.66095C11.2695 7.7908 12.196 8.71728 13.3258 8.71728C13.8682 8.71728 14.3879 8.49131 14.7947 8.10716C15.1788 7.72302 15.4048 7.20329 15.4048 6.63836C15.4048 5.50851 14.4783 4.58203 13.3258 4.58203ZM12.196 6.63836C12.196 6.02824 12.7157 5.50851 13.3258 5.50851C13.6196 5.50851 13.9134 5.6215 14.1167 5.84747C14.3427 6.07344 14.4557 6.3446 14.4557 6.66095C14.4557 7.29367 13.936 7.7908 13.3033 7.7908C12.6931 7.7682 12.196 7.24848 12.196 6.63836Z"
                                                fill="white" />
                                            <path
                                                d="M7.51805 10.2536H10.6364H10.772C12.6928 10.2536 14.6361 10.2536 16.5568 10.2536H19.1329C19.5848 10.2536 19.743 10.0954 19.743 9.64351V3.58753C19.743 3.54234 19.743 3.51974 19.743 3.47454C19.7204 3.18078 19.5396 3 19.2459 3C18.5454 3 17.8223 3 17.1218 3H7.51805C7.1113 3 6.95312 3.15818 6.95312 3.56492C6.95312 4.40101 6.95312 5.23711 6.95312 6.07319V7.70018V9.64351C6.95312 10.0954 7.1113 10.2536 7.51805 10.2536ZM18.7939 8.71704V9.32715H18.1612C18.2742 9.07858 18.5228 8.80743 18.7939 8.71704ZM18.1838 3.94907H18.7939V4.5592C18.5228 4.46881 18.2742 4.22024 18.1838 3.94907ZM18.7939 5.55347L18.8165 5.57606C18.8165 5.9828 18.8165 6.36695 18.8165 6.77369C18.8165 7.09005 18.8165 7.40641 18.8165 7.72277V7.74536H18.7939C18.4098 7.83575 18.0934 8.01653 17.7997 8.28769C17.5059 8.55886 17.3025 8.92041 17.2121 9.34975H11.7437H10.659H9.50658C9.371 8.80743 9.09984 8.37808 8.6705 8.10692C8.51232 7.99394 8.35413 7.92614 8.17336 7.85835C8.08297 7.81315 8.01518 7.79055 7.92479 7.74536L7.90219 7.72277C7.90219 7.54199 7.90219 7.36121 7.90219 7.18044V6.27656C7.90219 6.02799 7.90219 5.77943 7.90219 5.57606C7.90219 5.57606 7.92479 5.55347 7.94739 5.55347C8.01518 5.50827 8.10557 5.48568 8.19596 5.44048C8.35414 5.37269 8.53491 5.30489 8.69309 5.19191C9.12243 4.89815 9.39359 4.4914 9.52918 3.94907H17.2347C17.4155 4.78516 17.9578 5.3275 18.7939 5.55347ZM7.8796 4.5592V3.94907H8.48972C8.42192 4.19764 8.17336 4.44621 7.8796 4.5592ZM8.51231 9.32715H7.90219V8.71704C8.17336 8.83003 8.42193 9.07858 8.51231 9.32715Z"
                                                fill="white" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('Earning')}} {{\App\CPU\translate('reports')}}
                                        </span>
                                    </a>
                                </li>
                                <li
                                    class="d-none nav-item {{Request::is('admin/report/inhoue-product-sale')?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.report.inhoue-product-sale')}}"
                                        title="{{\App\CPU\translate('inhouse')}} {{\App\CPU\translate('sales')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M18.3198 13.1111C17.8897 12.4421 17.4595 11.7491 17.0294 11.08C16.8383 10.7694 16.6471 10.4827 16.4559 10.172L15.4285 8.54718C15.8586 8.23655 16.0975 7.78256 16.0736 7.25687C16.0497 6.75508 15.763 6.30107 15.2851 6.03822C15.2851 6.01433 15.309 5.96655 15.309 5.94265L15.5002 5.2497C15.7152 4.53285 15.9064 3.816 16.1214 3.09916C16.2409 2.69295 16.0258 2.50179 15.9064 2.4301C15.7152 2.33453 15.5241 2.23894 15.3568 2.14336C15.2851 2.09557 15.1895 2.07168 15.1178 2.02389L15.0939 2H14.6399H14.616C14.2576 2.07168 13.947 2.26284 13.6125 2.54958C13.5169 2.64516 13.3735 2.69294 13.254 2.69294C13.1107 2.69294 12.9912 2.64516 12.8717 2.54958C12.585 2.28674 12.2505 2.11947 11.892 2.02389H11.8681H11.3663H11.3425C10.984 2.11947 10.6734 2.28674 10.3628 2.54958C10.2672 2.64516 10.1238 2.69294 10.0043 2.69294C9.86098 2.69294 9.7415 2.64516 9.64592 2.54958C9.33529 2.26284 9.00076 2.09558 8.64234 2H8.61844H8.14054L8.11665 2.02389C8.04497 2.07168 7.94939 2.09557 7.8777 2.14336C7.68655 2.23894 7.49538 2.31063 7.32812 2.4301C7.08917 2.57347 7.01749 2.78852 7.11307 3.09916C7.18475 3.33811 7.25643 3.57706 7.32812 3.83991L7.94939 6.03822C7.47149 6.30107 7.18475 6.75508 7.16086 7.25687C7.13696 7.75866 7.37591 8.23655 7.80602 8.54718C7.78212 8.57108 7.78212 8.59498 7.75823 8.61888L7.18475 9.52688C6.44401 10.6738 5.70327 11.8447 4.96253 12.9916C4.1501 14.282 3.83947 15.6679 4.07842 17.1254C4.50852 19.8734 6.92191 21.9761 9.66982 22C10.2911 22 10.9123 22 11.5336 22C12.2027 22 12.8478 22 13.5169 21.9761C14.5205 21.9522 15.4763 21.6894 16.3365 21.1637C17.6985 20.3513 18.6543 19.037 19.0366 17.5078C19.4189 16.0502 19.1561 14.4731 18.3198 13.1111ZM8.66623 6.94624H9.83708H11.6053C11.6053 6.94624 14.1382 6.94624 14.5683 6.94624C14.7116 6.94624 14.855 6.99402 14.9267 7.0896C14.9984 7.16129 15.0462 7.28076 15.0223 7.40023C14.9984 7.61529 14.8311 7.78256 14.5922 7.78256C14.5205 7.78256 14.3771 7.78256 14.3771 7.78256H9.47866C9.47866 7.78256 8.8096 7.78256 8.64234 7.78256C8.47507 7.78256 8.3556 7.73476 8.26002 7.63918C8.18833 7.5675 8.14054 7.47191 8.16444 7.35244C8.18833 7.11349 8.37949 6.94624 8.66623 6.94624ZM14.2576 5.94265H8.97686L8.18833 3.14695C8.26002 3.12306 8.3317 3.09916 8.40339 3.09916C8.54676 3.09916 8.71402 3.17085 8.88128 3.31422C9.21581 3.62485 9.57424 3.76821 9.98045 3.76821C10.1716 3.76821 10.3628 3.72042 10.5778 3.64874C10.8168 3.57705 11.0079 3.40979 11.1513 3.29031C11.3186 3.14694 11.4858 3.07527 11.6292 3.07527C11.7726 3.07527 11.9398 3.14695 12.0832 3.26642C12.4177 3.57706 12.8239 3.74432 13.2302 3.74432C13.6364 3.74432 14.0426 3.57706 14.3771 3.26642C14.5205 3.14695 14.6638 3.07527 14.8072 3.07527C14.8789 3.07527 14.9506 3.09917 15.0223 3.12306L14.2576 5.94265ZM6.99359 11.773C7.61486 10.8172 8.21223 9.83751 8.8335 8.88172C8.88129 8.81003 8.90518 8.81004 8.95297 8.81004C9.09634 8.81004 9.21581 8.81004 9.35918 8.81004H9.38308H14.2815C14.3293 8.81004 14.3771 8.83393 14.401 8.85783C15.4763 10.5544 16.456 12.0836 17.4356 13.6129C18.1764 14.7837 18.3914 16.1219 18.033 17.4361C17.6746 18.7503 16.7905 19.8495 15.6196 20.4707C14.9506 20.8291 14.2576 20.9964 13.5647 21.0203C12.8478 21.0203 12.131 21.0442 11.4141 21.0442C10.8407 21.0442 10.2672 21.0442 9.69371 21.0203C7.37591 20.9725 5.34485 19.1087 5.082 16.7909C4.96253 15.644 5.20148 14.5687 5.84664 13.589L6.99359 11.773Z"
                                                fill="white" />
                                            <path
                                                d="M9.93223 17.8908C10.219 18.0342 10.5296 18.1537 10.8402 18.2731C10.9358 18.297 11.0075 18.3209 11.1031 18.3209C11.1031 18.4165 11.1031 18.4882 11.1031 18.5838C11.1031 18.8705 11.342 19.0856 11.6049 19.0856C11.8916 19.0856 12.1067 18.8705 12.1067 18.5599C12.1067 18.4643 12.1067 18.3687 12.1067 18.2731V18.2492C12.943 17.9386 13.4687 17.3173 13.5404 16.5527C13.612 15.573 13.158 14.9279 12.1306 14.5933V13.1118C12.2261 13.1596 12.2978 13.2074 12.3934 13.2552C12.489 13.303 12.5846 13.3269 12.6801 13.3269C12.7996 13.3269 12.9191 13.2791 13.0386 13.1835C13.158 13.0641 13.2058 12.9207 13.1819 12.7534C13.158 12.6101 13.0625 12.4667 12.943 12.395C12.7518 12.2755 12.5607 12.2038 12.3456 12.1083C12.2739 12.0844 12.1306 12.0127 12.1306 12.0127C12.1306 11.941 12.1306 11.8454 12.1306 11.7498C12.1067 11.4631 11.9155 11.248 11.6049 11.248C11.342 11.248 11.127 11.4631 11.1031 11.7498C11.1031 11.8454 11.1031 11.9171 11.1031 12.0127V12.0366C11.0792 12.0366 11.0553 12.0605 11.0314 12.0605C10.9119 12.1083 10.7925 12.1322 10.6969 12.2038C10.1473 12.5145 9.86055 12.9924 9.83666 13.6375C9.81276 14.2588 10.0756 14.7128 10.6252 15.0234C10.6969 15.0473 10.7447 15.0951 10.8163 15.119C10.888 15.1668 10.9836 15.2146 11.0553 15.2624C11.0792 15.2624 11.0792 15.2863 11.1031 15.2863C11.1031 15.8597 11.1031 16.4332 11.1031 17.0067V17.2934C10.8641 17.2695 10.673 17.174 10.4579 17.0306C10.3623 16.9828 10.2668 16.935 10.1473 16.935C9.98003 16.935 9.81276 17.0306 9.71718 17.174C9.6455 17.2934 9.62161 17.4368 9.6694 17.5802C9.71719 17.7236 9.81276 17.8191 9.93223 17.8908ZM11.1031 13.1596V14.1154C10.888 13.996 10.8402 13.8526 10.8402 13.6614C10.8641 13.4464 10.9597 13.2791 11.1031 13.1596ZM12.1306 17.1262V15.6925C12.3934 15.812 12.5368 16.027 12.5368 16.3137C12.5607 16.6483 12.3934 16.9589 12.1306 17.1262Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('inhouse')}} {{\App\CPU\translate('sales')}}
                                        </span>
                                    </a>
                                </li>
                                <li class="d-none nav-item {{ Request::is('admin/currency/view') ?'active':'' }}">
                                    <a class="nav-link" href="{{route('admin.currency.view')}}"
                                        title="{{\App\CPU\translate('Currency_Management')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M11.8619 22C11.3838 22 10.8791 21.8141 10.4011 21.4157L8.91368 20.2204C8.64807 20.008 8.46215 19.9283 8.27623 19.9283C8.0903 19.9283 7.90438 20.0345 7.63878 20.247C7.18725 20.6188 6.78884 20.8048 6.31075 20.8579C6.25763 20.8579 6.17795 20.8579 6.12483 20.8579C4.92961 20.8579 4.02656 19.9283 4 18.7596C4 17.0332 4 15.3068 4 13.5803V4.25763C4 2.87649 4.87649 2 6.25763 2H10.5073C11.676 2 12.8181 2 13.9867 2C14.6773 2 15.2351 2.23905 15.7131 2.71713C16.8818 3.91235 17.9708 4.97477 19.0066 5.98406C19.5113 6.46215 19.7238 7.04649 19.7238 7.73706C19.7238 10.0744 19.7238 12.4382 19.7238 14.7756C19.7238 16.077 19.7238 17.3785 19.7238 18.6799C19.7238 19.8486 18.9801 20.7251 17.8114 20.8579C17.7317 20.8579 17.6521 20.8845 17.5724 20.8845C17.1474 20.8845 16.749 20.7517 16.3772 20.4861C16.2709 20.4329 16.1912 20.3533 16.1115 20.2736L16.0053 20.1939C15.7928 20.0345 15.6335 19.9548 15.4475 19.9548C15.2616 19.9548 15.0757 20.0345 14.8898 20.1939C14.3586 20.6188 13.8539 21.0173 13.3227 21.4422C12.8446 21.7875 12.3665 22 11.8619 22ZM8.24967 18.5737C8.72775 18.5737 9.20584 18.7596 9.60425 19.0784C9.97609 19.3705 10.3214 19.6361 10.6667 19.9283L11.2244 20.3798C11.4635 20.5657 11.6494 20.6454 11.8353 20.6454C12.0212 20.6454 12.2072 20.5657 12.4462 20.3798L13.1899 19.7689C13.4821 19.5299 13.7742 19.2908 14.0664 19.0784C14.4648 18.7596 14.9429 18.5737 15.421 18.5737C15.8991 18.5737 16.3506 18.7331 16.7756 19.0518C16.8021 19.0783 16.8552 19.1049 16.8818 19.158C16.9615 19.2377 17.0412 19.3174 17.1474 19.344C17.3333 19.4236 17.5192 19.4768 17.652 19.4768C17.6786 19.4768 17.7052 19.4768 17.7317 19.4768C18.1301 19.4236 18.3692 19.1315 18.3692 18.6268C18.3692 15.2802 18.3692 11.9336 18.3692 8.56042C18.3692 8.53386 18.3692 8.48074 18.3692 8.42762V8.29482H16.51C16.1647 8.29482 15.7928 8.29482 15.4475 8.29482C14.8101 8.29482 14.2789 8.05578 13.8539 7.55113C13.5086 7.15273 13.3493 6.67464 13.3493 6.14343V3.24834H6.25763C5.62018 3.24834 5.32802 3.51395 5.32802 4.17796V18.5471C5.32802 18.6003 5.32802 18.6534 5.32802 18.7065C5.35458 19.1315 5.69987 19.4236 6.09827 19.4236C6.25764 19.4236 6.39044 19.3705 6.52324 19.2908C6.60292 19.2377 6.70916 19.158 6.78884 19.0784L6.89508 18.9987C7.32005 18.7596 7.79814 18.5737 8.24967 18.5737ZM14.7038 4.47013C14.7038 5.08102 14.7038 5.71847 14.7038 6.32936C14.7038 6.70121 15.0226 6.99337 15.3944 6.99337C15.7663 6.99337 16.1381 6.99337 16.51 6.99337H18.077L14.7038 3.56707V4.47013Z"
                                                fill="white" />
                                            <path
                                                d="M15.3946 12.6775C14.6244 12.6775 13.8276 12.6775 13.0573 12.6775H9.17948C8.91388 12.6775 8.62171 12.6775 8.35611 12.6775C8.01082 12.6775 7.79834 12.5447 7.66554 12.2791C7.53274 12.0135 7.61242 11.7479 7.85146 11.5088C8.17018 11.1901 8.86076 10.4995 8.86076 10.4995C9.20604 10.1542 9.55133 9.80894 9.89661 9.46366C10.056 9.3043 10.2419 9.22461 10.4278 9.22461C10.5872 9.22461 10.7465 9.30429 10.8793 9.41054C11.0121 9.54334 11.0653 9.7027 11.0653 9.86207C11.0653 10.048 10.9856 10.2339 10.8262 10.3933C10.6403 10.5792 10.4544 10.7651 10.2419 10.951L9.94973 11.2432L10.0029 11.3494H13.7213C14.2791 11.3494 14.8368 11.3494 15.3681 11.3494C15.6337 11.3494 15.8461 11.4822 15.9789 11.6682C16.0852 11.8541 16.1117 12.0666 16.0321 12.2791C15.9524 12.5447 15.7133 12.6775 15.3946 12.6775Z"
                                                fill="white" />
                                            <path
                                                d="M13.3238 17.6708C13.2175 17.6708 13.1113 17.6443 13.0316 17.5911C12.766 17.4583 12.6332 17.1662 12.6863 16.874C12.7129 16.7412 12.7926 16.6084 12.8988 16.4756C13.0847 16.2631 13.2972 16.0772 13.4831 15.8913L13.855 15.5194H8.38353C7.90545 15.5194 7.61328 15.2538 7.61328 14.8554C7.61328 14.6695 7.6664 14.5101 7.79921 14.4039C7.93201 14.2711 8.14449 14.1914 8.38353 14.1914H11.8895C13.0582 14.1914 14.2268 14.1914 15.3955 14.1914C15.7673 14.1914 15.9798 14.3242 16.1126 14.5898C16.2454 14.882 16.1657 15.121 15.9001 15.3866C15.5548 15.7319 15.183 16.1038 14.8377 16.449L14.7315 16.5553C14.4393 16.8475 14.1471 17.1396 13.855 17.4318C13.6691 17.6177 13.5097 17.6708 13.3238 17.6708Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Currency_Management')}}
                                        </span>
                                    </a>
                                </li>

                                <li
                                    class="navbar-vertical-aside-has-menu {{(Request::is('admin/transaction/order-transaction-list') || Request::is('admin/transaction/expense-transaction-list') || Request::is('admin/transaction/refund-transaction-list'))?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.transaction.order-transaction-list')}}"
                                        title="{{\App\CPU\translate('Transaction_Report')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M5.86446 22C5.60987 21.9434 5.35527 21.8868 5.10068 21.802C3.60138 21.2362 2.75273 19.7369 3.0639 18.1527C3.43165 16.2857 3.74282 14.4187 4.13886 12.5516C4.61977 10.2885 5.97762 8.73267 8.09926 7.82743C8.15583 7.79914 8.21241 7.77085 8.26899 7.77085C8.26899 7.77085 8.26899 7.77086 8.26899 7.74257C7.84466 6.58274 7.98611 5.96039 8.91963 5.1966C8.89134 5.14002 8.86305 5.08345 8.83476 5.02688C8.46701 4.37624 8.12755 3.7256 7.7598 3.04668C7.44862 2.48091 7.73151 2 8.38214 2C9.7117 2 11.0413 2 12.3708 2C13.0215 2 13.3043 2.48091 12.9932 3.04668C12.5971 3.75389 12.2011 4.4611 11.8333 5.1966C12.7103 5.81895 12.9366 6.63932 12.5406 7.65771C12.5688 7.686 12.5971 7.686 12.6537 7.71429C12.9366 7.88402 13.0498 8.22347 12.9366 8.53465C12.8234 8.84582 12.484 8.95897 12.1728 8.90239C11.6636 8.81753 11.1544 8.67609 10.6452 8.67609C8.15583 8.6478 5.94933 10.4017 5.46843 12.8628C5.10068 14.7298 4.73292 16.5969 4.39346 18.4639C4.16715 19.5955 5.07238 20.6987 6.2605 20.727C7.10916 20.727 7.95781 20.727 8.80647 20.727C9.11764 20.727 9.37225 20.925 9.42882 21.1796C9.51369 21.4625 9.40053 21.7454 9.17422 21.8868C9.11765 21.9151 9.03278 21.9717 8.9762 22C7.87295 22 6.88285 22 5.86446 22ZM10.3623 7.34652C10.5321 7.34652 10.7018 7.34652 10.8715 7.34652C11.1544 7.34652 11.3807 7.12022 11.409 6.83733C11.4373 6.55445 11.2393 6.27157 10.9564 6.24328C10.5604 6.21499 10.1643 6.21499 9.76828 6.24328C9.4854 6.27157 9.28738 6.55445 9.31567 6.83733C9.34395 7.12022 9.57026 7.31823 9.85315 7.34652C10.0229 7.34652 10.1926 7.34652 10.3623 7.34652ZM9.34396 3.30127C9.62684 3.83875 9.90973 4.34794 10.1926 4.85714C10.2775 4.99858 10.4189 4.97029 10.5038 4.85714C10.6735 4.57425 10.8432 4.26308 10.9847 3.9802C11.0978 3.75389 11.211 3.55587 11.3524 3.32956C10.6735 3.30128 10.0512 3.30127 9.34396 3.30127Z"
                                                fill="white" />
                                            <path
                                                d="M13.6998 22.0002C13.3038 21.9154 12.8794 21.8871 12.5117 21.7456C10.1071 20.9818 8.57957 19.3694 8.12695 16.88C7.53289 13.7117 9.45651 10.7414 12.5683 9.86444C16.0195 8.87434 19.697 11.2223 20.2627 14.7866C20.8002 18.0964 18.7352 21.1233 15.4537 21.8588C15.1708 21.9154 14.9162 21.9436 14.6333 22.0002C14.3221 22.0002 14.011 22.0002 13.6998 22.0002ZM19.0746 15.8333C19.0746 13.1459 16.8964 10.9677 14.209 10.9394C11.5216 10.9394 9.31507 13.1176 9.31507 15.8333C9.31507 18.5207 11.4933 20.6989 14.1807 20.6989C16.8681 20.6989 19.0463 18.5207 19.0746 15.8333Z"
                                                fill="white" />
                                            <path
                                                d="M12.6268 18.0971C12.3439 18.0405 12.1459 17.9274 12.0327 17.7011C11.9196 17.4465 11.9479 17.2202 12.1176 17.0221C12.1459 16.9939 12.1742 16.9656 12.2025 16.909C13.2209 15.8906 14.2675 14.8722 15.2859 13.8255C15.4839 13.6275 15.682 13.5427 15.9648 13.5992C16.4175 13.7124 16.5872 14.2499 16.3043 14.6176C16.3043 14.6459 16.276 14.6459 16.276 14.6742C15.201 15.7492 14.1261 16.8241 13.0511 17.8708C12.938 17.984 12.7682 18.0122 12.6268 18.0971Z"
                                                fill="white" />
                                            <path
                                                d="M16.3614 17.417C16.3614 17.8979 15.9936 18.2657 15.5127 18.2657C15.0601 18.2657 14.6641 17.8696 14.6641 17.417C14.6641 16.9361 15.0601 16.5684 15.5127 16.5684C15.9936 16.5684 16.3614 16.9361 16.3614 17.417Z"
                                                fill="white" />
                                            <path
                                                d="M12.8526 15.0977C12.3717 15.0977 12.0039 14.73 12.0039 14.249C12.0039 13.7681 12.3999 13.4004 12.8526 13.4004C13.3335 13.4004 13.7012 13.7964 13.7012 14.249C13.7295 14.7017 13.3335 15.0977 12.8526 15.0977Z"
                                                fill="white" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('Transaction_Report')}}
                                        </span>
                                    </a>
                                </li>
                                <li
                                    class="d-none navbar-vertical-aside-has-menu {{(Request::is('admin/taxes/add-new'))?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        title="{{\App\CPU\translate('tax_setup')}}"
                                        href="{{route('admin.taxes.add-new')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M18.3198 13.1111C17.8897 12.4421 17.4595 11.7491 17.0294 11.08C16.8383 10.7694 16.6471 10.4827 16.4559 10.172L15.4285 8.54718C15.8586 8.23655 16.0975 7.78256 16.0736 7.25687C16.0497 6.75508 15.763 6.30107 15.2851 6.03822C15.2851 6.01433 15.309 5.96655 15.309 5.94265L15.5002 5.2497C15.7152 4.53285 15.9064 3.816 16.1214 3.09916C16.2409 2.69295 16.0258 2.50179 15.9064 2.4301C15.7152 2.33453 15.5241 2.23894 15.3568 2.14336C15.2851 2.09557 15.1895 2.07168 15.1178 2.02389L15.0939 2H14.6399H14.616C14.2576 2.07168 13.947 2.26284 13.6125 2.54958C13.5169 2.64516 13.3735 2.69294 13.254 2.69294C13.1107 2.69294 12.9912 2.64516 12.8717 2.54958C12.585 2.28674 12.2505 2.11947 11.892 2.02389H11.8681H11.3663H11.3425C10.984 2.11947 10.6734 2.28674 10.3628 2.54958C10.2672 2.64516 10.1238 2.69294 10.0043 2.69294C9.86098 2.69294 9.7415 2.64516 9.64592 2.54958C9.33529 2.26284 9.00076 2.09558 8.64234 2H8.61844H8.14054L8.11665 2.02389C8.04497 2.07168 7.94939 2.09557 7.8777 2.14336C7.68655 2.23894 7.49538 2.31063 7.32812 2.4301C7.08917 2.57347 7.01749 2.78852 7.11307 3.09916C7.18475 3.33811 7.25643 3.57706 7.32812 3.83991L7.94939 6.03822C7.47149 6.30107 7.18475 6.75508 7.16086 7.25687C7.13696 7.75866 7.37591 8.23655 7.80602 8.54718C7.78212 8.57108 7.78212 8.59498 7.75823 8.61888L7.18475 9.52688C6.44401 10.6738 5.70327 11.8447 4.96253 12.9916C4.1501 14.282 3.83947 15.6679 4.07842 17.1254C4.50852 19.8734 6.92191 21.9761 9.66982 22C10.2911 22 10.9123 22 11.5336 22C12.2027 22 12.8478 22 13.5169 21.9761C14.5205 21.9522 15.4763 21.6894 16.3365 21.1637C17.6985 20.3513 18.6543 19.037 19.0366 17.5078C19.4189 16.0502 19.1561 14.4731 18.3198 13.1111ZM8.66623 6.94624H9.83708H11.6053C11.6053 6.94624 14.1382 6.94624 14.5683 6.94624C14.7116 6.94624 14.855 6.99402 14.9267 7.0896C14.9984 7.16129 15.0462 7.28076 15.0223 7.40023C14.9984 7.61529 14.8311 7.78256 14.5922 7.78256C14.5205 7.78256 14.3771 7.78256 14.3771 7.78256H9.47866C9.47866 7.78256 8.8096 7.78256 8.64234 7.78256C8.47507 7.78256 8.3556 7.73476 8.26002 7.63918C8.18833 7.5675 8.14054 7.47191 8.16444 7.35244C8.18833 7.11349 8.37949 6.94624 8.66623 6.94624ZM14.2576 5.94265H8.97686L8.18833 3.14695C8.26002 3.12306 8.3317 3.09916 8.40339 3.09916C8.54676 3.09916 8.71402 3.17085 8.88128 3.31422C9.21581 3.62485 9.57424 3.76821 9.98045 3.76821C10.1716 3.76821 10.3628 3.72042 10.5778 3.64874C10.8168 3.57705 11.0079 3.40979 11.1513 3.29031C11.3186 3.14694 11.4858 3.07527 11.6292 3.07527C11.7726 3.07527 11.9398 3.14695 12.0832 3.26642C12.4177 3.57706 12.8239 3.74432 13.2302 3.74432C13.6364 3.74432 14.0426 3.57706 14.3771 3.26642C14.5205 3.14695 14.6638 3.07527 14.8072 3.07527C14.8789 3.07527 14.9506 3.09917 15.0223 3.12306L14.2576 5.94265ZM6.99359 11.773C7.61486 10.8172 8.21223 9.83751 8.8335 8.88172C8.88129 8.81003 8.90518 8.81004 8.95297 8.81004C9.09634 8.81004 9.21581 8.81004 9.35918 8.81004H9.38308H14.2815C14.3293 8.81004 14.3771 8.83393 14.401 8.85783C15.4763 10.5544 16.456 12.0836 17.4356 13.6129C18.1764 14.7837 18.3914 16.1219 18.033 17.4361C17.6746 18.7503 16.7905 19.8495 15.6196 20.4707C14.9506 20.8291 14.2576 20.9964 13.5647 21.0203C12.8478 21.0203 12.131 21.0442 11.4141 21.0442C10.8407 21.0442 10.2672 21.0442 9.69371 21.0203C7.37591 20.9725 5.34485 19.1087 5.082 16.7909C4.96253 15.644 5.20148 14.5687 5.84664 13.589L6.99359 11.773Z"
                                                fill="white" />
                                            <path
                                                d="M9.93223 17.8908C10.219 18.0342 10.5296 18.1537 10.8402 18.2731C10.9358 18.297 11.0075 18.3209 11.1031 18.3209C11.1031 18.4165 11.1031 18.4882 11.1031 18.5838C11.1031 18.8705 11.342 19.0856 11.6049 19.0856C11.8916 19.0856 12.1067 18.8705 12.1067 18.5599C12.1067 18.4643 12.1067 18.3687 12.1067 18.2731V18.2492C12.943 17.9386 13.4687 17.3173 13.5404 16.5527C13.612 15.573 13.158 14.9279 12.1306 14.5933V13.1118C12.2261 13.1596 12.2978 13.2074 12.3934 13.2552C12.489 13.303 12.5846 13.3269 12.6801 13.3269C12.7996 13.3269 12.9191 13.2791 13.0386 13.1835C13.158 13.0641 13.2058 12.9207 13.1819 12.7534C13.158 12.6101 13.0625 12.4667 12.943 12.395C12.7518 12.2755 12.5607 12.2038 12.3456 12.1083C12.2739 12.0844 12.1306 12.0127 12.1306 12.0127C12.1306 11.941 12.1306 11.8454 12.1306 11.7498C12.1067 11.4631 11.9155 11.248 11.6049 11.248C11.342 11.248 11.127 11.4631 11.1031 11.7498C11.1031 11.8454 11.1031 11.9171 11.1031 12.0127V12.0366C11.0792 12.0366 11.0553 12.0605 11.0314 12.0605C10.9119 12.1083 10.7925 12.1322 10.6969 12.2038C10.1473 12.5145 9.86055 12.9924 9.83666 13.6375C9.81276 14.2588 10.0756 14.7128 10.6252 15.0234C10.6969 15.0473 10.7447 15.0951 10.8163 15.119C10.888 15.1668 10.9836 15.2146 11.0553 15.2624C11.0792 15.2624 11.0792 15.2863 11.1031 15.2863C11.1031 15.8597 11.1031 16.4332 11.1031 17.0067V17.2934C10.8641 17.2695 10.673 17.174 10.4579 17.0306C10.3623 16.9828 10.2668 16.935 10.1473 16.935C9.98003 16.935 9.81276 17.0306 9.71718 17.174C9.6455 17.2934 9.62161 17.4368 9.6694 17.5802C9.71719 17.7236 9.81276 17.8191 9.93223 17.8908ZM11.1031 13.1596V14.1154C10.888 13.996 10.8402 13.8526 10.8402 13.6614C10.8641 13.4464 10.9597 13.2791 11.1031 13.1596ZM12.1306 17.1262V15.6925C12.3934 15.812 12.5368 16.027 12.5368 16.3137C12.5607 16.6483 12.3934 16.9589 12.1306 17.1262Z"
                                                fill="white" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('tax_setup')}}
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>


                        @endif
                        <!--Reports & Analytics section End-->

                        <li class=" navbar-vertical-aside-has-menu {{(Request::is('admin/business-settings/web-config') || Request::is('admin/business-settings/web-config/app-settings') || Request::is('admin/product-settings/inhouse-shop') || Request::is('admin/business-settings/seller-settings') || Request::is('admin/customer/customer-settings') || Request::is('admin/refund-section/refund-index') || Request::is('admin/business-settings/shipping-method/setting') || Request::is('admin/business-settings/order-settings/index') || Request::is('admin/product-settings') || Request::is('admin/business-settings/web-config/delivery-restriction') || Request::is('admin/business-settings/cookie-settings') || Request::is('admin/business-settings/otp-setup') || Request::is('admin/business-settings/all-pages-banner*') || Request::is('admin/business-settings/delivery-restriction'))?'active':''}}"
                            title="{{\App\CPU\translate('Settings')}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{route('admin.business-settings.web-config.index')}}"
                                title="{{\App\CPU\translate('Business_Setup')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M20.8054 7.62288L20.183 6.54279C19.6564 5.62887 18.4895 5.31359 17.5743 5.83798C17.1387 6.09461 16.6189 6.16742 16.1295 6.04035C15.6401 5.91329 15.2214 5.59678 14.9656 5.16064C14.8011 4.88342 14.7127 4.56766 14.7093 4.24531C14.7242 3.72849 14.5292 3.22767 14.1688 2.85694C13.8084 2.4862 13.3133 2.27713 12.7963 2.27734H11.5423C11.0357 2.27734 10.5501 2.47918 10.1928 2.83821C9.83547 3.19724 9.63595 3.68386 9.63839 4.19039C9.62338 5.23619 8.77126 6.07608 7.72535 6.07597C7.40299 6.07262 7.08724 5.98421 6.81001 5.81968C5.89484 5.29528 4.72789 5.61056 4.20132 6.52448L3.53313 7.62288C3.00719 8.53566 3.31818 9.70187 4.22878 10.2316C4.82068 10.5733 5.18531 11.2049 5.18531 11.8883C5.18531 12.5718 4.82068 13.2033 4.22878 13.5451C3.31934 14.0712 3.00801 15.2346 3.53313 16.1446L4.1647 17.2339C4.41143 17.6791 4.82538 18.0076 5.31497 18.1467C5.80456 18.2859 6.32942 18.2242 6.7734 17.9753C7.20986 17.7206 7.72997 17.6508 8.21812 17.7815C8.70627 17.9121 9.12201 18.2323 9.37294 18.6709C9.53748 18.9482 9.62589 19.2639 9.62924 19.5863C9.62924 20.6428 10.4857 21.4993 11.5423 21.4993H12.7963C13.8493 21.4993 14.7043 20.6484 14.7093 19.5954C14.7069 19.0873 14.9076 18.5993 15.2669 18.24C15.6262 17.8807 16.1143 17.6799 16.6224 17.6824C16.944 17.691 17.2584 17.779 17.5377 17.9387C18.4505 18.4646 19.6167 18.1536 20.1464 17.243L20.8054 16.1446C21.0605 15.7068 21.1305 15.1853 21 14.6956C20.8694 14.206 20.549 13.7886 20.1098 13.5359C19.6705 13.2832 19.3502 12.8658 19.2196 12.3762C19.089 11.8866 19.159 11.3651 19.4141 10.9272C19.58 10.6376 19.8202 10.3975 20.1098 10.2316C21.0149 9.70216 21.3252 8.54276 20.8054 7.63204V7.62288Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M12.1752 14.5243C13.6311 14.5243 14.8114 13.344 14.8114 11.8881C14.8114 10.4322 13.6311 9.25195 12.1752 9.25195C10.7193 9.25195 9.53906 10.4322 9.53906 11.8881C9.53906 13.344 10.7193 14.5243 12.1752 14.5243Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Settings')}}
                                </span>
                            </a>
                        </li>
                        <li
                            class="d-none navbar-vertical-aside-has-menu {{(Request::is('admin/login-report/report/*') || Request::is('admin/user-active/report/*'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:"
                                title="{{\App\CPU\translate('User Report')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M16.5918 12.2754C16.1983 12.2754 15.9453 12.5565 15.9453 12.9781C15.9453 13.6807 15.9453 14.4115 15.9453 15.1141C15.9453 15.8449 15.9453 16.5757 15.9453 17.2783C15.9453 17.3627 15.9453 17.4751 15.9734 17.5594C16.114 17.8686 16.395 18.0372 16.7323 17.981C17.0415 17.9248 17.2663 17.6437 17.2663 17.2783C17.2663 15.8449 17.2663 14.4115 17.2663 12.9781C17.2663 12.5565 16.9853 12.2754 16.5918 12.2754Z"
                                        fill="white" />
                                    <path
                                        d="M11.3078 14.411C11.1953 14.158 10.8862 13.9613 10.6332 14.0175C10.2959 14.0737 10.0711 14.3266 10.043 14.692C10.043 14.8607 10.043 15.0293 10.043 15.1979C10.043 15.9006 10.043 16.6033 10.043 17.2778C10.043 17.6994 10.324 18.0086 10.7456 17.9805C11.1391 17.9805 11.364 17.6994 11.364 17.2497C11.364 16.8281 11.364 16.4065 11.364 15.9849C11.364 15.5633 11.364 15.1136 11.364 14.692C11.364 14.6077 11.3359 14.5234 11.3078 14.411Z"
                                        fill="white" />
                                    <path
                                        d="M14.289 15.4226C14.2047 15.0854 13.9236 14.8886 13.5582 14.9167C13.2491 14.9448 13.0242 15.2259 12.9961 15.5632C12.9961 15.8442 12.9961 16.1534 12.9961 16.4345C12.9961 16.7436 12.9961 17.0809 12.9961 17.3901C12.9961 17.5868 13.1085 17.7555 13.2491 17.8679C13.4739 18.0084 13.6988 18.0365 13.9236 17.9241C14.1766 17.7836 14.289 17.5587 14.289 17.2777C14.289 16.7155 14.289 16.1815 14.289 15.6194C14.3171 15.5632 14.3171 15.4788 14.289 15.4226Z"
                                        fill="white" />
                                    <path
                                        d="M7.79251 14.918C7.39902 14.918 7.11795 15.1709 7.08984 15.5644C7.08984 16.1547 7.08984 16.7449 7.08984 17.3351C7.08984 17.7286 7.39902 17.9816 7.79251 17.9816C8.15789 17.9816 8.41085 17.7005 8.41085 17.307C8.41085 17.026 8.41085 16.7449 8.41085 16.4638C8.41085 16.1828 8.41085 15.8736 8.41085 15.5925C8.41085 15.199 8.15789 14.918 7.79251 14.918Z"
                                        fill="white" />
                                    <path
                                        d="M10.6611 12.3312C10.9141 12.2188 11.1109 11.9096 11.0546 11.6566C10.9984 11.3194 10.7455 11.0945 10.3801 11.0664C10.2114 11.0664 10.0428 11.0664 9.87416 11.0664C9.1715 11.0664 8.46884 11.0664 7.79428 11.0664C7.37268 11.0664 7.06351 11.3475 7.09162 11.7691C7.09162 12.1626 7.37268 12.3874 7.82239 12.3874C8.24399 12.3874 8.66558 12.3874 9.08718 12.3874C9.50878 12.3874 9.95849 12.3874 10.3801 12.3874C10.4644 12.3593 10.5487 12.3593 10.6611 12.3312Z"
                                        fill="white" />
                                    <path
                                        d="M12.9641 9.46438C12.9641 9.07089 12.7111 8.78983 12.3177 8.76172C11.7274 8.76172 8.32653 8.76172 7.73629 8.76172C7.3428 8.76172 7.08984 9.07089 7.08984 9.46438C7.08984 9.82977 7.37091 10.0827 7.7644 10.0827C8.04546 10.0827 8.32653 10.0827 8.60759 10.0827C8.88866 10.0827 12.0085 10.0827 12.2895 10.0827C12.683 10.0827 12.9641 9.82977 12.9641 9.46438Z"
                                        fill="white" />
                                    <path
                                        d="M20.3299 5.52959C20.3299 5.47337 20.3299 5.41717 20.3299 5.36095C20.3018 4.6864 19.8521 4.09615 19.2056 3.92751C18.9527 3.8713 18.7278 3.8713 18.4749 3.8713H16.6479C16.395 3.36538 15.8891 3 15.2707 3C13.1908 3 11.1391 3 9.05917 3C8.49704 3 8.04734 3.28107 7.76627 3.75888C7.71006 3.87131 7.62574 3.89941 7.51331 3.89941C7.06361 3.89941 6.64201 3.89941 6.19231 3.89941C5.91124 3.89941 5.63017 3.8713 5.34911 3.92751C4.53402 4.03994 4 4.68639 4 5.52959C4 8.00296 4 10.4763 4 12.9497C4 15.395 4 17.8402 4 20.2855C4 20.4541 4 20.5947 4.02811 20.7633C4.19675 21.5222 4.81509 22 5.60208 22C7.429 22 9.28402 22 11.1109 22C13.6405 22 16.142 22 18.6716 22C19.037 22 19.3743 21.9438 19.6834 21.7189C20.1612 21.3817 20.3299 20.9039 20.3299 20.3417C20.3299 15.3669 20.3299 10.4482 20.3299 5.52959ZM8.86242 4.63017C8.86242 4.37722 8.91864 4.32101 9.1716 4.32101C9.87426 4.32101 10.605 4.32101 11.3077 4.32101C12.5725 4.32101 13.8654 4.32101 15.1302 4.32101C15.4394 4.32101 15.4956 4.37722 15.4956 4.68639C15.4956 4.91124 15.4956 5.10799 15.4956 5.33284C15.4956 5.5858 15.4394 5.67012 15.1583 5.67012C14.3151 5.67012 13.4719 5.67012 12.6287 5.67012C12.4882 5.67012 12.3195 5.67012 12.179 5.67012C11.1953 5.67012 10.2115 5.67012 9.25592 5.67012C8.91864 5.67012 8.89053 5.6139 8.89053 5.27662C8.86243 5.05177 8.86242 4.82692 8.86242 4.63017ZM18.6997 20.6509C14.3432 20.6509 9.98669 20.6509 5.63017 20.6509C5.37722 20.6509 5.32101 20.5947 5.32101 20.3417C5.32101 15.395 5.32101 10.4482 5.32101 5.50149C5.32101 5.24853 5.37722 5.19231 5.63017 5.19231C6.22041 5.19231 6.78255 5.19231 7.37278 5.19231C7.51331 5.19231 7.54142 5.24853 7.54142 5.36095C7.54142 6.28847 8.18787 6.96301 9.08728 6.96301C11.1391 6.99112 13.1908 6.96301 15.2426 6.96301C16.0577 6.96301 16.676 6.40089 16.7885 5.5858C16.8166 5.44527 16.8166 5.33284 16.8166 5.19231H18.3343C18.4468 5.19231 18.5592 5.19231 18.6997 5.19231C18.9527 5.19231 19.0089 5.24853 19.0089 5.50149C19.0089 10.4482 19.0089 15.395 19.0089 20.3136C19.0089 20.5947 18.9808 20.6509 18.6997 20.6509Z"
                                        fill="white" />
                                </svg>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('User Report')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/login-report*')|| Request::is('admin/user-active/report/*')?'block':'none'}}">

                                <li class="nav-item {{Request::is('admin/login-report/report/1')?'active':''}}"
                                    title="{{\App\CPU\translate('User Login Report')}}">
                                    <a class="nav-link " href="{{route('admin.login-report.user.report', 1)}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M6.29592 21.949C4.94388 21.949 4 21.0051 4 19.6786C4 15.3163 4 10.9541 4 6.59184C4 5.23979 4.94388 4.32144 6.27041 4.32144H7.11225V3.88776C7.11225 3.70919 7.11225 3.53062 7.11225 3.37756C7.11225 2.58674 7.72449 2 8.51531 2C9.53571 2 10.5816 2 11.602 2C12.648 2 13.6939 2 14.7653 2C15.5561 2 16.1684 2.61224 16.1684 3.40306C16.1684 3.55612 16.1684 3.73469 16.1684 3.91326V4.34694H17.0102C18.3878 4.34694 19.3061 5.26531 19.3061 6.64286C19.3061 11.0051 19.3061 15.3418 19.3061 19.7041C19.3061 21.0561 18.3622 22 17.0357 22H11.6531L6.29592 21.949ZM6.32143 5.4949C5.58163 5.4949 5.22449 5.87756 5.22449 6.61735V19.6275C5.22449 20.3673 5.60714 20.75 6.34694 20.75H16.9592C17.75 20.75 18.1071 20.3929 18.1071 19.602V6.59184C18.1071 6.5153 18.1071 6.46429 18.1071 6.38776C18.0561 5.92857 17.699 5.57144 17.2653 5.52042C17.1378 5.52042 17.0357 5.52042 16.9082 5.52042C16.8316 5.52042 16.7551 5.52042 16.7041 5.52042H16.6531C16.551 5.52042 16.4745 5.52042 16.398 5.52042H16.2194L16.1939 5.67347C16.0663 6.64286 15.5561 7.07654 14.6122 7.07654H14.2551C14.1786 7.07654 14.102 7.07654 14.0255 7.07654H13.9745C13.3112 7.07654 12.8265 6.77041 12.5459 6.13265C12.4694 5.92857 12.2398 5.7245 12.0357 5.59695C11.9082 5.54593 11.7806 5.4949 11.6531 5.4949C11.2704 5.4949 10.9388 5.75 10.7602 6.13265C10.5306 6.66837 10.199 6.97449 9.71428 7.02551C9.45918 7.05102 9.22959 7.07654 8.97449 7.07654C8.71939 7.07654 8.4898 7.05102 8.2602 7.02551C7.62245 6.94898 7.2398 6.4643 7.11225 5.64797L7.08673 5.4949H6.32143ZM11.6786 4.29592C12.5204 4.29592 13.2857 4.80613 13.6429 5.59695C13.6939 5.69899 13.8214 5.87755 13.9745 5.87755H14.051C14.2296 5.87755 14.3827 5.87756 14.5612 5.85205C14.6378 5.85205 14.8673 5.82653 14.8673 5.82653H14.9439V3.14796H8.33673V5.82653C8.33673 5.82653 8.87245 5.82653 8.92347 5.82653C9 5.82653 9.10204 5.82653 9.17857 5.82653C9.20408 5.82653 9.20408 5.82653 9.22959 5.82653C9.48469 5.82653 9.58673 5.67348 9.66327 5.52042C10.0459 4.7296 10.6837 4.29593 11.551 4.24491C11.602 4.29593 11.6531 4.29592 11.6786 4.29592Z"
                                                fill="white" />
                                            <path
                                                d="M7.82549 14.1169C7.62141 14.1169 7.41733 14.0404 7.28978 13.9129C7.18774 13.8108 7.13672 13.6577 7.13672 13.5047C7.13672 13.1475 7.41733 12.918 7.851 12.918H15.4786C15.9122 12.918 16.1673 13.1475 16.1928 13.5047C16.1928 13.6577 16.1418 13.8108 16.0398 13.9129C15.9122 14.0404 15.7337 14.1169 15.5041 14.1169H11.6775H7.82549Z"
                                                fill="white" />
                                            <path
                                                d="M7.77447 17.2302C7.41733 17.2302 7.13672 16.9751 7.13672 16.6435C7.13672 16.4904 7.18774 16.3374 7.31529 16.2098C7.44284 16.0823 7.5959 16.0312 7.79998 16.0312C8.3357 16.0312 8.89692 16.0312 9.43264 16.0312H13.8204C14.4071 16.0312 14.9939 16.0312 15.5806 16.0312C15.8612 16.0312 16.0908 16.2098 16.1673 16.4649C16.2439 16.72 16.1418 16.9751 15.9377 17.1027C15.8102 17.1792 15.6826 17.2047 15.5806 17.2047C14.05 17.2047 12.5194 17.2047 11.0143 17.2047C9.89182 17.2302 8.8459 17.2302 7.77447 17.2302Z"
                                                fill="white" />
                                            <path
                                                d="M7.79998 10.9801C7.5959 10.9801 7.41733 10.9036 7.28978 10.8015C7.18774 10.6995 7.13672 10.5464 7.13672 10.3934C7.13672 10.0618 7.41733 9.80664 7.79998 9.80664C8.05509 9.80664 9.12651 9.80664 9.12651 9.80664H11.5755C11.9837 9.80664 12.2388 10.0617 12.2643 10.4189C12.2643 10.572 12.2132 10.725 12.1112 10.827C11.9837 10.9546 11.8051 11.0056 11.601 11.0056C10.9632 11.0056 10.3255 11.0056 9.71325 11.0056C9.101 11.0056 8.43774 10.9801 7.79998 10.9801Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('User Login Report')}}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/login-report/report/2')?'active':''}}"
                                    title="{{\App\CPU\translate('Vendor Login Report')}}">
                                    <a class="nav-link " href="{{route('admin.login-report.user.report', 2)}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M13.3758 21.9491C12.3057 21.9491 11.2102 21.9491 10.1401 21.9491C7.18471 21.9491 4.71338 19.9108 4.12739 17.0318C4.05096 16.6497 4 16.2166 4 15.7834C4 12.4459 4 9.08281 4 5.74523V4.31847C4 2.94267 4.94267 2 6.31847 2H18.5223C19.9236 2 20.8408 2.94268 20.8408 4.34395C20.8408 6.15287 20.8408 19.6051 20.8408 19.6051C20.8408 21.0573 19.8981 22 18.4713 22L13.3758 21.9491ZM7.64331 15.1975C8.53503 15.1975 9.1465 15.8089 9.1465 16.7006C9.1465 17.465 9.1465 18.2293 9.1465 18.9936V20.5733H9.27388C9.37579 20.5733 9.4777 20.5732 9.55414 20.5987C9.75796 20.6242 9.93631 20.6242 10.1146 20.6242C11.6688 20.6242 13.1975 20.6242 14.7516 20.6242H18.4713C19.1592 20.6242 19.5159 20.2675 19.5159 19.5796V4.29299C19.5159 3.6051 19.1592 3.24841 18.4713 3.24841H6.29299C5.6051 3.24841 5.24841 3.6051 5.24841 4.29299V15.1975H6.90446H7.64331ZM5.35032 16.6497C5.63057 18.1274 6.3949 19.1974 7.66879 19.9873L7.87261 20.1146V16.4968H5.32484L5.35032 16.6497Z"
                                                fill="white" />
                                            <path
                                                d="M16.2787 8.82889C16.2787 9.59322 16.2787 10.332 16.2787 11.0964C16.2787 11.4785 16.0748 11.7843 15.7436 11.8862C15.2341 12.0391 14.75 11.6824 14.75 11.0964C14.75 10.1537 14.75 9.21103 14.75 8.26836C14.75 7.68237 14.75 7.09639 14.75 6.5104C14.75 6.10276 15.0048 5.82251 15.3869 5.74607C15.6927 5.66964 16.0494 5.84799 16.1767 6.1792C16.2277 6.30659 16.2532 6.48493 16.2532 6.63779C16.3041 7.37665 16.2787 8.11551 16.2787 8.82889Z"
                                                fill="white" />
                                            <path
                                                d="M11.6445 9.5919C11.6445 9.08234 11.6445 8.59826 11.6445 8.08871C11.6445 7.68106 11.8738 7.40081 12.2815 7.2989C12.5872 7.22247 12.9439 7.40081 13.0713 7.70654C13.1222 7.83393 13.1477 7.96132 13.1477 8.08871C13.1477 9.08234 13.1477 10.1014 13.1477 11.0951C13.1477 11.4517 12.9948 11.7065 12.6636 11.8339C12.3834 11.9613 12.0267 11.8849 11.8483 11.6556C11.721 11.4772 11.6445 11.2734 11.6445 11.0696C11.6445 10.5855 11.6445 10.1015 11.6445 9.5919Z"
                                                fill="white" />
                                            <path
                                                d="M8.53516 10.4076C8.53516 10.1529 8.53516 9.92357 8.53516 9.66879C8.53516 9.21019 8.86637 8.85352 9.29949 8.85352C9.70713 8.85352 10.0638 9.21019 10.0638 9.66879C10.0638 10.1529 10.0638 10.637 10.0638 11.121C10.0638 11.5796 9.70713 11.9363 9.29949 11.9363C8.89184 11.9363 8.56064 11.5796 8.56064 11.1465C8.53516 10.8917 8.53516 10.6369 8.53516 10.4076Z"
                                                fill="white" />
                                            <path
                                                d="M13.9596 18.1776C13.9596 18.5853 13.6029 18.942 13.1952 18.942C12.8131 18.942 12.4564 18.5853 12.4309 18.2031C12.4054 17.821 12.7876 17.4388 13.1698 17.4388C13.5774 17.4133 13.9341 17.77 13.9596 18.1776Z"
                                                fill="white" />
                                            <path
                                                d="M17.0677 18.1776C17.0677 18.5853 16.6856 18.942 16.3034 18.942C15.9212 18.942 15.5645 18.5853 15.5391 18.2031C15.5391 17.7955 15.8958 17.4388 16.3034 17.4388C16.6856 17.4133 17.0677 17.77 17.0677 18.1776Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('Vendor Login Report')}}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/login-report/report/3')?'active':''}}"
                                    title="{{\App\CPU\translate('Driver Login Report')}}">
                                    <a class="nav-link " href="{{route('admin.login-report.user.report', 3)}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M6.61874 19.3556C6.33632 19.3556 6.07959 19.3556 5.82285 19.3556C4.79589 19.3299 4 18.5597 4 17.5327C4 15.8896 4 14.2465 4 12.6033C4 9.70218 4 6.80103 4 3.92554C4 2.97561 4.56482 2.25674 5.46341 2.05135C5.61745 2.02568 5.79718 2 5.95122 2C8.9294 2 11.9076 2 14.9114 2C16.0924 2 16.8883 2.7959 16.8883 3.9769C16.8883 4.18229 16.8883 4.38767 16.8883 4.64441C17.1194 4.64441 17.3504 4.64441 17.5558 4.64441C18.7625 4.64441 19.5327 5.41463 19.5327 6.62131C19.5327 11.0886 19.5327 15.5558 19.5327 20.0231C19.5327 21.2041 18.7625 22 17.5815 22C14.6033 22 11.6252 22 8.62131 22C7.44031 22 6.64441 21.2041 6.64441 20.0231C6.61874 19.792 6.61874 19.5867 6.61874 19.3556ZM5.0783 10.6778C5.0783 12.9114 5.0783 15.1451 5.0783 17.3787C5.0783 17.9949 5.36071 18.2773 5.97689 18.2773C8.92939 18.2773 11.9076 18.2773 14.8601 18.2773C15.4762 18.2773 15.7587 17.9949 15.7587 17.3787C15.7587 12.9371 15.7587 8.49551 15.7587 4.02825C15.7587 3.3864 15.4762 3.10398 14.8344 3.10398C11.8819 3.10398 8.92939 3.10398 5.97689 3.10398C5.36071 3.10398 5.0783 3.38639 5.0783 4.00256C5.0783 6.21052 5.0783 8.44415 5.0783 10.6778ZM7.72272 19.3556C7.72272 19.5867 7.72272 19.7664 7.72272 19.9461C7.72272 20.6136 8.00513 20.896 8.67265 20.896C11.5995 20.896 14.552 20.896 17.4788 20.896C18.0693 20.896 18.4031 20.5623 18.4031 19.9461C18.3774 15.5045 18.4031 11.0886 18.4031 6.64699C18.4031 6.56997 18.4031 6.49294 18.4031 6.41592C18.3774 6.08216 18.1463 5.77408 17.8126 5.7484C17.5045 5.72273 17.1964 5.7484 16.8626 5.7484C16.8626 5.8511 16.8626 5.92811 16.8626 6.03081C16.8626 9.80488 16.8626 13.5789 16.8626 17.353C16.8626 17.5584 16.837 17.7895 16.7856 17.9692C16.5545 18.8164 15.8357 19.3299 14.9114 19.3556C12.6008 19.3556 10.2901 19.3556 8.00514 19.3556C7.90244 19.3556 7.82542 19.3556 7.72272 19.3556Z"
                                                fill="white" />
                                            <path
                                                d="M10.4206 13.0391C11.3962 13.0391 12.3975 13.0391 13.3731 13.0391C13.8096 13.0391 14.092 13.4242 13.9123 13.7836C13.8096 14.0147 13.6042 14.1174 13.3474 14.1174C12.7569 14.1174 12.1408 14.1174 11.5503 14.1174C10.1895 14.1174 8.82883 14.1174 7.49378 14.1174C7.083 14.1174 6.82626 13.8093 6.8776 13.4755C6.92895 13.1931 7.16002 13.0391 7.46811 13.0391C8.46939 13.0391 9.445 13.0391 10.4206 13.0391Z"
                                                fill="white" />
                                            <path
                                                d="M10.4199 10.8577C11.3955 10.8577 12.3968 10.8577 13.3724 10.8577C13.8089 10.8577 14.0913 11.2685 13.9116 11.6279C13.8089 11.859 13.6035 11.936 13.3467 11.936C12.6792 11.936 12.0374 11.936 11.3698 11.936C10.0605 11.936 8.77678 11.936 7.46741 11.936C7.05662 11.936 6.79988 11.6279 6.8769 11.2685C6.92825 10.9861 7.15932 10.832 7.46741 10.832C8.46869 10.8577 9.4443 10.8577 10.4199 10.8577Z"
                                                fill="white" />
                                            <path
                                                d="M9.51942 16.3263C8.8519 16.3263 8.18437 16.3263 7.51685 16.3263C7.13174 16.3263 6.875 16.121 6.875 15.7872C6.875 15.4534 7.13175 15.248 7.49118 15.248C8.8519 15.248 10.1869 15.248 11.5477 15.248C11.9328 15.248 12.1638 15.4534 12.1895 15.7872C12.1895 16.121 11.9584 16.3263 11.5733 16.3263C10.8801 16.3263 10.2126 16.3263 9.51942 16.3263Z"
                                                fill="white" />
                                            <path
                                                d="M9.56915 8.41888H7.4639C6.92474 8.41888 6.48828 7.98243 6.48828 7.44327V5.59475C6.48828 5.05559 6.92474 4.61914 7.4639 4.61914H9.56915C10.1083 4.61914 10.5448 5.05559 10.5448 5.59475V7.44327C10.5448 7.98243 10.1083 8.41888 9.56915 8.41888ZM7.4639 5.44071C7.38687 5.44071 7.30985 5.51773 7.30985 5.59475V7.44327C7.30985 7.52029 7.38687 7.59731 7.4639 7.59731H9.56915C9.64617 7.59731 9.7232 7.52029 9.7232 7.44327V5.59475C9.7232 5.51773 9.64617 5.44071 9.56915 5.44071H7.4639Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('Driver Login Report')}}</span>
                                    </a>
                                </li>


                                <li class="nav-item {{Request::is('admin/user-active/report/1')?'active':''}}"
                                    title="{{\App\CPU\translate('User Active Report')}}">
                                    <a class="nav-link " href="{{route('admin.user-active.user.report', 1)}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M8.52857 17.6358C8.19528 17.6358 7.94531 17.3858 7.94531 17.0248C7.94531 16.5248 7.94531 16.0527 7.94531 15.5805V14.9695V14.8306C7.94531 14.7195 7.94531 14.6084 7.94531 14.4973C7.97309 14.1918 8.1675 13.9696 8.47302 13.9141C8.50079 13.9141 8.52857 13.9141 8.52857 13.9141C8.72298 13.9141 8.94518 14.0529 9.05627 14.2751C9.08405 14.3585 9.08405 14.4418 9.08405 14.5251C9.08405 14.5251 9.08405 16.7748 9.08405 17.0525C9.08405 17.4136 8.86185 17.6358 8.52857 17.6358Z"
                                                fill="white" />
                                            <path
                                                d="M11.4149 17.6654C11.3038 17.6654 11.1927 17.6376 11.0816 17.5543C10.9149 17.4432 10.8594 17.3044 10.8594 17.1377V15.9434C10.8594 15.749 10.8594 15.5546 10.8594 15.3602C10.8594 15.0547 11.0538 14.8325 11.3315 14.8047C11.3593 14.8047 11.3871 14.8047 11.4149 14.8047C11.6926 14.8047 11.9148 14.9713 11.9703 15.2491C11.9981 15.3046 11.9981 15.3601 11.9981 15.4435V15.6379C11.9981 16.1101 11.9981 16.5822 11.9981 17.0821C11.9981 17.3599 11.887 17.5543 11.6648 17.6376C11.5815 17.6376 11.4982 17.6654 11.4149 17.6654Z"
                                                fill="white" />
                                            <path
                                                d="M5.6106 17.6361C5.27731 17.6361 5.02734 17.3861 5.02734 17.0806C5.02734 16.4974 5.02734 15.9141 5.02734 15.3309C5.02734 14.9976 5.27731 14.7754 5.6106 14.7754C5.94389 14.7754 6.16608 15.0254 6.16608 15.3586C6.16608 15.5531 6.16608 15.7475 6.16608 15.9419V16.4696C6.16608 16.664 6.16608 16.8584 6.16608 17.0528C6.16608 17.3861 5.94389 17.6361 5.6106 17.6361Z"
                                                fill="white" />
                                            <path
                                                d="M5.6388 12.109C5.24997 12.109 5.02777 11.9146 5 11.5813C5 11.4147 5.05555 11.2758 5.16665 11.1647C5.27774 11.0536 5.44439 10.998 5.6388 10.998C5.94432 10.998 6.22206 10.998 6.52757 10.998H7.72186H8.02737C8.08292 10.998 8.16624 10.998 8.22179 10.998C8.5273 11.0258 8.7495 11.2202 8.80504 11.5258C8.83282 11.748 8.69395 11.9979 8.47175 12.109C8.38843 12.1368 8.33289 12.1368 8.22179 12.1368H6.11096L5.6388 12.109Z"
                                                fill="white" />
                                            <path
                                                d="M5.6106 9.85901C5.27731 9.85901 5.02734 9.63681 5.02734 9.33129C5.02734 9.16465 5.08289 9.02578 5.19399 8.91468C5.30508 8.80359 5.44395 8.74805 5.58282 8.74805C5.86056 8.74805 6.74933 8.74805 7.86029 8.74805C8.97126 8.74805 9.86002 8.74805 10.11 8.74805C10.4433 8.74805 10.6655 8.99801 10.6655 9.33129C10.6655 9.66458 10.4155 9.85901 10.0822 9.85901C9.97112 9.85901 9.38787 9.85901 8.72129 9.85901H5.91611C5.80502 9.85901 5.72169 9.85901 5.6106 9.85901Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M17.9978 13.1655V8.97142V5.41633V5.24969C17.9701 4.63866 17.5535 4.08317 16.9424 3.91652C16.7758 3.86099 16.5815 3.86099 16.4426 3.86099H16.4425H16.2481H14.3872L14.3594 3.80545C14.1372 3.30552 13.6373 3 13.0818 3H10.3044H6.94378C6.41607 3 5.99946 3.22219 5.72172 3.69435C5.66617 3.80545 5.55507 3.86099 5.4162 3.86099H4.74963H4.08305H3.86086H3.63867H3.63855C3.47197 3.86099 3.3609 3.86099 3.24983 3.88876C2.49993 3.99985 2 4.58311 2 5.38856V10.5545V20.0533C2 20.1921 2 20.3032 2.02777 20.4421C2.19442 21.1364 2.7499 21.5808 3.4998 21.5808H5.83281H12.6652H15.6358C15.1572 21.2804 14.7385 20.8935 14.4015 20.4421H3.4998C3.19428 20.4421 3.11096 20.3588 3.11096 20.0533V5.38856C3.11096 5.11082 3.22206 4.99972 3.4998 4.99972L3.63867 5.02748H4.44411H5.22179C5.44398 5.02748 5.47175 5.13859 5.47175 5.27746C5.47175 6.16623 6.08278 6.77726 6.916 6.77726H9.88782H13.0263C13.804 6.77726 14.3872 6.24956 14.4705 5.49966C14.4983 5.36081 14.4983 5.22194 14.4983 5.11085V5.11082V5.02748H16.0814H16.1925H16.4147C16.7202 5.02748 16.8036 5.11081 16.8036 5.41633V13.339C17.1837 13.2307 17.5841 13.1705 17.9978 13.1655ZM13.3318 5.19415C13.3318 5.49967 13.2207 5.61077 12.9152 5.61077L9.94337 5.58296H7.05487C6.69381 5.58296 6.61049 5.49966 6.61049 5.11082V4.4998C6.61049 4.19429 6.69381 4.11096 6.99932 4.11096H12.8874C13.2485 4.11096 13.3318 4.19428 13.3318 4.55534V5.19415Z"
                                                fill="white" />
                                            <path
                                                d="M12.832 20.2754C12.8876 20.3587 12.8598 20.442 12.832 20.5531C12.832 20.442 12.832 20.3587 12.832 20.2754Z"
                                                fill="white" />
                                            <path
                                                d="M21.9972 17.3031C21.9694 16.8588 21.8306 16.4144 21.6084 15.97C21.3584 15.47 21.0251 15.0257 20.5807 14.6646C20.053 14.2202 19.4142 13.9147 18.7199 13.8036C18.6365 13.7758 18.5532 13.7758 18.4699 13.7758C18.4143 13.7758 18.3588 13.7758 18.3033 13.748H18.2477C18.2199 13.748 18.2199 13.748 18.1922 13.748C18.1644 13.748 18.1644 13.748 18.1088 13.748H17.97C17.9422 13.748 17.9422 13.748 17.9144 13.748C17.8866 13.748 17.8866 13.748 17.8311 13.748H17.7478C17.72 13.748 17.6922 13.748 17.6367 13.748C17.6089 13.748 17.5534 13.748 17.5256 13.748C17.0812 13.8036 16.6646 13.9425 16.248 14.1647C15.7758 14.4146 15.3592 14.7201 15.0259 15.1367C14.5815 15.6645 14.276 16.3033 14.1649 16.9976C14.1649 17.0532 14.1372 17.1365 14.1372 17.2198C14.1372 17.2753 14.1371 17.3309 14.1094 17.3864V17.4698C14.1094 17.4975 14.1094 17.5531 14.1094 17.6086V17.7475C14.1094 17.7753 14.1094 17.8308 14.1094 17.8864V17.9697C14.1094 17.9975 14.1094 18.0252 14.1094 18.0808C14.1094 18.1086 14.1094 18.1641 14.1094 18.1919C14.1649 18.6085 14.276 19.0251 14.4704 19.3862C14.7204 19.8861 15.0537 20.3305 15.4981 20.6916C15.9702 21.0804 16.5257 21.3581 17.1367 21.497C17.4145 21.5526 17.6922 21.5803 17.9977 21.5803C18.1366 21.5803 18.3033 21.5803 18.4421 21.5525C18.8865 21.5248 19.3309 21.3859 19.7753 21.1637C20.2752 20.9138 20.7196 20.5805 21.0807 20.1361C21.4695 19.6639 21.7472 19.1084 21.8861 18.4974C22.025 18.1641 21.9972 17.6642 21.9972 17.3031ZM18.0255 14.6924C18.831 14.6924 19.5809 14.9979 20.1641 15.5811C20.7474 16.1644 21.0529 16.9143 21.0529 17.7197C21.0529 19.3862 19.692 20.7471 18.0255 20.7471C17.2201 20.7471 16.4702 20.4416 15.8869 19.8583C15.3037 19.2751 14.9981 18.5252 14.9981 17.7197C14.9981 16.0533 16.3591 14.6924 18.0255 14.6924Z"
                                                fill="white" />
                                            <path
                                                d="M16.914 17.4419C16.8306 17.3586 16.6918 17.303 16.5807 17.303C16.4696 17.303 16.3585 17.3586 16.2474 17.4419C16.053 17.6363 16.053 17.9141 16.2474 18.1085C16.4973 18.3585 16.7473 18.6084 16.9973 18.8584L17.1917 19.0528C17.275 19.1361 17.3861 19.1917 17.525 19.1917C17.6916 19.1917 17.8027 19.1083 17.8583 19.025L19.7747 17.1086C19.8025 17.0808 19.8302 17.0531 19.858 17.0253C19.9413 16.9142 19.9691 16.7476 19.9413 16.6087C19.8858 16.4698 19.7747 16.3587 19.6358 16.3032C19.5803 16.3032 19.5525 16.2754 19.4969 16.2754C19.3581 16.2754 19.247 16.331 19.1359 16.4421L18.4693 17.1086C18.1638 17.4141 17.8305 17.7474 17.4972 18.0529C17.3028 17.8585 17.1084 17.6641 16.914 17.4419Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('User Active Report')}}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/user-active/report/2')?'active':''}}"
                                    title="{{\App\CPU\translate('Vendor Active Report')}}">
                                    <a class="nav-link " href="{{route('admin.user-active.user.report', 2)}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M6.78573 14.0983C6.57981 14.0983 6.42537 14.0468 6.29667 13.9181C6.19371 13.8151 6.16797 13.6865 6.16797 13.5578C6.16797 13.2489 6.42537 13.043 6.81147 13.043H14.5077C14.8938 13.043 15.1255 13.2489 15.1512 13.5835C15.1512 13.7379 15.0998 13.8409 15.0225 13.9439C14.9196 14.0468 14.7394 14.124 14.5335 14.124H10.6725L6.78573 14.0983Z"
                                                fill="white" />
                                            <path
                                                d="M6.75999 10.9323C6.57981 10.9323 6.42537 10.8808 6.32241 10.7521C6.21945 10.6492 6.16797 10.5205 6.16797 10.3918C6.16797 10.0829 6.42537 9.87695 6.75999 9.87695C7.01739 9.87695 8.09847 9.87695 8.09847 9.87695H10.5695C10.9299 9.87695 11.1615 10.0829 11.1873 10.4175C11.1873 10.5719 11.1358 10.7006 11.0328 10.7779C10.9299 10.8808 10.7754 10.958 10.5695 10.958C9.92602 10.958 9.28251 10.958 8.66475 10.958C8.04699 10.958 7.40349 10.9323 6.75999 10.9323Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M3 19.7864C3 21.0734 3.92664 22 5.23939 22H10.6448H15.7892C15.1911 21.7686 14.659 21.4048 14.2286 20.9447H5.29087C4.49293 20.9447 4.08109 20.5071 4.08109 19.7349V6.60747C4.08109 5.80953 4.49292 5.39767 5.26512 5.39767H6.19177L6.21751 5.62934C6.32047 6.40154 6.68083 6.86486 7.29859 6.94208C7.50451 6.96782 7.73617 6.99358 7.99357 6.99358C8.20387 6.99358 8.41417 6.97235 8.64372 6.94918C8.66704 6.94682 8.69056 6.94445 8.71429 6.94208C9.17761 6.8906 9.51223 6.60747 9.71815 6.09267C9.89833 5.65509 10.2587 5.39767 10.6705 5.39767C10.825 5.39767 10.9794 5.42342 11.1081 5.50064C11.3398 5.6036 11.5457 5.83525 11.6487 6.06691C11.9318 6.68467 12.3694 6.99358 13.0129 6.99358H13.0644H13.296H13.6564C14.583 6.99358 15.0463 6.58172 15.175 5.65507L15.2008 5.42341H15.4324H15.6898H15.7413H15.9472C16.0116 5.42341 16.0695 5.42985 16.1274 5.43629C16.1853 5.44273 16.2433 5.44917 16.3076 5.44917C16.7967 5.47491 17.1828 5.88675 17.2342 6.37581V6.58171V13.8411C17.2602 13.8406 17.2862 13.8404 17.3122 13.8404C17.6486 13.8404 17.9759 13.8797 18.2896 13.9541V6.58171C18.2896 5.24323 17.3887 4.34234 16.0502 4.34234H15.1236V3.82754V3.33847C15.1236 2.56627 14.5315 2 13.7851 2H10.5933H7.47877C6.73231 2.02574 6.14029 2.59201 6.14029 3.33847V3.85327V4.36807H5.21365C3.92664 4.36807 3 5.26897 3 6.58171V19.7864ZM12.5753 5.62934C12.2407 4.8314 11.4942 4.34234 10.6705 4.34234H10.6705C10.6448 4.34234 10.5933 4.34234 10.5676 4.36807C9.71815 4.41955 9.10039 4.8314 8.71429 5.60361C8.63707 5.75805 8.50837 5.93821 8.19949 5.93821H8.14801H7.89061H7.22137V3.08107H14.0425V5.91247C14.0425 5.91247 13.6564 5.93821 13.5792 5.93821C13.5049 5.93821 13.4247 5.94416 13.3414 5.95035C13.2515 5.95703 13.1579 5.96397 13.0644 5.96397H12.9871C12.7812 5.96397 12.6268 5.75804 12.5753 5.62934Z"
                                                fill="white" />
                                            <path
                                                d="M20.9664 17.6756C20.9407 17.2638 20.812 16.8519 20.6061 16.4401C20.3744 15.9767 20.0655 15.5649 19.6537 15.2303C19.1646 14.8185 18.5726 14.5353 17.9291 14.4323C17.8519 14.4066 17.7747 14.4066 17.6974 14.4066C17.646 14.4066 17.5945 14.4066 17.543 14.3809H17.4915C17.4658 14.3809 17.4658 14.3809 17.44 14.3809C17.4143 14.3809 17.4143 14.3809 17.3628 14.3809H17.2341C17.2084 14.3809 17.2084 14.3809 17.1826 14.3809C17.1569 14.3809 17.1569 14.3809 17.1054 14.3809H17.0282C17.0025 14.3809 16.9767 14.3809 16.9252 14.3809C16.8995 14.3809 16.848 14.3809 16.8223 14.3809C16.4104 14.4323 16.0243 14.561 15.6382 14.767C15.2007 14.9986 14.8146 15.2818 14.5057 15.6679C14.0938 16.1569 13.8107 16.7489 13.7077 17.3924C13.7077 17.4439 13.682 17.5211 13.682 17.5984C13.682 17.6498 13.682 17.7013 13.6562 17.7528V17.83C13.6562 17.8558 13.6562 17.9073 13.6562 17.9587V18.0874C13.6562 18.1132 13.6562 18.1647 13.6562 18.2161V18.2933C13.6562 18.3191 13.6562 18.3448 13.6562 18.3963C13.6562 18.422 13.6562 18.4735 13.6562 18.4993C13.7077 18.8854 13.8107 19.2715 13.9909 19.6061C14.2225 20.0694 14.5314 20.4813 14.9433 20.8159C15.3808 21.1762 15.8956 21.4336 16.4619 21.5623C16.7193 21.6138 16.9767 21.6395 17.2599 21.6395C17.3886 21.6395 17.543 21.6395 17.6717 21.6138C18.0835 21.5881 18.4954 21.4594 18.9072 21.2534C19.3705 21.0218 19.7824 20.7129 20.117 20.3011C20.4774 19.8635 20.7348 19.3487 20.8635 18.7824C20.9922 18.4478 20.9922 18.0102 20.9664 17.6756ZM17.3113 15.256C18.0578 15.256 18.7528 15.5392 19.2933 16.0797C19.8339 16.6202 20.117 17.3152 20.117 18.0617C20.117 19.6061 18.8557 20.8673 17.3113 20.8673C16.5649 20.8673 15.8699 20.5842 15.3294 20.0437C14.7888 19.5031 14.5057 18.8082 14.5057 18.0617C14.5057 16.5173 15.7669 15.256 17.3113 15.256Z"
                                                fill="white" />
                                            <path
                                                d="M16.2568 17.8038C16.1796 17.7265 16.0509 17.6751 15.9479 17.6751C15.845 17.6751 15.742 17.7265 15.639 17.8038C15.4589 17.9839 15.4589 18.2413 15.639 18.4215C15.8707 18.6532 16.1024 18.8848 16.334 19.1165L16.5142 19.2967C16.5914 19.3739 16.6944 19.4254 16.8231 19.4254C16.9775 19.4254 17.0805 19.3481 17.132 19.2709L18.908 17.4949C18.9338 17.4691 18.9595 17.4434 18.9852 17.4177C19.0625 17.3147 19.0882 17.1603 19.0625 17.0316C19.011 16.9029 18.908 16.7999 18.7793 16.7484C18.7278 16.7484 18.7021 16.7227 18.6506 16.7227C18.5219 16.7227 18.419 16.7741 18.316 16.8771L17.6982 17.4949C17.4151 17.778 17.1062 18.0869 16.7973 18.37C16.6429 18.1898 16.437 17.9839 16.2568 17.8038Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M13.3789 16.207C13.2166 16.5565 13.1289 16.9315 13.0872 17.2624H9.92441C8.86933 17.2366 7.78852 17.2366 6.7077 17.2366H6.70691C6.39802 17.2366 6.14062 17.005 6.14062 16.7218C6.14062 16.5674 6.1921 16.4387 6.29506 16.3615C6.39802 16.2585 6.55246 16.207 6.70691 16.207H8.35427H12.7816H13.3789Z"
                                                fill="white" />
                                        </svg>
                                        <span
                                            class="text-truncate">{{\App\CPU\translate('Vendor Active Report')}}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/user-active/report/3')?'active':''}}"
                                    title="{{\App\CPU\translate('Driver Active Report')}}">
                                    <a class="nav-link " href="{{route('admin.user-active.user.report', 3)}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M5.00122 11.6293C5.00746 11.2423 5.34748 10.9639 5.76033 10.9705C6.43121 10.9813 7.1021 10.9922 7.79878 11.0034L8.62447 11.0167L9.16633 11.0254C9.57918 11.0321 9.99204 11.0388 10.4049 11.0454C10.7919 11.0516 11.0208 11.2618 11.092 11.6501C11.0912 11.7017 11.0907 11.7275 11.0899 11.7791C11.0858 12.0372 10.9272 12.2669 10.6933 12.3664C10.5635 12.4159 10.434 12.4396 10.2792 12.4371C9.73734 12.4284 9.16967 12.4192 8.6278 12.4105L7.31185 12.3893C6.79579 12.3809 6.27973 12.3726 5.73787 12.3639C5.37662 12.3581 5.12149 12.1733 5.02327 11.862C4.99872 11.7842 4.99997 11.7067 5.00122 11.6293Z"
                                                fill="white" />
                                            <path
                                                d="M4.9943 8.83244C4.99722 8.65182 5.07752 8.47245 5.2082 8.37132C5.36468 8.2706 5.59818 8.19693 5.77921 8.17404C6.14087 8.15406 6.50211 8.15989 6.83755 8.1653C6.99237 8.1678 7.12137 8.16988 7.27619 8.17237L7.74066 8.17986C8.1019 8.18569 8.43734 8.1911 8.79858 8.19692C9.18563 8.20316 9.41454 8.41332 9.51193 8.77623C9.51109 8.82784 9.53647 8.85406 9.53564 8.90566C9.53148 9.16369 9.37292 9.39343 9.13903 9.4929C9.03498 9.54284 8.90554 9.56656 8.80233 9.5649C7.82181 9.54909 6.7897 9.53245 5.75758 9.51581C5.42214 9.5104 5.19239 9.35183 5.06795 9.06592C5.01718 9.01348 4.99306 8.90985 4.9943 8.83244Z"
                                                fill="white" />
                                            <path
                                                d="M4.99797 5.684C5.00421 5.29695 5.34424 5.01852 5.7575 4.99938C5.91232 5.00187 6.09292 5.00479 6.24774 5.00728L6.47997 5.01103L6.7122 5.01477C6.89283 5.01768 7.04764 5.02018 7.22827 5.02309C7.66692 5.03016 7.97157 5.3448 7.96533 5.73184C7.95909 6.11889 7.61865 6.42311 7.2058 6.41646C6.68974 6.40814 6.19949 6.40023 5.73504 6.39274C5.32219 6.38609 4.99173 6.07105 4.99797 5.684Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M8.09032 21.9484H11.3677L16.529 22C16.6655 22 16.7974 21.991 16.9242 21.9734C16.271 21.7612 15.6875 21.3943 15.2168 20.9161H12.7871H8.09032H8.09014C7.88377 20.9161 7.70317 20.9161 7.49678 20.8903C7.41938 20.8645 7.31618 20.8645 7.21298 20.8645H7.21291L6.98065 20.8387V19.1097V16.7871C6.98065 15.9613 6.4129 15.3935 5.5871 15.3935H4.83871H3.03226V4.21935C3.03226 3.44515 3.44516 3.03226 4.21936 3.03226H16.5548C17.329 3.03226 17.7419 3.47096 17.7419 4.21935V13.7435C17.9032 13.7248 18.0671 13.7153 18.2333 13.7153C18.4255 13.7153 18.6146 13.7281 18.8 13.7529V4.24515C18.8 2.90321 17.9226 2 16.5806 2H4.21936C2.90323 2 2 2.90322 2 4.21935V5.6645V15.8323C2 16.271 2.05161 16.6838 2.12903 17.0709C2.72258 19.9355 5.1742 21.9484 8.09032 21.9484ZM5.50968 20.2451C4.19355 19.4193 3.41936 18.2839 3.10968 16.7613L3.03226 16.4516H5.89678V20.4774L5.50968 20.2451Z"
                                                fill="white" />
                                            <path
                                                d="M21.8954 17.561C21.8696 17.1481 21.7406 16.7352 21.5341 16.3223C21.3019 15.8578 20.9922 15.4449 20.5793 15.1094C20.089 14.6965 19.4954 14.4126 18.8503 14.3094C18.7729 14.2836 18.6954 14.2836 18.618 14.2836C18.5664 14.2836 18.5148 14.2836 18.4632 14.2578H18.4116C18.3858 14.2578 18.3858 14.2578 18.36 14.2578C18.3341 14.2578 18.3341 14.2578 18.2825 14.2578H18.1535C18.1277 14.2578 18.1277 14.2578 18.1019 14.2578C18.0761 14.2578 18.0761 14.2578 18.0245 14.2578H17.9471C17.9212 14.2578 17.8954 14.2578 17.8438 14.2578C17.818 14.2578 17.7664 14.2578 17.7406 14.2578C17.3277 14.3094 16.9406 14.4385 16.5535 14.6449C16.1148 14.8772 15.7277 15.161 15.418 15.5481C15.0051 16.0385 14.7212 16.632 14.618 17.2772C14.618 17.3288 14.5922 17.4062 14.5922 17.4836C14.5922 17.5352 14.5922 17.5868 14.5664 17.6385V17.7159C14.5664 17.7417 14.5664 17.7933 14.5664 17.8449V17.9739C14.5664 17.9997 14.5664 18.0513 14.5664 18.1029V18.1804C14.5664 18.2062 14.5664 18.232 14.5664 18.2836C14.5664 18.3094 14.5664 18.361 14.5664 18.3868C14.618 18.7739 14.7212 19.161 14.9019 19.4965C15.1341 19.961 15.4438 20.3739 15.8567 20.7094C16.2954 21.0707 16.8116 21.3288 17.3793 21.4578C17.6374 21.5094 17.8954 21.5352 18.1793 21.5352C18.3083 21.5352 18.4632 21.5352 18.5922 21.5094C19.0051 21.4836 19.418 21.3546 19.8309 21.1481C20.2954 20.9159 20.7083 20.6062 21.0438 20.1933C21.4051 19.7546 21.6632 19.2385 21.7922 18.6707C21.9212 18.3352 21.9212 17.8965 21.8954 17.561ZM18.2309 15.1352C18.9793 15.1352 19.6761 15.4191 20.218 15.961C20.76 16.503 21.0438 17.1997 21.0438 17.9481C21.0438 19.4965 19.7793 20.761 18.2309 20.761C17.4825 20.761 16.7858 20.4772 16.2438 19.9352C15.7019 19.3933 15.418 18.6965 15.418 17.9481C15.418 16.3997 16.6567 15.1352 18.2309 15.1352Z"
                                                fill="white" />
                                            <path
                                                d="M17.1728 17.6913C17.0954 17.6139 16.9664 17.5622 16.8631 17.5622C16.7599 17.5622 16.6567 17.6139 16.5535 17.6913C16.3728 17.8719 16.3728 18.13 16.5535 18.3106C16.7857 18.5429 17.018 18.7751 17.2502 19.0074L17.4309 19.1881C17.5083 19.2655 17.6115 19.3171 17.7406 19.3171C17.8954 19.3171 17.9986 19.2397 18.0502 19.1623L19.8309 17.3816C19.8567 17.3558 19.8825 17.33 19.9083 17.3042C19.9857 17.201 20.0115 17.0461 19.9857 16.9171C19.9341 16.7881 19.8309 16.6848 19.7018 16.6332C19.6502 16.6332 19.6244 16.6074 19.5728 16.6074C19.4438 16.6074 19.3406 16.659 19.2373 16.7623L18.618 17.3816C18.3341 17.6655 18.0244 17.9752 17.7147 18.259C17.5341 18.0784 17.3535 17.8977 17.1728 17.6913Z"
                                                fill="white" />
                                        </svg>
                                        <span
                                            class="text-truncate">{{\App\CPU\translate('Driver Active Report')}}</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <!-- Plan End  -->





                        <!--promotion management end-->
                        @endif

                        <!--support section -->
                        @if(\App\CPU\Helpers::module_permission_check('support_section'))

                        <li class="d-none navbar-vertical-aside-has-menu {{Request::is('admin/support-ticke')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:void(0)"
                                title="{{\App\CPU\translate('help_&_support_section')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M12.5165 21.9981C12.1532 21.9233 11.6892 21.7977 11.2837 21.4948C10.3903 20.8266 10.0298 19.6849 10.3855 18.6534C10.7382 17.6305 11.7103 16.9432 12.8051 16.9432C12.8271 16.9432 12.8492 16.9432 12.8712 16.9442C13.9458 16.971 14.9093 17.7216 15.216 18.7694L15.2438 18.8662C15.3023 18.8739 15.3665 18.8777 15.4327 18.8777C16.2475 18.8777 17.5426 18.1971 18.0775 17.4944H17.9951C17.9376 17.4944 17.881 17.4944 17.8245 17.4935C17.4055 17.4867 17.1927 17.2701 17.1927 16.8502V16.4514C17.1918 14.6655 17.1918 12.8192 17.1956 10.9441C17.1956 10.8089 17.2234 10.6622 17.2733 10.5299C17.3605 10.2979 17.533 10.1762 17.7737 10.1762C17.7832 10.1762 17.7928 10.1762 17.8024 10.1772C17.8599 10.1791 17.9165 10.18 17.9731 10.18C17.9903 10.18 18.1025 10.1791 18.1677 10.1791C18.229 8.83123 18.1725 7.4067 17.3614 6.11638C16.1574 4.19911 14.4664 3.22801 12.3353 3.22801C12.1963 3.22801 12.0554 3.23185 11.9154 3.24047C9.08267 3.40248 6.84522 5.48943 6.47423 8.31453C6.41671 8.75071 6.41192 9.18593 6.40712 9.64703V9.66812C6.40521 9.83972 6.40329 10.0084 6.3985 10.1791H6.74744C7.11076 10.181 7.32933 10.3727 7.37918 10.7332C7.39068 10.8194 7.39068 10.9038 7.39068 10.9853V11.0198C7.39164 12.9562 7.39164 14.8927 7.39068 16.8291C7.39068 17.2902 7.18554 17.4963 6.72635 17.4963C6.5653 17.4963 6.40425 17.4973 6.2432 17.4992H6.1828C6.02846 17.5002 5.88467 17.5011 5.74183 17.5011C5.42356 17.5011 5.16377 17.4963 4.92603 17.4858C3.8792 17.4398 3.04136 16.5991 3.01739 15.5704C2.99343 14.5274 2.99438 13.4212 3.01931 12.1884C3.03944 11.2173 3.85428 10.1772 5.03628 10.1772C5.06504 10.1772 5.09475 10.1781 5.12447 10.1791C5.14364 10.18 5.16282 10.18 5.18391 10.18H5.20787C5.20787 10.1302 5.20787 10.0832 5.20787 10.0362C5.20883 9.89148 5.20883 9.74769 5.20787 9.60293C5.20595 8.99995 5.20308 8.37684 5.33345 7.76331C5.85974 5.27757 7.27757 3.52998 9.54953 2.56751C10.4391 2.19077 11.3709 2 12.319 2C15.3895 2 18.0948 3.99013 19.0496 6.95135C19.2835 7.678 19.3938 8.4612 19.3851 9.34506C19.3832 9.54925 19.3832 9.75344 19.3842 9.96242V10.1743C19.7657 10.2011 20.0878 10.3257 20.3677 10.5549C20.965 11.0428 21.2928 11.6822 21.3177 12.4041C21.357 13.5822 21.3455 14.7355 21.3283 15.7094C21.3129 16.5943 20.7588 17.2375 19.8827 17.388C19.6487 17.4283 19.505 17.527 19.3688 17.7417C18.6086 18.9362 17.5187 19.6859 16.1296 19.9687C15.9158 20.0118 15.7059 20.0338 15.4835 20.0568C15.402 20.0655 15.3272 20.0731 15.2515 20.0818C15.0837 20.8305 14.638 21.3817 13.9276 21.721C13.7436 21.8092 13.5451 21.8648 13.3524 21.9185L13.34 21.9224C13.247 21.9482 13.1693 21.9703 13.0936 21.9942L13.0696 22H12.5165V21.9981ZM12.7773 18.1357C12.4197 18.1367 12.0822 18.2795 11.8292 18.5374C11.5838 18.7876 11.4505 19.1126 11.4553 19.4529C11.4658 20.1776 12.0813 20.7902 12.7993 20.7902H12.8214C13.5336 20.7777 14.1098 20.2006 14.1059 19.5037C14.1021 18.7234 13.5327 18.1357 12.7811 18.1357H12.7773ZM5.15706 11.3831C4.6001 11.3831 4.22144 11.7493 4.21664 12.2948C4.20802 13.2477 4.20706 14.2638 4.21568 15.4937C4.21856 15.9098 4.556 16.2635 4.96821 16.2817C5.1168 16.2885 5.27593 16.2913 5.4715 16.2913C5.60379 16.2913 5.73608 16.2904 5.87029 16.2885H5.89905C5.99108 16.2875 6.08119 16.2865 6.17034 16.2865V11.3841H5.63063L5.15706 11.3831ZM18.4131 16.2817C18.4888 16.2827 18.5636 16.2837 18.6393 16.2846H18.6527C18.7889 16.2865 18.9068 16.2885 19.0237 16.2885C19.1934 16.2885 19.3324 16.2846 19.4599 16.276C19.8798 16.2472 20.1223 16.0258 20.1233 15.6682C20.1281 14.6588 20.1319 13.5794 20.1204 12.5277C20.1166 12.1625 19.9833 11.8433 19.735 11.6036C19.6171 11.4905 19.4628 11.408 19.3401 11.3956C19.2193 11.3831 19.0851 11.3764 18.9183 11.3764C18.8234 11.3764 18.7285 11.3783 18.6316 11.3802C18.5482 11.3822 18.4792 11.3831 18.4131 11.3841V16.2817Z"
                                        fill="#fff" />
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('help_&_support_section')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/support-ticke')?'block':'none'}}">
                                <li
                                    class="nav-item {{(Request::is('admin/support-ticket*') || Request::is('admin/contact*'))?'scroll-here':''}}">
                                    <small class="nav-subtitle"
                                        title="">{{\App\CPU\translate('help_&_support_section')}}</small>
                                    <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                                </li>
                                <li
                                    class="navbar-vertical-aside-has-menu {{Request::is('admin/contact*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.contact.list')}}"
                                        title="{{\App\CPU\translate('messages')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M21.6407 10.1437C21.3669 8.86624 20.3176 8.0222 19.0174 8.0222C17.3521 8.0222 15.6869 8.0222 14.0216 8.0222C13.862 8.0222 13.7935 7.99941 13.7935 7.81692C13.7935 6.67635 13.7935 5.51294 13.7935 4.37237C13.7935 2.98087 12.8126 2 11.4211 2C9.04874 2 6.67635 2 4.32677 2C3.00371 2 2 3.00369 2 4.32676C2 5.90075 2 7.49756 2 9.07155C2 9.25405 2.02281 9.43651 2.06844 9.61901C2.31936 10.6683 3.1862 11.3755 4.25834 11.3983C4.3952 11.3983 4.44083 11.4439 4.41802 11.5808C4.41802 11.9458 4.41802 12.3336 4.41802 12.6986C4.41802 12.8126 4.41802 12.9267 4.46364 13.0179C4.69175 13.8163 5.64984 14.0672 6.24293 13.497C6.74479 12.9951 7.22383 12.4933 7.72568 11.9914C7.74849 12.0142 7.7713 12.0142 7.7713 12.037C7.7713 12.1055 7.7713 12.1739 7.7713 12.2423C7.7713 13.6338 7.7713 15.0025 7.7713 16.394C7.7713 16.6678 7.79411 16.9187 7.86255 17.1696C8.18191 18.333 9.20842 19.1086 10.4402 19.1086C11.6493 19.1086 12.8583 19.1086 14.0445 19.1086C14.1813 19.1086 14.2726 19.1542 14.3638 19.2455C15.1622 20.0439 15.9378 20.8195 16.7362 21.6179C17.1012 21.9828 17.5574 22.0969 18.0593 21.9144C18.5155 21.7319 18.7892 21.3213 18.7892 20.7738C18.7892 20.272 18.7892 19.7929 18.7892 19.2911C18.7892 19.1314 18.8349 19.1086 18.9717 19.1086C20.5457 19.0858 21.6863 17.9452 21.6863 16.3712C21.6863 14.5235 21.6863 12.6529 21.6863 10.8052C21.6863 10.5543 21.6863 10.349 21.6407 10.1437ZM7.79411 10.3262C7.7713 10.5315 7.70287 10.6912 7.54319 10.8508C6.90447 11.4667 6.28856 12.1055 5.64983 12.7214C5.5814 12.7898 5.53578 12.8811 5.42172 12.8354C5.33047 12.7898 5.35329 12.6986 5.35329 12.6073C5.35329 12.0827 5.35329 11.558 5.35329 11.0105C5.35329 10.5999 5.17079 10.4402 4.783 10.4402C4.53207 10.4402 4.30396 10.463 4.05303 10.4174C3.41431 10.3034 2.93527 9.7787 2.93527 9.11717C2.93527 7.49755 2.93527 5.87793 2.93527 4.25831C2.93527 3.52835 3.52837 2.95807 4.25834 2.95807C5.46734 2.95807 6.69916 2.95807 7.90817 2.95807C9.07155 2.95807 10.2349 2.95807 11.3983 2.95807C12.3108 2.95807 12.8583 3.50555 12.8583 4.41801C12.8583 5.55858 12.8583 6.69915 12.8583 7.86253C12.8583 8.04503 12.7898 8.06784 12.6301 8.06784C11.8089 8.06784 10.9877 8.04503 10.1665 8.09065C8.9575 8.11346 7.93098 9.11718 7.79411 10.3262ZM20.751 16.3712C20.751 17.3977 20.0211 18.1277 18.9945 18.1505C18.7892 18.1505 18.5839 18.1505 18.3786 18.1505C18.0365 18.1505 17.854 18.333 17.854 18.6752C17.854 19.3595 17.854 20.0211 17.854 20.7054C17.854 20.7966 17.854 20.9107 17.7627 20.9791C17.6259 21.0932 17.489 21.0247 17.3749 20.9107C16.7362 20.272 16.0747 19.6104 15.436 18.9717C15.2307 18.7892 15.0482 18.5839 14.8429 18.3786C14.6832 18.1961 14.5007 18.1277 14.2726 18.1277C12.9951 18.1277 11.7405 18.1277 10.4631 18.1277C9.59622 18.1277 8.93469 17.6259 8.72938 16.8275C8.68376 16.6678 8.68376 16.5081 8.68376 16.3484C8.68376 14.4778 8.68376 12.6073 8.68376 10.7368C8.68376 9.68745 9.41373 8.9803 10.4402 8.9803C11.8546 8.9803 13.2689 8.9803 14.706 8.9803C16.1203 8.9803 17.5346 8.9803 18.9717 8.9803C19.8158 8.9803 20.4773 9.48215 20.6826 10.2577C20.7282 10.4174 20.7282 10.5771 20.7282 10.714C20.751 12.6073 20.751 14.4779 20.751 16.3712Z"
                                                fill="white" />
                                            <path
                                                d="M14.043 13.5418C14.043 13.154 14.3623 12.8574 14.7501 12.8574C15.1379 12.8574 15.4345 13.154 15.4345 13.5418C15.4345 13.9296 15.1379 14.2489 14.7501 14.2489C14.3395 14.2489 14.043 13.9296 14.043 13.5418Z"
                                                fill="white" />
                                            <path
                                                d="M11.125 13.5652C11.125 13.1774 11.4216 12.8809 11.8322 12.8809C12.22 12.8809 12.5393 13.2002 12.5165 13.588C12.5165 13.953 12.1971 14.2724 11.8322 14.2724C11.4444 14.2495 11.125 13.953 11.125 13.5652Z"
                                                fill="white" />
                                            <path
                                                d="M18.3329 13.5646C18.3329 13.9524 18.0135 14.2489 17.6258 14.2489C17.238 14.2489 16.9414 13.9296 16.9414 13.5418C16.9414 13.1768 17.2608 12.8574 17.6486 12.8574C18.0364 12.8574 18.3329 13.1768 18.3329 13.5646Z"
                                                fill="white" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            <span class="position-relative">
                                                {{\App\CPU\translate('messages')}}
                                                @php($message=\App\Model\Contact::where('seen',0)->count())
                                                @if($message!=0)
                                                <span
                                                    class="btn-status btn-xs-status btn-status-danger position-absolute top-0 menu-status"></span>
                                                @endif
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li
                                    class="navbar-vertical-aside-has-menu {{Request::is('admin/support-ticket*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.support-ticket.view')}}"
                                        title="{{\App\CPU\translate('Support_Ticket')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M5.54738 22C4.71562 22 4.1178 21.4801 4.01383 20.6484C3.96184 20.3365 4.06581 20.0246 4.16978 19.7127C5.1315 16.7495 7.00295 14.7481 9.81014 13.7864C9.83613 13.7864 9.86212 13.7604 9.88811 13.7604L10.0181 13.7084L9.75815 13.5265C9.62819 13.4225 9.49823 13.3445 9.39426 13.2405C9.16033 13.0586 8.97838 12.8767 8.82243 12.6687C8.74445 12.5647 8.66647 12.5128 8.53651 12.5128C8.27658 12.5128 8.01666 12.5128 7.75674 12.5128H7.02895C5.88528 12.5128 5.10551 11.733 5.10551 10.5893C5.10551 10.1215 5.10551 8.25 5.10551 8.25C5.10551 7.83412 5.20948 7.47022 5.44341 7.13231C5.52139 7.02834 5.57337 6.89841 5.59936 6.76845C5.62536 6.69047 5.65135 6.63847 5.67734 6.56049C6.5091 4.68904 7.93868 3.18149 9.88811 2.4797C12.1235 1.64794 14.6707 1.90785 16.6721 3.18148C18.0237 4.03923 19.0115 5.39082 19.5833 6.87239C19.6093 6.97636 19.6613 7.08035 19.7392 7.18432C19.9472 7.47023 20.0252 7.80814 20.0252 8.19803V8.63989C20.0252 9.2897 20.0252 9.9395 20.0252 10.6153C20.0252 11.707 19.2454 12.4868 18.1537 12.4868C17.9198 12.4868 17.6858 12.4868 17.4779 12.4868C17.192 12.4868 16.9061 12.4868 16.6202 12.4868C16.4902 12.4868 16.3862 12.5388 16.3082 12.6427C15.9963 13.0066 15.6324 13.3445 15.1906 13.6304L15.1126 13.6824L15.2166 13.7084C15.7884 13.9163 16.2303 14.0723 16.6202 14.2802C18.9335 15.5019 20.441 17.4773 21.0908 20.1285C21.1948 20.5704 21.1168 21.0123 20.8569 21.3502C20.571 21.7141 20.1291 21.922 19.6093 21.922C18.1537 21.922 16.6981 21.922 15.2166 21.922H8.53651L5.54738 22ZM12.6433 14.4622C12.1754 14.4622 11.6816 14.5142 11.1617 14.5922C8.14662 15.112 5.75532 17.3733 5.07951 20.3884C5.05352 20.4924 5.05352 20.6224 5.10551 20.7264C5.20948 20.9083 5.39143 20.9343 5.57337 20.9343C6.97696 20.9343 8.35456 20.9343 9.75815 20.9343H12.5913H18.8295C19.0634 20.9343 19.3234 20.9343 19.5573 20.9343C19.7133 20.9343 19.8432 20.8823 19.9212 20.7783C19.9992 20.7004 20.0252 20.5704 19.9732 20.4405C19.5053 18.465 18.4916 16.9315 16.984 15.8918C15.7104 14.9301 14.2548 14.4622 12.6433 14.4622ZM12.5913 5.62477C12.4094 5.62477 12.2274 5.65075 12.0195 5.67674C10.9798 5.8327 10.0181 6.40455 9.39426 7.28829C8.77044 8.14604 8.53651 9.23773 8.77044 10.2774C9.13433 12.0709 10.7459 13.3965 12.6173 13.3965C13.2151 13.3965 13.813 13.2665 14.3588 12.9806C14.8007 12.7727 15.1646 12.4608 15.5025 12.0709L15.5545 12.0189L15.5025 11.9929C15.4505 11.9669 15.3985 11.9409 15.3465 11.9409H15.2685H14.4368C14.2548 11.9409 14.0729 11.9409 13.9169 11.9409C13.605 11.9409 13.4231 11.811 13.3451 11.551C13.2931 11.3691 13.3191 11.2131 13.4231 11.0832C13.5271 10.9532 13.709 10.8752 13.9429 10.8752C14.2808 10.8752 14.5927 10.8752 14.9306 10.8752C15.2945 10.8752 15.6584 10.8752 16.0223 10.8752C16.1783 10.8752 16.2563 10.8233 16.3082 10.6673C16.4122 10.3034 16.4902 9.9395 16.4902 9.60159C16.5162 8.5619 16.0743 7.54822 15.3205 6.76845C14.5668 6.01467 13.605 5.62477 12.5913 5.62477ZM7.47082 11.4731C7.57479 11.4731 7.65277 11.4731 7.75674 11.4731H7.80872C7.8607 11.4731 7.93868 11.473 7.96468 11.4211C7.99067 11.3691 7.99067 11.2911 7.93868 11.2131C7.62677 10.4074 7.5488 9.52364 7.70475 8.6139C7.75674 8.30199 7.83471 7.99008 7.99067 7.60019L8.01666 7.54822L7.96468 7.5222C7.93868 7.49621 7.91269 7.49621 7.8867 7.49621H6.95097C6.4831 7.49621 6.1712 7.78214 6.1712 8.25C6.1712 9.05577 6.1712 9.86154 6.1712 10.6933C6.1712 11.1612 6.4831 11.4471 6.95097 11.4731H7.08093H7.47082ZM17.14 7.5742C17.6339 8.84783 17.6339 10.0955 17.14 11.3951L17.114 11.4731H17.8418C17.9718 11.4731 18.1277 11.4731 18.2577 11.4731C18.6736 11.4471 18.9595 11.1611 18.9595 10.7713C18.9595 9.96549 18.9595 9.10776 18.9595 8.22402C18.9595 7.86012 18.6995 7.54819 18.3616 7.5222C18.2317 7.5222 18.0757 7.49621 17.8938 7.49621C17.7898 7.49621 17.6599 7.49621 17.5299 7.49621C17.3999 7.49621 17.296 7.49621 17.166 7.49621H17.088L17.14 7.5742ZM7.8607 6.43053C8.09464 6.43053 8.30258 6.43053 8.53651 6.43053C8.66647 6.43053 8.71845 6.37855 8.79643 6.30057C9.73216 5.1829 10.9538 4.61107 12.4094 4.55909C12.4614 4.55909 12.5393 4.55909 12.5913 4.55909C14.1249 4.55909 15.3985 5.1569 16.3862 6.32656C16.4382 6.37854 16.4902 6.43053 16.5942 6.43053C16.8541 6.43053 17.088 6.43053 17.3479 6.43053H18.2057L18.1537 6.35254C17.088 4.35113 14.9566 3.10349 12.6173 3.10349C12.4354 3.10349 12.2794 3.10351 12.0975 3.12951C9.6022 3.31145 7.78273 4.89697 7.08093 6.35254L7.05494 6.43053H7.8607Z"
                                                fill="white" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            <span class="position-relative">
                                                {{\App\CPU\translate('Support_Ticket')}}
                                                @if(\App\Model\SupportTicket::where('status','open')->count()>0)
                                                <span
                                                    class="btn-status btn-xs-status btn-status-danger position-absolute top-0 menu-status"></span>
                                                @endif
                                            </span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        <!--support section ends here-->







                        <!--System Settings-->
                        @if(\App\CPU\Helpers::module_permission_check('system_settings'))
                        <li
                            class="nav-item {{(Request::is('admin/business-settings/social-media') || Request::is('admin/business-settings/web-config/app-settings') || Request::is('admin/business-settings/terms-condition') || Request::is('admin/business-settings/page*') || Request::is('admin/business-settings/privacy-policy') || Request::is('admin/business-settings/about-us') || Request::is('admin/helpTopic/list') || Request::is('admin/business-settings/fcm-index') || Request::is('admin/business-settings/mail')|| Request::is('admin/business-settings/web-config/db-index')||Request::is('admin/business-settings/web-config/environment-setup') || Request::is('admin/business-settings/web-config') || Request::is('admin/business-settings/cookie-settings')  || Request::is('admin/business-settings/all-pages-banner*') || Request::is('admin/business-settings/otp-setup') || Request::is('admin/system-settings/software-update') || Request::is('admin/business-settings/web-config/theme/setup') || Request::is('admin/business-settings/delivery-restriction')) ? 'scroll-here' : '' }}">
                            <small class="nav-subtitle" title="">{{\App\CPU\translate('System_Settings')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>



                        <li
                            class="d-none navbar-vertical-aside-has-menu {{(Request::is('admin/business-settings/mail') || Request::is('admin/business-settings/sms-module') || Request::is('admin/business-settings/captcha') || Request::is('admin/social-login/view') || Request::is('admin/social-media-chat/view') || Request::is('admin/business-settings/map-api') || Request::is('admin/business-settings/payment-method') || Request::is('admin/business-settings/fcm-index'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{route('admin.business-settings.sms-module')}}"
                                title="{{\App\CPU\translate('3rd_party')}}">
                                <!-- ================================================== -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M20.9385 6.09782C20.624 5.9329 20.3078 5.84958 20 5.84958C19.5801 5.84958 19.1754 6.0043 18.7963 6.30694C18.1468 6.82891 17.9189 7.51921 18.1196 8.35742C18.1553 8.50364 18.1604 8.67197 17.9376 8.80798C17.2099 9.25174 16.4567 9.72101 15.7018 10.2022C15.6491 10.2362 15.576 10.2753 15.4927 10.2753C15.3754 10.2753 15.2972 10.2056 15.2343 10.1359C14.4131 9.23304 13.3232 8.73658 12.1637 8.73658C11.3748 8.73658 10.5876 8.97121 9.88535 9.41667C9.82585 9.45407 9.75444 9.49318 9.66773 9.49318C9.55041 9.49318 9.4671 9.42176 9.41439 9.36906C8.78361 8.72807 8.13242 8.07348 7.42343 7.37129C7.28061 7.23017 7.26021 7.09246 7.35542 6.91053C7.62916 6.38346 7.72097 5.85129 7.63596 5.28171C7.44043 3.98103 6.28937 3 4.95809 3C4.90369 3 4.84928 3.0017 4.79317 3.0051C3.43299 3.08671 2.30234 4.25818 2.27513 5.61496C2.25983 6.36646 2.53187 7.06525 3.03853 7.58382C3.5367 8.09219 4.23039 8.38463 4.93939 8.38463C5.37125 8.38463 5.7929 8.28092 6.19416 8.07689C6.24347 8.05138 6.30977 8.02418 6.38458 8.02418C6.5019 8.02418 6.58351 8.09218 6.63452 8.14489C7.34011 8.85899 7.993 9.51188 8.62889 10.1393C8.77681 10.2855 8.79381 10.43 8.6833 10.6102C7.77878 12.0741 7.75837 13.5737 8.62209 15.0648C8.7598 15.3029 8.64929 15.4321 8.52177 15.529C8.09502 15.8537 7.63935 16.2006 7.19899 16.5542C7.14459 16.5984 7.05788 16.6579 6.95076 16.6579C6.83855 16.6579 6.75354 16.5967 6.68553 16.5372C6.15336 16.0782 5.53617 15.8452 4.84928 15.8452C4.76597 15.8452 4.68266 15.8486 4.59765 15.8554C3.04874 15.9762 1.93339 17.2717 2.00309 18.8699C2.066 20.3032 3.35818 21.5138 4.81697 21.5138C4.87308 21.5087 4.92749 21.5036 4.9836 21.5002C5.09921 21.4917 5.20803 21.4832 5.31174 21.4628C6.12785 21.2996 6.80624 20.8507 7.2211 20.1995C7.63595 19.5483 7.75667 18.7458 7.56114 17.9348C7.52034 17.7648 7.56454 17.6424 7.70396 17.537C8.38235 17.0269 8.89923 16.6358 9.37869 16.2669C9.4484 16.2142 9.51641 16.187 9.58612 16.187C9.67283 16.187 9.74084 16.2261 9.79014 16.2601C11.1333 17.1816 13.0087 17.1629 14.3672 16.2839C15.5454 15.5222 16.2612 14.1875 16.2289 12.7848C16.2204 12.387 16.1524 11.9925 16.0368 11.6117C15.9722 11.4008 16.0215 11.2648 16.2034 11.1509C17.0144 10.6459 17.8135 10.1393 18.582 9.6462C18.6874 9.57819 18.7793 9.54758 18.8762 9.54758C18.9561 9.54758 19.0377 9.56969 19.1295 9.61559C19.41 9.75671 19.7093 9.82982 20.0187 9.82982C21.0797 9.82982 21.9485 9.01711 21.9961 7.97997C22.042 7.11796 21.6832 6.48547 20.9385 6.09782ZM4.9734 7.29648H4.94959C4.07227 7.28288 3.36328 6.56028 3.37008 5.68637C3.37688 4.79204 4.08757 4.09155 4.98699 4.09155H5.0074C5.87961 4.10175 6.58351 4.82775 6.57501 5.71017C6.56821 6.58409 5.84901 7.29648 4.9734 7.29648ZM4.96659 20.2063H4.94789C4.07398 20.1961 3.36668 19.4752 3.36838 18.5962C3.37178 17.7155 4.09098 16.998 4.9734 16.998H4.9836C5.41205 17.0014 5.81331 17.1714 6.11425 17.4792C6.41689 17.7869 6.58011 18.1916 6.57501 18.62C6.56651 19.4939 5.84391 20.2063 4.96659 20.2063ZM12.1569 15.9047C10.4804 15.9047 9.11345 14.5446 9.10835 12.8732C9.10665 12.0622 9.4212 11.3005 9.99417 10.7241C10.5688 10.1478 11.3357 9.82812 12.1501 9.82812H12.1535C13.8214 9.82812 15.1799 11.1883 15.1816 12.8579C15.1833 14.5327 13.8299 15.8996 12.1569 15.9047ZM20.0306 8.73488C19.7943 8.73488 19.5716 8.64306 19.4032 8.47474C19.2349 8.30641 19.1414 8.08368 19.1414 7.84905C19.1414 7.36619 19.5325 6.97004 20.0136 6.96324H20.0255H20.0272C20.5152 6.96324 20.913 7.36109 20.9147 7.84905C20.9164 8.32172 20.5169 8.72807 20.0306 8.73488Z" fill="white"/>
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('3rd_party')}}
                                </span>
                            </a>
                        </li>

                        <li
                            class=" navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/driver-page/*') || Request::is('admin/business-settings/vendor-page/*') || Request::is('admin/business-settings/terms-condition') || Request::is('admin/business-settings/page*') || Request::is('admin/business-settings/privacy-policy') || Request::is('admin/business-settings/about-us') || Request::is('admin/helpTopic/list') || Request::is('admin/business-settings/social-media') || Request::is('admin/file-manager*') || Request::is('admin/business-settings/features-section') ?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:"
                                title="{{\App\CPU\translate('Pages_&_Media')}}">






                                <!-- ==================================================== -->



                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M17.4266 15.2512C17.3932 15.2121 17.3624 15.1698 17.3264 15.1338C16.4941 14.2995 15.6619 13.4656 14.8286 12.6323C14.5056 12.3094 14.1852 12.3104 13.8617 12.6334C13.2862 13.2084 12.708 13.7813 12.1393 14.3631C12.0281 14.4764 11.9665 14.469 11.8648 14.359C11.6707 14.1487 11.4656 13.9478 11.2585 13.75C11.0158 13.5183 10.6809 13.4896 10.4487 13.7145C9.93052 14.2165 9.43012 14.7378 8.92294 15.2512C8.24513 15.9254 7.56367 16.5964 6.8916 17.2763C6.71733 17.4527 6.67976 17.6775 6.78516 17.9097C6.89265 18.1466 7.0831 18.2619 7.34295 18.2625C11.2308 18.2625 15.1187 18.2625 19.0065 18.2625C19.2664 18.2625 19.4563 18.1472 19.5649 17.9108C19.6718 17.6781 19.6306 17.4521 19.4584 17.2773C18.7858 16.5964 18.1044 15.9259 17.4266 15.2512ZM8.89998 17.0321C8.91876 16.9705 8.96625 16.9449 9.00121 16.91C9.52874 16.3788 10.0657 15.856 10.5687 15.3008C10.6099 15.2554 10.6522 15.2084 10.7049 15.1766C10.7988 15.1197 10.8927 15.1432 10.9725 15.2063C11.1379 15.3368 11.3007 15.4599 11.4562 15.6055C11.6044 15.7438 11.7902 15.8737 11.9968 15.8946C12.2514 15.9207 12.4022 15.7469 12.5786 15.5919C13.151 15.0884 13.6749 14.5395 14.2228 14.0109C14.3496 13.8893 14.4038 13.9452 14.5171 14.0584C14.756 14.2974 14.994 14.5364 15.233 14.7753C15.4725 15.0154 15.7167 15.2502 15.9525 15.4933C16.1993 15.748 16.4461 16.0026 16.6929 16.2572C16.8166 16.3845 16.9397 16.5119 17.0634 16.6392C17.1709 16.7498 17.3619 16.8881 17.4224 17.0258C17.2283 17.0618 16.9987 17.031 16.7999 17.031C16.5875 17.031 16.3746 17.031 16.1623 17.031C15.7375 17.031 15.3128 17.031 14.8875 17.031C14.0386 17.031 13.1891 17.031 12.3401 17.031C12.0526 17.0321 8.89998 17.0321 8.89998 17.0321Z" fill="white"/>
                                <path d="M10.8248 12.394C11.7875 12.3955 12.5817 11.6092 12.5864 10.6501C12.5906 9.67595 11.8027 8.87657 10.8358 8.875C9.87571 8.87344 9.07841 9.66343 9.07424 10.6199C9.07006 11.5941 9.85797 12.3924 10.8248 12.394Z" fill="white"/>
                                <path d="M21.943 7.96733C21.8548 7.52276 21.6498 7.13507 21.2292 6.83452C20.8191 6.54127 20.351 6.50057 19.872 6.52979C19.6878 6.54075 19.6404 6.47814 19.6497 6.30229C19.6717 5.87756 19.6555 5.45595 19.4175 5.07869C19.0873 4.4849 18.5681 4.18643 17.9038 4.18278C15.7707 4.1713 13.6382 4.17756 11.5051 4.18069C11.3726 4.18069 11.3079 4.14521 11.2447 4.01685C10.9384 3.39435 10.4276 3.02075 9.73623 3.01292C7.72734 2.99153 5.7174 3.00197 3.70798 3.00718C2.96234 3.00875 2.27097 3.56341 2.07739 4.28766C1.94537 4.7818 2.02521 5.2848 2.01216 5.78363C2.01216 7.19195 2.01216 8.60026 2.01268 10.0081C2.01268 11.1033 2.01268 12.1985 2.01268 13.2943C2.0106 13.7164 2.00642 14.138 2.00694 14.5602C2.00799 15.573 2.0106 16.5852 2.01268 17.598C2.0106 18.0212 2.00381 18.4449 2.00799 18.8681C2.01738 19.825 2.7938 20.6067 3.75233 20.6072C9.25306 20.6103 14.7543 20.6124 20.255 20.6041C20.9558 20.603 21.4755 20.255 21.8068 19.6314C21.9602 19.3429 22.0025 19.011 21.9999 18.6886C21.9915 17.5839 21.9942 16.4783 21.9942 15.3721C21.9942 14.8226 21.9942 14.2732 21.9942 13.7237C21.9942 12.0509 21.991 10.378 21.9988 8.70514C21.9988 8.68844 21.9988 8.67123 21.9988 8.65453C22.0015 8.41398 21.9863 8.18387 21.943 7.96733ZM4.68321 7.15281C4.23186 7.73357 4.3237 8.50164 4.32943 9.20241C4.34248 10.8471 4.36909 12.4923 4.38161 14.137C4.39257 15.5401 4.38579 16.9432 4.37483 18.3463C4.37431 18.4365 4.37222 18.5294 4.36492 18.6207V18.8242C4.36492 19.1509 4.10037 19.4154 3.77373 19.4154C3.44709 19.4154 3.18254 19.1509 3.18254 18.8242V18.4908C3.18254 18.4705 3.18358 18.4501 3.18567 18.4303C3.17367 17.693 3.19454 16.9505 3.20132 16.2121C3.21124 15.1352 3.23941 14.0587 3.24046 12.9817C3.24098 12.5507 3.2415 12.1192 3.24098 11.6882C3.24046 11.3136 3.23941 10.9395 3.23785 10.5648C3.23159 8.64253 3.24098 6.71921 3.24098 4.79641C3.25507 4.45776 3.45857 4.25844 3.79773 4.2407C3.87548 4.23652 3.95375 4.23861 4.03149 4.23861C5.59165 4.23861 7.15128 4.23861 8.71144 4.23861C9.00416 4.23861 9.29637 4.23444 9.58909 4.24018C10.3259 4.25479 10.0295 5.09435 10.5972 5.34742C11.0339 5.54152 11.7337 5.41421 12.2012 5.41421C12.8101 5.41473 13.419 5.41525 14.0285 5.41577C15.2297 5.41629 16.4308 5.41577 17.6315 5.40847C17.9028 5.4069 18.1772 5.46534 18.3171 5.71945C18.4183 5.90312 18.5514 6.47866 18.2148 6.46353C18.1371 6.45988 18.0588 6.46353 17.9805 6.46353C14.0509 6.46353 10.1213 6.46248 6.19171 6.46405C5.61356 6.46509 5.04377 6.68946 4.68321 7.15281ZM20.7878 11.8197C20.7909 13.5166 20.7831 15.214 20.7653 16.9108C20.7601 17.4018 20.7596 17.8923 20.7559 18.3828C20.7549 18.5273 20.7575 18.6828 20.7392 18.8263C20.7038 19.105 20.5911 19.3403 20.2263 19.3685C20.1418 19.3747 20.0573 19.3721 19.9727 19.3721C18.172 19.3721 16.3713 19.3721 14.5706 19.3721C11.802 19.3721 9.0339 19.3721 6.26528 19.3721C6.26267 19.3721 6.26006 19.3732 6.25745 19.3732H5.65896C5.58173 19.3732 5.51912 19.3105 5.51912 19.2333V18.8352C5.51912 18.8347 5.51912 18.8347 5.51912 18.8341C5.51807 17.8088 5.51599 16.7835 5.51442 15.7582C5.51234 14.5205 5.50294 13.2499 5.50346 11.9997C5.50346 11.3564 5.50712 10.713 5.51651 10.0696C5.52538 9.468 5.47007 8.80533 5.59947 8.21779C5.66626 7.91359 5.8969 7.76905 6.19953 7.76174C6.26476 7.76018 6.32946 7.76122 6.39468 7.76122C10.9165 7.76122 15.4384 7.76122 19.9602 7.76122C20.0317 7.76122 20.1032 7.75966 20.1747 7.76279C20.95 7.79201 20.7711 8.74115 20.7752 9.27389C20.782 10.1228 20.7862 10.9713 20.7878 11.8197Z" fill="white"/>
                                </svg>


                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Pages_&_Media')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/business-settings/vendor-page/*') || Request::is('admin/business-settings/terms-condition') || Request::is('admin/business-settings/page*') || Request::is('admin/business-settings/privacy-policy') || Request::is('admin/business-settings/about-us') || Request::is('admin/helpTopic/list') || Request::is('admin/business-settings/social-media') || Request::is('admin/file-manager*') || Request::is('admin/business-settings/features-section')?'block':'none'}}">
                                <li
                                    class="nav-item {{(Request::is('admin/business-settings/terms-condition') || Request::is('admin/business-settings/page*') || Request::is('admin/business-settings/privacy-policy') || Request::is('admin/business-settings/about-us') || Request::is('admin/helpTopic/list')|| Request::is('admin/business-settings/features-section'))?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.business-settings.terms-condition')}}"
                                        title="{{\App\CPU\translate('pages')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M7.74609 16.2227C7.74609 15.8084 8.08188 15.4727 8.49609 15.4727H15.7161C16.1303 15.4727 16.4661 15.8084 16.4661 16.2227C16.4661 16.6369 16.1303 16.9727 15.7161 16.9727H8.49609C8.08188 16.9727 7.74609 16.6369 7.74609 16.2227Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M7.74609 12.0371C7.74609 11.6229 8.08188 11.2871 8.49609 11.2871H15.7161C16.1303 11.2871 16.4661 11.6229 16.4661 12.0371C16.4661 12.4513 16.1303 12.7871 15.7161 12.7871H8.49609C8.08188 12.7871 7.74609 12.4513 7.74609 12.0371Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M7.74609 7.85938C7.74609 7.44516 8.08188 7.10938 8.49609 7.10938H11.2511C11.6653 7.10938 12.0011 7.44516 12.0011 7.85938C12.0011 8.27359 11.6653 8.60938 11.2511 8.60938H8.49609C8.08188 8.60938 7.74609 8.27359 7.74609 7.85938Z"
                                                fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M15.908 2C17.4684 2 18.8017 2.51495 19.7441 3.48761C20.6837 4.45737 21.165 5.80983 21.165 7.357V16.553C21.165 18.0929 20.6872 19.4393 19.7554 20.4075C18.8207 21.3785 17.498 21.8974 15.9496 21.907L15.945 21.907L8.25664 21.91C6.6962 21.91 5.36245 21.395 4.42028 20.4223C3.48095 19.4525 3 18.1001 3 16.553V7.357C3 5.81725 3.47746 4.47105 4.40912 3.50304C5.34357 2.53213 6.66607 2.01355 8.21438 2.00401L8.219 2.00399L15.908 2ZM15.9082 3.5C15.9081 3.5 15.9082 3.5 15.9082 3.5L8.22362 3.50399C8.22294 3.50399 8.22226 3.50399 8.22159 3.504C7.01091 3.51189 6.09959 3.90972 5.48988 4.54321C4.87704 5.17995 4.5 6.12675 4.5 7.357V16.553C4.5 17.7899 4.88005 18.741 5.49772 19.3787C6.11251 20.0134 7.03234 20.41 8.25579 20.41C8.25573 20.41 8.25586 20.41 8.25579 20.41L15.9404 20.407C15.941 20.407 15.9417 20.407 15.9423 20.407C17.1529 20.3991 18.0646 20.0011 18.6746 19.3673C19.2878 18.7302 19.665 17.7831 19.665 16.553V7.357C19.665 6.12017 19.2848 5.16913 18.6669 4.53139C18.0518 3.8966 17.1316 3.50007 15.9082 3.5Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('pages')}}
                                        </span>
                                    </a>
                                </li>

                                <li
                                    class="nav-item {{ Request::is('admin/business-settings/vendor-page/*') ?'active':''}}">
                                    <a class="nav-link"
                                        href="{{route('admin.business-settings.vendor.page',['vendor-privacy-policy'])}}"
                                        title="{{\App\CPU\translate('Vendor_policy_pages')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M14.7366 2.76175H8.08455C6.00455 2.75279 4.29955 4.41079 4.25055 6.49079V17.3398C4.21555 19.3898 5.84855 21.0808 7.89955 21.1168C7.96055 21.1168 8.02255 21.1168 8.08455 21.1148H16.0726C18.1416 21.0938 19.8056 19.4088 19.8026 17.3398V8.03979L14.7366 2.76175Z"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M14.4727 2.75V5.659C14.4727 7.079 15.6217 8.23 17.0417 8.234H19.7957"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M12.9 17H8" stroke="white" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M16 13H8" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Seller_policy_pages')}}
                                        </span>
                                    </a>
                                </li>

                                <li
                                    class="nav-item {{ Request::is('admin/business-settings/driver-page/*') ?'active':''}}">
                                    <a class="nav-link"
                                        href="{{route('admin.business-settings.driver.page',['driver-privacy-policy'])}}"
                                        title="{{\App\CPU\translate('Driver_policy_pages')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M7.29592 21.949C5.94388 21.949 5 21.0051 5 19.6786C5 15.3163 5 10.9541 5 6.59184C5 5.23979 5.94388 4.32142 7.27041 4.32142H8.11224V3.88776C8.11224 3.70919 8.11224 3.53062 8.11224 3.37756C8.11224 2.58674 8.72449 2 9.5153 2C10.5357 2 11.5816 2 12.602 2C13.648 2 14.6939 2 15.7653 2C16.5561 2 17.1684 2.61224 17.1684 3.40306C17.1684 3.55612 17.1684 3.73469 17.1684 3.91326V4.34693H18.0102C19.3878 4.34693 20.3061 5.26529 20.3061 6.64284C20.3061 11.0051 20.3061 15.3418 20.3061 19.7041C20.3061 21.0561 19.3622 22 18.0357 22H12.6531L7.29592 21.949ZM7.32143 5.52042C6.58163 5.52042 6.22449 5.90305 6.22449 6.64284V19.653C6.22449 20.3928 6.60714 20.7755 7.34694 20.7755H17.9592C18.75 20.7755 19.1071 20.4184 19.1071 19.6275V6.61734C19.1071 6.54081 19.1071 6.48981 19.1071 6.41328C19.0561 5.95409 18.699 5.59694 18.2653 5.54592C18.1378 5.54592 18.0357 5.54592 17.9082 5.54592C17.8316 5.54592 17.7551 5.54592 17.7041 5.54592H17.6531C17.551 5.54592 17.4745 5.54592 17.398 5.54592H17.2449L17.2194 5.69898C17.0918 6.66837 16.5816 7.10204 15.6378 7.10204H15.2806C15.2041 7.10204 15.1275 7.10204 15.051 7.10204H15C14.3367 7.10204 13.852 6.79593 13.5714 6.15817C13.4949 5.95409 13.2653 5.74999 13.0612 5.62244C12.9337 5.57141 12.8061 5.52042 12.6786 5.52042C12.2959 5.52042 11.9643 5.77552 11.7857 6.15817C11.5561 6.69389 11.2245 7.00001 10.7398 7.05103C10.4847 7.07654 10.2551 7.10204 10 7.10204C9.7449 7.10204 9.51531 7.07654 9.28571 7.05103C8.64796 6.9745 8.26531 6.4898 8.13776 5.67347L8.11224 5.52042H7.32143ZM12.7041 4.29592C13.5459 4.29592 14.3112 4.80612 14.6684 5.59693C14.7194 5.69897 14.8469 5.87754 15 5.87754H15.0765C15.2551 5.87754 15.4082 5.87754 15.5867 5.85203C15.6633 5.85203 15.8929 5.82653 15.8929 5.82653H15.9694V3.14796H9.36225V5.82653C9.36225 5.82653 9.89796 5.82653 9.94898 5.82653C10.0255 5.82653 10.1275 5.82653 10.2041 5.82653C10.2296 5.82653 10.2296 5.82653 10.2551 5.82653C10.5102 5.82653 10.6122 5.67348 10.6888 5.52042C11.0714 4.7296 11.7092 4.29593 12.5765 4.24491C12.6275 4.29593 12.6531 4.29592 12.7041 4.29592Z"
                                                fill="white" />
                                            <path
                                                d="M8.85284 14.1169C8.64876 14.1169 8.44468 14.0404 8.31713 13.9128C8.21508 13.8108 8.16406 13.6577 8.16406 13.5047C8.16406 13.1475 8.44468 12.918 8.87835 12.918H16.5059C16.9396 12.918 17.1947 13.1475 17.2202 13.5047C17.2202 13.6577 17.1692 13.8108 17.0671 13.9128C16.9396 14.0404 16.761 14.1169 16.5314 14.1169H12.7049H8.85284Z"
                                                fill="white" />
                                            <path
                                                d="M8.77447 17.2556C8.41733 17.2556 8.13672 17.0005 8.13672 16.6689C8.13672 16.5158 8.18774 16.3628 8.31529 16.2352C8.44284 16.1077 8.5959 16.0566 8.79998 16.0566C9.3357 16.0566 9.89692 16.0566 10.4326 16.0566H14.8204C15.4071 16.0566 15.9939 16.0566 16.5806 16.0566C16.8612 16.0566 17.0908 16.2352 17.1673 16.4903C17.2439 16.7454 17.1418 17.0005 16.9377 17.1281C16.8102 17.2046 16.6826 17.2301 16.5806 17.2301C15.05 17.2301 13.5194 17.2301 12.0143 17.2301C10.9173 17.2556 9.8459 17.2556 8.77447 17.2556Z"
                                                fill="white" />
                                            <path
                                                d="M8.82733 10.9801C8.62325 10.9801 8.44468 10.9036 8.31713 10.8015C8.21508 10.6995 8.16406 10.5464 8.16406 10.3934C8.16406 10.0618 8.44468 9.80664 8.82733 9.80664C9.08243 9.80664 10.1539 9.80664 10.1539 9.80664H12.6028C13.011 9.80664 13.2661 10.0617 13.2916 10.4189C13.2916 10.572 13.2406 10.725 13.1386 10.827C13.011 10.9546 12.8324 11.0056 12.6283 11.0056C11.9906 11.0056 11.3528 11.0056 10.7406 11.0056C10.1283 11.0056 9.46508 10.9801 8.82733 10.9801Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Driver_policy_pages')}}
                                        </span>
                                    </a>
                                </li>

                                <!-- <li
                                    class="navbar-vertical-aside-has-menu d-none{{Request::is('admin/business-settings/social-media')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.business-settings.social-media')}}"
                                        title="{{\App\CPU\translate('Social_Media_Links')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M21.558 7.55251V6.08841C21.558 5.31493 21.3646 4.62432 21.0055 4.01659C20.2044 2.69062 18.9889 2 17.442 2C15.8121 2 14.2099 2 12.5801 2H6.03315C5.95028 2 5.8674 2 5.78452 2C5.06629 2.05525 4.37569 2.27625 3.79558 2.66299C3.79558 2.66299 3.76795 2.66298 3.76795 2.69061C3.74033 2.71823 3.71271 2.71825 3.68508 2.74588C3.62984 2.7735 3.6022 2.82876 3.57458 2.85638C3.21546 3.16025 2.58011 3.74033 2.35911 4.34807C2.11049 4.90055 2 5.48067 2 6.11603V9.37571C2 10.7293 2 12.0553 2 13.4088C2 13.6298 2 13.8508 2.02763 14.0718C2.221 15.8674 3.68508 17.3867 5.45304 17.6077C5.83978 17.663 6.22651 17.663 6.61325 17.663H6.86188C7 17.663 7.13812 17.663 7.27624 17.663C7.27624 17.9669 7.27624 18.6022 7.27624 18.6022C7.27624 19.3205 7.27624 20.0387 7.27624 20.7569C7.27624 21.3923 7.52486 21.8066 8.07735 22H8.10497H8.57458L8.65746 21.9448C8.71271 21.8895 8.76795 21.8619 8.85082 21.8066C8.98894 21.6961 9.15469 21.5856 9.29281 21.4475C10.5083 20.1768 11.6961 18.9061 12.8011 17.7182C12.8564 17.663 12.884 17.6354 12.9668 17.6354C13.7679 17.6354 14.5691 17.6354 15.3702 17.6354H17.5801C17.7182 17.6354 17.8563 17.6354 17.9945 17.6077C20.1215 17.3591 21.6133 15.674 21.6133 13.547C21.558 11.558 21.558 9.51384 21.558 7.55251ZM20.453 13.5194C20.453 13.7403 20.4254 13.9337 20.3978 14.0995C20.1492 15.5359 18.9337 16.5304 17.4696 16.558C16.8066 16.558 16.1436 16.558 15.453 16.558C14.5691 16.558 13.6575 16.558 12.7735 16.558C12.4696 16.558 12.1934 16.6685 11.9724 16.9171C10.7569 18.2155 9.5138 19.5138 8.27071 20.8398C8.27071 19.6796 8.27071 18.547 8.27071 17.3868C8.27071 16.8343 7.99447 16.558 7.44198 16.558H6.66851C6.36464 16.558 6.06077 16.558 5.78452 16.558C5.39778 16.558 5.0663 16.4751 4.7348 16.3094C3.60221 15.7569 3.0221 14.8177 2.99448 13.547C2.99448 11.9448 2.99448 10.2873 2.99448 8.71272C2.99448 7.88399 2.99448 7.05525 2.99448 6.25415C2.99448 5.75691 3.07735 5.31494 3.24309 4.9282C3.24309 4.90058 3.27072 4.87294 3.27072 4.84532C3.29834 4.79007 3.32596 4.73483 3.35359 4.67958C3.57458 4.32046 3.98895 3.76798 4.70718 3.40887C5.09392 3.24312 5.53591 3.16024 6.00552 3.16024C7.74585 3.16024 11.9724 3.16024 11.9724 3.16024C13.768 3.16024 15.5635 3.16024 17.3591 3.16024C17.5801 3.16024 17.8011 3.18785 17.9945 3.21548C19.4033 3.46409 20.3978 4.7072 20.3978 6.19891C20.453 8.43648 20.453 10.8674 20.453 13.5194Z"
                                                fill="white" />
                                            <path
                                                d="M14.0428 5.86719C13.3246 5.86719 12.6616 6.14345 12.1367 6.61306C11.9986 6.75119 11.8881 6.86168 11.75 7.02743L11.8605 7.11028L11.75 7.05505L11.7224 7.02743C11.5842 6.86168 11.4461 6.69592 11.2804 6.55779C10.7831 6.11581 10.1201 5.86719 9.42954 5.86719C8.84943 5.86719 8.26932 6.06056 7.79971 6.39206C7.1091 6.88929 6.69473 7.63516 6.61186 8.43626C6.55661 9.26499 6.86048 10.0937 7.49584 10.7291C8.35219 11.5854 9.20854 12.4418 10.0649 13.2981L11.0594 14.2926C11.2804 14.5136 11.5014 14.6241 11.75 14.6241C11.9986 14.6241 12.2196 14.5136 12.4406 14.2926C12.7997 13.9335 13.1588 13.5744 13.5179 13.2153L14.319 12.4142C14.3467 12.3865 14.3743 12.3865 14.3743 12.3589L15.7003 11.033C15.8384 10.8948 15.9765 10.7567 16.1146 10.591C17.1643 9.45837 17.1091 7.74565 16.0317 6.6683C15.4793 6.14344 14.7887 5.86719 14.0428 5.86719ZM15.3135 9.81748C15.2306 9.90035 15.1478 9.98322 15.0649 10.0937L14.8439 10.3147C14.8163 10.3424 14.8163 10.3423 14.7887 10.37C14.6782 10.4805 14.5677 10.5909 14.4572 10.7014L13.0759 12.0827C12.634 12.5246 12.2196 12.939 11.7776 13.381L11.75 13.4086L10.7003 12.3589C9.89915 11.5578 9.09804 10.7291 8.26931 9.92798C7.82733 9.48599 7.63396 8.93348 7.77208 8.35337C7.88258 7.74564 8.26932 7.30366 8.84943 7.08266C9.07042 6.99979 9.29142 6.97216 9.48479 6.97216C9.87153 6.97216 10.2583 7.11031 10.6174 7.41418C10.7279 7.52467 10.8384 7.60754 10.9489 7.71804C11.0041 7.77328 11.0594 7.82853 11.1146 7.88377C11.3356 8.07715 11.5566 8.18764 11.7776 8.18764C11.9986 8.18764 12.2196 8.07715 12.4406 7.88377L12.634 7.69042C12.7445 7.57992 12.8549 7.4694 12.9654 7.35891C13.2693 7.08266 13.6837 6.91692 14.098 6.91692C14.3467 6.91692 14.5953 6.97217 14.8163 7.08266C15.3135 7.30366 15.645 7.74565 15.7555 8.29814C15.8107 8.87825 15.6726 9.43074 15.3135 9.81748Z"
                                                fill="white" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('Social_Media_Links')}}
                                        </span>
                                    </a>
                                </li> -->

                                <!-- <li
                                    class="navbar-vertical-aside-has-menu d-none {{Request::is('admin/file-manager*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.file-manager.index')}}"
                                        title="{{\App\CPU\translate('gallery')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M16.553 3H7.901C4.889 3 3 5.134 3 8.154V16.3C3 19.32 4.881 21.454 7.901 21.454H16.548C19.573 21.454 21.453 19.32 21.453 16.3V8.154C21.457 5.134 19.576 3 16.553 3Z"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M10.9537 9.03545C10.9537 10.0555 10.1277 10.8815 9.10772 10.8815C8.08872 10.8815 7.26172 10.0555 7.26172 9.03545C7.26172 8.01545 8.08872 7.18945 9.10772 7.18945C10.1267 7.19045 10.9527 8.01645 10.9537 9.03545Z"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M21.457 15.201C20.534 14.251 18.759 12.332 16.829 12.332C14.898 12.332 13.785 16.565 11.928 16.565C10.071 16.565 8.384 14.651 6.896 15.878C5.408 17.104 4 19.611 4 19.611"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('gallery')}}
                                        </span>
                                    </a>
                                </li> -->
                            </ul>
                        </li>

                        <li
                            class="d-none navbar-vertical-aside-has-menu {{(Request::is('admin/business-settings/web-config/environment-setup') || Request::is('admin/business-settings/web-config/mysitemap') || Request::is('admin/business-settings/analytics-index') || Request::is('admin/currency/view') || Request::is('admin/business-settings/web-config/db-index') || Request::is('admin/business-settings/language*') || Request::is('admin/business-settings/web-config/theme/setup')  || Request::is('admin/system-settings/software-update'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                title="{{\App\CPU\translate('System_Setup')}}"
                                href="{{route('admin.business-settings.web-config.environment-setup')}}">


                                <!-- ================================================== -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M21.9946 5.28623C21.9946 4.97924 21.9565 4.67762 21.8137 4.39703C21.3591 3.50636 20.6337 3.04049 19.6345 3.00187C19.5372 2.99796 19.4394 3.00139 19.3421 3.00139C14.375 3.00139 9.40742 3.00187 4.4403 3.00041C4.05069 3.00041 3.67184 3.05614 3.32427 3.23359C2.56461 3.62221 2.07333 4.2098 2 5.09118C2 8.42069 2 11.7502 2 15.0802C2.04497 15.3075 2.04497 15.5431 2.15399 15.7587C2.61301 16.666 3.34676 17.1587 4.36306 17.1656C6.49539 17.1802 8.6282 17.17 10.7605 17.17C10.8255 17.17 10.8906 17.1724 10.9556 17.1705C11.0837 17.1661 11.1472 17.2184 11.1467 17.3533C11.1453 17.7106 11.1477 18.0685 11.1457 18.4258C11.1453 18.5446 11.0944 18.6077 10.9707 18.6072C10.9057 18.6067 10.8407 18.6086 10.7757 18.6086C9.65086 18.6091 8.52604 18.6072 7.40121 18.6101C7.13626 18.6106 7.09373 18.6541 7.09031 18.9156C7.08591 19.2275 7.08884 19.5399 7.08933 19.8518C7.08982 20.279 7.10155 20.2912 7.52391 20.2912C10.5014 20.2917 13.4795 20.2912 16.457 20.2912C16.4893 20.2912 16.522 20.2912 16.5543 20.2907C16.8427 20.2854 16.8838 20.2472 16.8867 19.9647C16.8901 19.6724 16.8877 19.3795 16.8877 19.0867C16.8877 18.6081 16.8872 18.6081 16.4155 18.6081C15.3038 18.6081 14.1917 18.6091 13.0801 18.6072C12.8528 18.6067 12.8332 18.5871 12.8308 18.3637C12.8278 18.0386 12.8337 17.7136 12.8283 17.3885C12.8259 17.2321 12.8841 17.1573 13.0449 17.169C13.1094 17.1739 13.1749 17.1695 13.2399 17.1695C15.3723 17.1695 17.5051 17.171 19.6374 17.169C20.9265 17.1675 22.0054 16.1019 22 14.8817C21.9868 11.6837 21.9966 8.48472 21.9946 5.28623ZM19.4981 15.5485C14.4923 15.5485 9.48661 15.5485 4.48087 15.5485C3.86004 15.5485 3.56038 15.2498 3.55989 14.6314C3.55989 11.6021 3.55989 8.57223 3.55989 5.54287C3.55989 4.92253 3.85858 4.62287 4.47794 4.62287C6.98081 4.62287 9.48368 4.62287 11.9865 4.62287C14.4894 4.62287 16.9923 4.62287 19.4952 4.62287C20.116 4.62287 20.4156 4.92156 20.4161 5.53994C20.4161 8.56929 20.4161 11.5991 20.4161 14.6285C20.4161 15.2488 20.1175 15.5485 19.4981 15.5485Z" fill="white"/>
                                </svg>


                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('System_Setup')}}
                                </span>
                            </a>
                        </li>
                        <li class="navbar-vertical-aside-has-menu">
                            <a class="js-navbar-vertical-aside-menu-link nav-link" href="javascript:" onclick="Swal.fire({
                                    title: '{{\App\CPU\translate('Do_you_want_to_logout')}}?',
                                    showDenyButton: true,
                                    showCancelButton: true,
                                    confirmButtonColor: '#377dff',
                                    cancelButtonColor: '#363636',
                                    confirmButtonText: `Yes`,
                                    denyButtonText: `Don't Logout`,
                                    }).then((result) => {
                                    if (result.value) {
                                    location.href='{{route('admin.auth.logout')}}';
                                    } else{
                                    Swal.fire('Canceled', '', 'info')
                                    }
                                    })">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M15.0174 7.38948V6.45648C15.0174 4.42148 13.3674 2.77148 11.3324 2.77148H6.45744C4.42344 2.77148 2.77344 4.42148 2.77344 6.45648V17.5865C2.77344 19.6215 4.42344 21.2715 6.45744 21.2715H11.3424C13.3714 21.2715 15.0174 19.6265 15.0174 17.5975V16.6545"
                                        stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M21.8105 12.0215H9.76953" stroke="#fff" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M18.8828 9.10645L21.8108 12.0214L18.8828 14.9374" stroke="#fff"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate"
                                    title="Sign out">{{ \App\CPU\translate('sign_out')}}</span>
                            </a>
                        </li>



                        @endif
                        <!--System Settings end-->

                        <li class="nav-item pt-5">
                        </li>
                    </ul>
                </div>
                <!-- End Content -->
            </div>
        </div>
    </aside>
</div>

@push('script_2')
<script>
$(window).on('load', function() {
    if ($(".navbar-vertical-content li.active").length) {
        $('.navbar-vertical-content').animate({
            scrollTop: $(".navbar-vertical-content li.active").offset().top - 150
        }, 10);
    }
});

//Sidebar Menu Search
var $rows = $('.navbar-vertical-content .navbar-nav > li');
$('#search-bar-input').keyup(function() {
    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

    $rows.show().filter(function() {
        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
        return !~text.indexOf(val);
    }).hide();
});
</script>
@endpush