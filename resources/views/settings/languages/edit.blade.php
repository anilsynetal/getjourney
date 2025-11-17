@extends('layouts.master')

@section('title')
    {{ $page_title }}
@endsection
@section('css')
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            @lang('translation.Languages')
        @endslot
        @slot('title')
            @lang('translation.EditLanguage')
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between pb-0">
                    <h4 class="card-title">
                        @lang('translation.EditLanguage')
                    </h4>
                    <a href="{{ route('settings.index', ['tab' => 'language_setting']) }}"
                        class="btn btn-outline-dark ms-auto" title="Back">
                        @lang('translation.Back')
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">
                                    @lang('translation.Language')
                                    <span class="text-danger">*</span> :</label>
                                <input type="text" class="form-control" id="name" name="name" autocomplete="off"
                                    value="{{ $result->name }}" disabled style="background: #f8f9fa;">
                            </div>
                        </div>
                        @if (count($english_translations) > count($translations))
                            <div class="col-md-4">
                                <div class="form-group mt-2">
                                    <button type="submit" class="btn btn-primary form-button sync"
                                        data-url="{{ route('settings.languages.sync', ['id' => $result->id]) }}"
                                        style="margin-top: 20px !important;" id="sync">@lang('translation.Sync')</button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <section class="mt-3">
                        <h5 class="mt-3">@lang('translation.LabelsMessages')</h5>
                        <form name="master_form" method="post" action="{{ $route_action }}" novalidate>
                            @csrf
                            @method('PUT')
                            <div class="row">
                                @foreach ($translations as $key => $translation)
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="{{ $key }}" class="form-label">
                                                @lang('translation.' . $key)
                                                <span class="text-danger">*</span> :</label>
                                            <input type="text" class="form-control" id="{{ $key }}"
                                                name="{{ $key }}" autocomplete="off" value="{{ $translation }}"
                                                required>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12 col-sm-12">
                                    <div class="modal-footer">
                                        <a href="{{ route('settings.index', ['tab' => 'language_setting']) }}"
                                            class="btn btn-danger me-2">@lang('translation.Cancel')</a>
                                        <button type="submit"
                                            class="btn btn-primary form-button">@lang('translation.Update')</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </section>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
@endsection
@section('script')
    <!-- Sweet alert init js-->
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
@endsection
