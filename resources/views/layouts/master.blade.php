<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-menu-fixed" dir="ltr"
    data-theme="theme-default" data-assets-path="{{ asset('assets') }}" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8">
    <title> @yield('title') </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    @php
        $favicon = \App\Utils\Util::getSettingValue('favicon');
        $faviconPath = $favicon ? asset('storage/' . $favicon) : asset('assets/img/favicon.png');
    @endphp
    <link rel="shortcut icon" href="{{ $faviconPath }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('build/css/icons.min.css') }}">

    @include('layouts.head-css')

</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <div id="overlay" class="overlay">
                <div id="loader" class="loader"></div>
            </div>
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                @include('layouts.sidebar')
            </aside>
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="layout-page">
                @include('layouts.topbar')
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    @yield('content')
                    @include('layouts.footer')
                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <audio style="display: none;" id="audio" src="{{ asset('assets/bell.wav') }}"></audio>
    <audio style="display: none;" id="error" src="{{ asset('assets/error.mp3') }}"></audio>
    <!-- END layout-wrapper -->
    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')
    @include('layouts.notify')

</body>

</html>
