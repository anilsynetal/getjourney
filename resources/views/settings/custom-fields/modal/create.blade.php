<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">{{ $page_title }}</h5>
</div>
<div class="modal-body">
    <form name="master_form" method="post" action="{{ $route_action }}" novalidate>
        @csrf
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group mb-3">
                    <label for="main_menu_id" class="form-label">
                        @lang('translation.MainModuleName')
                        <span class="text-danger">*</span> :</label>
                    <select class="form-control" id="main_menu_id" name="main_menu_id"
                        data-url="{{ route('settings.sub-menus.get-sub-menu-by-main-menu') }}"
                        data-append_id="sub_menu_id">
                        <option value="">@lang('translation.please_select')</option>
                        @foreach ($main_menus as $main_menu)
                            <option value="{{ $main_menu->id }}">{{ $main_menu->menu_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="sub_menu_id" class="form-label"> @lang('translation.ModuleName')<span
                            class="text-danger">*</span></label>
                    <select class="form-control " name="sub_menu_id" id="sub_menu_id">
                        <option value="">@lang('translation.please_select')</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="label" class="form-label">@lang('translation.FieldLabel') <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="label" name="label"
                        placeholder="@lang('translation.FieldLabel')" autocomplete="off">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="field_placeholder" class="form-label">@lang('translation.Placeholder') <span
                            class="text-danger"></span></label>
                    <input type="text" class="form-control" id="field_placeholder" name="field_placeholder"
                        placeholder="@lang('translation.Placeholder')" autocomplete="off">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="field_type" class="form-label">@lang('translation.FieldType') <span
                            class="text-danger">*</span></label>
                    <select class="form-control" id="field_type" name="field_type">
                        <option value="">@lang('translation.please_select')</option>
                        @foreach ($field_types as $key => $field_type)
                            <option value="{{ $key }}">{{ $field_type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 field_length" style="display: none;">
                <div class="form-group mb-3">
                    <label for="minimum_length" class="form-label">@lang('translation.MinimumLength') <span
                            class="text-danger"></span></label>
                    <input type="text" class="form-control" id="minimum_length" name="minimum_length"
                        placeholder="@lang('translation.MinimumLength')" autocomplete="off"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');">
                </div>
            </div>
            <div class="col-md-3 field_length" style="display: none;">
                <div class="form-group mb-3">
                    <label for="maximum_length" class="form-label">@lang('translation.MaximumLength') <span
                            class="text-danger"></span></label>
                    <input type="text" class="form-control" id="maximum_length" name="maximum_length"
                        placeholder="@lang('translation.MaximumLength')" autocomplete="off"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');">
                </div>
            </div>
        </div>
        <div class="row" id="field_values" style="display: none;">
            <div class="col-md-12 mb-3">
                <label for="field_options" class="form-label">@lang('translation.FieldValues') <span
                        class="text-danger">*</span></label>
                <input type="hidden" name="field_values">
                <div class="table-responsive">
                    <table class="table table-bordered text-nwrap mb-0" id="itemsTable">
                        <thead>
                            <tr>
                                <th class="font-weight-bold">S.No</th>
                                <th class="font-weight-bold">Values</th>
                                <th class="font-weight-bold">Remove</th>
                            </tr>
                        </thead>
                        <tbody class="text-dark">
                            <tr id="add-more-tr">
                                <td colspan="3"><button type="button" class="btn btn-primary btn-sm"
                                        id="addMore">Add
                                        More</button></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="is_required" class="form-label">@lang('translation.IsRequired')</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_required" name="is_required"
                            value="1">
                        <label class="form-check-label" for="is_required">
                            @lang('translation.Yes')
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12">
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">@lang('translation.Close')</button>
                    <button type="submit" class="btn btn-primary form-button">@lang('translation.Save')</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    //Dynamic Inputs
    let tableBody = document.querySelector('#itemsTable tbody');
    let addMoreButton = document.querySelector('#addMore');

    function updateSerialNumbers() {
        let rows = tableBody.querySelectorAll('.item-row');
        for (let i = 0; i < rows.length; i++) {
            rows[i].children[0].textContent = i + 1;
        }
    }

    if ($("#add-more-tr").length) {
        addMoreButton.addEventListener('click', function() {
            let newRow = document.createElement('tr');
            newRow.classList.add('item-row');
            newRow.innerHTML =
                `
    <td></td>
    <td><input required type="text" class="form-control"
                                                            name="field_options[]" placeholder="Value"></td>
`;
            newRow.innerHTML += `
    <td><a href="javascript:void(0);" class="btn btn-danger btn-sm remove-item">Remove</a></td>
`;
            let addMoreRow = document.querySelector('#add-more-tr');
            tableBody.insertBefore(newRow, addMoreRow);
            newRow.querySelector('.remove-item').addEventListener('click', function() {
                deleteAlert(function() {
                    newRow.remove();
                    updateSerialNumbers();
                });
            });
            updateSerialNumbers();
        });

        //Remove Item
        let removeButtons = document.querySelectorAll('.remove-item');
        removeButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                deleteAlert(function() {
                    let row = button.closest('.item-row');
                    row.remove();
                    updateSerialNumbers();
                });
            });
        });
    }
</script>
