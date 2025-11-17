<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">@lang('translation.ChangePassword')</h5>
</div>
<div class="modal-body">
    <form name="master_form" method="post" action="{{ $route_action }}">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="form-group  mb-3">
                    <label for="password" class="form-label">
                        @lang('translation.Password')<span class="text-danger">*</span>:</label>
                    <div class="input-group auth-pass-inputgroup">
                        <input type="password" placeholder="@lang('translation.Password')" name="password" class="form-control"
                            id="password" autocomplete="off">
                        <button class="btn btn-light ms-0" type="button" id="password-eye-button"
                            onclick="toggleEyeButton('password-eye-button','password');"><i
                                class="mdi mdi-eye-outline"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label for="password_confirmation" class="form-label">
                        @lang('translation.ConfirmPassword')<span class="text-danger">*</span>:</label>
                    <div class="input-group auth-pass-inputgroup">
                        <input type="password" placeholder="@lang('translation.ConfirmPassword')" name="password_confirmation"
                            class="form-control" id="password_confirmation" autocomplete="off">
                        <button class="btn btn-light ms-0" type="button" id="confirm-password-eye-button"
                            onclick="toggleEyeButton('confirm-password-eye-button','password_confirmation');"><i
                                class="mdi mdi-eye-outline"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12">
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary form-button">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>
