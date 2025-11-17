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
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ $common_data['module'] }} /</span>
            {{ $common_data['active_page'] }}</h4>
        <!-- Responsive Table -->
        <div class="card">
            <h5 class="card-header">{{ $common_data['module'] }}</h5>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table id="data_table" class="table table-striped table-bordered dt-responsive  w-100">
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
        </div>
        <!--/ Responsive Table -->
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
