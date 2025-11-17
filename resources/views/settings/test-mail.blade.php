<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">{{ $page_title }}</h5>
</div>
<div class="modal-body">
    <form name="master_form" action="{{ route('settings.test.send.mail') }}" method="POST" data-validate>
        @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="email">{{ __('Email') }}</label>
                        <input type="text" name="email" class="form-control" placeholder="{{ __('Enter email') }}"
                            required>
                        @error('email')
                            <span class="invalid-email" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="text-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="submit" id="save-btn" class="btn btn-primary">{{ __('Save') }}</button>
            </div>
        </div>
    </form>
</div>
