<div class="card">
    <div class="card-header">
        <h5>{{ __('translation.AppSetting') }}</h5>
    </div>
    <form name="master_form" action="{{ route('settings.appname.update') }}" method="POST" enctype="multipart/form-data"
        data-validate>
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4 col-sm-6 col-md-6">
                    <div class="form-group">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('translation.AppDarkLogo') }}</h5>

                            </div>
                            <div class="pt-0 card-body">
                                <div class="inner-content">
                                    <div class="py-2 mt-4 text-center logo-content dark-logo-content">

                                        @php
                                            $darkLogoUrl = Utility::getpath(Utility::getsettings('app_dark_logo'));
                                            $darkLogoPath = public_path(parse_url($darkLogoUrl, PHP_URL_PATH));

                                            $lightLogoUrl = Utility::getpath(Utility::getsettings('app_logo'));
                                            $lightLogoPath = public_path(parse_url($lightLogoUrl, PHP_URL_PATH));

                                            $faviconUrl = Utility::getpath(Utility::getsettings('favicon_logo'));
                                            $faviconPath = public_path(parse_url($faviconUrl, PHP_URL_PATH));
                                        @endphp
                                        @if (file_exists($darkLogoPath))
                                            <a href="{{ $darkLogoUrl }}" target="_blank">
                                                <img src="{{ $darkLogoUrl }}" id="app_dark" width="200px">
                                            </a>
                                        @else
                                            <a href="{{ asset('assets/img/dark-logo.png') }}" target="_blank">
                                                <img src="{{ asset('assets/img/dark-logo.png') }}" id="app_dark"
                                                    width="200px">
                                            </a>
                                        @endif
                                    </div>
                                    <div class="mt-3 text-center choose-files">
                                        <label for="app_dark_logo">
                                            <input type="file" accept="image/*" class="form-control file"
                                                id="app_dark_logo" name="app_dark_logo"
                                                onchange="document.getElementById('app_dark').src = window.URL.createObjectURL(this.files[0])"
                                                data-filename="app_dark_logo">
                                        </label>
                                        <small>Please Upload PNG , JPG , JPEG file and size should be
                                            less than
                                            2MB</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 col-md-6">
                    <div class="form-group">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('translation.AppLightLogo') }}</h5>
                            </div>
                            <div class="pt-0 card-body bg-primary">
                                <div class="inner-content">
                                    <div class="py-2 mt-4 text-center logo-content light-logo-content">


                                        @if (file_exists($lightLogoPath))
                                            <a href="{{ $lightLogoUrl }}" target="_blank">
                                                <img src="{{ $lightLogoUrl }}" id="app_light" width="200px">
                                            </a>
                                        @else
                                            <a href="{{ asset('assets/img/logo.png') }}" target="_blank">
                                                <img src="{{ asset('assets/img/logo.png') }}" id="app_light"
                                                    width="200px">
                                            </a>
                                        @endif


                                    </div>
                                    <div class="mt-3 text-center choose-files">
                                        <label for="app_logo">
                                            <input type="file" accept="image/*" class="form-control file"
                                                id="app_logo" name="app_logo"
                                                onchange="document.getElementById('app_light').src = window.URL.createObjectURL(this.files[0])"
                                                data-filename="app_logo">
                                        </label>
                                        <small class="text-white">Please Upload PNG , JPG , JPEG file
                                            and size should be
                                            less than
                                            2MB</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 col-md-6">
                    <div class="form-group">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('translation.AppFaviconLogo') }}</h5>
                            </div>
                            <div class="pt-0 card-body">
                                <div class="inner-content">
                                    <div class="py-2 mt-4 text-center logo-content">
                                        @if (file_exists($faviconPath))
                                            <a href="{{ $faviconUrl }}" target="_blank">
                                                <img height="35px" src="{{ $faviconUrl }}" id="app_favicon">
                                            </a>
                                        @else
                                            <a href="{{ asset('assets/img/favicon.ico') }}" target="_blank">
                                                <img height="35px" src="{{ asset('assets/img/favicon.ico') }}"
                                                    alt="app_favicon" id="app_favicon">
                                            </a>
                                        @endif


                                    </div>
                                    <div class="mt-3 text-center choose-files">
                                        <label for="favicon_logo">
                                            <div class="bg-primary company_logo_update"> <i
                                                    class="px-1 ti ti-upload"></i>{{ __('translation.Choose file here') }}
                                            </div>
                                            <input type="file" accept="image/*" class="form-control file"
                                                id="favicon_logo" name="favicon_logo"
                                                onchange="document.getElementById('app_favicon').src = window.URL.createObjectURL(this.files[0])"
                                                data-filename="favicon_logo">
                                        </label>
                                        <small>Please Upload PNG , JPG , JPEG file and size should be
                                            less than 1MB with 32x32 dimension</small>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label for="app_name" class="form-label">{{ __('translation.ApplicationName') }}</label>
                        <input type="text" class="form-control" id="app_name" name="app_name"
                            value="{{ Utility::getsettings('app_name') }}"
                            placeholder="{{ __('translation.EnterApplicationName') }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="text-end">
                <button type="submit" class="btn btn-primary">{{ __('translation.Save') }}</button>
            </div>
        </div>
    </form>
</div>
