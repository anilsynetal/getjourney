<!doctype html>
<html lang="en" class=" layout-wide  customizer-hide" dir="ltr" data-skin="default"
    data-assets-path="{{ asset('assets') }}" data-template="vertical-menu-template" data-bs-theme="light">

<head>

    <meta charset="utf-8" />
    <title> @yield('title') |
        {{ \App\Utils\Util::getSettingValue('app_name') ? \App\Utils\Util::getSettingValue('app_name') : config('app.name') }}
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    @php
        $favicon = \App\Utils\Util::getSettingValue('favicon');
        $faviconPath = $favicon ? asset('storage/' . $favicon) : asset('assets/img/favicon.ico');
    @endphp
    <link rel="shortcut icon" href="{{ $faviconPath }}">
    @include('layouts.head-css')
</head>

@yield('body')

@yield('content')

@include('layouts.vendor-script-without-nav')
</body>

</html>
