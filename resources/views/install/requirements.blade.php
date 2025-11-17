@extends('install.layouts.master')

@section('title', trans('installer_messages.requirements.title'))
@section('container')
    <form method="post" class="form-box fadeInDown" action="{{ route('installer.environmentSave') }}" id="env-form">
        @csrf
        <div class="logo-box-inner">
            <img src="{{ asset('assets/installer/images/logo.png') }}" alt="">
            <h1>
                Welcome To {{ config('app.name') }}
            </h1>
        </div>
        @include('install.layouts.tab', ['tab_name' => 'requirement'])
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade active show" id="pills-requirement" role="tabpanel"
                aria-labelledby="pills-requirement-tab" tabindex="0">
                <div class="main">
                    <ul class="list">
                        <li class="list__item {{ $phpSupportInfo['supported'] ? 'success' : 'error' }}">PHP Version >=
                            {{ $phpSupportInfo['minimum'] }}</li>

                        @foreach ($requirements['requirements'] as $extention => $enabled)
                            <li class="list__item {{ $enabled ? 'success' : 'error' }}">{{ $extention }}</li>
                        @endforeach
                    </ul>
                    @if (!isset($requirements['errors']) && $phpSupportInfo['supported'] == 'success')
                        <div class="pagination">
                            <a href="{{ route('installer.permissions') }}" class="ThemeBtn fadeInRight">
                                {{ trans('installer_messages.next') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
    <a href="javascript:void(0);" class="down-btn-help fadeInUp">
        Need Help?
        <i class="bi bi-question-circle-fill">
        </i>
    </a>
@stop
