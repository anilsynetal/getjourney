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
    @component('components.breadcrumb')
        @slot('li_1')
            {{ $common_data['module'] }}
        @endslot
        @slot('title')
            {{ $common_data['active_page'] }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
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
                            <a href="{{ $common_data['create_route'] }}"class="btn btn-outline-primary"> <i
                                    class="las la-plus"></i>
                                @lang('translation.Add')
                            </a>
                        @endif
                    @endif
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">

                                <div class="card-body">
                                    {{-- //Add Filter To Get Deleted Records --}}


                                    <table id="data_table"
                                        class="table table-striped table-bordered dt-responsive  nowrap w-100">
                                        <thead>
                                            <tr>
                                                @foreach ($common_data['columns'] as $column)
                                                    <th
                                                        class="{{ in_array($column, ['Status', 'Action']) ? 'notexport' : '' }}">
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
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
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
        let data_table;
        $(document).ready(function() {
            data_table = initializeTable('#data_table', '{{ $ajax_url }}',
                <?= json_encode($common_data['js_columns']) ?>, <?= json_encode($common_data['buttons']) ?>);
            $('#status_filter').change(function() {
                data_table.ajax.reload();
            });
        });
    </script>
@endsection
