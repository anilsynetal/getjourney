@extends('layouts.master')

@section('title')
    {{ $common_data['module'] }}
@endsection
@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/datatables.net-buttons/css/buttons.dataTables.min.css') }}">
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- datepicker css -->
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.css') }}">
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> {{ $common_data['module'] }} /</span>
            {{ $common_data['active_page'] }}</h5>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between pb-0">
                        <h5 class="card-title"> {{ $common_data['module'] }}</h5>
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
                                <a href="{{ $common_data['create_route'] }}"class="btn btn-outline-primary"> <i
                                        class="las la-plus"></i>
                                    @lang('translation.Add')
                                </a>
                            @endif
                        @endif
                    </div>
                    <div class="card-body">
                        {{-- //Add Filter To Get Deleted Records --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-inline float-sm-end">
                                    <div class="form-group">
                                        <label for="status_filter"
                                            class="col-form-label text-muted me-2">@lang('translation.FilterByStatus')</label>
                                        <select class="form-select" id="status_filter">
                                            <option value="all">@lang('translation.Active')/@lang('translation.Inactive')
                                            </option>
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
    <!-- end card body -->
    <div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

            </div>
        </div>
    </div>
    <div class="modal fade" id="commonModalLarge" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

            </div>
        </div>
    </div>
@endsection
@section('script')
    <!-- Required datatable js -->
    <script src="{{ URL::asset('assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ URL::asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ URL::asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <!-- Sweet alert init js-->
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- datepicker js -->
    <script src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script>
        function initializeTableOnPage(tableId, ajaxUrl, columns, buttons = []) {
            if ($(tableId).length) {
                let leftColumnsCount = columns.filter(col => col.freeze).length;

                // Initialize DataTable
                let table = $(tableId).DataTable({
                    processing: true,
                    serverSide: true,
                    scrollY: "75vh",
                    scrollX: true,
                    aLengthMenu: [
                        [-1, 10, 25, 50, 100, 200],
                        ["All", 10, 25, 50, 100, 200]
                    ],
                    pageLength: 10,
                    paging: true,
                    scrollCollapse: true,
                    ajax: {
                        url: ajaxUrl,
                        type: 'POST',
                        data: function(d) {
                            var api = new $.fn.dataTable.Api(tableId);
                            d._token = $('meta[name="csrf-token"]').attr('content');
                            if ($("#status_filter").length) {
                                d.status_filter = $("#status_filter").val();
                            }
                            api.columns().every(function(i) {
                                if (columns[i].is_select_search && $('select', $(api.column(i)
                                        .footer())).val()) {
                                    d[api.column(i).dataSrc() + '_search'] = $('select', $(api.column(i)
                                        .footer())).val();
                                }
                            });
                        }
                    },
                    dom: '<"top"lfB>rt<"bottom"ip><"clear">',
                    buttons: buttons,
                    aaSorting: [],
                    columns: columns,
                    fixedHeader: true,
                    fixedColumns: {
                        leftColumns: leftColumnsCount
                    },
                    createdRow: function(row, data, dataIndex) {
                        columns.forEach((col, index) => {
                            if (col.wrap) {
                                $('td:eq(' + index + ')', row).addClass('dt-wrap');
                            }
                        });
                        // Add a unique data-id attribute for drag-and-drop
                        $(row).attr('data-id', data.id);
                    },
                    headerCallback: function(thead, data, start, end, display) {
                        $(thead).find('th').each(function(index) {
                            $(this).html(columns[index].title);
                        });
                    },
                    initComplete: function() {
                        this.api().columns().every(function() {
                            var column = this;
                            var columnOptions = columns[this.index()];
                            if (columnOptions.searchable !== false) {
                                if (columnOptions.is_input_search) {
                                    $('<input type="text" class="form-control" placeholder="Search by ' +
                                            columnOptions.name + '" />')
                                        .appendTo($(column.footer()).empty())
                                        .on('keyup', function() {
                                            var val = $.fn.dataTable.util.escapeRegex(
                                                $(this).val()
                                            );
                                            column.search(val ? val : '', true, false).draw();
                                        });
                                } else {
                                    var select = $(
                                            '<select class="form-select"><option value="">All</option></select>'
                                        )
                                        .appendTo($(column.footer()).empty())
                                        .on('change', function() {
                                            var val = $.fn.dataTable.util.escapeRegex(
                                                $(this).val()
                                            );
                                            column.search(val ? '^' + val + '$' : '', true, false)
                                                .draw();
                                        });

                                    column.data().unique().sort().each(function(d, j) {
                                        select.append('<option value="' + d + '">' + d +
                                            '</option>')
                                    });
                                }
                            }
                        });

                        // Enable drag-and-drop
                        enableDragAndDrop(tableId);
                    }
                });

                return table;
            }
            return null;
        }

        function enableDragAndDrop(tableId) {
            $(tableId + ' tbody').sortable({
                items: 'tr',
                cursor: 'move',
                update: function(event, ui) {
                    let order = $(this).sortable('toArray', {
                        attribute: 'data-id'
                    });
                    $.ajax({
                        url: '{{ route('settings.sub-menus.update-order') }}',
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            order: order
                        },
                        success: function(response) {
                            data_table.ajax.reload();
                        },
                        error: function(xhr) {
                            console.error("Error updating order:", xhr.responseText);
                        }
                    });
                }
            }).disableSelection();
        }

        @if (Session::has('message'))
            notify("{{ session('status') }}", "{{ session('message') }}");
        @endif

        let data_table;
        $(document).ready(function() {
            data_table = initializeTableOnPage('#data_table', '{{ $ajax_url }}',
                <?= json_encode($common_data['js_columns']) ?>, <?= json_encode($common_data['buttons']) ?>);
        });
    </script>
@endsection
