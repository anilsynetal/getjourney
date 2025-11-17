@extends('layouts.master')
@section('title')
    {{ $page_title }}
@endsection
@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/datatables.net-buttons/css/buttons.dataTables.min.css') }}">
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- datepicker css -->
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.css') }}">
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> @lang('translation.AccountSettings') /</span>
            @lang('translation.Settings')</h4>
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-xl-3">
                        <div class="card sticky-top1 stick-top">
                            <div class="list-group list-group-flush" id="useradd-sidenav">
                                {{-- @can('settings.app-setting') --}}
                                <a href="{{ route('settings.index', ['tab' => 'app_setting']) }}"
                                    class="border-0 list-group-item list-group-item-action {{ request()->get('tab') == 'app_setting' || !request()->get('tab') ? 'setting-active' : '' }}">
                                    {{ __('translation.AppSetting') }}
                                    <div class="float-end">
                                        <i class="ti ti-chevron-right"></i>
                                    </div>
                                </a>
                                {{-- @endcan --}}
                                @can('settings.email-setting')
                                    <a href="{{ route('settings.index', ['tab' => 'email_setting']) }}"
                                        class="border-0 list-group-item list-group-item-action {{ request()->get('tab') == 'email_setting' ? 'setting-active' : '' }}">
                                        {{ __('translation.EmailSetting') }}
                                        <div class="float-end">
                                            <i class="ti ti-chevron-right"></i>
                                        </div>
                                    </a>
                                @endcan

                                {{-- @can('settings.social-login-setting')
                                    <a href="{{ route('settings.index', ['tab' => 'social_login_setting']) }}"
                                        class="list-group-item list-group-item-action border-0 {{ request()->get('tab') == 'social_login_setting' ? 'setting-active' : '' }}">
                                        {{ __('translation.SocialLoginSettings') }}
                                        <div class="float-end">
                                            <i class="ti ti-chevron-right"></i>
                                        </div>
                                    </a>
                                @endcan --}}
                                @can('settings.security-setting')
                                    <a href="{{ route('settings.index', ['tab' => 'security_setting']) }}"
                                        class="list-group-item list-group-item-action border-0 {{ request()->get('tab') == 'security_setting' ? 'setting-active' : '' }}">
                                        {{ __('translation.SecuritySettings') }}
                                        <div class="float-end">
                                            <i class="ti ti-chevron-right"></i>
                                        </div>
                                    </a>
                                @endcan

                                @can('settings.database-backup')
                                    <a href="{{ route('settings.index', ['tab' => 'database_backup']) }}"
                                        class="list-group-item list-group-item-action border-0 {{ request()->get('tab') == 'database_backup' ? 'setting-active' : '' }}">
                                        {{ __('translation.DatabaseBackup') }}
                                        <div class="float-end">
                                            <i class="ti ti-chevron-right"></i>
                                        </div>
                                    </a>
                                @endcan
                                @can('settings.bank-account-details')
                                    <a href="{{ route('settings.index', ['tab' => 'bank_account_details']) }}"
                                        class="list-group-item list-group-item-action border-0 {{ request()->get('tab') == 'bank_account_details' ? 'setting-active' : '' }}">
                                        {{ __('translation.BankAccountDetails') }}
                                        <div class="float-end">
                                            <i class="ti ti-chevron-right"></i>
                                        </div>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9">
                        {{-- @can('settings.app-setting') --}}
                        <div id="app_setting">
                            @if (request()->get('tab') == 'app_setting' || !request()->get('tab'))
                                @include('settings.app-setting')
                            @endif
                        </div>
                        {{-- @endcan --}}

                        @can('settings.email-setting')
                            <!-- Email Setting -->
                            <div id="email_setting">
                                @if (request()->get('tab') == 'email_setting')
                                    @include('settings.email-setting')
                                @endif
                            </div>
                        @endcan

                        <!-- Social Login Settings -->
                        {{-- @can('settings.social-login-setting')
                            <div id="social_login_setting">
                                @if (request()->get('tab') == 'social_login_setting')
                                    @include('settings.social-login-setting')
                                @endif
                            </div>
                        @endcan --}}
                        @can('settings.security-setting')
                            @if (request()->get('tab') == 'security_setting')
                                <!-- Security Settings -->
                                <div id="security_setting">
                                    @include('settings.security-setting')
                                </div>
                            @endif
                        @endcan

                        <!-- Module Database Backup -->
                        @can('settings.database-backup')
                            <div>
                                @if (request()->get('tab') == 'database_backup')
                                    @php
                                        $common_data = \App\Models\Setting::getBackupTableData();
                                        $ajax_url = $common_data['ajax_url'];
                                        $common_data['is_deleted'] = false;
                                    @endphp

                                    {{-- Display success/error messages --}}
                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                            </button>
                                        </div>
                                    @endif

                                    @if (session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                            </button>
                                        </div>
                                    @endif

                                    @include('settings.datatable', [
                                        'common_data' => $common_data,
                                        'ajax_url' => $ajax_url,
                                    ])
                                @endif
                            </div>
                        @endcan
                        <!-- Module System Update -->
                        @can('settings.system-update')
                            <div>
                                @if (request()->get('tab') == 'system_update')
                                    @php
                                        $live_version = \App\Models\Setting::checkUpdate();
                                    @endphp
                                    @include('settings.system-update', [
                                        'live_version' => $live_version,
                                    ])
                                @endif
                            </div>
                        @endcan
                        @can('settings.bank-account-details')
                            <div>
                                @if (request()->get('tab') == 'bank_account_details')
                                    @include('settings.bank-account-details')
                                @endif
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

            </div>
        </div>
    </div>

    <div class="modal fade" id="commonModalLarge" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Field</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="createForm" class="ajax-form" autocomplete="off">
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="f-14 text-dark-grey mb-12 mt-3" data-label="" for="module">Module
                                </label>
                                <div class="form-group mb-0">
                                    <select name="module" id="module" data-live-search="true"
                                        class="form-control select-picker" data-size="8">
                                        <option value="1">Company</option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group my-3">
                                    <label class="f-14 text-dark-grey mb-12" data-label="true" for="label">Field
                                        Label
                                        <sup class="f-14 mr-1">*</sup>
                                    </label>
                                    <input type="text" class="form-control height-35 f-14" placeholder=""
                                        value="" name="label" id="label">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="f-14 text-dark-grey mb-12 mt-3" data-label="" for="type">Field Type
                                </label>
                                <div class="form-group mb-0">
                                    <select name="type" id="type" data-live-search="true"
                                        class="form-control select-picker" data-size="8" tabindex="null">
                                        <option value="text">text</option>
                                        <option value="number">number</option>
                                        <option value="password">password</option>
                                        <option value="textarea">textarea</option>
                                        <option value="select">select</option>
                                        <option value="radio">radio</option>
                                        <option value="date">date</option>
                                        <option value="checkbox">checkbox</option>
                                        <option value="file">file</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group my-3">
                                    <label class="f-14 text-dark-grey mb-12 w-100" for="usr">is required</label>
                                    <div class="d-flex">
                                        <div class="form-check-inline custom-control custom-radio mt-2 mr-3">
                                            <input type="radio" value="yes" class="custom-control-input"
                                                id="optionsRadios1" name="required" checked="">
                                            <label class="custom-control-label pt-1 cursor-pointer"
                                                for="optionsRadios1">Yes</label>
                                        </div>
                                        <div class="form-check-inline custom-control custom-radio mt-2 mr-3">
                                            <input type="radio" value="no" class="custom-control-input"
                                                id="optionsRadios2" name="required">
                                            <label class="custom-control-label pt-1 cursor-pointer"
                                                for="optionsRadios2">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group my-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="export" id="export"
                                            value="1">
                                        <label class="form-check-label form_custom_label " for="export">
                                            Allow export in table view
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-repeater d-none">
                            <div id="addMoreBox1" class="row my-3">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label class="control-label">Value</label>
                                        <input class="form-control height-35 f-14" name="value[]" type="text"
                                            value="" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div id="insertBefore"></div>
                            <div class="row">
                                <div class="col-md-12 mt-4">
                                    <a class="f-15 f-w-500" href="javascript:;" data-repeater-create=""
                                        id="plusButton"><i class="icons icon-plus font-weight-bold mr-1"></i>Add
                                        Item</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <!-- Required datatable js -->
    <script src="{{ URL::asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ URL::asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ URL::asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- datepicker js -->
    <script src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script>
        // load date picker
        flatpickr('.datepicker-basic', {
            dateFormat: "d-m-Y",
            defaultDate: new Date()
        });
        flatpickr('.datepicker-timepicker', {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
        });
    </script>

    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300,
        });

        //If google_recaptcha_status is active then key and secret is required
        $(document).on('change', '#google_recaptcha_status', function() {
            if ($(this).is(':checked')) {
                $('#google_recaptcha_site_key').attr('required', 'required');
                $('#google_recaptcha_secret_key').attr('required', 'required');
            } else {
                $('#google_recaptcha_site_key').removeAttr('required');
                $('#google_recaptcha_secret_key').removeAttr('required');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            var genericExamples = document.querySelectorAll('[data-trigger]');
            for (i = 0; i < genericExamples.length; ++i) {
                var element = genericExamples[i];
                new Choices(element, {
                    placeholderValue: 'This is a placeholder set in the config',
                    searchPlaceholderValue: 'Select Option',
                });
            }
        });

        function removeClassByPrefix(node, postfix) {
            for (let i = 0; i < node.classList.length; i++) {
                let value = node.classList[i];
                if (value.endsWith(postfix)) {
                    node.classList.remove(value);
                    i--;
                }
            }
        }
        //View Google Client Secret Key
        //View Google Secret Key
        $(document).on('click', '#google-eye-button', function() {
            var google_client_secret = $('#google_client_secret');
            var google_eye_button = $('#google-eye-button');
            if (google_client_secret.attr('type') == 'password') {
                google_client_secret.attr('type', 'text');
                google_eye_button.html('<i class="mdi mdi-eye-off"></i>');
            } else {
                google_client_secret.attr('type', 'password');
                google_eye_button.html('<i class="mdi mdi-eye-outline"></i>');
            }
        });

        $(document).on('click', '#eye-button', function() {
            var app_secret = $('#pusher_app_secret');
            var eye_button = $('#eye-button');
            if (app_secret.attr('type') == 'password') {
                app_secret.attr('type', 'text');
                eye_button.html('<i class="mdi mdi-eye-off"></i>');
            } else {
                app_secret.attr('type', 'password');
                eye_button.html('<i class="mdi mdi-eye-outline"></i>');
            }
        });

        //On click copy google webhook link
        $(document).on('click', '#copy_google_webhook_link', function() {
            var google_webhook_link = document.getElementById('google_webhook_link');
            var copyText = google_webhook_link.innerText;
            navigator.clipboard.writeText(copyText);
            $('#copy_google_webhook_link').html('<i class="fa fa-copy mx-1"></i> Copied');
            setTimeout(() => {
                $('#copy_google_webhook_link').html('<i class="fa fa-copy mx-1"></i> Copy');
            }, 1000);
        });

        //Same For Facebook
        $(document).on('click', '#facebook-eye-button', function() {
            var facebook_client_secret = $('#facebook_client_secret');
            var facebook_eye_button = $('#facebook-eye-button');
            if (facebook_client_secret.attr('type') == 'password') {
                facebook_client_secret.attr('type', 'text');
                facebook_eye_button.html('<i class="mdi mdi-eye-off"></i>');
            } else {
                facebook_client_secret.attr('type', 'password');
                facebook_eye_button.html('<i class="mdi mdi-eye-outline"></i>');
            }
        });

        //On click copy facebook webhook link
        $(document).on('click', '#copy_facebook_webhook_link', function() {
            var facebook_webhook_link = document.getElementById('facebook_webhook_link');
            var copyText = facebook_webhook_link.innerText;
            navigator.clipboard.writeText(copyText);
            $('#copy_facebook_webhook_link').html('<i class="fa fa-copy mx-1"></i> Copied');
            setTimeout(() => {
                $('#copy_facebook_webhook_link').html('<i class="fa fa-copy mx-1"></i> Copy');
            }, 1000);
        });

        // theme color
        var themescolors = document.querySelectorAll(".themes-color > a");
        for (var h = 0; h < themescolors.length; h++) {
            var c = themescolors[h];
            c.addEventListener("click", function(event) {
                var targetElement = event.target;
                if (targetElement.tagName == "SPAN") {
                    targetElement = targetElement.parentNode;
                }
                var temp = targetElement.getAttribute("data-value");
                removeClassByPrefix(document.querySelector("body"), "theme-");
                document.querySelector("body").classList.add(temp);
            });
        }

        $(document).on('change', 'input[name="domain_config"]', function() {
            if ($(this).is(':checked')) {
                $('.main-domain').addClass('d-block');
                $('.main-domain').removeClass('d-none');
            } else {
                $('.main-domain').addClass('d-none');
                $('.main-domain').removeClass('d-block');
            }
        });

        $('body').on('click', '.send_mail', function() {
            var action = $(this).data('url');
            var modal = $('#common_modal');
            $.get(action, function(response) {
                modal.find('.modal-title').html('{{ __('translation.Test Mail') }}');
                modal.find('.body').html(response);
                modal.modal('show');
            })
        });

        $(document).on('click', "input[name='storage_type']", function() {
            if ($(this).val() == 's3') {
                $('.s3-setting').removeClass('d-none');
                $('.google-setting').addClass('d-none');
                $('.local-setting').addClass('d-none');
            } else if ($(this).val() == 'google') {
                $('.s3-setting').addClass('d-none');
                $('.google-setting').removeClass('d-none');
                $('.local-setting').addClass('d-none');
            } else {
                $('.s3-setting').addClass('d-none');
                $('.google-setting').addClass('d-none');
                $('.local-setting').removeClass('d-none');
            }
        });

        // change notification status
        $(document).on("change", ".chnageEmailNotifyStatus", function(e) {
            var csrf = $("meta[name=csrf-token]").attr("content");
            var email = $(this).parent().find("input[name=email_notification]").is(":checked");
            var action = $(this).attr("data-url");
            $.ajax({
                type: "POST",
                url: action,
                data: {
                    _token: csrf,
                    type: 'email',
                    email_notification: email,
                },
                success: function(response) {
                    if (response.warning) {
                        show_toastr("Warning!", response.warning, "warning");
                    }
                    if (response.is_success) {
                        show_toastr("Success!", response.message, "success");
                    }
                },
            });
        });
        $(document).on("change", ".chnagesmsNotifyStatus", function(e) {
            var csrf = $("meta[name=csrf-token]").attr("content");
            var sms = $(this).parent().find("input[name=sms_notification]").is(":checked");
            var action = $(this).attr("data-url");
            $.ajax({
                type: "POST",
                url: action,
                data: {
                    _token: csrf,
                    type: 'sms',
                    sms_notification: sms,
                },
                success: function(response) {
                    if (response.warning) {
                        show_toastr("Warning!", response.warning, "warning");
                    }
                    if (response.is_success) {
                        show_toastr("Success!", response.message, "success");
                    }
                },
            });
        });
        $(document).on("change", ".chnageNotifyStatus", function(e) {
            var csrf = $("meta[name=csrf-token]").attr("content");
            var notify = $(this).parent().find("input[name=notify]").is(":checked");
            var action = $(this).attr("data-url");
            $.ajax({
                type: "POST",
                url: action,
                data: {
                    _token: csrf,
                    type: 'notify',
                    notify: notify,
                },
                success: function(response) {
                    if (response.warning) {
                        show_toastr("Warning!", response.warning, "warning");
                    }
                    if (response.is_success) {
                        show_toastr("Success!", response.message, "success");
                    }
                },
            });
        });
    </script>
    <script>
        $(document).on('change', '#field_type', function() {
            $('#itemsTable tbody .item-row').remove();
            let field_type = $(this).val();
            if (field_type == 'select' || field_type == 'radio' || field_type ==
                'checkbox') {
                $('#field_values').show();
                $("#addMore").click();
            } else {
                $('#field_values').hide();
            }
            if (field_type == 'textarea' || field_type == 'text') {
                $(".field_length").show();
            } else {
                $(".field_length").hide();
            }
        });

        let data_table;
        $(document).ready(function() {
            @if (isset($ajax_url))
                data_table = initializeTable('#data_table', '{{ $ajax_url }}',
                    <?= json_encode($common_data['js_columns']) ?>, <?= json_encode($common_data['buttons']) ?>);
                $('#status_filter').change(function() {
                    data_table.ajax.reload();
                });
            @endif
        });
    </script>
@endsection
