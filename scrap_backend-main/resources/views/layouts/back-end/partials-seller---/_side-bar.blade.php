<div id="sidebarMain" class="d-none">
    <aside style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container">
            <div class="navbar-vertical-footer-offset pb-0">
                <div class="navbar-brand-wrapper justify-content-between side-logo">
                    <!-- Logo -->
                    @php($shop=\App\Model\Shop::where(['seller_id'=>auth('seller')->id()])->first())
                    <a class="navbar-brand" href="{{route('seller.dashboard.index')}}" aria-label="Front">
                        @if (isset($shop))
                            <img onerror="this.src='{{asset('public/assets/back-end/img/900x400/img1.jpg')}}'"
                                class="navbar-brand-logo-mini for-seller-logo"
                                src="{{asset("storage/app/public/shop/$shop->image")}}" alt="Logo">
                        @else
                            <img class="navbar-brand-logo-mini for-seller-logo"
                                src="{{asset('public/assets/back-end/img/login-img/semi-logo-for-company.png')}}" alt="Logo">
                        @endif
                    </a>
                    <!-- End Logo -->

                    <!-- Navbar Vertical Toggle -->
                    <button type="button" class="d-none js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                        <!-- <i class="tio-clear tio-lg"></i> -->
                        <img class="navbar-brand-logo-mini for-seller-logo"
                                src="{{asset('public/assets/back-end/img/login-img/semi-logo-for-company.png')}}" alt="Logo">
                    </button>
                    <!-- End Navbar Vertical Toggle -->

                    <button type="button" class="js-navbar-vertical-aside-toggle-invoker close mr-3">
                        <!-- <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip" data-placement="right" title="" data-original-title="Collapse"></i>
                        <i class="tio-last-page navbar-vertical-aside-toggle-full-align" data-template="<div class=&quot;tooltip d-none d-sm-block&quot; role=&quot;tooltip&quot;><div class=&quot;arrow&quot;></div><div class=&quot;tooltip-inner&quot;></div></div>" data-toggle="tooltip" data-placement="right" title="" data-original-title="Expand"></i> -->
                    </button>
                </div>

                <!-- Content -->
                <div class="navbar-vertical-content">
                    <!-- Search Form -->
                    <!-- <div class="sidebar--search-form pb-3 pt-4">
                        <div class="search--form-group">
                            <button type="button" class="btn"><i class="tio-search"></i></button>
                            <input type="text" class="js-form-search form-control form--control" id="search-bar-input"
                                   placeholder="{{\App\CPU\translate('search_menu')}}...">
                        </div>
                    </div> -->
                    <ul class="navbar-nav navbar-nav-lg nav-tabs">
                        <!-- Dashboards -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('seller/dashboard')?'show':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('seller.dashboard.index')}}">
                               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                  <path fill-rule="evenodd" clip-rule="evenodd" d="M3 6.5C3 3.87479 3.02811 3 6.5 3C9.97189 3 10 3.87479 10 6.5C10 9.12521 10.0111 10 6.5 10C2.98893 10 3 9.12521 3 6.5Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                  <path fill-rule="evenodd" clip-rule="evenodd" d="M14 6.5C14 3.87479 14.0281 3 17.5 3C20.9719 3 21 3.87479 21 6.5C21 9.12521 21.0111 10 17.5 10C13.9889 10 14 9.12521 14 6.5Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                  <path fill-rule="evenodd" clip-rule="evenodd" d="M3 17.5C3 14.8748 3.02811 14 6.5 14C9.97189 14 10 14.8748 10 17.5C10 20.1252 10.0111 21 6.5 21C2.98893 21 3 20.1252 3 17.5Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                  <path fill-rule="evenodd" clip-rule="evenodd" d="M14 17.5C14 14.8748 14.0281 14 17.5 14C20.9719 14 21 14.8748 21 17.5C21 20.1252 21.0111 21 17.5 21C13.9889 21 14 20.1252 14 17.5Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Dashboard')}}
                                </span>
                            </a>
                        </li>
                        <!-- End Dashboards -->
                        @php($seller = auth('seller')->user())
                        <!-- POS -->
                        @php($sellerId = $seller->id)
                        @php($seller_pos=\App\Model\BusinessSetting::where('type','seller_pos')->first()->value)
                        @if ($seller_pos==1)
                            @if ($seller->pos_status == 1)
                                <li class="nav-item">
                                    <small
                                        class="nav-subtitle">{{\App\CPU\translate('pos')}} {{\App\CPU\translate('system')}} </small>
                                    <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                                </li>
                                <li class="navbar-vertical-aside-has-menu {{Request::is('seller/pos')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{route('seller.pos.index')}}">
                                        <i class="tio-shopping"></i>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('create order')}}</span>
                                    </a>
                                </li>
                            @endif
                        @endif
                        <!-- End POS -->

                        <li class="nav-item">
                            <small class="nav-subtitle">{{\App\CPU\translate('Employee Management')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>
                        <!-- Pages -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('cab-panel/sub-admin/list/*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:" title="  {{\App\CPU\translate('Employee Management')}}">
                               <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                    <path d="M12.1992 8.32715V15.6535" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M15.8646 11.9907H8.53125" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.8849 2H7.5135C4.24684 2 2.19922 4.31208 2.19922 7.58516V16.4148C2.19922 19.6879 4.23731 22 7.5135 22H16.8849C20.1611 22 22.1992 19.6879 22.1992 16.4148V7.58516C22.1992 4.31208 20.1611 2 16.8849 2Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Employee Management')}}
                                </span>
                            </a>

                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('cab-panel/sub-admin/list/*')?'block':'none'}}">

                                <li class="nav-item {{Request::is('cab-panel/sub-admin/list/business-associates')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.sub-admin.list',['business-associates'])}}" title="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0412 15.207C15.7302 15.207 18.8832 15.766 18.8832 17.999C18.8832 20.232 15.7512 20.807 12.0412 20.807C8.35122 20.807 5.19922 20.253 5.19922 18.019C5.19922 15.785 8.33022 15.207 12.0412 15.207Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0422 12.02C9.62025 12.02 7.65625 10.057 7.65625 7.635C7.65625 5.213 9.62025 3.25 12.0422 3.25C14.4632 3.25 16.4273 5.213 16.4273 7.635C16.4363 10.048 14.4862 12.011 12.0732 12.02H12.0422Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Business Associates')}}
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('cab-panel/sub-admin/list/associate-managers')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.sub-admin.list',['associate-managers'])}}" title="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0412 15.207C15.7302 15.207 18.8832 15.766 18.8832 17.999C18.8832 20.232 15.7512 20.807 12.0412 20.807C8.35122 20.807 5.19922 20.253 5.19922 18.019C5.19922 15.785 8.33022 15.207 12.0412 15.207Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0422 12.02C9.62025 12.02 7.65625 10.057 7.65625 7.635C7.65625 5.213 9.62025 3.25 12.0422 3.25C14.4632 3.25 16.4273 5.213 16.4273 7.635C16.4363 10.048 14.4862 12.011 12.0732 12.02H12.0422Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Associate Managers')}}
                                            
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('cab-panel/sub-admin/list/auditors')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.sub-admin.list',['auditors'])}}" title="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0412 15.207C15.7302 15.207 18.8832 15.766 18.8832 17.999C18.8832 20.232 15.7512 20.807 12.0412 20.807C8.35122 20.807 5.19922 20.253 5.19922 18.019C5.19922 15.785 8.33022 15.207 12.0412 15.207Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0422 12.02C9.62025 12.02 7.65625 10.057 7.65625 7.635C7.65625 5.213 9.62025 3.25 12.0422 3.25C14.4632 3.25 16.4273 5.213 16.4273 7.635C16.4363 10.048 14.4862 12.011 12.0732 12.02H12.0422Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Auditors')}}
                                            
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('cab-panel/sub-admin/list/office-reviewers')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.sub-admin.list',['office-reviewers'])}}" title="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0412 15.207C15.7302 15.207 18.8832 15.766 18.8832 17.999C18.8832 20.232 15.7512 20.807 12.0412 20.807C8.35122 20.807 5.19922 20.253 5.19922 18.019C5.19922 15.785 8.33022 15.207 12.0412 15.207Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0422 12.02C9.62025 12.02 7.65625 10.057 7.65625 7.635C7.65625 5.213 9.62025 3.25 12.0422 3.25C14.4632 3.25 16.4273 5.213 16.4273 7.635C16.4363 10.048 14.4862 12.011 12.0732 12.02H12.0422Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Office Reviewers')}}
                                            
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('cab-panel/sub-admin/list/accounts')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.sub-admin.list',['accounts'])}}" title="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0412 15.207C15.7302 15.207 18.8832 15.766 18.8832 17.999C18.8832 20.232 15.7512 20.807 12.0412 20.807C8.35122 20.807 5.19922 20.253 5.19922 18.019C5.19922 15.785 8.33022 15.207 12.0412 15.207Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0422 12.02C9.62025 12.02 7.65625 10.057 7.65625 7.635C7.65625 5.213 9.62025 3.25 12.0422 3.25C14.4632 3.25 16.4273 5.213 16.4273 7.635C16.4363 10.048 14.4862 12.011 12.0732 12.02H12.0422Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Accounts')}}
                                            
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('cab-panel/sub-admin/list/certification-managers')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.sub-admin.list',['certification-managers'])}}" title="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0412 15.207C15.7302 15.207 18.8832 15.766 18.8832 17.999C18.8832 20.232 15.7512 20.807 12.0412 20.807C8.35122 20.807 5.19922 20.253 5.19922 18.019C5.19922 15.785 8.33022 15.207 12.0412 15.207Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0422 12.02C9.62025 12.02 7.65625 10.057 7.65625 7.635C7.65625 5.213 9.62025 3.25 12.0422 3.25C14.4632 3.25 16.4273 5.213 16.4273 7.635C16.4363 10.048 14.4862 12.011 12.0732 12.02H12.0422Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Certification Managers')}}
                                            
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('cab-panel/sub-admin/list/print-dispatch-team')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.sub-admin.list',['print-dispatch-team'])}}" title="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0412 15.207C15.7302 15.207 18.8832 15.766 18.8832 17.999C18.8832 20.232 15.7512 20.807 12.0412 20.807C8.35122 20.807 5.19922 20.253 5.19922 18.019C5.19922 15.785 8.33022 15.207 12.0412 15.207Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0422 12.02C9.62025 12.02 7.65625 10.057 7.65625 7.635C7.65625 5.213 9.62025 3.25 12.0422 3.25C14.4632 3.25 16.4273 5.213 16.4273 7.635C16.4363 10.048 14.4862 12.011 12.0732 12.02H12.0422Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <span class="text-truncate">
                                            {{\App\CPU\translate('Print & Dispatch Team')}}
                                            
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- End Pages -->
                        <li class="nav-item {{ ( Request::is('seller/business-settings*')) ? 'scroll-here' : '' }}">
                            <small class="nav-subtitle" title="">{{\App\CPU\translate('business_section')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <!-- Pages -->
                        <li class="navbar-vertical-aside-has-menu {{
                            Request::is('cab-panel/documents/list/balance-sheet') ||
                            Request::is('cab-panel/documents/list/itr') ||
                            Request::is('cab-panel/documents/list/indemnity-insurance') ||
                            Request::is('cab-panel/documents/list/trademark') ||
                            Request::is('cab-panel/documents/list/mrm-report') ||
                            Request::is('cab-panel/documents/list/irb-report') ||
                            Request::is('cab-panel/documents/list/internal-audit-report') ||
                            Request::is('cab-panel/documents/list/external-audit-report')
                            ?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:" title="  {{\App\CPU\translate('Legal & Managerial Compliance')}}">
                               <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                    <path d="M12.1992 8.32715V15.6535" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M15.8646 11.9907H8.53125" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.8849 2H7.5135C4.24684 2 2.19922 4.31208 2.19922 7.58516V16.4148C2.19922 19.6879 4.23731 22 7.5135 22H16.8849C20.1611 22 22.1992 19.6879 22.1992 16.4148V7.58516C22.1992 4.31208 20.1611 2 16.8849 2Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Legal & Managerial Compliance')}}
                                </span>
                            </a>

                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('cab-panel/documents/list/balance-sheet') ||
                            Request::is('cab-panel/documents/list/itr') ||
                            Request::is('cab-panel/documents/list/indemnity-insurance') ||
                            Request::is('cab-panel/documents/list/trademark') ||
                            Request::is('cab-panel/documents/list/mrm-report') ||
                            Request::is('cab-panel/documents/list/irb-report') ||
                            Request::is('cab-panel/documents/list/internal-audit-report') ||
                            Request::is('cab-panel/documents/list/external-audit-report')?'block':'none'}}">

                                @php($docsLists=\App\CPU\Helpers::getDocTypeEnums())
                                @foreach($docsLists as $typeKey => $typeValue)
                                    <li class="nav-item {{Request::is('cab-panel/documents/list/' . $typeKey)?'active':''}}">
                                        <a class="nav-link " href="{{route('seller.documents.list',[$typeKey])}}" title="">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0412 15.207C15.7302 15.207 18.8832 15.766 18.8832 17.999C18.8832 20.232 15.7512 20.807 12.0412 20.807C8.35122 20.807 5.19922 20.253 5.19922 18.019C5.19922 15.785 8.33022 15.207 12.0412 15.207Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0422 12.02C9.62025 12.02 7.65625 10.057 7.65625 7.635C7.65625 5.213 9.62025 3.25 12.0422 3.25C14.4632 3.25 16.4273 5.213 16.4273 7.635C16.4363 10.048 14.4862 12.011 12.0732 12.02H12.0422Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <span class="text-truncate">
                                                {{ $typeValue }}
                                            </span>
                                        </a>
                                    </li>
                                @endforeach

                            </ul>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{
                            Request::is('cab-panel/documents/list/procedures') ||
                            Request::is('cab-panel/documents/list/guidelines') ||
                            Request::is('cab-panel/documents/list/work-instructions') ||
                            Request::is('cab-panel/documents/list/application-forms') ||
                            Request::is('cab-panel/documents/list/logo-regulations')
                            ?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:" title="  {{\App\CPU\translate('Resource Management')}}">
                               <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                    <path d="M12.1992 8.32715V15.6535" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M15.8646 11.9907H8.53125" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.8849 2H7.5135C4.24684 2 2.19922 4.31208 2.19922 7.58516V16.4148C2.19922 19.6879 4.23731 22 7.5135 22H16.8849C20.1611 22 22.1992 19.6879 22.1992 16.4148V7.58516C22.1992 4.31208 20.1611 2 16.8849 2Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Resource Management')}}
                                </span>
                            </a>

                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{
                                    Request::is('cab-panel/documents/list/procedures') ||
                                    Request::is('cab-panel/documents/list/guidelines') ||
                                    Request::is('cab-panel/documents/list/work-instructions') ||
                                    Request::is('cab-panel/documents/list/application-forms') ||
                                    Request::is('cab-panel/documents/list/logo-regulations')?'block':'none'}}">

                                @php($docsLists=\App\CPU\Helpers::getDocTypeEnums('doc2'))
                                @foreach($docsLists as $typeKey => $typeValue)
                                    <li class="nav-item {{Request::is('cab-panel/documents/list/' . $typeKey)?'active':''}}">
                                        <a class="nav-link " href="{{route('seller.documents.list',[$typeKey])}}" title="">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0412 15.207C15.7302 15.207 18.8832 15.766 18.8832 17.999C18.8832 20.232 15.7512 20.807 12.0412 20.807C8.35122 20.807 5.19922 20.253 5.19922 18.019C5.19922 15.785 8.33022 15.207 12.0412 15.207Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0422 12.02C9.62025 12.02 7.65625 10.057 7.65625 7.635C7.65625 5.213 9.62025 3.25 12.0422 3.25C14.4632 3.25 16.4273 5.213 16.4273 7.635C16.4363 10.048 14.4862 12.011 12.0732 12.02H12.0422Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <span class="text-truncate">
                                                {{ $typeValue }}
                                            </span>
                                        </a>
                                    </li>
                                @endforeach

                            </ul>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('seller/profile/update/*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('seller.profile.update',auth('seller')->user()->id)}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                      <path d="M21.9826 8.31182C21.9818 8.08382 21.9527 7.85997 21.8989 7.63861C21.6054 6.44638 21.2862 5.26079 20.9935 4.06857C20.9205 3.77176 20.8509 3.47494 20.7688 3.18062C20.6071 2.59694 20.0807 1.99835 19.2358 2C14.4006 2.00581 9.56539 2.00249 4.73017 2.00415C4.53617 2.00415 4.34382 2.02156 4.15976 2.10613C3.62915 2.34988 3.32322 2.76442 3.18476 3.32239C2.93852 4.31895 2.68897 5.31385 2.43776 6.30958C2.27443 6.95627 2.06633 7.59301 2 8.26125C2 8.33918 2 8.41712 2 8.49505C2 8.57298 2 8.65092 2 8.72885C2 8.87145 2 9.01488 2 9.15749C2.0398 9.21635 2.01907 9.28434 2.02487 9.34735C2.08705 9.98326 2.39464 10.5197 2.72545 11.0412C2.86142 11.2551 2.92111 11.4648 2.92028 11.7152C2.91531 14.5722 2.9236 17.4293 2.91282 20.2871C2.91033 21.0175 3.40696 21.9154 4.30402 21.9859C4.34299 21.9859 4.38196 21.9859 4.42093 21.9859C9.46093 21.9859 14.5009 21.9859 19.5401 21.9859C19.5658 21.9859 19.5923 21.9859 19.618 21.9859C19.8212 21.9519 20.0293 21.9494 20.2158 21.8383C20.8094 21.4851 21.0781 20.9504 21.0797 20.2797C21.0855 18.415 21.0822 13.2333 21.0822 11.3687C21.0822 11.3512 21.0814 11.333 21.0805 11.3156C21.1394 11.2484 21.1966 11.1788 21.2513 11.1067C21.6709 10.5528 21.9445 9.93683 22 9.23542C22 9.01406 22 8.79352 22 8.57215C21.9627 8.48759 21.9925 8.39805 21.9826 8.31182ZM15.9369 3.38375C16.984 3.39038 18.032 3.38872 19.0791 3.3854C19.2342 3.38457 19.3444 3.42188 19.3859 3.58687C19.7805 5.16379 20.1976 6.73573 20.564 8.31928C20.8625 9.61017 19.8991 10.9632 18.5809 11.1788C17.2386 11.3977 15.9518 10.4475 15.7868 9.11355C15.7686 8.96597 15.7587 8.81591 15.7587 8.6675C15.7562 7.84919 15.757 7.03089 15.757 6.21258C15.757 5.32961 15.7611 4.44663 15.7528 3.56366C15.752 3.41276 15.7943 3.38292 15.9369 3.38375ZM9.87547 3.38375C11.3206 3.38872 12.7657 3.38872 14.2108 3.38375C14.3525 3.38292 14.3956 3.41193 14.394 3.562C14.3857 4.45824 14.3898 5.35531 14.3898 6.25155C14.389 6.25155 14.3882 6.25155 14.3873 6.25155C14.3873 7.13535 14.3998 8.01915 14.384 8.90213C14.365 9.94678 13.5856 10.906 12.5915 11.144C11.3305 11.4458 10.0827 10.6722 9.77515 9.39958C9.72126 9.17821 9.6939 8.95353 9.6939 8.72554C9.69556 7.00353 9.69722 5.28235 9.69058 3.56034C9.68976 3.40696 9.73618 3.38292 9.87547 3.38375ZM3.40115 9.06297C3.33897 8.52821 3.48821 8.03408 3.6134 7.53248C3.9326 6.25072 4.25843 4.96978 4.58094 3.68885C4.65058 3.41359 4.68292 3.38706 4.96481 3.38706C6.01277 3.38706 7.0599 3.39038 8.10703 3.38375C8.25129 3.38292 8.29109 3.41608 8.28943 3.56366C8.28197 4.45326 8.28529 5.34287 8.28529 6.23248C8.28529 7.05742 8.28695 7.88153 8.28446 8.70647C8.28031 10.0297 7.36749 11.0478 6.05505 11.1979C4.79484 11.343 3.5479 10.329 3.40115 9.06297ZM10.3306 18.2666C10.3306 17.552 10.334 16.8381 10.3282 16.1235C10.3265 15.9809 10.3489 15.922 10.5147 15.9236C11.5038 15.9328 12.4929 15.9319 13.482 15.9245C13.6412 15.9228 13.6735 15.9717 13.6727 16.1201C13.6669 17.5619 13.6677 19.0037 13.6718 20.4455C13.6718 20.5756 13.6486 20.6229 13.5044 20.6221C12.502 20.6154 11.5005 20.6154 10.4981 20.6221C10.3597 20.6229 10.3265 20.5839 10.3273 20.4488C10.334 19.7217 10.3306 18.9937 10.3306 18.2666ZM19.5617 20.0832C19.5617 20.5383 19.5376 20.5624 19.0941 20.5624C17.8894 20.5624 16.6847 20.5624 15.4809 20.5624C15.4162 20.5624 15.3507 20.5632 15.2861 20.5591C15.2007 20.5533 15.1567 20.5101 15.1509 20.4247C15.1468 20.3534 15.1468 20.2821 15.1468 20.21C15.1468 18.6182 15.1468 17.0263 15.1468 15.4337C15.1468 15.277 15.1393 15.1219 15.0854 14.9727C14.9768 14.6692 14.7289 14.4819 14.4064 14.481C12.8046 14.4777 11.2028 14.4802 9.60104 14.4794C9.30009 14.4794 9.09862 14.6294 8.95767 14.879C8.86813 15.0374 8.8557 15.2115 8.85653 15.3889C8.85736 17.0006 8.85736 18.6115 8.85736 20.2233C8.85736 20.5624 8.85736 20.5624 8.50914 20.5624C7.71488 20.5624 6.92061 20.5624 6.12635 20.5624C5.67036 20.5624 5.21519 20.5649 4.75919 20.5615C4.50964 20.5599 4.45243 20.4994 4.44248 20.249C4.44082 20.21 4.44165 20.171 4.44165 20.1321C4.44165 17.6498 4.44165 15.1675 4.44165 12.6852C4.44165 12.6272 4.43917 12.5683 4.44663 12.5103C4.45575 12.4456 4.49306 12.4216 4.55607 12.4332C4.6141 12.444 4.66799 12.4672 4.72437 12.4838C5.69772 12.7723 6.64287 12.6968 7.55569 12.2574C8.05978 12.0145 8.50168 11.6854 8.84658 11.2393C8.92949 11.1324 8.99084 11.1108 9.08038 11.236C9.19396 11.3952 9.34403 11.5229 9.48414 11.6588C10.5263 12.672 12.2864 12.9472 13.5939 12.3088C14.1096 12.0576 14.5714 11.7417 14.9312 11.2882C15.0664 11.1183 15.0962 11.1232 15.2396 11.2949C15.3234 11.3952 15.408 11.493 15.5025 11.5826C16.6466 12.6595 18.2235 12.9108 19.5608 12.3901C19.5625 14.6867 19.5617 18.5444 19.5617 20.0832Z" fill="#fff"/>
                                    </svg>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Profile_Setting')}}
                                </span>
                            </a>
                        </li>

                        @php( $shipping_method = \App\CPU\Helpers::get_business_settings('shipping_method'))
                        {{-- @if($shipping_method=='sellerwise_shipping') --}}
                            <li class="d-none nav-item {{Request::is('seller/delivery-man*')?'scroll-here':''}}">
                                <small class="nav-subtitle">{{\App\CPU\translate('delivery_man_management')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>
                            <li class="d-none navbar-vertical-aside-has-menu {{Request::is('seller/delivery-man*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link its-drop" href="javascript:">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                      <path d="M19.7608 20.7743C19.7414 20.7354 19.7219 20.716 19.7219 20.6965C19.469 20.0934 19.1772 19.5876 18.8075 19.1595C18.0488 18.2646 17.0954 17.7393 15.967 17.6031C15.753 17.5837 15.539 17.5642 15.325 17.5642C15.2083 17.5642 15.0721 17.5642 14.9554 17.5448C14.8581 17.5448 14.7803 17.5058 14.7803 17.3891C14.7414 17.0973 14.683 16.8054 14.6441 16.5914C14.6246 16.5136 14.6441 16.4747 14.7219 16.4358C15.3445 16.0856 15.8892 15.5992 16.3172 14.9572L16.5702 14.5486C16.9203 13.9261 17.1344 13.2841 17.2511 12.6226C17.2705 12.5448 17.29 12.4669 17.3678 12.4086C17.7569 12.0389 18.0293 11.6304 18.2044 11.1634C18.4573 10.4241 18.4379 9.68483 18.1266 8.88717L18.1071 8.84825C18.0877 8.78989 18.0682 8.73152 18.0682 8.67315C18.146 7.95331 18.146 7.33074 18.0488 6.72762C17.8542 5.36576 17.2511 4.27626 16.2589 3.45914C15.1305 2.52529 13.7297 2.03891 12.0954 2C12.0371 2 11.9982 2 11.9398 2C10.8892 2 9.81918 2.27238 8.78805 2.81712C7.21217 3.6537 6.29778 4.9572 6.0254 6.74709C5.94758 7.29183 5.92813 7.87549 5.98649 8.49806C6.00595 8.67316 5.98649 8.8288 5.92813 8.98444C5.79194 9.31518 5.73358 9.66538 5.71412 10.0545C5.67521 10.9689 6.00595 11.7665 6.70634 12.428C6.7647 12.4864 6.80362 12.5448 6.82307 12.6226C6.9398 13.323 7.17327 13.9844 7.50401 14.5486L7.73747 14.9183C8.16548 15.5603 8.71023 16.0662 9.35225 16.4358C9.43008 16.4747 9.44953 16.5136 9.43007 16.6109C9.37171 16.8833 9.31334 17.1751 9.27443 17.4669C9.25498 17.5447 9.23552 17.5837 9.13825 17.5837C9.06042 17.5837 9.00206 17.5837 8.92424 17.5837C8.80751 17.5837 8.67132 17.5837 8.55459 17.5837C7.504 17.6031 6.58961 17.9533 5.83085 18.5953C5.22774 19.1012 4.76081 19.7627 4.39116 20.6187C4.17716 21.144 4.13825 21.572 4.29389 21.8054C4.39116 21.9416 4.52735 22 4.74136 22C5.03319 22 6.60906 22 8.71023 22H9.07988C9.27443 22 9.48844 22 9.66354 22H9.70245H11.5896H12.2122H19.5274C19.7219 22 19.8581 21.9416 19.9165 21.8249C20.0332 21.6109 19.9748 21.2412 19.7608 20.7743ZM11.6869 3.18677C11.7063 3.18677 11.7258 3.18677 11.7647 3.16732H11.8036H12.1927C12.9904 3.24514 13.613 3.38133 14.1577 3.59533C15.1305 3.98444 15.8309 4.50973 16.2783 5.24903C16.648 5.83269 16.8425 6.53308 16.9009 7.36966L16.5507 7.19456L16.2005 7.03892C15.5001 6.74709 14.7219 6.53308 13.827 6.39689C13.2044 6.31907 12.6013 6.26071 11.9982 6.26071C11.0449 6.26071 10.111 6.37744 9.19661 6.59145C8.69077 6.70818 8.24331 6.86382 7.8542 7.03892C7.71801 7.07783 7.58182 7.15565 7.42618 7.23347L7.17327 7.36966C7.17327 7.13619 7.21218 6.92219 7.25109 6.68872C7.52346 5.21012 8.4184 4.17899 9.91646 3.59533C10.4612 3.38133 11.0643 3.24514 11.6869 3.18677ZM8.74914 7.95332C9.74136 7.62258 10.7919 7.44747 11.9787 7.44747C12.2705 7.44747 12.5624 7.44748 12.8542 7.46693C13.8464 7.5253 14.7219 7.71985 15.539 8.03113C15.9476 8.18677 16.2783 8.36187 16.5896 8.57587C16.5118 8.6537 16.434 8.71207 16.3561 8.78989C16.2005 8.94553 16.0254 9.10118 15.8309 9.21791C15.6363 9.33464 15.4028 9.393 15.1694 9.393C15.0721 9.393 14.9554 9.37354 14.8386 9.35408C13.827 9.14008 12.8931 9.02335 12.0371 9.02335C11.9787 9.02335 11.9398 9.02335 11.8814 9.02335C11.0449 9.04281 10.1694 9.14008 9.23552 9.33463C9.11879 9.35409 9.00206 9.37355 8.90478 9.37355C8.55459 9.37355 8.2433 9.23736 7.97093 8.98444C7.93202 8.94553 7.89311 8.90661 7.83474 8.8677C7.75692 8.78988 7.65965 8.69261 7.56237 8.61479C7.52346 8.57588 7.50401 8.55643 7.50401 8.53697C7.50401 8.51752 7.54291 8.4786 7.58183 8.45915C7.93202 8.28405 8.32112 8.0895 8.74914 7.95332ZM6.9398 9.72374C6.99817 9.74319 7.03708 9.76265 7.05653 9.80156L7.07599 9.82101C7.62074 10.3463 8.20439 10.5992 8.88533 10.5992C9.09933 10.5992 9.3328 10.5798 9.56626 10.5214C10.3834 10.3463 11.2005 10.249 12.0176 10.249C12.8153 10.249 13.6324 10.3463 14.4495 10.5214C14.683 10.5798 14.9165 10.5992 15.1499 10.5992C15.7336 10.5992 16.2589 10.4047 16.7452 10.035C16.8231 9.97665 16.9009 9.89884 16.9787 9.82101C17.0176 9.7821 17.0565 9.7432 17.0954 9.70429C17.1927 10.1518 17.1733 10.5409 16.9787 10.9494C16.8425 11.2412 16.6869 11.4553 16.4923 11.5914C16.2005 11.8054 16.1032 12.0973 16.0449 12.4475C15.8892 13.4202 15.5001 14.2374 14.8775 14.8599C14.2939 15.4436 13.5351 15.7938 12.6207 15.8716C12.3678 15.8911 12.1538 15.9105 11.9398 15.9105C11.434 15.9105 10.9865 15.8521 10.5585 15.716C9.56626 15.4047 8.82696 14.7237 8.34058 13.7121C8.12657 13.2451 7.99039 12.7393 7.93202 12.2724C7.91256 12.0389 7.81529 11.8444 7.62073 11.6887C7.01762 11.144 6.78416 10.5019 6.9398 9.72374ZM11.9593 17.0778C12.2122 17.0778 12.4651 17.0584 12.7375 17.0389C12.9709 17.0195 13.1849 16.9805 13.3795 16.9222C13.3989 16.9222 13.4184 16.9222 13.4184 16.9222C13.4573 16.9222 13.4768 16.9222 13.4962 17.0195C13.5157 17.1167 13.5351 17.214 13.5351 17.2918C13.574 17.5253 13.5935 17.7393 13.6713 17.9533C13.6908 18.0117 13.6713 18.0506 13.613 18.0895C13.5935 18.109 13.574 18.1284 13.574 18.1284L13.5546 18.1479V18.1673L13.3017 18.3813L13.2239 18.4397L13.0098 18.5759H12.9904H12.9515L12.932 18.5953L12.6986 18.6926C12.5235 18.7704 12.3484 18.8093 12.0954 18.8093C11.9787 18.8093 11.8814 18.8093 11.7647 18.7899H11.6091C11.5312 18.7899 11.4534 18.7704 11.4145 18.7315L11.1227 18.6148L11.0449 18.5953L10.9865 18.5564C10.9281 18.5175 10.8892 18.4981 10.8114 18.4592L10.7336 18.4202L10.5585 18.2646C10.3639 18.1284 10.325 17.9728 10.4028 17.7393C10.4612 17.5642 10.4807 17.3697 10.5001 17.1946C10.5001 17.1362 10.5196 17.0973 10.5196 17.0389C10.539 16.9611 10.5585 16.9611 10.5974 16.9611C10.6168 16.9611 10.6363 16.9611 10.6558 16.9611C11.0838 17.0389 11.4923 17.0778 11.9593 17.0778ZM12.0176 19.9572C12.1149 19.9572 12.2316 19.9572 12.3289 19.9377C13.1849 19.8794 13.9048 19.5097 14.5079 18.8677C14.6052 18.7704 14.683 18.7315 14.8192 18.7315H15.0137C15.4223 18.7315 15.8698 18.7315 16.2783 18.8483C17.076 19.0623 17.718 19.5486 18.2044 20.3074C18.2433 20.3852 18.3017 20.463 18.3406 20.5409C18.36 20.5798 18.3989 20.6381 18.4184 20.677C18.4379 20.6965 18.4573 20.7549 18.4379 20.7743C18.4379 20.7743 18.4184 20.7938 18.3795 20.7938C18.3795 20.7938 18.36 20.7938 18.3406 20.7938C18.3017 20.7938 18.2822 20.7938 18.2433 20.7938H18.0877H5.55848C5.77249 20.3463 6.04486 19.9767 6.35614 19.6459C6.9398 19.0623 7.6791 18.751 8.53513 18.7315C8.67132 18.7315 8.80751 18.7315 8.96315 18.7315C9.07988 18.7315 9.21607 18.7315 9.3328 18.7315C9.39116 18.7315 9.43008 18.751 9.44953 18.8093C10.1694 19.5681 11.0254 19.9572 12.0176 19.9572Z" fill="white"/>
                                    </svg>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Delivery-Man') }}</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub" style="display: {{ Request::is('seller/delivery-man*') ? 'block' : 'none' }}">
                                    <li class="nav-item {{ Request::is('seller/delivery-man/add') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{route('seller.delivery-man.add')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                          <path d="M12.1992 8.32715V15.6535" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                          <path d="M15.8646 11.9907H8.53125" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                          <path fill-rule="evenodd" clip-rule="evenodd" d="M16.8849 2H7.5135C4.24684 2 2.19922 4.31208 2.19922 7.58516V16.4148C2.19922 19.6879 4.23731 22 7.5135 22H16.8849C20.1611 22 22.1992 19.6879 22.1992 16.4148V7.58516C22.1992 4.31208 20.1611 2 16.8849 2Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <span class="text-truncate">{{\App\CPU\translate('Add_New')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ Request::is('seller/delivery-man/list') || Request::is('seller/delivery-man/earning-statement*') || Request::is('seller/delivery-man/earning-active-log*') || Request::is('seller/delivery-man/order-wise-earning*') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{route('seller.delivery-man.list')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M5.87389 21.9984C5.61804 21.9092 5.34404 21.8531 5.11131 21.7227C4.36854 21.3034 4.00045 20.6498 4.00045 19.7964C3.9988 17.8438 4.0021 15.8911 4.00375 13.9384C4.00705 11.106 4.01035 8.2752 4.01365 5.44276C4.01531 4.49696 4.4098 3.77729 5.27802 3.37619C5.55697 3.24745 5.88874 3.21113 6.20071 3.17812C6.50112 3.14511 6.80813 3.17152 7.1234 3.17152C7.12835 3.07083 7.12835 2.98665 7.13661 2.90412C7.19108 2.37428 7.59547 2.00124 8.13357 2.00124C10.4576 1.99959 12.7817 1.99959 15.1057 2.00124C15.6934 2.00124 16.0994 2.40399 16.1159 2.9883C16.1176 3.03947 16.1159 3.09064 16.1159 3.17152C16.2678 3.17152 16.4081 3.16492 16.5484 3.17317C16.9049 3.19298 17.2697 3.17317 17.613 3.25075C18.5539 3.46203 19.2124 4.30549 19.2306 5.27274C19.2405 5.73491 19.2339 6.19708 19.2323 6.65925C19.229 11.0136 19.224 15.3695 19.2191 19.7238C19.2174 20.696 18.7981 21.4107 17.9085 21.825C17.7352 21.9059 17.542 21.9439 17.3572 22C13.5294 21.9984 9.70165 21.9984 5.87389 21.9984ZM7.10194 4.3418C6.83784 4.3418 6.56549 4.3418 6.29479 4.3418C5.57348 4.3418 5.18394 4.72804 5.18394 5.44936C5.17898 10.2213 5.17568 14.9932 5.17238 19.7651C5.17238 20.4237 5.57348 20.8264 6.22712 20.8264C9.81389 20.8264 13.4007 20.8264 16.9874 20.8264C17.0782 20.8264 17.1706 20.8248 17.2581 20.805C17.7632 20.6993 18.0488 20.3098 18.0488 19.7288C18.0537 15.1005 18.057 10.4722 18.0603 5.84385C18.0603 5.65568 18.0653 5.46586 18.0587 5.27769C18.0438 4.86999 17.7731 4.4689 17.3786 4.40122C16.9693 4.33025 16.5451 4.3484 16.1308 4.32694C16.1209 4.39462 16.1159 4.41278 16.1143 4.43093C16.0845 5.1704 15.7247 5.51538 14.9852 5.51538C13.9305 5.51538 12.8758 5.51538 11.821 5.51538C10.5979 5.51538 9.37318 5.51703 8.15008 5.51538C7.69121 5.51538 7.32312 5.26614 7.19273 4.85514C7.14321 4.70163 7.13495 4.53657 7.10194 4.3418ZM8.30854 4.3319C10.5253 4.3319 12.7256 4.3319 14.9291 4.3319C14.9291 3.94235 14.9291 3.56271 14.9291 3.18637C12.7157 3.18637 10.5154 3.18637 8.30854 3.18637C8.30854 3.57097 8.30854 3.93905 8.30854 4.3319Z" fill="white"/>
                                            <path d="M13.9204 16.4509C14.5905 16.4509 15.259 16.4492 15.9291 16.4509C16.3121 16.4509 16.5729 16.6869 16.5762 17.0286C16.5795 17.3802 16.317 17.6212 15.9242 17.6212C14.579 17.6228 13.2337 17.6228 11.8868 17.6212C11.4956 17.6212 11.2249 17.3769 11.2266 17.0319C11.2282 16.6869 11.4989 16.4492 11.8918 16.4492C12.5685 16.4509 13.2453 16.4509 13.9204 16.4509Z" fill="white"/>
                                            <path d="M13.8924 9.81214C13.2222 9.81214 12.5521 9.81379 11.8836 9.81214C11.494 9.81214 11.2217 9.5662 11.2266 9.22123C11.2299 8.88285 11.4973 8.64186 11.8753 8.64186C13.2222 8.64021 14.5675 8.64021 15.9144 8.64186C16.2923 8.64186 16.5597 8.8845 16.563 9.22288C16.5663 9.5662 16.2923 9.81214 15.9028 9.81214C15.2327 9.81379 14.5625 9.81214 13.8924 9.81214Z" fill="white"/>
                                            <path d="M13.8874 13.7179C13.2239 13.7179 12.5603 13.7179 11.8968 13.7179C11.5006 13.7179 11.2316 13.4835 11.2266 13.1402C11.2217 12.7886 11.4957 12.5476 11.9017 12.5476C13.242 12.5476 14.5807 12.546 15.921 12.5476C16.3699 12.5476 16.6571 12.9091 16.5333 13.3069C16.4541 13.5594 16.2313 13.7162 15.9358 13.7179C15.2525 13.7195 14.5691 13.7179 13.8874 13.7179Z" fill="white"/>
                                            <path d="M7.944 13.2184C8.41113 12.7496 8.83533 12.3188 9.26614 11.8963C9.35197 11.8121 9.45761 11.7345 9.56985 11.6949C9.81249 11.6074 10.0815 11.7131 10.2218 11.9227C10.3704 12.1439 10.3704 12.4327 10.1806 12.6275C9.58141 13.2415 8.97398 13.849 8.35831 14.4481C8.13878 14.6611 7.82186 14.6611 7.59573 14.4547C7.31677 14.2005 7.04938 13.9348 6.79518 13.6558C6.58391 13.4247 6.61527 13.0715 6.8348 12.8619C7.04772 12.6572 7.38775 12.6391 7.61554 12.8404C7.73438 12.9444 7.82681 13.0814 7.944 13.2184Z" fill="white"/>
                                            <path d="M7.97893 17.142C8.08952 17.0067 8.1704 16.8961 8.26448 16.7987C8.61606 16.4422 8.96764 16.084 9.32747 15.7374C9.56681 15.5063 9.92829 15.5195 10.1528 15.7489C10.3723 15.975 10.3839 16.325 10.1544 16.5594C9.56681 17.1585 8.97424 17.7527 8.37342 18.3387C8.13408 18.5714 7.81056 18.5681 7.56462 18.3404C7.30383 18.0961 7.04963 17.8435 6.80534 17.5811C6.58416 17.3434 6.60232 16.9902 6.82515 16.7706C7.04798 16.5528 7.39626 16.5462 7.63725 16.7657C7.74619 16.8647 7.83862 16.9852 7.97893 17.142Z" fill="white"/>
                                            <path d="M7.96953 9.0408C8.10323 8.884 8.19401 8.7635 8.298 8.65786C8.63968 8.30959 8.983 7.96296 9.33458 7.62458C9.56732 7.40175 9.92385 7.41 10.1467 7.62788C10.3712 7.84741 10.3926 8.2056 10.1665 8.43668C9.57557 9.04246 8.97805 9.63997 8.37393 10.2309C8.13624 10.4636 7.80942 10.457 7.56513 10.2276C7.30929 9.9866 7.0617 9.73736 6.81906 9.48317C6.59457 9.24878 6.59953 8.89885 6.81741 8.67272C7.04024 8.43998 7.39512 8.43008 7.64106 8.65951C7.74835 8.7602 7.83583 8.88234 7.96953 9.0408Z" fill="white"/>
                                            </svg>
                                            <span class="text-truncate">{{\App\CPU\translate('List')}}</span>
                                        </a>
                                    </li>
                                    <li class="d-none nav-item {{Request::is('seller/delivery-man/withdraw-list') || Request::is('seller/delivery-man/withdraw-view*')?'active':''}}">
                                        <a class="nav-link " href="{{route('seller.delivery-man.withdraw-list')}}"
                                           title="{{\App\CPU\translate('withdraws')}}">
                                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M8.37526 21.1404C6.82271 21.1404 5.73793 20.0521 5.73793 18.4937V11.7476C5.73793 11.7476 5.58396 11.7112 5.5299 11.6982C5.41825 11.6724 5.31012 11.6465 5.202 11.6171C3.19227 11.0636 1.87948 9.17022 2.00876 7.01123C2.12394 5.08025 3.74818 3.342 5.70502 3.05406C5.92832 3.02115 6.15985 3.0047 6.39608 3.0047L7.30105 3.00352C9.23909 3.00235 11.1783 3 13.1163 3C14.6125 3 16.1074 3.00117 17.6036 3.00352C19.8542 3.00705 21.777 4.74294 21.9803 6.95364C22.1907 9.24191 20.6969 11.2399 18.4263 11.7029L18.2558 11.7382V13.809C18.2558 15.3722 18.2558 16.9353 18.2558 18.4996C18.2558 20.0533 17.1687 21.1393 15.6115 21.1393H11.9928L8.37526 21.1404ZM7.02486 18.5066C7.02486 19.3505 7.5314 19.8535 8.37995 19.8535H15.6162C16.4635 19.8535 16.9701 19.3493 16.9701 18.5055V8.0443H7.02486V18.5066ZM11.7636 4.28458C9.77149 4.28458 7.99917 4.2881 6.3432 4.29633C5.13383 4.30221 4.19713 4.88044 3.63417 5.96875C3.08179 7.03708 3.15348 8.14302 3.8422 9.16434C4.26412 9.78959 4.87645 10.208 5.66388 10.4066L5.73675 10.4254V7.54598C5.73675 6.96892 5.96476 6.74444 6.54887 6.74444H17.4508C18.029 6.74444 18.2547 6.97127 18.2547 7.55538V10.4195C18.2547 10.4195 18.3839 10.389 18.4074 10.3843C18.4545 10.3737 18.4921 10.3655 18.5285 10.3537C19.9788 9.90947 20.9061 8.45565 20.684 6.97245C20.4536 5.42343 19.1808 4.2975 17.66 4.29398C15.6973 4.28928 13.7298 4.28458 11.7636 4.28458Z" fill="white"/>
                                                    <path d="M11.9947 17.0122C11.5799 17.0122 11.1862 16.843 10.8559 16.5221C10.4775 16.1554 10.1014 15.7735 9.73821 15.4056L9.70882 15.3762C9.56779 15.2329 9.4867 15.0589 9.48082 14.8861C9.47494 14.7157 9.54311 14.5512 9.67239 14.4243C9.7958 14.3032 9.94623 14.2386 10.1096 14.2386C10.2859 14.2386 10.4634 14.3173 10.6091 14.4595C10.7478 14.5959 10.8841 14.7404 11.0169 14.8803C11.0769 14.9437 11.138 15.0084 11.1979 15.0718L11.2296 15.1047L11.353 15.0331V14.3784C11.353 13.343 11.353 12.3076 11.3542 11.271V11.2369C11.3542 11.157 11.3542 11.0747 11.3695 11.0007C11.4341 10.701 11.6892 10.5 12.003 10.5C12.0253 10.5 12.0476 10.5012 12.0711 10.5035C12.3932 10.5329 12.6364 10.8103 12.6388 11.1487C12.6423 11.9162 12.6411 12.6837 12.6411 13.4511V15.2211L12.9561 14.8967C13.0983 14.7498 13.2276 14.6158 13.3581 14.4854C13.5179 14.3267 13.7071 14.2386 13.8905 14.2386C14.0515 14.2386 14.2007 14.3032 14.3241 14.4266C14.5945 14.6981 14.571 15.0871 14.2654 15.3962C13.8916 15.7735 13.525 16.1437 13.1489 16.5116C12.8174 16.8348 12.4084 17.0122 11.9947 17.0122Z" fill="white"/>
                                                    </svg>
                                            <span class="text-truncate">{{\App\CPU\translate('withdraws')}}</span>
                                        </a>
                                    </li>

                                    <li class="d-none nav-item {{Request::is('seller/delivery-man/emergency-contact/') ? 'active' : ''}}">
                                        <a class="nav-link " href="{{route('seller.delivery-man.emergency-contact.index')}}"
                                           title="{{\App\CPU\translate('withdraws')}}">
                                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <path d="M14.3516 2.5C18.0526 2.911 20.9766 5.831 21.3916 9.532" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M14.3516 6.04297C16.1226 6.38697 17.5066 7.77197 17.8516 9.54297" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.0315 12.4724C15.0205 16.4604 15.9254 11.8467 18.4653 14.3848C20.9138 16.8328 22.3222 17.3232 19.2188 20.4247C18.8302 20.737 16.3613 24.4943 7.68447 15.8197C-0.993406 7.144 2.76157 4.67244 3.07394 4.28395C6.18377 1.17385 6.66682 2.58938 9.11539 5.03733C11.6541 7.5765 7.04253 8.48441 11.0315 12.4724Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                            <span class="text-truncate">{{\App\CPU\translate('Emergency_Contact')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li
                            class="d-none navbar-vertical-aside-has-menu {{ Request::is('seller/business-settings/driver-page/*') || Request::is('seller/business-settings/vendor-page/*') || Request::is('seller/business-settings/terms-condition') || Request::is('seller/business-settings/page*') || Request::is('seller/business-settings/privacy-policy') || Request::is('seller/business-settings/about-us') || Request::is('seller/helpTopic/list') || Request::is('seller/business-settings/social-media') || Request::is('seller/file-manager*') || Request::is('seller/business-settings/features-section') ?'active':''}}">
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
                                    style="display: {{ Request::is('seller/business-settings/vendor-page/*') || Request::is('seller/business-settings/terms-condition') || Request::is('seller/business-settings/page*') || Request::is('seller/business-settings/privacy-policy') || Request::is('seller/business-settings/about-us') || Request::is('seller/helpTopic/list') || Request::is('seller/business-settings/social-media') || Request::is('seller/file-manager*') || Request::is('seller/business-settings/features-section')?'block':'none'}}">
                                
                                        <li
                                        class="nav-item {{ Request::is('seller/business-settings/vendor-page/*') ?'active':''}}">
                                        <a class="nav-link"
                                            href="{{route('seller.business-settings.vendor.page',['vendor-privacy-policy'])}}"
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
                                </ul>
                            </li>

                            <li class="nav-item {{Request::is('seller/orders/list/canceled')?'active':''}} ">
                                <a class="nav-link js-navbar-vertical-aside-menu-link nav-link" href="javascript:" onclick="Swal.fire({
                                title: '{{\App\CPU\translate('Do you want to logout')}}?',
                                showDenyButton: true,
                                showCancelButton: true,
                                confirmButtonColor: '#377dff',
                                cancelButtonColor: '#363636',
                                confirmButtonText: `Yes`,
                                denyButtonText: `Don't Logout`,
                                }).then((result) => {
                                if (result.value) {
                                location.href='{{route('seller.auth.logout')}}';
                                } else{
                                Swal.fire('Canceled', '', 'info')
                                }
                                })" title="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
                                            <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
                                            </svg>
                                    <span class="text-truncate">
                                        {{\App\CPU\translate('LogOut')}}

                                    </span>
                                </a>
                            </li>
                        {{-- @endif --}}
                    </ul>
                </div>
                <!-- End Content -->
            </div>
        </div>
    </aside>
</div>

@push('script_2')
    <script>
        $(window).on('load' , function() {
            if($(".navbar-vertical-content li.active").length) {
                $('.navbar-vertical-content').animate({
                    scrollTop: $(".navbar-vertical-content li.active").offset().top - 150
                }, 10);
            }
        });
        //Sidebar Menu Search
        var $rows = $('.navbar-vertical-content li');
        $('#search-bar-input').keyup(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

            $rows.show().filter(function() {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });
    </script>
@endpush
