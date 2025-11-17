<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        @yield('title')
    </title>
    <link href="{{ asset('assets/installer/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/installer/css/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/installer/css/style.css') }}">
</head>

<body>
    <section class="form-center-section">
        <video src="{{ asset('assets/installer/images/bg-video.mp4') }}" autoplay loop inlineplays muted
            preload="metadata">
        </video>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    @yield('container')
                </div>
            </div>
        </div>
    </section>
    <script src="{{ asset('assets/installer/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
