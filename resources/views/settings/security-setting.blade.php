<div class="card">
    <div class="card-header">
        <h5>{{ __('translation.SecuritySettings') }}</h5>
    </div>

    <div class="card-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#tt005" role="tab">
                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                    <span class="d-none d-sm-block">
                        {{ __('translation.TwoFactorAuthentication') }}
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tt006" role="tab">
                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                    <span class="d-none d-sm-block">
                        {{ __('translation.GoogleRecaptcha') }}
                    </span>
                </a>
            </li>

        </ul>

        <!-- Tab panes -->
        <div class="tab-content p-3 text-muted">
            <div class="tab-pane active" id="tt005" role="tabpanel">

                <div class="row">
                    <div class="col-lg-12 mt-3">
                        <div class="card border-grey mt-3 p-4 rounded-top">
                            <div class="row justify-content-center">
                                <div class="col-md-1 d-flex justify-content-center">
                                    <svg class="svg-inline--fa fa-envelope-open-text fa-w-16 f-27 text-lightest"
                                        aria-hidden="true" focusable="false" data-prefix="fa"
                                        data-icon="envelope-open-text" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512" data-fa-i2svg="">
                                        <path fill="currentColor"
                                            d="M176 216h160c8.84 0 16-7.16 16-16v-16c0-8.84-7.16-16-16-16H176c-8.84 0-16 7.16-16 16v16c0 8.84 7.16 16 16 16zm-16 80c0 8.84 7.16 16 16 16h160c8.84 0 16-7.16 16-16v-16c0-8.84-7.16-16-16-16H176c-8.84 0-16 7.16-16 16v16zm96 121.13c-16.42 0-32.84-5.06-46.86-15.19L0 250.86V464c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V250.86L302.86 401.94c-14.02 10.12-30.44 15.19-46.86 15.19zm237.61-254.18c-8.85-6.94-17.24-13.47-29.61-22.81V96c0-26.51-21.49-48-48-48h-77.55c-3.04-2.2-5.87-4.26-9.04-6.56C312.6 29.17 279.2-.35 256 0c-23.2-.35-56.59 29.17-73.41 41.44-3.17 2.3-6 4.36-9.04 6.56H96c-26.51 0-48 21.49-48 48v44.14c-12.37 9.33-20.76 15.87-29.61 22.81A47.995 47.995 0 0 0 0 200.72v10.65l96 69.35V96h320v184.72l96-69.35v-10.65c0-14.74-6.78-28.67-18.39-37.77z">
                                        </path>
                                    </svg>
                                    <!-- <i class="fa fa-envelope-open-text f-27 text-lightest"></i> Font Awesome fontawesome.com -->
                                </div>
                                <div class="col-md-11">
                                    <h6>
                                        Setup Using Email OTP
                                    </h6>
                                    <p class="mb-4 mt-2 f-14 text-dark-grey">Enabling this feature
                                        will send code on your email account
                                        <b>{{ auth()->user()->email }}</b> for log in.
                                    </p>
                                    <button type="button"
                                        class="btn btn-{{ Utility::getsettings('email_2fa_status') == 'on' ? 'danger' : 'primary' }}
                                    update_status"
                                        id="email_2fa_status"
                                        data-url="{{ route('settings.two.factor.update', [
                                            'key' => 'email_2fa_status',
                                            'value' => Utility::getsettings('email_2fa_status') == 'on' ? 'off' : 'on',
                                        ]) }}">
                                        {{ Utility::getsettings('email_2fa_status') == 'on' ? 'Disable' : 'Enable' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card border-grey p-4  rounded-bottom">
                            <div class="row justify-content-center">
                                <div class="col-md-1 d-flex justify-content-center align-self-baseline">
                                    <svg class="svg-inline--fa fa-mobile-alt fa-w-10 f-27 text-lightest"
                                        aria-hidden="true" focusable="false" data-prefix="fa" data-icon="mobile-alt"
                                        role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"
                                        data-fa-i2svg="">
                                        <path fill="currentColor"
                                            d="M192 416c0 17.67-14.33 32-32 32s-32-14.33-32-32 14.33-32 32-32 32 14.33 32 32zM0 64C0 28.65 28.65 0 64 0h192c35.35 0 64 28.65 64 64v384c0 35.35-28.65 64-64 64H64c-35.35 0-64-28.65-64-64V64zm64 352h192V96H64v320z">
                                        </path>
                                    </svg>

                                </div>
                                <div class="col-md-11">
                                    <h6>Setup Using Mobile OTP</h6>
                                    <p class="mb-4 mt-2 f-14 text-dark-grey">
                                        Enabling this feature
                                        will send code on your mobile number
                                        <b>{{ auth()->user()->mobile }}</b> for log in
                                    </p>
                                    <button type="button"
                                        class="btn btn-{{ Utility::getsettings('sms_2fa_status') == 'on' ? 'danger' : 'primary' }}
                                    update_status"
                                        id="sms_2fa_status"
                                        data-url="{{ route('settings.two.factor.update', [
                                            'key' => 'sms_2fa_status',
                                            'value' => Utility::getsettings('sms_2fa_status') == 'on' ? 'off' : 'on',
                                        ]) }}">
                                        {{ Utility::getsettings('sms_2fa_status') == 'on' ? 'Disable' : 'Enable' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="tab-pane" id="tt006" role="tabpanel">

                <div class="row">
                    <form name="master_form" action="{{ route('settings.google-recaptcha.update') }}" method="POST"
                        data-validate>
                        @csrf
                        <div class="card">
                            <div class="card-header">

                                <div class="row">
                                    <div class="col-lg-8">
                                        <h5>{{ __('translation.GoogleRecaptcha') }}</h5>
                                    </div>

                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                name="google_recaptcha_status" id="google_recaptcha_status"
                                                autocomplete="off"
                                                {{ Utility::getsettings('google_recaptcha_status') == 'on' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="google_recaptcha_status">
                                                {{ __('translation.Status') }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row border-top-grey mt-3">
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group my-3 mr-0 mr-lg-2 mr-md-2">
                                                            <label class="f-14 text-dark-grey mb-12" data-label="true"
                                                                for="google_recaptcha_site_key">
                                                                {{ __('translation.GoogleRecaptchaSiteKey') }}
                                                                <span class="text-danger">*</sup>
                                                            </label>

                                                            <input type="text" class="form-control"
                                                                placeholder="e.g. 6LeL_s8ZAAAAAMVC2clQdxxxXXXxxxxxXXX"
                                                                value="{{ Utility::getsettings('google_recaptcha_site_key') }}"
                                                                name="google_recaptcha_site_key"
                                                                id="google_recaptcha_site_key" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group my-3 ml-0 ml-lg-2 ml-md-2">
                                                            <label class="f-14 text-dark-grey mb-12" data-label="true"
                                                                for="google_recaptcha_secret_key">
                                                                {{ __('translation.GoogleRecaptchaSecretKey') }}
                                                                <span class="text-danger">*</sup>
                                                            </label>

                                                            <input type="text" class="form-control"
                                                                placeholder="e.g. 6LeL_s8ZAAAAAMVC2clQdxxxXXXxxxxxXXX"
                                                                value="{{ Utility::getsettings('google_recaptcha_secret_key') }}"
                                                                name="google_recaptcha_secret_key"
                                                                id="google_recaptcha_secret_key" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-end">
                                    <button type="submit"
                                        class="btn btn-primary">{{ __('translation.Save') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>


        </div>
    </div><!-- end card-body -->
</div>
