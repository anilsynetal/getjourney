<form name="master_form" action="{{ route('settings.social-login.update') }}" method="POST" data-validate>
    @csrf
    <div class="card">
        <div class="card-header">
            <h5>{{ __('Social Login Settings') }}</h5>
        </div>

        <div class="card-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#tt001" role="tab">
                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                        <span class="d-none d-sm-block"><img src="{{ asset('assets/img/google-icon.svg') }}"
                                alt="" height="20" class="imgg"> Google</span>
                    </a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content p-3 text-muted">
                <div class="active" id="tt001" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-check">
                                <input class="form-check-input" fieldrequired="true" type="checkbox" name="google_login"
                                    id="google_login" value="active" autocomplete="off"
                                    {{ Utility::getsettings('google_login') == 'on' ? 'checked' : '' }}>
                                <label class="form-check-label form_custom_label " for="google_login">
                                    Status
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-12 pusher_details">
                            <div class="row mt-3">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group mb-3 mr-0 mr-lg-2 mr-md-2">
                                        <label class="f-14" data-label="true" for="google_client_id">Google Client ID
                                            <sup class="f-14 mr-1">*</sup>
                                        </label>
                                        <input type="text" class="form-control height-35 f-14"
                                            placeholder="e.g. 1275901"
                                            value="{{ Utility::getsettings('google_client_id') }}"
                                            name="google_client_id" id="google_client_id" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="f-14 " data-label="true" for="google_client_secret">Google Secret
                                        <sup class="f-14 mr-1">*</sup>
                                    </label>
                                    <div class="input-group auth-pass-inputgroup">
                                        <input id="google_client_secret" type="password" placeholder="Enter Password"
                                            class="form-control " name="google_client_secret"
                                            value="{{ Utility::getsettings('google_client_secret') }}">
                                        <button class="btn btn-light ms-0" type="button" id="google-eye-button"><i
                                                class="mdi mdi-eye-outline"></i></button>
                                    </div>

                                </div>
                                <div class="col-md-12">
                                    <div class="form-group my-3">
                                        <label for="mail_from_name">Callback</label>
                                        <p class="text-bold">
                                            <span id="google_webhook_link">{{ url('auth/google/callback') }}</span>
                                            <input type="hidden" id="google_redirect" name="google_redirect"
                                                value="{{ url('auth/google/callback') }}">
                                            <a href="javascript:;" class="btn btn-outline-primary ms-2"
                                                id="copy_google_webhook_link">

                                                <i class="fa fa-copy mx-1"></i> Copy
                                            </a>
                                        </p>
                                        <p class="text-primary">(Add this callback url on your google
                                            app settings.)</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="tab-pane" id="tt002" role="tabpanel">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-check">
                                <input class="form-check-input" fieldrequired="true" type="checkbox"
                                    name="facebook_login" id="facebook_login" value="active" autocomplete="off"
                                    {{ Utility::getsettings('facebook_login') == 'on' ? 'checked' : '' }}>
                                <label class="form-check-label form_custom_label " for="facebook_login">
                                    Status
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-12 pusher_details">
                            <div class="row mt-3">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group mb-3 mr-0 mr-lg-2 mr-md-2">
                                        <label class="f-14" data-label="true" for="facebook_client_id">Facebook App
                                            ID
                                            <sup class="f-14 mr-1">*</sup>
                                        </label>
                                        <input type="text" class="form-control height-35 f-14"
                                            placeholder="e.g. 1275901"
                                            value="{{ Utility::getsettings('facebook_client_id') }}"
                                            name="facebook_client_id" id="facebook_client_id" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="f-14 " data-label="true" for="facebook_client_secret">Facebook
                                        Secret
                                        <sup class="f-14 mr-1">*</sup>
                                    </label>
                                    <div class="input-group auth-pass-inputgroup">
                                        <input id="facebook_client_secret" type="password"
                                            placeholder="Enter Facebook Secret" class="form-control "
                                            name="facebook_client_secret"
                                            value="{{ Utility::getsettings('facebook_client_secret') }}">
                                        <button class="btn btn-light ms-0" type="button" id="facebook-eye-button"><i
                                                class="mdi mdi-eye-outline"></i></button>
                                    </div>

                                </div>
                                <div class="col-md-12">
                                    <div class="form-group my-3">
                                        <label for="mail_from_name">Callback</label>
                                        <p class="text-bold">
                                            <span
                                                id="facebook_webhook_link">{{ url('auth/facebook/callback') }}</span>
                                            <input type="hidden" id="facebook_redirect" name="facebook_redirect"
                                                value="{{ url('auth/facebook/callback') }}">
                                            <a href="javascript:;" class="btn btn-outline-primary ms-2"
                                                id="copy_facebook_webhook_link">
                                                <i class="fa fa-copy mx-1"></i> Copy
                                            </a>
                                        </p>
                                        <p class="text-primary">(Add this callback url on your google
                                            app settings.)</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div> --}}
            </div>
        </div><!-- end card-body -->

        <div class="card-footer">
            <div class="text-end">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>

    </div>
</form>
