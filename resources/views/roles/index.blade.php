@extends('layouts.master')

@section('title')
    @lang('translation.ManageUsers')
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
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> @lang('translation.ManageUsers') /</span>
            @lang('translation.Roles')</h5>
        <!-- Responsive Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between pb-0">
                <h5 class="card-title">
                    @lang('translation.Roles')
                </h5>
                <a href="{{ route('user-managements.roles.create') }}"class="btn btn-outline-primary mb-2"> <i
                        class="las la-plus"></i>
                    @lang('translation.Add')
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table id="data_table" class="table table-striped table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('translation.RoleName')</th>
                                <th>@lang('translation.Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $result)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $result->name }}</td>
                                    <td>
                                        @if ($result->name != 'Admin')
                                            <a href="{{ route('user-managements.roles.edit', $result->id) }}"
                                                class="btn btn-outline-warning btn-sm" title="Edit">
                                                <i class="bx bx-pencil"></i>
                                            </a>
                                            {{-- <a href="javascript:void(0)"
                                                data-url="{{ route('user-managements.roles.destroy', $result->id) }}"
                                                class="btn btn-outline-danger btn-sm delete_record" title="Delete">
                                                <i class="bx bx-trash"></i>
                                            </a> --}}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--/ Responsive Table -->
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
            $("#data_table").DataTable({
                // "order": [
                //     [0, "desc"]
                // ],
                "columnDefs": [{
                    "targets": 'notexport',
                    "orderable": false,
                }]
            });
        });
    </script>
@endsection
