@extends('layouts.master-without-nav')

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
                                <span class="app-brand-text demo text-body fw-bolder">{{ config('app.name') }}</span>
                            </a>
                        </div>
                        <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                        <div class="card-body">
                            @if (session('resent'))
                                <div class="alert alert-success" role="alert">
                                    {{ __('A fresh verification link has been sent to your email address.') }}
                                </div>
                            @endif

                            {{ __('Before proceeding, please check your email for a verification link.') }}
                            {{ __('If you did not receive the email') }},
                            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <button type="submit"
                                    class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
