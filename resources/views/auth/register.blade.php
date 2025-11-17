@extends('layouts.master-without-nav')

@section('title')
    @lang('translation.Register')
@endsection

@section('css')
@endsection

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="{{ url('/') }}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    @php
                                        $logo = \App\Utils\Util::getSettingValue('app_logo');
                                    @endphp
                                    @if (file_exists(public_path('storage/' . $logo)))
                                        <img src="{{ asset('storage/' . $logo) }}" alt="Logo" height="100">
                                    @else
                                        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" height="100">
                                    @endif
                                </span>
                                <span class="app-brand-text demo text-body fw-bolder">{{ config('app.name') }}</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <p class="text-muted mt-2">Sign up as a Doctor or Individual</p>

                        <form method="POST" action="{{ route('register') }}" class="custom-form mt-4 pt-2" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Register as: <span class="text-danger">*</span></label>
                                <div class="btn-group w-100" role="group" aria-label="User Role">
                                    <input type="radio" class="btn-check" name="role" id="doctor" value="Doctor"
                                        autocomplete="off" {{ old('role') == 'doctor' ? 'checked' : '' }} checked>
                                    <label class="btn btn-outline-primary" for="doctor">Doctor</label>

                                    <input type="radio" class="btn-check" name="role" id="mr" value="Mr"
                                        autocomplete="off" {{ old('role') == 'mr' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary" for="mr">Medical Rep.</label>
                                </div>

                                @error('role')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-4 form-control-validation">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    placeholder="Enter Name" value="{{ old('name') }}" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-4 form-control-validation">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    placeholder="Enter Email" value="{{ old('email') }}" autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-4 form-control-validation">
                                <label for="mobile" class="form-label">Mobile <span class="text-danger">*</span></label>
                                <input id="mobile" type="text"
                                    class="form-control @error('mobile') is-invalid @enderror" name="mobile"
                                    placeholder="Enter Mobile Number" value="{{ old('mobile') }}" autofocus
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                                @error('mobile')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div id="doctor-extra">
                                <div class="mb-3">
                                    <label class="form-label" for="specialization">Specialization <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="specialization" id="specialization"
                                        value="{{ old('specialization') }}" @error('specialization') is-invalid @enderror
                                        placeholder="e.g. Cardiologist">
                                </div>
                            </div>
                            <div class="mb-4 form-password-toggle form-control-validation">
                                <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        value="{{ old('password') }}" autocomplete="new-password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i
                                            class="icon-base bx bx-hide"></i></span>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-4 form-password-toggle form-control-validation">
                                <label class="form-label" for="password_confirmation">Confirm Password <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password_confirmation"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        name="password_confirmation" value="{{ old('password_confirmation') }}"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password_confirmation" />
                                    <span class="input-group-text cursor-pointer"><i
                                            class="icon-base bx bx-hide"></i></span>
                                </div>
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">Sign Up</button>
                            </div>
                        </form>

                        <p class="text-center">
                            <span>Already have an account?</span>
                            <a href="{{ route('login') }}" class="fw-medium">
                                <span>Login</span>
                            </a>
                        </p>
                    </div>
                </div>
                <!-- /Register -->

                <div class="text-center mt-5">
                    {{ date('Y') }} ©
                    {{ \App\Utils\Util::getSettingValue('app_name') ? \App\Utils\Util::getSettingValue('app_name') : config('app.name') }},
                    Design & Developed by
                    <a href="https://aparkitsolutions.com/" target="_blank" class="text-primary">
                        APARK IT Solutions
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function toggleExtraFields() {
            const role = document.querySelector('input[name="role"]:checked')?.value;
            document.getElementById('doctor-extra').style.display = (role === 'Doctor') ? 'block' : 'none';
            //Update button class
            const doctorButton = document.querySelector('label[for="doctor"]');
            const mrButton = document.querySelector('label[for="mr"]');
            //Manage Checked and Unchecked
            if (role === 'Doctor') {
                doctorButton.classList.add('btn-primary');
                doctorButton.classList.remove('btn-outline-primary');
                mrButton.classList.remove('btn-primary');
                mrButton.classList.add('btn-outline-primary');
                $("#doctor").attr("checked", true);
                $("#mr").attr("checked", false);
            } else {
                mrButton.classList.add('btn-primary');
                mrButton.classList.remove('btn-outline-primary');
                doctorButton.classList.remove('btn-primary');
                doctorButton.classList.add('btn-outline-primary');
                $("#mr").attr("checked", true);
                $("#doctor").attr("checked", false);
            }
        }

        document.querySelectorAll('input[name="role"]').forEach(el => {
            el.addEventListener('change', toggleExtraFields);
        });

        // On page load
        document.addEventListener('DOMContentLoaded', toggleExtraFields);
    </script>
@endsection
