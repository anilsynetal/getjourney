@extends('layouts.master-without-nav')

@section('title')
    @lang('translation.VerifyOTP')
@endsection

@section('body')

    <body>
    @endsection

    @section('content')
        <div class="auth-page">
            <div class="container-fluid p-0">
                <div class="row g-0">
                    <div class="col-xxl-3 col-lg-4 col-md-5">
                        <div class="auth-full-page-content d-flex p-sm-5 p-4">
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
                                    <div class="auth-content">
                                        <div class="text-center">
                                            <h5 class="mb-0">Verify OTP </h5>

                                            <p class="text-muted mt-2">
                                                @if (!empty($masked_email) && !empty($masked_mobile))
                                                    Enter the OTP code sent to your email address
                                                    ({{ $masked_email }}) and mobile number ({{ $masked_mobile }})
                                                @elseif(!empty($masked_email))
                                                    Enter the OTP code sent to your email address
                                                    ({{ $masked_email }})
                                                @elseif(!empty($masked_mobile))
                                                    Enter the OTP code sent to your mobile number
                                                    ({{ $masked_mobile }})
                                                @endif
                                            </p>
                                            </p>
                                        </div>

                                        @if (session('error'))
                                            <div class="alert alert-danger">{{ session('error') }}</div>
                                        @endif
                                        <form class="form-horizontal" method="POST" action="{{ route('validate.otp') }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="otp" class="form-label">Enter OTP <span
                                                        class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('otp') is-invalid @enderror" id="otp"
                                                    name="otp" placeholder="Enter OTP" value="{{ old('otp') }}"
                                                    minlength="6" maxlength="6" required>
                                                @error('otp')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="text-end">
                                                <button class="btn btn-primary w-100 waves-effect waves-light"
                                                    type="submit">Verify OTP</button>
                                            </div>

                                        </form>
                                        <!-- Resend OTP Button and Timer -->
                                        <div class="mt-3 text-center">
                                            <div id="resend-otp-container" style="display: none;">
                                                <form method="POST" action="{{ route('resend-otp') }}">
                                                    @csrf
                                                    <button class="btn btn-link text-primary" type="submit">Resend
                                                        OTP</button>
                                                </form>
                                            </div>
                                            <div id="timer-container">
                                                <p>Resend OTP in <span id="countdown-timer">60</span> seconds</p>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-center">
                                            <p class="text-muted mb-0"><a href="{{ url('login') }}"
                                                    class="text-primary fw-semibold"> Sign In </a> </p>
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
                        <!-- end auth full page content -->
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
        <script>
            // Countdown Timer Logic
            var countdown = 60; // Starting count (60 seconds)
            var timer = document.getElementById("countdown-timer");
            var resendOtpContainer = document.getElementById("resend-otp-container");
            var timerContainer = document.getElementById("timer-container");

            var countdownInterval = setInterval(function() {
                countdown--;
                timer.textContent = countdown;

                // Show the Resend OTP button after 60 seconds
                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    timerContainer.style.display = "none";
                    resendOtpContainer.style.display = "block"; // Show Resend OTP button
                }
            }, 1000);
        </script>
    @endsection
