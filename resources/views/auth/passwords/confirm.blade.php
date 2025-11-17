@extends('layouts.master-without-nav')

@section('title')
    Confirm Password
@endsection

@section('css')
    <!-- owl.carousel css -->
    <link rel="stylesheet" href="{{ URL::asset('/buildlibs/owl.carousel/owl.carousel.min.css') }}">
@endsection

@section('body')

    <body class="auth-body-bg">
    @endsection

    @section('content')
        <div class="auth-page">
            <div class="container-fluid p-0">
                <div class="row g-0">

                    <div class="col-xxl-3 col-lg-4 col-md-5">
                        <div class="auth-full-page-content p-md-5 p-4">
                            <div class="w-100">

                                <div class="d-flex flex-column h-100">
                                    <div class="text-center">
                                        <a href="{{ url('/') }}" class="d-block auth-logo">
                                            @php
                                                $logo = \App\Utils\Util::getSettingValue('app_logo');
                                            @endphp
                                            @if (file_exists(public_path('storage/' . $logo)))
                                                <img src="{{ asset('storage/' . $logo) }}" alt="Logo" height="100">
                                            @else
                                                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" height="100">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="my-auto">
                                        <div class="text-center">
                                            <p class="text-muted mt-2">
                                                {{ \App\Utils\Util::getSettingValue('app_name') ? \App\Utils\Util::getSettingValue('app_name') : config('app.name') }}
                                            </p>
                                        </div>
                                        <div>
                                            <h5 class="text-primary"> Confirm Password</h5>
                                            <p class="text-muted">Re-Password with Minia.</p>
                                        </div>

                                        <div class="mt-4">
                                            <form class="form-horizontal" method="POST"
                                                action="{{ route('password.confirm') }}">
                                                @csrf

                                                <div class="mb-3">
                                                    <div class="float-end">
                                                        @if (Route::has('password.request'))
                                                            <a href="{{ route('password.request') }}"
                                                                class="text-muted">Forgot password?</a>
                                                        @endif
                                                    </div>
                                                    <label for="userpassword">Password</label>
                                                    <input type="password"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        name="password" id="userpassword" placeholder="Enter password">
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="text-end">
                                                    <button class="btn btn-primary w-md waves-effect waves-light"
                                                        type="submit">Confirm Password</button>
                                                </div>

                                            </form>
                                            <div class="mt-5 text-center">
                                                <p>Remember It ? <a href="{{ url('login') }}"
                                                        class="font-weight-medium text-primary"> Sign In here</a> </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        {{ date('Y') }} ©
                                        {{ \App\Utils\Util::getSettingValue('app_name') ? \App\Utils\Util::getSettingValue('app_name') : config('app.name') }},
                                        Design & Developed by
                                        <a href="https://adsinfotech.in/" target="_blank" class="text-primary">
                                            ADS INFOTECH
                                        </a>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <!-- end col -->

                    <div class="col-xxl-9 col-lg-8 col-md-7">
                        <div class="auth-bg pt-md-5 p-4 d-flex">
                            <div class="bg-overlay bg-primary"></div>
                            <ul class="bg-bubbles">
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ul>
                            <!-- end bubble effect -->
                            <div class="row justify-content-center align-items-center w-100">
                                <div class="col-md-8">

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container fluid -->
        </div>
    @endsection

    @section('script')
        <!-- owl.carousel js -->
        <script src="{{ URL::asset('/buildlibs/owl.carousel/owl.carousel.min.js') }}"></script>
        <!-- auth-2-carousel init -->
        <script src="{{ URL::asset('/buildjs/pages/auth-2-carousel.init.js') }}"></script>
    @endsection
