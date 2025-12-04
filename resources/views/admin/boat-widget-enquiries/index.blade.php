@extends('layouts.master')

@section('title', 'Boat Widget Enquiries')

@section('css')
    <!-- DataTables CSS -->
    <link href="{{ URL::asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/datatables.net-buttons/css/buttons.dataTables.min.css') }}">
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h1 class="h3 mb-4 text-gray-800">
            <i class="fas fa-ship"></i> Boat Widget Enquiries
        </h1>


        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    {{-- <div class="col-md-4">
                        <label>Search</label>
                        <input type="text" id="filterSearch" class="form-control" placeholder="Search by name, email...">
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button class="btn btn-primary w-100" onclick="loadEnquiries()">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div> --}}
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <a href="{{ route('boat-widget-enquiries.export') }}" class="btn btn-success w-100">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enquiries Table -->
        <div class="card">
            <div class="card-body">
                <table class="table table-striped table-bordered dt-responsive nowrap w-100" id="enquiriesTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Visa Category</th>
                            <th>Message Preview</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enquiries as $enquiry)
                            <tr>
                                <td>#{{ $enquiry->id }}</td>
                                <td>{{ $enquiry->name ?? 'N/A' }}</td>
                                <td>{{ $enquiry->email ?? 'N/A' }}</td>
                                <td>{{ $enquiry->phone ?? 'N/A' }}</td>
                                <td>
                                    @if ($enquiry->visa_category)
                                        <span class="badge bg-info">{{ $enquiry->visa_category }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ Str::limit($enquiry->message, 50) }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $enquiry->status_badge }}">{{ $enquiry->status_label }}</span>
                                </td>
                                <td>{{ $enquiry->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('boat-widget-enquiries.show', $enquiry->id) }}"
                                            class="btn btn-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    No enquiries found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="enquiryId">
                    <div class="mb-3">
                        <label class="form-label">New Status</label>
                        <select id="newStatus" class="form-control">
                            <option value="new">New</option>
                            <option value="read">Read</option>
                            <option value="responded">Responded</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveStatus()">Update Status</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editStatus(enquiryId) {
            $('#enquiryId').val(enquiryId);
            new bootstrap.Modal(document.getElementById('statusModal')).show();
        }

        function saveStatus() {
            const enquiryId = $('#enquiryId').val();
            const status = $('#newStatus').val();

            $.ajax({
                url: `/admin/boat-widget-enquiries/${enquiryId}/change-status`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: {
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        alert('Status updated successfully');
                        location.reload();
                    }
                },
                error: function() {
                    alert('Error updating status');
                }
            });

            bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
        }

        function loadEnquiries() {
            // Reload page with filters
            const status = $('#filterStatus').val();
            const search = $('#filterSearch').val();
            const url = new URL(window.location);
            if (status) url.searchParams.set('status', status);
            if (search) url.searchParams.set('search', search);
            window.location = url.toString();
        }
    </script>
@endsection

@section('script')
    <!-- DataTables JS -->
    <script src="{{ URL::asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Only initialize DataTable if there are records
            var tableBody = $('#enquiriesTable tbody tr');
            if (tableBody.length > 0 && !tableBody.first().find('td').eq(0).text().includes('No enquiries')) {
                $('#enquiriesTable').DataTable({
                    responsive: true,
                    autoWidth: false,
                    pageLength: 10,
                    lengthChange: true,
                    searching: true,
                    ordering: true,
                    info: true,
                    paging: true,
                    dom: 'lBfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    columnDefs: [{
                        targets: -1,
                        orderable: false,
                        searchable: false
                    }]
                });
            }
        });
    </script>
@endsection
