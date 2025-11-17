<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center d-none">
            <form class="app-search d-none d-lg-block" method="post" action="{{ route('home.search') }}">
                @csrf
                <div class="nav-item position-relative"> <!-- Added position-relative for parent -->
                    <div class="d-flex align-items-center">
                        <i class="bx bx-search fs-4 lh-0"></i>
                        <input type="text" class="form-control border-0 shadow-none" id="search-input" required
                            placeholder="Search..." aria-label="Search..." />
                        <input type="hidden" name="route_name" id="route_name">
                        <button class="btn btn-primary" type="submit"><i
                                class="bx bx-search-alt align-middle"></i></button>
                    </div>
                    <ul id="suggestions" class="list-group position-absolute mt-2 w-100 bg-white"
                        style="z-index: 1000; display: none; max-width: 300px; <!-- Set max-width as per your preference -->">
                        <!-- Adjust max-width and background color as needed -->
                    </ul>
                </div>
            </form>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            {{-- //Back To Admin --}}
            @if (session()->has('admin_id') && session()->get('admin_id') != null)
                <a href="{{ route('back-to-admin') }}" class="btn btn-primary d-none d-lg-block"> <i
                        class="las la-arrow-left"></i> @lang('translation.BackToAdmin')</a>
            @endif
            {{-- //Back To Admin --}}
            <!-- Place this tag where you want the button to render. -->
            <li class="nav-item lh-1 me-3">
                @can('notifications.notify-user.list')
                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item noti-icon position-relative"
                            id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="bx bx-bell bx-tada icon-lg"></i>
                            <span class="badge bg-danger rounded-pill" id="bell-count">{{ $unread_message_count }}</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                            aria-labelledby="page-header-notifications-dropdown">
                            <div class="p-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0"> @lang('translation.Notifications') </h6>
                                    </div>
                                    <div class="col-auto">
                                        <a href="{{ route('notify-users.index') }}"
                                            class="small text-reset text-decoration-underline"> @lang('translation.Unread')
                                            (<span id="unread-count">{{ $unread_message_count }}</span>)</a>
                                    </div>
                                </div>
                            </div>
                            @if ($user_notifications->count() > 0)
                                <div data-simplebar style="max-height: 230px;" id="notification-section-top">
                                    @foreach ($user_notifications as $user_notification)
                                        <a href="{{ route('notify-users.index', ['user_id' => $user_notification->user_id]) }}"
                                            class="text-reset notification-item">
                                            <div class="d-flex">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $user_notification->notification->title }}</h6>
                                                    <div class="font-size-13 text-muted">
                                                        <p class="mb-1">
                                                            {{ substr($user_notification->notification->description, 0, 25) }}.
                                                        </p>
                                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>
                                                                {{ \Carbon\Carbon::parse($user_notification->created_at)->diffForHumans() }}
                                                            </span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach

                                </div>
                                <div class="p-2 border-top d-grid">
                                    <a class="btn btn-sm btn-link font-size-14 text-center"
                                        href="{{ route('notify-users.index', ['user_id' => $user_notification->user_id]) }}">
                                        <i class="mdi mdi-arrow-right-circle me-1"></i> <span>@lang('translation.ViewMore')</span>
                                    </a>
                                </div>
                            @else
                                <div class="p-2 border-top d-grid text-center">
                                    <span>@lang('translation.notification_not_found')</span>
                                </div>
                            @endif

                        </div>
                    </div>
                @endcan
            </li>

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        @if (Auth::user()->image)
                            <img class="w-px-40 h-auto rounded-circle" src="{{ Auth::user()->image_url }}"
                                alt="Profile Image">
                        @else
                            <img src="{{ asset('assets/img/default-profile.png') }}" alt
                                class="w-px-40 h-auto rounded-circle" />
                        @endif
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        @if (Auth::user()->image)
                                            <img class="w-px-40 h-auto rounded-circle"
                                                src="{{ Auth::user()->image_url }}" alt="Profile Image">
                                        @else
                                            <img src="{{ asset('assets/img/default-profile.png') }}" alt
                                                class="w-px-40 h-auto rounded-circle" />
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                                    <small class="text-muted">{{ ucfirst(Auth::user()->role) }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void();"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                            style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
