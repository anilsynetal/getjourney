<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">{{ $page_title }}</h5>
</div>
<div class="modal-body">
    <form name="master_form" method="post" action="{{ $route_action }}" novalidate>
        @csrf
        <div class="row">
            @foreach ($fields as $field)
                @if ($field['type'] == 'hidden')
                    <input type="hidden" name="{{ $field['name'] }}" id="{{ $field['id'] }}"
                        {{ isset($field['value']) ? $field['value'] : '' }}>
                @else
                    <div class="{{ $field['col_size'] }}">
                        <div class="form-group mb-3">
                            @if ($field['type'] == 'checkbox')
                                <div class="custom-control custom-checkbox">
                                    <input class="form-check-input" type="checkbox" name="{{ $field['name'] }}"
                                        id="{{ $field['name'] }}" value=""
                                        {{ $field['required'] ? 'required' : '' }}>
                                    <label class="form-check-label"
                                        for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                                </div>
                            @else
                                <label for="{{ $field['id'] }}" class="form-label">{{ $field['label'] }} <span
                                        class="text-danger">{{ $field['required'] ? '*' : '' }}</span></label>
                                @if ($field['type'] == 'select')
                                    <div class="input-group">
                                        <select class="form-control {{ $field['class'] }}" name="{{ $field['name'] }}"
                                            id="{{ $field['id'] }}"
                                            {{ isset($field['data_url']) ? 'data-url=' . $field['data_url'] : '' }}
                                            {{ isset($field['data_append_id']) ? 'data-append_id=' . $field['data_append_id'] : '' }}
                                            {{ $field['required'] ? 'required' : '' }}
                                            {{ isset($field['multiple']) && $field['multiple'] == true ? 'multiple' : '' }}>
                                            @if (count($field['options']) > 0)
                                                <option value="">@lang('translation.please_select')</option>
                                            @endif
                                            @foreach ($field['options'] as $key => $option)
                                                <option value="{{ $key }}">
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if (isset($field['master_url']))
                                            <div class="input-group-append">
                                                <button class="btn btn-primary {{ $field['load_modal_class'] }}"
                                                    data-url="{{ $field['master_url'] }}"
                                                    data-masterid="{{ $field['id'] }}" type="button"><i
                                                        class="fas fa-plus"></i></button>
                                            </div>
                                        @endif
                                    </div>
                                @elseif($field['type'] == 'textarea')
                                    <textarea class="form-control {{ $field['class'] }}" id="{{ $field['id'] }}" name="{{ $field['name'] }}"
                                        placeholder="{{ $field['placeholder'] }}" autocomplete="off"
                                        rows="{{ isset($field['rows']) ? $field['rows'] : 3 }}" {{ $field['required'] ? 'required' : '' }}
                                        {{ isset($field['readonly']) && $field['readonly'] == true ? 'readonly' : '' }}
                                        {{ isset($field['minlength']) && $field['minlength'] != null ? 'minlength=' . $field['minlength'] : '' }}
                                        {{ isset($field['maxlength']) && $field['maxlength'] != null ? 'maxlength=' . $field['maxlength'] : '' }}></textarea>
                                @elseif($field['type'] == 'file')
                                    <input type="file" class="form-control {{ $field['class'] }}"
                                        id="{{ $field['id'] }}" name="{{ $field['name'] }}"
                                        placeholder="{{ $field['placeholder'] }}" autocomplete="off"
                                        {{ isset($field['oninput']) ? $field['oninput'] : '' }}
                                        {{ isset($field['accept']) ? $field['accept'] : '' }}
                                        {{ $field['required'] ? 'required' : '' }}>
                                @elseif($field['type'] == 'radio' && $field['is_custom_field'] == 1)
                                    <div class="custom-control custom-radio">
                                        @foreach ($field['options'] as $key => $option)
                                            <input class="form-check-input" type="radio" name="{{ $field['name'] }}"
                                                id="{{ $field['id'] . $key }}" value="{{ $option }}"
                                                {{ $field['required'] ? 'required' : '' }}>
                                            <label class="form-check-label"
                                                for="{{ $field['id'] . $key }}">{{ $option }}</label>
                                        @endforeach
                                    </div>
                                @else
                                    @if (isset($field['datalist']) && $field['datalist'])
                                        <input type="{{ $field['type'] }}" class="form-control {{ $field['class'] }}"
                                            id="{{ $field['id'] }}" name="{{ $field['name'] }}"
                                            list="{{ $field['id'] }}-list" placeholder="{{ $field['placeholder'] }}"
                                            autocomplete="off" {{ isset($field['oninput']) ? $field['oninput'] : '' }}
                                            {{ isset($field['minlength']) && $field['minlength'] != null ? 'minlength=' . $field['minlength'] : '' }}
                                            {{ isset($field['maxlength']) && $field['maxlength'] != null ? 'maxlength=' . $field['maxlength'] : '' }}
                                            {{ $field['required'] ? 'required' : '' }}
                                            {{ isset($field['value']) ? $field['value'] : '' }}
                                            {{ isset($field['readonly']) && $field['readonly'] == true ? 'readonly' : '' }}>
                                        <datalist id="{{ $field['id'] }}-list">
                                            @foreach ($field['datalist'] as $option)
                                                <option value="{{ $option }}">
                                            @endforeach
                                        </datalist>
                                    @else
                                        <input type="{{ $field['type'] }}" class="form-control {{ $field['class'] }}"
                                            id="{{ $field['id'] }}" name="{{ $field['name'] }}"
                                            placeholder="{{ $field['placeholder'] }}" autocomplete="off"
                                            {{ isset($field['oninput']) ? $field['oninput'] : '' }}
                                            {{ isset($field['minlength']) && $field['minlength'] != null ? 'minlength=' . $field['minlength'] : '' }}
                                            {{ isset($field['maxlength']) && $field['maxlength'] != null ? 'maxlength=' . $field['maxlength'] : '' }}
                                            {{ $field['required'] ? 'required' : '' }}
                                            {{ isset($field['value']) ? $field['value'] : '' }}
                                            {{ isset($field['readonly']) && $field['readonly'] == true ? 'readonly' : '' }}>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
            <div class="col-md-12 col-sm-12">
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger modal-close">@lang('translation.Close')</button>
                    <button type="submit" class="btn btn-primary form-button">@lang('translation.Save')</button>
                </div>
            </div>
        </div>
    </form>
</div>
