@extends('install.layouts.master')

@section('title', trans('installer_messages.welcome.title'))
@section('container')
    <form method="post" class="form-box fadeInDown" action="{{ route('installer.environmentSave') }}" id="env-form">
        @csrf
        <div class="logo-box-inner ">
            <img src="{{ asset('assets/installer/images/logo.png') }}" alt="">
            <h1>
                Welcome To {{ config('app.name') }}
            </h1>
        </div>
        <p class="paragraph" style="text-align: center;">{{ trans('installer_messages.welcome.message') }}</p>
        <div class="pagination">
            <p class="fadeInLeft">
                By clicking "Next", you agree to our Terms & Privacy Policy.
            </p>
            <a href="{{ route('installer.environment') }}" class="ThemeBtn fadeInRight">
                Next
            </a>
        </div>
        <p class="paragraph"> </p>

        <a href="javascript:void(0);" class="down-btn-help">
            Version: {{ config('app.app_version') }} | Need Help?
            <i class="bi bi-question-circle-fill">
            </i>
        </a>
    </form>
@stop
