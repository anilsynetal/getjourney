@extends('layouts.master')

@section('title')
    {{ $page_title }}
@endsection
@section('css')
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> @lang('translation.MainMenus') /</span>
            @lang('translation.Add')</h5>
        <div class="card">
            <div class="card-header d-flex justify-content-between pb-0">
                <h5 class="card-title">
                    @lang('translation.Add')
                </h5>
            </div>
            <div class="card-body">
                <form name="master_form" method="post" action="{{ $route_action }}" novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="menu_name" class="form-label">
                                    @lang('translation.MenuName')
                                    <span class="text-danger">*</span> :</label>
                                <input type="text" class="form-control" id="menu_name" name="menu_name"
                                    placeholder="@lang('translation.MenuName')" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 d-none">
                            <div class="form-group mb-3">
                                <label for="route_name" class="form-label">
                                    @lang('translation.RouteName')
                                    <span class="text-danger">*</span> :</label>
                                <input type="text" class="form-control" id="route_name" name="route_name"
                                    placeholder="@lang('translation.RouteName')" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="menu_icon" class="form-label">
                                    @lang('translation.SelectIcon')
                                    <span class="text-danger">*</span> :</label>
                                <input type="hidden" class="form-control" id="menu_icon" name="menu_icon"
                                    placeholder="@lang('translation.SelectIcon')" autocomplete="off" required value="">
                                <input type="text" class="form-control" id="icon-search"
                                    placeholder="@lang('translation.SearchIcons')...">
                            </div>
                        </div>
                    </div>

                    <div class="row icon-demo-content">
                        @foreach ($icons as $icon)
                            <div class="col-xl-1 col-lg-2 col-sm-3 icon-demo">
                                <i class="{{ $icon }}"></i>
                            </div>
                        @endforeach
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 col-sm-12">
                            <div class="modal-footer">
                                <a href="{{ route('settings.main-menus.index') }}"
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
    <script>
        document.getElementById('icon-search').addEventListener('keyup', function() {
            var searchQuery = this.value.toLowerCase();
            var icons = document.querySelectorAll('.icon-demo');

            icons.forEach(function(icon) {
                var iconClass = icon.querySelector('i').className.toLowerCase();
                if (iconClass.includes(searchQuery)) {
                    icon.style.display = 'block';
                } else {
                    icon.style.display = 'none';
                }
            });
        });
        $(document).ready(function() {
            $('.icon-demo').click(function() {
                //Remove active class
                $(".icon-demo").removeClass('active')
                var icon = $(this).find('i').attr('class');
                $('#menu_icon').val(icon);
                $('#icon-search').val(icon);
                $(this).addClass('active')
            });
        });
    </script>
@endsection
