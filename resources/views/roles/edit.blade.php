@extends('layouts.master')

@section('title')
    {{ $page_title }}
@endsection
@section('css')
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> @lang('translation.Roles') /</span>
            @lang('translation.EditRole')</h5>
        <div class="card">
            <div class="card-header d-flex justify-content-between pb-0">
                <h5 class="card-title">
                    @lang('translation.EditRole')
                </h5>
            </div>
            <div class="card-body">
                <form name="master_form" method="post" action="{{ $route_action }}" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">
                                    @lang('translation.RoleName')
                                    <span class="text-danger">*</span> :</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="@lang('translation.RoleName')" autocomplete="off" required value="{{ $result->name }}">
                            </div>
                        </div>
                    </div>
                    <h5 class="mt-3">Permissions</h5>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-bordered text-nwrap mb-0">
                                <thead>
                                    <tr>
                                        <th class="font-weight-bold bg-primary text-white">Module</th>
                                        <th class="font-weight-bold bg-primary text-white">Sub Module</th>
                                        <th class="font-weight-bold bg-primary text-white">Select All</th>
                                        <th class="font-weight-bold bg-primary text-white">Permissions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($main_menus as $menu)
                                        @php
                                            $rowspan = count($menu['sub_menus']);
                                            $first = true;
                                            $main_menu_permissions = json_decode($menu['permissions'], true);
                                        @endphp
                                        @foreach ($menu['sub_menus'] as $sub_menu)
                                            @php
                                                $menu_name = strtolower(
                                                    str_replace(' ', '_', str_replace('& ', '', $menu['menu_name'])),
                                                );
                                            @endphp
                                            <tr>
                                                @if ($first)
                                                    <td rowspan="{{ $rowspan }}">
                                                        <input type="checkbox"
                                                            class="form-check-input main-module-checkbox main-module-{{ $menu_name }}"
                                                            data-mainmodule="{{ $menu_name }}"
                                                            id="main-menu-permission-{{ $i }}"
                                                            name="permissions[]"
                                                            value="{{ $main_menu_permissions['permission'] }}"
                                                            @if (in_array($main_menu_permissions['permission'], $result->permissions->pluck('name')->toArray())) checked @endif>

                                                        <strong>{{ $menu['menu_name'] }}
                                                        </strong>
                                                    </td>
                                                    @php
                                                        $first = false;
                                                    @endphp
                                                @endif
                                                <td>
                                                    <strong>{{ $sub_menu['menu_name'] }}
                                                    </strong>
                                                </td>
                                                <td>
                                                    <div class="form-group custom-form-select">
                                                        <input type="checkbox" class="form-check-input select_all"
                                                            data-module="{{ $menu_name }}"
                                                            id="module-{{ $i }}" autocomplete="off">
                                                        <label class="form-label ms-1"
                                                            for="module-{{ $i }}">Select All</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group custom-form-select">
                                                        @php
                                                            $permissions = json_decode($sub_menu['permissions'], true);
                                                        @endphp
                                                        @foreach ($permissions as $permission)
                                                            <div class="custom-form-inner-select">
                                                                <input type="checkbox"
                                                                    class="form-check-input permission-checkbox"
                                                                    id="permission-{{ $i }}"
                                                                    data-module="{{ $menu_name }}" name="permissions[]"
                                                                    value="{{ $permission['permission'] }}"
                                                                    @if (in_array($permission['permission'], $result->permissions->pluck('name')->toArray())) checked @endif>
                                                                <label class="form-check-label me-2"
                                                                    for="permission-{{ $i }}">{{ $permission['name'] }}</label>
                                                            </div>
                                                            @php
                                                                $i++;
                                                            @endphp
                                                        @endforeach
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 col-sm-12">
                            <div class="modal-footer">
                                <a href="{{ route('user-managements.roles.index') }}"
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
    <!-- end col -->
@endsection
@section('script')
    <!-- Sweet alert init js-->
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
@endsection
