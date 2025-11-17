@extends('install.layouts.master')

@section('title', trans('installer_messages.final.title'))
@section('container')
    <form method="post" class="form-box fadeInDown">
        @csrf
        <div class="logo-box-inner">
            <img src="{{ asset('assets/installer/images/logo.png') }}" alt="">
            <h1>
                Welcome To {{ config('app.name') }}
            </h1>
        </div>
        @include('install.layouts.tab', ['tab_name' => 'finish'])
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade active show" id="pills-requirement" role="tabpanel"
                aria-labelledby="pills-requirement-tab" tabindex="0">

                <img src="{{ asset('assets/installer/images/finish-start.png') }}" alt="" class="finish bounceIn">
                <div class="pagination mt-3">
                    <a class="ThemeBtn fadeInRight" href="{{ url('/') }}">
                        {{ trans('installer_messages.final.exit') }}
                    </a>
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
