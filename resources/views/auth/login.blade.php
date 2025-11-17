@extends('layouts.master-without-nav')

@section('title')
    @lang('translation.Login')
@endsection
@section('css')
@endsection
@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="{{ url('/') }}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    @php
                                        $logo = \App\Utils\Util::getSettingValue('app_logo');
                                    @endphp
                                    @if (file_exists(public_path('storage/' . $logo)))
                                        <img src="{{ asset('storage/' . $logo) }}" alt="Logo" height="100">
                                    @else
                                        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" height="100">
                                    @endif
                                </span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <p class="text-muted mt-2">Sign in to continue to
                            {{ \App\Utils\Util::getSettingValue('app_name') ? \App\Utils\Util::getSettingValue('app_name') : config('app.name') }}
                        </p>
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif


                        <form method="POST" action="{{ route('login') }}" class="custom-form mt-4 pt-2">
                            @csrf
                            <div class="mb-6 form-control-validation">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    placeholder="Enter Email ID" value="{{ old('email') }}" autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-6 form-password-toggle form-control-validation">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        value="{{ old('password') }}" autocomplete="current-password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i
                                            class="icon-base bx bx-hide"></i></span>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-7">
                                <div class="d-flex justify-content-between">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">
                                            Remember Me
                                        </label>
                                    </div>
                                    <a href="@if (Route::has('password.request')) {{ route('password.request') }} @endif">
                                        <span>Forgot Password?</span>
                                    </a>
                                </div>
                            </div>
                            @if ($setting['google_recaptcha_status'] == 'on')
                                <div class="mb-6">
                                    <div class="form-group col-md-12">
                                        <div class="g-recaptcha"
                                            data-sitekey="{{ $setting['google_recaptcha_site_key'] }}">
                                        </div>
                                        @if ($errors->has('g-recaptcha-response'))
                                            <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div class="mb-6">
                                <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                            </div>
                        </form>
                        @if ($setting['google_login'] == 'on' || $setting['facebook_login'] == 'on')
                            <div class="mt-4 pt-2 text-center">
                                <div class="signin-other-title">
                                    <h5 class="font-size-14 mb-3 text-muted fw-medium">- Sign in with -</h5>
                                </div>
                                <ul class="list-inline mb-0">
                                    @if ($setting['google_login'] == 'on')
                                        <li class="list-inline-item">
                                            <a href="{{ url('auth/google') }}"
                                                class="social-list-item bg-danger text-white border-danger">
                                                <i class="mdi mdi-google"></i>
                                            </a>
                                        </li>
                                    @endif
                                    @if ($setting['facebook_login'] == 'on')
                                        <li class="list-inline-item">
                                            <a href="{{ url('auth/facebook') }}"
                                                class="social-list-item bg-primary text-white border-primary">
                                                <i class="mdi mdi-facebook"></i>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- /Register -->
                <div class="text-center mt-5">
                    {{ date('Y') }} ©
                    {{ \App\Utils\Util::getSettingValue('app_name') ? \App\Utils\Util::getSettingValue('app_name') : config('app.name') }},
                    Design & Developed by
                    <a href="https://aparkitsolutions.com/" target="_blank" class="text-primary">
                        APARK IT Solutions
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <!-- password addon init -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        function toggleExtraFields() {
            const role = document.querySelector('input[name="role"]:checked')?.value;
            document.getElementById('doctor-extra').style.display = (role === 'Doctor') ? 'block' : 'none';
            //Update button class
            const doctorButton = document.querySelector('label[for="doctor"]');
            const mrButton = document.querySelector('label[for="mr"]');
            //Manage Checked and Unchecked
            if (role === 'Doctor') {
                doctorButton.classList.add('btn-primary');
                doctorButton.classList.remove('btn-outline-primary');
                mrButton.classList.remove('btn-primary');
                mrButton.classList.add('btn-outline-primary');
                $("#doctor").attr("checked", true);
                $("#mr").attr("checked", false);
            } else {
                mrButton.classList.add('btn-primary');
                mrButton.classList.remove('btn-outline-primary');
                doctorButton.classList.remove('btn-primary');
                doctorButton.classList.add('btn-outline-primary');
                $("#mr").attr("checked", true);
                $("#doctor").attr("checked", false);
            }
        }

        document.querySelectorAll('input[name="role"]').forEach(el => {
            el.addEventListener('change', toggleExtraFields);
        });

        // On page load
        document.addEventListener('DOMContentLoaded', toggleExtraFields);
    </script>
    <script>
        $(document).ready(function() {
            //OnCheck remember me
            if (localStorage.checkbox && localStorage.checkbox !== '') {
                $('#remember').attr('checked', 'checked');
                $('#email').val(localStorage.username);
                $('#password').val(localStorage.password);
            } else {
                $('#remember').removeAttr('checked');
                $('#email').val(<?= old('email') ?>);
                $('#password').val(<?= old('password') ?>);
            }
            $('#remember').on('click', function() {
                if ($('#remember').is(':checked')) {
                    // save username and password
                    localStorage.username = $('#email').val();
                    localStorage.password = $('#password').val();
                    localStorage.checkbox = $('#remember').val();
                } else {
                    localStorage.username = '';
                    localStorage.password = '';
                    localStorage.checkbox = '';
                }
            });
        });
    </script>
@endsection
