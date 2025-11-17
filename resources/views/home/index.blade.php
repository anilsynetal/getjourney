@extends('layouts.master')

@section('title')
    @lang('translation.Dashboard')
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/datatables.net-buttons/css/buttons.dataTables.min.css') }}">
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <!-- Content -->
    <div class="flex-grow-1 container-p-y container-fluid">
        <div class="row g-6 mb-6">
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('testimonials.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span class="text-heading">Total Testimonial</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">{{ $data['total_testimonials'] }}</h4>
                                    </div>
                                </div>
                                <div class="avatar">
                                    <span class="avatar-initial rounded bg-label-primary">
                                        <i class="icon-base bx bx-group icon-lg"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('clients.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span class="text-heading">Total Teams</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">{{ $data['total_teams'] }}</h4>
                                    </div>
                                </div>
                                <div class="avatar">
                                    <span class="avatar-initial rounded bg-label-success">
                                        <i class="icon-base bx bx-user-check icon-lg"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('news-letters.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span class="text-heading">Total Subscribers</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">{{ $data['total_subscribers'] }}</h4>
                                    </div>
                                </div>
                                <div class="avatar">
                                    <span class="avatar-initial rounded bg-label-info">
                                        <i class="icon-base bx bx-envelope icon-lg"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('services.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span class="text-heading">Total Services</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">{{ $data['total_services'] }}</h4>
                                    </div>
                                </div>
                                <div class="avatar">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="icon-base bx bx-cog icon-lg"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            {{-- //Total Service Enquiries --}}

            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('enquiries.services') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span class="text-heading">Total Service Enquiries</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">{{ $data['total_service_enquiries'] }}</h4>
                                    </div>
                                </div>
                                <div class="avatar">
                                    <span class="avatar-initial rounded bg-label-warning">
                                        <i class="icon-base bx bx-envelope icon-lg"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('tour-packages.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span class="text-heading">Total Tours</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">{{ $data['total_tours'] }}</h4>
                                    </div>
                                </div>
                                <div class="avatar">
                                    <span class="avatar-initial rounded bg-label-dark">
                                        <i class="icon-base bx bx-map icon-lg"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            {{-- //Total Tour Enquiries --}}

            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('enquiries.tours') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span class="text-heading">Total Tour Enquiries</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">{{ $data['total_tour_enquiries'] }}</h4>
                                    </div>
                                </div>
                                <div class="avatar">
                                    <span class="avatar-initial rounded bg-label-info">
                                        <i class="icon-base bx bx-envelope icon-lg"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>



            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('manage-blogs.blogs.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span class="text-heading">Total Blogs</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">{{ $data['total_blogs'] }}</h4>
                                    </div>
                                </div>
                                <div class="avatar">
                                    <span class="avatar-initial rounded bg-label-danger">
                                        <i class="icon-base bx bx-news icon-lg"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <h5 class="card-title"> @lang('translation.Enquiries')</h5>
                        <a href="{{ route('enquiries.index') }}" class="btn btn-primary ms-auto float-end">
                            @lang('translation.ViewAll')</a>
                    </div>
                    <table id="data_table" class="table table-striped table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th> @lang('translation.Name')</th>
                                <th> @lang('translation.Email')</th>
                                <th> @lang('translation.Mobile')</th>
                                <th> @lang('translation.Message')</th>
                                <th> @lang('translation.Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($data['enquiries']->count())
                                @foreach ($data['enquiries'] as $key => $enquiry)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $enquiry->name }}</td>
                                        <td>{{ $enquiry->email }}</td>
                                        <td>{{ $enquiry->mobile }}</td>
                                        <td>{{ $enquiry->message }}</td>

                                        <td>
                                            <button type="button"
                                                class="btn btn-outline-danger btn-sm mb-1 delete_record"
                                                data-url="{{ route('enquiries.destroy', $enquiry->id) }}"
                                                title="{{ __('translation.Delete') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center">No data available in table</td>
                                </tr>
                            @endif
                        </tbody>

                    </table>
                </div>
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
@endsection
