@extends('layouts.master')

@section('title')
    @lang('translation.Profile')
@endsection
@section('css')
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> {{ $common_data['module'] }} /</span>
            {{ $common_data['active_page'] }}</h4>
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm order-2 order-sm-1">
                                <div class="d-flex align-items-start mt-3 mt-sm-0">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-xl me-3">
                                            <img src="{{ $result->image_url }}" alt="profile image"
                                                class="img-fluid rounded-circle d-block"
                                                style="width: 90px; height: 50px;object-fit:contain;">
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div>
                                            <h5 class="font-size-16 mb-1">{{ Auth::user()->name }}</h5>
                                            <p class="text-muted font-size-13">{{ ucfirst(Auth::user()->role) }}</p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-auto order-1 order-sm-2">
                                <div class="d-flex align-items-start justify-content-end gap-2">
                                    <div>
                                        <button type="button" class="btn btn-dark loadRecordModal"
                                            data-url="{{ route('home.change-password') }}">
                                            Change Password
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form name="master_form" method="post" action="{{ route('profile.update', Auth::user()->id) }}"
                            novalidate="">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="first_name" class="form-label">
                                            @lang('translation.FirstName') <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control " id="first_name" name="first_name"
                                            placeholder="@lang('translation.FirstName')" autocomplete="off"
                                            value="{{ $result->first_name }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="last_name" class="form-label">
                                            @lang('translation.LastName') <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control " id="last_name" name="last_name"
                                            placeholder="@lang('translation.LastName')" autocomplete="off"
                                            value="{{ $result->last_name }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label">
                                            @lang('translation.Email') <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control " id="email" name="email"
                                            onkeyup="process(event)" placeholder="@lang('translation.Email')" autocomplete="off"
                                            value="{{ $result->email }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="mobile" class="form-label">
                                            @lang('translation.Mobile') <span class="text-danger">*</span></label>
                                        <input type="hidden" name="country_code" id="country_code"
                                            value="{{ $result->country_code }}">
                                        <div>
                                            <input type="text" class="form-control" id="mobile1" name="mobile"
                                                placeholder="@lang('translation.Mobile')" autocomplete="off"
                                                value="{{ $result->country_code . $result->mobile }}"
                                                oninput="this.value=this.value.replace(/[^0-9]/g,'').substring(0,10);"
                                                onclick="select();">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="image" class="form-label">
                                            @lang('translation.UploadImage') <span class="text-danger"></span></label>
                                        <input type="file" class="form-control " id="image" name="image"
                                            autocomplete="off">
                                        <small class="mt-3 text-muted"> Supported Files: <b>.png, .jpg, .jpeg.</b> </small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="modal-footer">
                                        <a href="{{ route('root') }}" class="btn btn-danger">Cancel</a> &nbsp;
                                        <button type="submit" class="btn btn-primary form-button">Update</button>
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
        </div>
    </div>
    <!-- end row -->
    <div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('assets/js/intlTelInput.min.js') }}"></script>
    <script>
        const country_code = document.querySelector("#country_code");
        const phoneInputField = document.querySelector("#mobile");

        if (phoneInputField != undefined && (country_code != undefined || country_code != '')) {
            const phoneInput = window.intlTelInput(phoneInputField, {
                utilsScript: "{{ asset('assets/js/utils.js') }}",
                formatOnDisplay: false,
            });
            phoneInput.setCountry('in');
            $(document).ready(function() {
                $(document).on('input blur', '#mobile', function() {
                    $(this).closest('.form-group').next('.has_error').remove();
                    const countryCode = phoneInput.getSelectedCountryData()
                        .dialCode;
                    const mobile = phoneInput.getNumber(intlTelInputUtils.numberFormat.NATIONAL);
                    if (mobile != '') {
                        const isValid = phoneInput.isValidNumber();
                        if (!isValid) {
                            $(this).closest('.form-group').after(
                                '<span class="text-danger has_error">Please enter a valid mobile number.</span>'
                            );
                        } else {
                            $(this).val($(this).val());
                            $(this).closest('.form-group').next('.has_error').remove();
                        }

                    }
                    $('#country_code').val('+' + countryCode);
                });
            });
        }
        $('#update-profile').on('submit', function(event) {
            event.preventDefault();
            var Id = $('#data_id').val();
            let formData = new FormData(this);
            $('#emailError').text('');
            $('#nameError').text('');
            $('#avatarError').text('');
            $.ajax({
                url: "{{ url('update-profile') }}" + "/" + Id,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#emailError').text('');
                    $('#nameError').text('');
                    $('#avatarError').text('');
                    if (response.isSuccess == false) {
                        alert(response.Message);
                    } else if (response.isSuccess == true) {
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }
                },
                error: function(response) {
                    $('#emailError').text(response.responseJSON.errors.email);
                    $('#nameError').text(response.responseJSON.errors.name);
                    $('#avatarError').text(response.responseJSON.errors.avatar);
                }
            });
        });
    </script>
@endsection
