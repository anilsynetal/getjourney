@extends('layouts.master-without-nav')

@section('title')
    @lang('translation.Login')
@endsection
@section('css')
    <link href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" rel="stylesheet">
@endsection
@section('body')

    <body>
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
                                    {{-- <span class="app-brand-text demo text-body fw-bolder">{{ config('app.name') }}</span> --}}
                                </a>
                            </div>
                            <!-- /Logo -->
                            <p class="text-muted mb-2">Forgot Password? 🔒</p>

                            @if (session('status'))
                                <div class="alert alert-success text-center mb-4" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="useremail" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="useremail" name="email" placeholder="Enter email" value="{{ old('email') }}"
                                        id="email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="text-end">
                                    <button class="btn btn-primary w-100 waves-effect waves-light"
                                        type="submit">Reset</button>
                                </div>

                            </form>
                            <div class="mt-5 text-center">
                                <p class="text-muted mb-0">Remember It ? <a href="{{ url('login') }}"
                                        class="text-primary fw-semibold"> Sign In </a> </p>
                            </div>
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
