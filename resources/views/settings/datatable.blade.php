<div class="card-body">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between pb-0">
                    <h4 class="card-title"> {{ $common_data['module'] }}</h4>
                    @if (isset($common_data['is_back_button']) && $common_data['is_back_button'])
                        <a href="{{ $common_data['back_route'] }}" class="btn btn-outline-dark ms-auto" title="Back">
                            @lang('translation.Back')
                        </a> &nbsp;
                    @endif
                    @if ($common_data['is_add'])
                        @if ($common_data['is_modal'])
                            @php
                                $modal_class = $common_data['is_modal_large']
                                    ? 'loadRecordModalLarge'
                                    : 'loadRecordModal';
                            @endphp
                            <button type="button" class="btn btn-primary {{ $modal_class }}"
                                data-url="{{ $common_data['create_route'] }}" title="{{ $common_data['title'] }}"> <i
                                    class="las la-plus"></i> @lang('translation.Add')</button>
                        @else
                            @if (request('tab') == 'database_backup')
                                @if (Utility::getsettings('storage_type') == 'google')
                                    <a href="{{ $common_data['create_route'] }}?drive=true"
                                        class="btn btn-outline-primary"
                                        onclick="return confirm('Are you sure you want to create the backup?');"> <i
                                            class="las la-plus"></i>
                                        @lang('translation.CreateBackupOnGoogleDrive')
                                    </a> &nbsp;
                                @endif
                                <a href="{{ $common_data['create_route'] }}" class="btn btn-outline-primary"
                                    onclick="return confirm('Are you sure you want to create the backup?');"> <i
                                        class="las la-plus"></i>
                                    @lang('translation.CreateBackup')
                                </a>
                            @else
                                <a href="{{ $common_data['create_route'] }}"class="btn btn-outline-primary"> <i
                                        class="las la-plus"></i>
                                    @lang('translation.Add')
                                </a>
                            @endif
                        @endif
                    @endif
                </div>
                <div class="card-body">
                    {{-- //Add Filter To Get Deleted Records --}}
                    @if (request('tab') != 'notification_setting' &&
                            request('tab') != 'database_backup' &&
                            request('tab') != 'email_template')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-inline float-sm-end">
                                    <div class="form-group">
                                        <label for="status_filter"
                                            class="col-form-label text-muted me-2">@lang('translation.FilterByStatus')</label>
                                        <select class="form-select" id="status_filter">
                                            <option value="all">@lang('translation.Active')/@lang('translation.Inactive')</option>
                                            <option value="1">@lang('translation.Active')</option>
                                            <option value="0">@lang('translation.Inactive')</option>
                                            @if (!isset($common_data['is_deleted']) || $common_data['is_deleted'] == true)
                                                <option value="2">@lang('translation.Deleted')</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <table id="data_table" class="table table-striped table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                @foreach ($common_data['columns'] as $column)
                                    <th class="{{ in_array($column, ['Status', 'Action']) ? 'notexport' : '' }}">
                                        {{ $column }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
</div>
