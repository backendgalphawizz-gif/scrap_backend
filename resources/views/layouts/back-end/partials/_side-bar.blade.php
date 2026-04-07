<nav class="sidebar sidebar-offcanvas customSidebar" id="sidebar" style="">
    <!-- <img src="{{ asset('assets/images/dashboard/star.svg')}}"  alt="" srcset=""> -->
    <ul class="nav">
        <li class="d-none nav-item nav-profile customShadow">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ auth('admin')->user()->image }}" alt="profile" />
                    <span class="login-status online"></span>
                    <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-0 ellipsis-text">{{ auth('admin')->user()->name }}</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-view-dashboard menu-icon"></i>
            </a>
        </li>
        @if(\App\CPU\Helpers::module_permission_check('user_management'))
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#users" aria-expanded="false" aria-controls="users">
                <span class="menu-title">User Management</span>
                <i class="mdi mdi-account-group menu-icon"></i>
            </a>
            <div class="collapse" id="users">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.user') }}">User Lists</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.user.wallet') }}">Wallet Transactions</a>
                    </li>
                </ul>
            </div>
        </li>
        @endif
        @if(\App\CPU\Helpers::module_permission_check('sale_management'))
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#sales" aria-expanded="false" aria-controls="sales">
                <span class="menu-title">Sale Management</span>
                <i class="mdi mdi-cash-multiple menu-icon"></i>
            </a>
            <div class="collapse" id="sales">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.sale.list') }}">Sales User</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.sale.wallet-transactions') }}">Wallet Transaction</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.sale.ledger-transactions') }}">Commission Ledger</a>
                    </li>
                    <li class="nav-item d-none">
                        <a class="nav-link" href="{{ route('admin.roles-nd-permissions') }}">Roles & Permission</a>
                    </li>
                </ul>
            </div>
        </li>
        @endif
        @if(\App\CPU\Helpers::module_permission_check('brand_management'))
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#brands" aria-expanded="false" aria-controls="brands">
                <span class="menu-title">Brand Management</span>
                <i class="mdi mdi-store menu-icon"></i>
            </a>
            <div class="collapse" id="brands">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.brand') }}">Brand User</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.campaign.list') }}">Campaigns</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.campaigns-transactions.list') }}">Campaign Participants</a>
                    </li>
                </ul>
            </div>
        </li>
        @endif
        @if(\App\CPU\Helpers::module_permission_check('admin_management'))
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#admins" aria-expanded="false" aria-controls="admins">
                <span class="menu-title">Admin Management</span>
                <i class="mdi mdi-shield-account menu-icon"></i>
            </a>
            <div class="collapse" id="admins">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.employee.list') }}">Admins</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.custom-role.create') }}">Roles & Permission</a>
                    </li>
                </ul>
            </div>
        </li>
        @endif

        @if(\App\CPU\Helpers::module_permission_check('banner_management'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.banner.list') }}">
                <span class="menu-title">Banners</span>
                <i class="mdi mdi-image menu-icon"></i>
            </a>
        </li>
        @endif

        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.activity.logs') }}">
                <span class="menu-title">Activity Logs</span>
                <i class="mdi mdi-history menu-icon"></i>
            </a>
        </li>

        @if(\App\CPU\Helpers::module_permission_check('banner_management'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.notification.add-new') }}">
                <span class="menu-title">Notification</span>
                <i class="mdi mdi-bell menu-icon"></i>
            </a>
        </li>
        @endif

        {{-- <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Basic UI Elements</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-crosshairs-gps menu-icon"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a class="nav-link" href="#">Buttons</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Dropdowns</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Typography</a>
                </li>
                </ul>
            </div>
        </li> --}}

        @if(\App\CPU\Helpers::module_permission_check('report_management'))
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#reports" aria-expanded="false" aria-controls="reports">
                <span class="menu-title">Report</span>
                <i class="mdi mdi-chart-bar menu-icon"></i>
            </a>
            <div class="collapse" id="reports">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.brand.reports') }}">Brand Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.campaign.reports') }}">Campaign Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.post.reports') }}">Post Reports</a>
                    </li>
                </ul>
            </div>
        </li>
        @endif

        @if(\App\CPU\Helpers::module_permission_check('business_settings'))
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#settings" aria-expanded="false" aria-controls="settings">
                <span class="menu-title">Business Setting</span>
                <i class="mdi mdi-cog menu-icon"></i>
            </a>
            <div class="collapse" id="settings">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.settings') }}">Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.business-settings.privacy-policy') }}">Static Pages</a>
                    </li>
                </ul>
            </div>
        </li>
        @endif

          <li class="nav-item ">
            <a class="nav-link" data-bs-toggle="collapse" href="#support-ticket" aria-expanded="false" aria-controls="supportchat">
                <span class="menu-title">Support Chat</span>
                <i class="mdi mdi-chat menu-icon"></i>
            </a>
            <div class="collapse" id="support-ticket">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.support-ticket.view-support') }}"
                        >Chat</a>
                    </li>
                </ul> 
            </div>
        </li>


        <li class="nav-item d-none">
            <a class="nav-link" data-bs-toggle="collapse" href="#forms" aria-expanded="false" aria-controls="forms">
                <span class="menu-title">Forms</span>
                <i class="mdi mdi-format-list-bulleted menu-icon"></i>
            </a>
            <div class="collapse" id="forms">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="pages/forms/basic_elements.html">Form Elements</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item d-none">
            <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
                <span class="menu-title">Charts</span>
                <i class="mdi mdi-chart-bar menu-icon"></i>
            </a>
            <div class="collapse" id="charts">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="pages/charts/chartjs.html">ChartJs</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item d-none">
            <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
                <span class="menu-title">Tables</span>
                <i class="mdi mdi-table-large menu-icon"></i>
            </a>
            <div class="collapse" id="tables">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="pages/tables/basic-table.html">Basic table</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item d-none">
            <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                <span class="menu-title">User Pages</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-lock menu-icon"></i>
            </a>
            <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="pages/samples/blank-page.html"> Blank Page </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/samples/login.html"> Login </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/samples/register.html"> Register </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/samples/error-404.html"> 404 </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/samples/error-500.html"> 500 </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item d-none">
            <a class="nav-link" href="docs/documentation.html" target="_blank">
                <span class="menu-title">Documentation</span>
                <i class="mdi mdi-file-document-box menu-icon"></i>
            </a>
        </li>
    </ul>
</nav>
@push('script_2')

@endpush