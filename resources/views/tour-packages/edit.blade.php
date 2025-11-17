@extends('layouts.master')
@section('title', $page_title)
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $page_title }}</h5>
                        <a href="{{ route('tour-packages.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i> {{ __('translation.Back') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <form name="master_form" method="post" action="{{ route('tour-packages.update', $result->id) }}"
                            enctype="multipart/form-data" novalidate>
                            @csrf
                            @method('PUT')
                            <div class="row">
                                @foreach ($fields as $field)
                                    @if ($field['type'] == 'hidden')
                                        <input type="hidden" name="{{ $field['name'] }}" id="{{ $field['id'] }}"
                                            {{ isset($field['value']) ? 'value="' . $field['value'] . '"' : '' }}>
                                    @else
                                        <div class="{{ $field['col_size'] }}">
                                            <div class="form-group mb-3">
                                                @if ($field['type'] == 'checkbox')
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="{{ $field['name'] }}" id="{{ $field['name'] }}"
                                                            value="" {{ $field['required'] ? 'required' : '' }}
                                                            {{ isset($result->{$field['name']}) && $result->{$field['name']} ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                                                    </div>
                                                @else
                                                    <label for="{{ $field['id'] }}"
                                                        class="form-label">{{ $field['label'] }} <span
                                                            class="text-danger">{{ $field['required'] ? '*' : '' }}</span></label>
                                                    @if ($field['type'] == 'select')
                                                        <div class="input-group">
                                                            <select class="form-control {{ $field['class'] }}"
                                                                name="{{ $field['name'] }}" id="{{ $field['id'] }}"
                                                                {{ isset($field['data_url']) ? 'data-url=' . $field['data_url'] : '' }}
                                                                {{ isset($field['data_append_id']) ? 'data-append_id=' . $field['data_append_id'] : '' }}
                                                                {{ $field['required'] ? 'required' : '' }}
                                                                {{ isset($field['multiple']) && $field['multiple'] == true ? 'multiple' : '' }}>
                                                                @if (count($field['options']) > 0)
                                                                    <option value="">@lang('translation.please_select')</option>
                                                                @endif
                                                                @foreach ($field['options'] as $key => $option)
                                                                    <option value="{{ $key }}"
                                                                        {{ isset($result->{$field['name']}) && $result->{$field['name']} == $key ? 'selected' : '' }}>
                                                                        {{ $option }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @if (isset($field['master_url']))
                                                                <div class="input-group-append">
                                                                    <button
                                                                        class="btn btn-primary {{ $field['load_modal_class'] }}"
                                                                        data-url="{{ $field['master_url'] }}"
                                                                        data-masterid="{{ $field['id'] }}"
                                                                        type="button"><i class="fas fa-plus"></i></button>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @elseif($field['type'] == 'textarea')
                                                        <textarea class="form-control {{ $field['class'] }}" id="{{ $field['id'] }}" name="{{ $field['name'] }}"
                                                            placeholder="{{ $field['placeholder'] }}" autocomplete="off"
                                                            rows="{{ isset($field['rows']) ? $field['rows'] : 3 }}" {{ $field['required'] ? 'required' : '' }}
                                                            {{ isset($field['readonly']) && $field['readonly'] == true ? 'readonly' : '' }}
                                                            {{ isset($field['minlength']) && $field['minlength'] != null ? 'minlength=' . $field['minlength'] : '' }}
                                                            {{ isset($field['maxlength']) && $field['maxlength'] != null ? 'maxlength=' . $field['maxlength'] : '' }}>{{ isset($result->{$field['name']}) ? $result->{$field['name']} : '' }}</textarea>
                                                    @elseif($field['type'] == 'file')
                                                        <input type="file" class="form-control {{ $field['class'] }}"
                                                            id="{{ $field['id'] }}" name="{{ $field['name'] }}"
                                                            placeholder="{{ $field['placeholder'] }}" autocomplete="off"
                                                            {{ isset($field['oninput']) ? $field['oninput'] : '' }}
                                                            {{ isset($field['accept']) ? 'accept="' . $field['accept'] . '"' : '' }}
                                                            {{ $field['required'] ? 'required' : '' }}>
                                                        @if (isset($result->{$field['name']}) && $result->{$field['name']})
                                                            <div class="mt-2">
                                                                <img src="{{ asset($result->{$field['name']}) }}"
                                                                    alt="Current Image" class="img-thumbnail"
                                                                    width="100">
                                                                <small class="text-muted d-block">Leave empty to keep
                                                                    current image</small>
                                                            </div>
                                                        @endif
                                                    @elseif($field['type'] == 'radio' && $field['is_custom_field'] == 1)
                                                        <div class="custom-control custom-radio">
                                                            @foreach ($field['options'] as $key => $option)
                                                                <input class="form-check-input" type="radio"
                                                                    name="{{ $field['name'] }}"
                                                                    id="{{ $field['id'] . $key }}"
                                                                    value="{{ $option }}"
                                                                    {{ $field['required'] ? 'required' : '' }}
                                                                    {{ isset($result->{$field['name']}) && $result->{$field['name']} == $option ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="{{ $field['id'] . $key }}">{{ $option }}</label>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <input type="{{ $field['type'] }}"
                                                            class="form-control {{ $field['class'] }}"
                                                            id="{{ $field['id'] }}" name="{{ $field['name'] }}"
                                                            placeholder="{{ $field['placeholder'] }}" autocomplete="off"
                                                            {{ isset($field['oninput']) ? $field['oninput'] : '' }}
                                                            {{ isset($field['minlength']) && $field['minlength'] != null ? 'minlength=' . $field['minlength'] : '' }}
                                                            {{ isset($field['maxlength']) && $field['maxlength'] != null ? 'maxlength=' . $field['maxlength'] : '' }}
                                                            {{ $field['required'] ? 'required' : '' }}
                                                            {{ isset($result->{$field['name']}) ? 'value=' . $result->{$field['name']} . '' : (isset($field['value']) ? 'value="' . $field['value'] . '"' : '') }}
                                                            {{ isset($field['readonly']) && $field['readonly'] == true ? 'readonly' : '' }}>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                <div class="col-md-12 col-sm-12">
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('tour-packages.index') }}" class="btn btn-secondary me-2">
                                            {{ __('translation.Cancel') }}
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-save me-1"></i> {{ __('translation.Update') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Initialize TinyMCE for textareas with tinymce-editor class
        tinymce.init({
            selector: '.tinymce-editor',
            height: 300,
            menubar: false,
            plugins: 'lists link image code table',
            toolbar: 'bold italic underline | bullist numlist | link image table | code | undo redo',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
            image_advtab: true,
            image_title: true,
            automatic_uploads: true,
            file_picker_types: 'image',
            paste_data_images: true,
        });
    </script>
@endsection
