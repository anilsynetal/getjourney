<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>@yield('title', config('app.name') . ' – Premier Travel & Visa Services')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('description', 'Discover seamless visa processing and curated tours with ' . config('app.name') . '. Your trusted partner for global adventures and immigration success.')">
    <meta name="keywords" content="@yield('keywords', 'visa services, immigration, travel tours, job visa, student visa, tourist visa, business visa, residence visa, diplomatic visa, travel agency, global relocation')">
    <meta name="author" content="@yield('author', config('app.name'))">
    <meta name="robots" content="index, follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:title" content="@yield('title', config('app.name') . ' – Premier Travel & Visa Services')">
    <meta property="og:description" content="@yield('description', 'Discover seamless visa processing and curated tours with ' . config('app.name') . '. Your trusted partner for global adventures and immigration success.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:image" content="@yield('og_image', asset('website/assets/img/og-image.png'))">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:locale" content="{{ app()->getLocale() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', config('app.name') . ' – Premier Travel & Visa Services')">
    <meta name="twitter:description" content="@yield('description', 'Discover seamless visa processing and curated tours with ' . config('app.name') . '. Your trusted partner for global adventures and immigration success.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('website/assets/img/og-image.png'))">
    <link rel="canonical" href="{{ request()->url() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('website/assets/img/favicon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('website/assets/img/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('website/assets/img/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('website/assets/img/apple-touch-icon.png') }}">
    <!-- inject css start -->

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Poppins:wght@200;300;400;500;600&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('website/assets/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('website/assets/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">


    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('website/assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('website/assets/css/style.css') }}" rel="stylesheet">
    @yield('styles')
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-secondary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->
    @include('website.layout.header')
    @yield('content')
    @include('website.layout.footer')
    <!-- Back to Top -->
    <a href="#" class="btn btn-primary btn-lg-square back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- WhatsApp Chat -->
    <a href="https://wa.me/{{ $contact->phone ?? '1234567890' }}?text=Hello! I'm interested in your travel services."
        target="_blank" class="btn btn-success btn-lg-square whatsapp-chat" title="Chat with us on WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('website/assets/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('website/assets/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('website/assets/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('website/assets/lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('website/assets/lib/owlcarousel/owl.carousel.min.js') }}"></script>


    <!-- Template Javascript -->
    <script src="{{ asset('website/assets/js/main.js') }}"></script>
    <script src="{{ asset('website/assets/js/custom.js') }}"></script>
    @yield('scripts')
</body>

</html>
