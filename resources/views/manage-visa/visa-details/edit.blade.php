@extends('layouts.master')

@section('title')
    {{ $page_title }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">@lang('translation.ManageVisa') /</span>
            <a href="{{ route('manage-visa.visa-details.index') }}" class="text-muted fw-light">@lang('translation.VisaDetailList')</a> /
            @lang('translation.Edit') @lang('translation.VisaDetail')
        </h5>

        <div class="row">
            <!-- Main Form -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">@lang('translation.Edit') @lang('translation.VisaDetail')</h5>
                    </div>
                    <div class="card-body">
                        <form name="master_form" method="post" action="{{ $route_action }}" novalidate>
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group mb-3">
                                        <label for="country_id" class="form-label">@lang('translation.Country') <span
                                                class="text-danger">*</span> :</label>
                                        <select name="country_id" id="country_id" class="form-control" required>
                                            <option value="">@lang('translation.please_select')</option>
                                            @foreach ($countries as $key => $country)
                                                <option value="{{ $key }}"
                                                    {{ $result->country_id == $key ? 'selected' : '' }}>
                                                    {{ $country }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group mb-3">
                                        <label for="city" class="form-label">@lang('translation.City') <span
                                                class="text-danger">*</span> :</label>
                                        <input type="text" class="form-control" id="city" name="city"
                                            list="city-list" placeholder="@lang('translation.EnterCity')" value="{{ $result->city }}"
                                            required>
                                        <datalist id="city-list">
                                            @foreach ($cities as $city)
                                                <option value="{{ $city }}">
                                            @endforeach
                                        </datalist>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group mb-3">
                                        <label for="visa_category_id" class="form-label">@lang('translation.VisaCategory') <span
                                                class="text-danger">*</span> :</label>
                                        <select name="visa_category_id" id="visa_category_id" class="form-control" required>
                                            <option value="">@lang('translation.please_select')</option>
                                            @foreach ($visa_categories as $key => $category)
                                                <option value="{{ $key }}"
                                                    {{ $result->visa_category_id == $key ? 'selected' : '' }}>
                                                    {{ $category }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group mb-3">
                                        <label for="logistic_charges" class="form-label">@lang('translation.LogisticCharges') :</label>
                                        <input type="text" id="logistic_charges" name="logistic_charges"
                                            class="form-control" placeholder="@lang('translation.EnterLogisticCharges')"
                                            value="{{ $result->logistic_charges }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group mb-3">
                                        <label for="visa_fees" class="form-label">@lang('translation.VisaFees') :</label>
                                        <textarea id="visa_fees" name="visa_fees" class="form-control" placeholder="@lang('translation.EnterVisaFees')" rows="4">{{ $result->visa_fees }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group mb-3">
                                        <label for="processing_time" class="form-label">@lang('translation.ProcessingTime') :</label>
                                        <input type="text" class="form-control" id="processing_time"
                                            name="processing_time" placeholder="@lang('translation.EnterProcessingTime')"
                                            value="{{ $result->processing_time }}" maxlength="255">
                                    </div>
                                </div>
                            </div>

                            {{-- Documents Section in tabular form --}}
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5 class="mb-3">@lang('translation.Documents')</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>@lang('translation.Title')</th>
                                                    <th>@lang('translation.Description')</th>
                                                    <th>@lang('translation.Link')</th>
                                                    <th>@lang('translation.Actions')</th>
                                                </tr>
                                            </thead>
                                            <tbody id="documents_table_body">
                                                @if ($result->documents->count() > 0)
                                                    @foreach ($result->documents as $index => $doc)
                                                        <tr data-row="{{ $doc->id }}">
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                    name="document_title[]" placeholder="@lang('translation.EnterDocumentTitle')"
                                                                    value="{{ $doc->title }}" maxlength="255">
                                                            </td>
                                                            <td>
                                                                <textarea class="form-control" name="document_description[]" placeholder="@lang('translation.EnterDocumentDescription')" maxlength="255"
                                                                    rows="3">{{ $doc->description }}</textarea>
                                                            </td>
                                                            <td>
                                                                <input type="url" class="form-control"
                                                                    name="document_link[]"
                                                                    placeholder="@lang('translation.EnterDocumentLink')"
                                                                    value="{{ $doc->link }}" maxlength="255">
                                                            </td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm remove-document-row">@lang('translation.Remove')</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr data-row="1">
                                                        <td>1</td>
                                                        <td>
                                                            <input type="text" class="form-control"
                                                                name="document_title[]" placeholder="@lang('translation.EnterDocumentTitle')"
                                                                maxlength="255">
                                                        </td>
                                                        <td>
                                                            <textarea class="form-control" name="document_description[]" placeholder="@lang('translation.EnterDocumentDescription')" maxlength="255"
                                                                rows="3"></textarea>
                                                        </td>
                                                        <td>
                                                            <input type="url" class="form-control"
                                                                name="document_link[]" placeholder="@lang('translation.EnterDocumentLink')"
                                                                maxlength="255">
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm remove-document-row">@lang('translation.Remove')</button>
                                                        </td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td colspan="5" class="text-center">
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                            id="add_document_row">@lang('translation.AddMore')</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary form-button">@lang('translation.Update')</button>
                                    <a href="{{ route('manage-visa.visa-details.index') }}"
                                        class="btn btn-secondary">@lang('translation.Cancel')</a>
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
        // Add Document Row
        let documentRow = {{ $result->documents->count() > 0 ? $result->documents->count() : 1 }};

        $('#add_document_row').on('click', function() {
            documentRow++;
            let newRow = `<tr data-row="${documentRow}">
                            <td>${documentRow}</td>
                            <td><input type="text" class="form-control" name="document_title[]" placeholder="@lang('translation.EnterDocumentTitle')" maxlength="255"></td>
                            <td><textarea class="form-control" name="document_description[]" placeholder="@lang('translation.EnterDocumentDescription')" maxlength="255" rows="3"></textarea></td>
                            <td><input type="url" class="form-control" name="document_link[]" placeholder="@lang('translation.EnterDocumentLink')" maxlength="255"></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-document-row">@lang('translation.Remove')</button>
                            </td>
                        </tr>`;
            $('#documents_table_body tr:last').before(newRow);
        });

        // Remove Document Row
        $(document).on('click', '.remove-document-row', function() {
            if ($('#documents_table_body tr').length > 2) { // Ensure at least one row remains
                $(this).closest('tr').remove();
            } else {
                alert('@lang('translation.AtLeastOneDocumentRowRequired')');
            }
        });
    </script>
@endsection
