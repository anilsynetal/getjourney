@extends('layouts.master')

@section('title')
    {{ $page_title }}
@endsection
@section('css')
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> @lang('translation.SubMenus') /</span>
            @lang('translation.EditSubMenu')</h5>
        <div class="card">
            <div class="card-header d-flex justify-content-between pb-0">
                <h5 class="card-title">
                    @lang('translation.EditSubMenu')
                </h5>
            </div>
            <div class="card-body">
                <form name="master_form" method="post" action="{{ $route_action }}" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="main_menu_id" class="form-label">
                                    @lang('translation.MainMenu')
                                    <span class="text-danger">*</span> :</label>
                                <select class="form-control" id="main_menu_id" name="main_menu_id" required>
                                    <option value="">Select</option>
                                    @foreach ($main_menus as $main_menu)
                                        <option value="{{ $main_menu->id }}"
                                            {{ $main_menu->id == $result->main_menu_id ? 'selected' : '' }}>
                                            {{ $main_menu->menu_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="menu_name" class="form-label">
                                    @lang('translation.MenuName')
                                    <span class="text-danger">*</span> :</label>
                                <input type="text" class="form-control" id="menu_name" name="menu_name"
                                    placeholder="@lang('translation.MenuName')" autocomplete="off" required
                                    value="{{ $result->menu_name }}">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 d-none">
                            <div class="form-group mb-3">
                                <label for="route_name" class="form-label">
                                    @lang('translation.RouteName')
                                    <span class="text-danger">*</span> :</label>
                                <input type="text" class="form-control" id="route_name" name="route_name"
                                    placeholder="@lang('translation.RouteName')" autocomplete="off" required
                                    value="{{ $result->route_name }}" readonly>
                            </div>
                        </div>
                    </div>
                    {{-- <h5 class="mt-3">Select Icon</h5> --}}
                    <input type="hidden" name="menu_icon" id="menu_icon">
                    <div class="row icon-demo-content d-none">
                        @foreach ($icons as $icon)
                            <div
                                class="col-xl-3 col-lg-4 col-sm-6 icon-demo {{ $icon == $result->menu_icon ? 'active' : '' }}">
                                <i class="{{ $icon }}"></i> {{ $icon }}
                            </div>
                        @endforeach
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 col-sm-12">
                            <div class="modal-footer">
                                <a href="{{ route('settings.sub-menus.index') }}"
                                    class="btn btn-danger me-2">@lang('translation.Cancel')</a>
                                <button type="submit" class="btn btn-primary form-button">@lang('translation.Save')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
@endsection
@section('script')
    <!-- Sweet alert init js-->
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
@endsection
