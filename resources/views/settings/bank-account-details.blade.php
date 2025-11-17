<form name="master_form" action="{{ route('settings.bank-account-details.setting.update') }}" method="POST" data-validate>
    @csrf
    <div class="card">
        <div class="card-header">

            <div class="row">
                <div class="col-lg-8">
                    <h5>{{ __('translation.BankAccountDetails') }}</h5>

                </div>
                <div class="col-lg-4 d-flex justify-content-end">
                    <div class="form-switch custom-switch-v1 d-inline-block">
                        <input type="checkbox" class="custom-control custom-switch form-check-input input-primary"
                            name="bank_account_details_setting_enable"
                            {{ Utility::getsettings('bank_account_details_setting_enable') == 'on' ? 'checked' : '' }}
                            data-onstyle="primary" data-toggle="switchbutton">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="account_holder" class="form-label">{{ __('translation.AccountHolder') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="account_holder" name="account_holder"
                            placeholder="{{ __('translation.EnterAccountHolder') }}"
                            value="{{ Utility::getsettings('account_holder') }}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="bank_name" class="form-label">{{ __('translation.BankName') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="bank_name" name="bank_name"
                            placeholder="{{ __('translation.EnterBankName') }}"
                            value="{{ Utility::getsettings('bank_name') }}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="account_number" class="form-label">{{ __('translation.AccountNumber') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="account_number" name="account_number"
                            placeholder="{{ __('translation.EnterAccountNumber') }}"
                            value="{{ Utility::getsettings('account_number') }}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="ifsc_code" class="form-label">{{ __('translation.IFSCCode') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="ifsc_code" name="ifsc_code"
                            placeholder="{{ __('translation.EnterIFSCCode') }}"
                            value="{{ Utility::getsettings('ifsc_code') }}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="branch" class="form-label">{{ __('translation.Branch') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="branch" name="branch"
                            placeholder="{{ __('translation.EnterBranch') }}"
                            value="{{ Utility::getsettings('branch') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('translation.QRCode') }}</h5>

                            </div>
                            <div class="pt-0 card-body">
                                <div class="inner-content">
                                    <div class="py-2 mt-4 text-center logo-content">

                                        @php
                                            $qrCodeUrl = Utility::getpath(Utility::getsettings('qr_code'));
                                            $qrCodePath = public_path(parse_url($qrCodeUrl, PHP_URL_PATH));
                                        @endphp
                                        @if (file_exists($qrCodePath))
                                            <a href="{{ $qrCodeUrl }}" target="_blank">
                                                <img src="{{ $qrCodeUrl }}" id="qr_code" width="200px">
                                            </a>
                                        @endif
                                    </div>
                                    <div class="mt-3 text-center choose-files">
                                        <label for="qr_code">
                                            <input type="file" accept="image/*" class="form-control file"
                                                id="qr_code" name="qr_code"
                                                onchange="document.getElementById('qr_code').src = window.URL.createObjectURL(this.files[0])"
                                                data-filename="qr_code">
                                        </label><br>
                                        <small>Please Upload PNG , JPG , JPEG file and size should be
                                            less than
                                            2MB</small>
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
                <button type="submit" class="btn btn-primary">{{ __('translation.Save') }}</button>
            </div>
        </div>
    </div>
</form>
