@extends('install.layouts.master')

@section('title', trans('installer_messages.environment.title'))
@section('style')
    <link href="{{ asset('installer/froiden-helper/helper.css') }}" rel="stylesheet" />
    <style>
        .form-control {
            height: 14px;
            width: 100%;
        }

        .has-error {
            color: red;
        }

        .has-error input {
            color: black;
            border: 1px solid red;
        }
    </style>
@endsection
@section('container')
    <form method="post" class="form-box fadeInDown" action="{{ route('installer.validate') }}" id="env-form">
        @csrf
        <div class="logo-box-inner ">
            <img src="{{ asset('assets/installer/images/logo.png') }}" alt="">
            <h1>
                Welcome To {{ config('app.name') }}
            </h1>
        </div>
        @if ($errors->has('error'))
            <div class="alert alert-danger">
                {{ $errors->first('error') }}
            </div>
        @endif
        @include('install.layouts.tab', ['tab_name' => 'verify'])
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-verify" role="tabpanel" aria-labelledby="pills-verify-tab"
                tabindex="0">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3 fadeInDown">
                            <label for="name" class="form-label">
                                {{ trans('installer_messages.environment.name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="{{ trans('installer_messages.environment.name') }}" required
                                value="{{ old('name') }}">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 fadeInDown">
                            <label for="mobile" class="form-label">
                                {{ trans('installer_messages.environment.mobile') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="mobile" name="mobile"
                                placeholder="{{ trans('installer_messages.environment.mobile') }}" required
                                value="{{ old('mobile') }}" maxlength="10" minlength="10"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');">
                            @error('mobile')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 fadeInDown">
                            <label for="email" class="form-label">
                                {{ trans('installer_messages.environment.email') }} <span
                                    class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="{{ trans('installer_messages.environment.email') }}" required
                                value="{{ old('email') }}">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 fadeInDown">
                            <label for="password" class="form-label">
                                {{ trans('installer_messages.environment.login_password') }} <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="password" name="password"
                                placeholder="{{ trans('installer_messages.environment.login_password') }}" required
                                value="">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 fadeInDown">
                            <label for="app_name" class="form-label">
                                {{ trans('installer_messages.environment.app_name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="app_name" name="app_name"
                                placeholder="{{ trans('installer_messages.environment.app_name') }}" required
                                value="{{ old('app_name') }}">
                            @error('app_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 fadeInDown">
                            <label for="license_code" class="form-label">
                                {{ trans('installer_messages.environment.license_code') }} <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="license_code" name="license_code"
                                placeholder="{{ trans('installer_messages.environment.license_code') }}" required
                                value="{{ old('license_code') }}">
                            @error('license_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                    </div>
                    <div class="pagination ">
                        <button class="ThemeBtn fadeInRight" onclick="checkEnv();return false">
                            {{ trans('installer_messages.next') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <a href="javascript:void(0);" class="down-btn-help fadeInUp">
        Need Help?
        <i class="bi bi-question-circle-fill">
        </i>
    </a>
    <script>
        function checkPort(db_connection) {
            if (db_connection == 'mysql') {
                document.getElementById("db_port").value = "3306"
            } else {
                document.getElementById("db_port").value = "1433"
            }
        }

        function checkEnv() {
            $.easyAjax({
                url: "{!! route('installer.environmentSave') !!}",
                type: "GET",
                data: $("#env-form").serialize(),
                container: "#env-form",
                messagePosition: "inline"
            });
        }
    </script>
@stop
@section('scripts')
    <script src="{{ asset('assets/installer/js/jQuery-2.2.0.min.js') }}"></script>
    <script src="{{ asset('assets/installer/froiden-helper/helper.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
@endsection
