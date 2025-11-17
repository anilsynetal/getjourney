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
    <form method="post" class="form-box fadeInDown" action="{{ route('installer.environmentSave') }}" id="env-form">
        @csrf
        <div class="logo-box-inner ">
            <img src="{{ asset('assets/installer/images/logo.png') }}" alt="">
            <h1>
                Welcome To {{ config('app.name') }}
            </h1>
        </div>
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @include('install.layouts.tab', ['tab_name' => 'database'])
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-database" role="tabpanel" aria-labelledby="pills-database-tab"
                tabindex="0">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3 fadeInDown">
                            <label for="db_connection" class="form-label">
                                {{ trans('installer_messages.environment.db_connection') }} <span
                                    class="text-danger">*</span></label>
                            <select name="db_connection" id="db_connection" class="form-control" required
                                onchange="checkPort(this.value)">
                                <option value="mysql" {{ old('mysql') == 'sqlsrv' ? 'selected' : '' }}>MySQL</option>
                                <option value="sqlsrv" {{ old('db_connection') == 'sqlsrv' ? 'selected' : '' }}>SQL Server
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 fadeInDown">
                            <label for="db_host" class="form-label">
                                {{ trans('installer_messages.environment.db_host') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="db_host" name="db_host"
                                placeholder="{{ trans('installer_messages.environment.db_host') }}" value="localhost"
                                required value="{{ old('db_host') }}">

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 fadeInDown">
                            <label for="db_port" class="form-label">
                                {{ trans('installer_messages.environment.db_port') }} <span class="text-danger"></span>
                            </label>
                            <input type="text" class="form-control" id="db_port" name="db_port"
                                placeholder="{{ trans('installer_messages.environment.db_port') }}" required
                                value="{{ old('db_port', '3306') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 fadeInDown">
                            <label for="db_database" class="form-label">
                                {{ trans('installer_messages.environment.db_database') }} <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="db_database" name="db_database"
                                placeholder="{{ trans('installer_messages.environment.db_database') }}" required
                                value="{{ old('db_database') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 fadeInDown">
                            <label for="db_username" class="form-label">
                                {{ trans('installer_messages.environment.db_username') }} <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="db_username" name="db_username"
                                placeholder="{{ trans('installer_messages.environment.db_username') }}" required
                                value="{{ old('db_username') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 fadeInDown">
                            <label for="db_password" class="form-label">
                                {{ trans('installer_messages.environment.db_password') }} <span class="text-danger"></span>
                            </label>
                            <input type="password" class="form-control" id="db_password" name="db_password"
                                placeholder="{{ trans('installer_messages.environment.db_password') }}">
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
