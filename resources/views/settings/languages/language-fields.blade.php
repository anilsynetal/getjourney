<h5 class="mt-3">@lang('translation.LabelsMessages')</h5>
<form name="master_form" method="post" action="{{ $route_action }}" novalidate>
    @csrf
    <input type="hidden" name="language" value="{{ $language }}">
    <div class="row">
        @foreach ($translations as $key => $translation)
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label for="{{ $key }}" class="form-label">
                        @lang('translation.' . $key)
                        <span class="text-danger">*</span> :</label>
                    <input type="text" class="form-control" id="{{ $key }}" name="{{ $key }}"
                        autocomplete="off" value="{{ $translation }}" required>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row mt-3">
        <div class="col-md-12 col-sm-12">
            <div class="modal-footer">
                <a href="{{ route('settings.index', ['tab' => 'language_setting']) }}"
                    class="btn btn-danger me-2">@lang('translation.Cancel')</a>
                <button type="submit" class="btn btn-primary form-button">@lang('translation.Save')</button>
            </div>
        </div>
    </div>
</form>
