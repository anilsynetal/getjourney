@extends('install.layouts.master')

@section('title', trans('installer_messages.permissions.title'))
@section('container')
    <form method="post" class="form-box fadeInDown" action="{{ route('installer.environmentSave') }}" id="env-form">
        @csrf
        <div class="logo-box-inner">
            <img src="{{ asset('assets/installer/images/logo.png') }}" alt="">
            <h1>
                Welcome To {{ config('app.name') }}
            </h1>
        </div>
        @include('install.layouts.tab', ['tab_name' => 'permissions'])
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade active show" id="pills-requirement" role="tabpanel"
                aria-labelledby="pills-requirement-tab" tabindex="0">
                @if (isset($permissions['errors']))
                    <div class="alert alert-danger">
                        Please fix the below error and then click
                        {{ trans('installer_messages.checkPermissionAgain') }}.
                        <br><br>
                        <strong>To set correct permissions, run the following commands:</strong>
                        <pre>
chmod -R 775 storage/app/
chmod -R 775 storage/framework/
chmod -R 775 storage/logs/
chmod -R 775 bootstrap/cache/
                        </pre>
                    </div>
                @endif
                <div class="main">
                    <ul class="step">
                        @foreach ($permissions['permissions'] as $permission)
                            <li class="list__item list__item--permissions {{ $permission['isSet'] ? 'success' : 'error' }}">
                                {{ $permission['folder'] }}<span>{{ $permission['permission'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="pagination mt-3">
                        @if (!isset($permissions['errors']))
                            <a class="ThemeBtn fadeInRight" href="{{ route('installer.database') }}">
                                {{ trans('installer_messages.next') }}
                            </a>
                        @else
                            <a class="ThemeBtn fadeInRight" href="javascript:window.location.href='';">
                                {{ trans('installer_messages.checkPermissionAgain') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
    <a href="javascript:void(0);" class="down-btn-help fadeInUp">
        Need Help?
        <i class="bi bi-question-circle-fill"></i>
    </a>
@stop
