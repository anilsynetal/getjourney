<form name="master_form" action="{{ route('settings.email.setting.update') }}" method="POST" data-validate>
    @csrf
    <div class="card">
        <div class="card-header">

            <div class="row">
                <div class="col-lg-8">
                    <h5>{{ __('translation.EmailSetting') }}</h5>
                    <small
                        class="text-muted">{{ __('Email Smtp Settings, Notifications And Others Related To Email.') }}</small>
                </div>
                <div class="col-lg-4 d-flex justify-content-end">
                    <div class="form-switch custom-switch-v1 d-inline-block">
                        <input type="checkbox" class="custom-control custom-switch form-check-input input-primary"
                            name="email_setting_enable"
                            {{ Utility::getsettings('email_setting_enable') == 'on' ? 'checked' : '' }}
                            data-onstyle="primary" data-toggle="switchbutton">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="mail_mailer" class="form-label">{{ __('translation.MailMailer') }}</label>
                        <input type="text" class="form-control" id="mail_mailer" name="mail_mailer"
                            placeholder="{{ __('translation.EnterMailMailer') }}"
                            value="{{ Utility::getsettings('mail_mailer') }}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="mail_host" class="form-label">{{ __('translation.MailHost') }}</label>
                        <input type="text" class="form-control" id="mail_host" name="mail_host"
                            placeholder="{{ __('translation.EnterMailHost') }}"
                            value="{{ Utility::getsettings('mail_host') }}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="mail_port" class="form-label">{{ __('translation.MailPort') }}</label>
                        <input type="text" class="form-control" id="mail_port" name="mail_port"
                            placeholder="{{ __('translation.EnterMailPort') }}"
                            value="{{ Utility::getsettings('mail_port') }}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="mail_username" class="form-label">{{ __('translation.MailUsername') }}</label>
                        <input type="text" class="form-control" id="mail_username" name="mail_username"
                            placeholder="{{ __('translation.EnterMailUsername') }}"
                            value="{{ Utility::getsettings('mail_username') }}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="mail_password" class="form-label">{{ __('translation.MailPassword') }}</label>
                        <input type="password" class="form-control" id="mail_password" name="mail_password"
                            placeholder="{{ __('translation.EnterMailPassword') }}"
                            value="{{ Utility::getsettings('mail_password') }}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="mail_encryption" class="form-label">{{ __('translation.MailEncryption') }}</label>
                        <input type="text" class="form-control" id="mail_encryption" name="mail_encryption"
                            placeholder="{{ __('translation.EnterMailEncryption') }}"
                            value="{{ Utility::getsettings('mail_encryption') }}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="mail_from_address"
                            class="form-label">{{ __('translation.MailFromAddress') }}</label>
                        <input type="text" class="form-control" id="mail_from_address" name="mail_from_address"
                            placeholder="{{ __('translation.EnterMailFromAddress') }}"
                            value="{{ Utility::getsettings('mail_from_address') }}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="mail_from_name" class="form-label">{{ __('translation.MailFromName') }}</label>
                        <input type="text" class="form-control" id="mail_from_name" name="mail_from_name"
                            placeholder="{{ __('translation.EnterMailFromName') }}"
                            value="{{ Utility::getsettings('mail_from_name') }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="text-end">
                <button type="button" class="btn btn-primary loadRecordModal float-start"
                    data-url="{{ route('settings.test.mail') }}"
                    id="test-mail">{{ __('translation.SendTestMail') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('translation.Save') }}</button>
            </div>
        </div>
    </div>
</form>
