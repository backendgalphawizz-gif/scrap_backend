<!-- partial:partials/_navbar.html -->
<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        @php($company_web_logo=\App\CPU\Helpers::get_business_settings('company_web_logo'))
        <a class="navbar-brand brand-logo" href="#"><img src="{{ asset('storage/company/'.$company_web_logo) }}" alt="logo" style="width:75%; height:auto;" /></a>
        <a class="navbar-brand brand-logo-mini" href="#"><img src="{{ asset('storage/company/'.$company_web_logo) }}"
                alt="logo" /></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span id="navbarToggler" class="mdi mdi-menu"></span>
        </button>
        <div class="search-field d-none">
            <form class="d-flex align-items-center h-100" action="#">
                <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                        <i class="input-group-text border-0 mdi mdi-magnify"></i>
                    </div>
                    <input type="text" class="form-control bg-transparent border-0 headerSearch" placeholder="Search projects">
                </div>
            </form>
        </div>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle d-flex align-items-center" id="profileDropdown" href="#" data-bs-toggle="dropdown"
   aria-expanded="false">
    <div class="nav-profile-img me-2"> <!-- me-2 adds margin-right -->
        <img src="{{ auth('admin')->user()->image }}" alt="image">
        <span class="availability-status online"></span>
    </div>
    <div class="nav-profile-text me-1"> <!-- optional spacing before dropdown arrow -->
        <p class="mb-0 text-white">{{ auth('admin')->user()->name }}</p>
    </div>
</a>
                <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                    <a class="dropdown-item" href="{{ route('admin.profile') }}">
                        <i class="mdi mdi-cached me-2 text-primary"></i> Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('admin.auth.logout') }}">
                        <i class="mdi mdi-logout me-2 text-primary"></i> Signout
                    </a>
                </div>
            </li>
            <li class="nav-item d-none full-screen-link">
                <a class="nav-link">
                    <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
                </a>
            </li>
            <li class="nav-item dropdown d-none">
                <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="mdi mdi-email-outline"></i>
                    <span class="count-symbol bg-warning"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                    <h6 class="p-3 mb-0">Messages</h6>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="{{ asset('assets/images/faces/face4.jpg') }}" alt="image" class="profile-pic">
                        </div>
                        <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                            <h6 class="preview-subject ellipsis mb-1 font-weight-normal">Mark send you a message</h6>
                            <p class="text-gray mb-0"> 1 Minutes ago </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="{{ asset('assets/images/faces/face2.jpg') }}" alt="image" class="profile-pic">
                        </div>
                        <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                            <h6 class="preview-subject ellipsis mb-1 font-weight-normal">Cregh send you a message</h6>
                            <p class="text-gray mb-0"> 15 Minutes ago </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="{{ asset('assets/images/faces/face3.jpg') }}" alt="image" class="profile-pic">
                        </div>
                        <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                            <h6 class="preview-subject ellipsis mb-1 font-weight-normal">Profile picture updated</h6>
                            <p class="text-gray mb-0"> 18 Minutes ago </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <h6 class="p-3 mb-0 text-center">4 new messages</h6>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#"
                    data-bs-toggle="dropdown">
                    <i class="mdi mdi-bell-outline"></i>
                    <span id="admin-bell-count" class="badge bg-danger rounded-pill top-0 start-100 translate-middle {{ ($adminUnreadCount ?? 0) > 0 ? '' : 'd-none' }}" style="font-size: 0.6rem; padding: 0.25em 0.4em;">
                        {{ $adminUnreadCount ?? 0 }}
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list p-0"
                    aria-labelledby="notificationDropdown" style="width: 320px; max-height: 400px; overflow-y: auto;">
                    <div class="p-3 d-flex justify-content-between align-items-center bg-light border-bottom">
                        <h6 class="mb-0 fw-bold">Notifications</h6>
                        @if(($adminUnreadCount ?? 0) > 0)
                            <span class="badge bg-danger rounded-pill">{{ $adminUnreadCount }} New</span>
                        @endif
                    </div>
                    
                    <?php
                        // Fetch a quick preview directly in the header if available, otherwise just use empty state
                        $headerNotifs = \App\Models\AdminNotification::orderByDesc('created_at')->limit(5)->get();
                    ?>

                    @forelse($headerNotifs as $n)
                        <a href="{{ $n->link ?? '#' }}" class="dropdown-item preview-item border-bottom py-3 {{ $n->is_read ? '' : 'bg-light' }}">
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-{{ $n->color }}">
                                    <i class="mdi {{ $n->icon }}"></i>
                                </div>
                            </div>
                            <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                <h6 class="preview-subject font-weight-normal mb-1" style="white-space: normal; line-height: 1.2; font-size: 0.85rem;">{{ $n->title }}</h6>
                                <p class="text-gray mb-0" style="font-size: 0.75rem;">
                                    {{ $n->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </a>
                    @empty
                        <div class="p-4 text-center text-muted">
                            <i class="mdi mdi-bell-sleep mdi-36px"></i>
                            <p class="mb-0 mt-2">No notifications yet</p>
                        </div>
                    @endforelse

                    <div class="p-2 border-top bg-light">
                        <a href="{{ route('admin.dashboard') }}#notif-panel-row" class="btn btn-sm btn-block btn-outline-primary w-100">View All in Dashboard</a>
                    </div>
                </div>
            </li>
            <li class="nav-item nav-logout d-none">
                <a class="nav-link" href="#">
                    <i class="mdi mdi-power"></i>
                </a>
            </li>
            <li class="nav-item nav-settings d-none">
                <a class="nav-link" href="#">
                    <i class="mdi mdi-format-line-spacing"></i>
                </a>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>