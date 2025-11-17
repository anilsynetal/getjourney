@php
    $theme_mode = Utility::getsettings('theme_mode');
    if ($theme_mode != null && $theme_mode == 'dark-theme') {
        $logo = Utility::getsettings('app_logo');
    } else {
        $logo = Utility::getsettings('app_dark_logo');
    }
@endphp
<aside id="layout-menu" class="layout-menu menu-vertical menu"
    style="touch-action: none; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">

    <div class="app-brand demo text-center">
        <a href="{{ route('root') }}" class="app-brand-link m-auto">
            <span class="app-brand-logo demo">
                <span class="text-primary">
                    @if (file_exists(public_path('storage/' . $logo)))
                        <img src="{{ asset('storage/' . $logo) }}" alt="Logo" height="50">
                    @else
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" height="50">
                    @endif
                </span>
            </span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="icon-base bx bx-chevron-left"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1 ps ps--active-y">
        <?php $count = 0; ?>
        @foreach ($menus as $key => $menu)
            @php
                $count++;
                $main_route =
                    $menu['route_name'] != 'javascript:void(0)' && $menu['route_name'] != 'javascript:void(0);'
                        ? route($menu['route_name'])
                        : 'javascript:void(0)';
                $main_permissions = json_decode($menu['permissions'], true);
                $has_permission = false;

                // Check permissions (handle both single object and array of objects)
                if (isset($main_permissions['permission'])) {
                    // Single permission object
                    $has_permission = auth()->user()->can($main_permissions['permission']);
                } elseif (is_array($main_permissions)) {
                    // Array of permissions
                    foreach ($main_permissions as $perm) {
                        if (auth()->user()->can($perm['permission'])) {
                            $has_permission = true;
                            break;
                        }
                    }
                }
                $main_menu_name = __('translation.' . $menu['language_key']);
                if (strlen($main_menu_name) > 19) {
                    $main_menu_name = substr($main_menu_name, 0, 18) . '...';
                }
            @endphp
            @if ($has_permission)
                <!-- Dashboards -->
                @php
                    $sub_route = array_column($menu['sub_menus'], 'route_name');
                @endphp
                @if (count($menu['sub_menus']) == 0)
                    <li class="menu-item {{ Route::currentRouteName() == $menu['route_name'] ? 'active' : '' }}">
                        <a href="{{ $main_route }}" class="menu-link">
                            <i class="menu-icon icon-base {{ $menu['menu_icon'] }}"></i>
                            <div data-i18n="{{ $main_menu_name }}">{{ $main_menu_name }}</div>
                        </a>
                    </li>
                @else
                    <li class="menu-item {{ in_array(Route::currentRouteName(), $sub_route) ? 'active open' : '' }}">
                        <a id="tour_step_{{ $count }}" href="{{ $main_route }}" class="menu-link menu-toggle">
                            <i class="menu-icon icon-base {{ $menu['menu_icon'] }}"></i>
                            <div data-i18n="{{ $main_menu_name }}">{{ $main_menu_name }}</div>
                        </a>
                        <ul class="menu-sub">
                            @foreach ($menu['sub_menus'] as $sub_menu)
                                @php
                                    $sub_route =
                                        $sub_menu['route_name'] != 'javascript:void(0)' &&
                                        $sub_menu['route_name'] != 'javascript:void(0);'
                                            ? route($sub_menu['route_name'])
                                            : 'javascript:void(0)';
                                    $sub_permissions = json_decode($sub_menu['permissions'], true);
                                    $sub_has_permission = false;

                                    // Check sub-menu permissions
                                    foreach ($sub_permissions as $perm) {
                                        if (auth()->user()->can($perm['permission'])) {
                                            $sub_has_permission = true;
                                            break;
                                        }
                                    }
                                @endphp
                                @if ($sub_has_permission)
                                    <li
                                        class="menu-item {{ Route::currentRouteName() == $sub_menu['route_name'] ? 'active' : '' }}">
                                        <a href="{{ $sub_route }}" class="menu-link">
                                            <div data-i18n="{{ __('translation.' . $sub_menu['language_key']) }}">
                                                {{ __('translation.' . $sub_menu['language_key']) }}
                                            </div>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endif
        @endforeach
    </ul>
</aside>
