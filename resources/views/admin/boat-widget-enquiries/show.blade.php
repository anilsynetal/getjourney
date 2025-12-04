@extends('layouts.master')

@section('title', 'Boat Widget Enquiry Details')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="h3 text-gray-800">
                    <i class="fas fa-ship"></i> Enquiry #{{ $enquiry->id }}
                </h1>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('boat-widget-enquiries.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-md-12">
                <!-- Enquiry Details -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Enquiry Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Name:</strong>
                                <p>{{ $enquiry->name ?? 'Not provided' }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Email:</strong>
                                <p>
                                    @if ($enquiry->email)
                                        <a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a>
                                    @else
                                        Not provided
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Phone:</strong>
                                <p>
                                    @if ($enquiry->phone)
                                        <a href="tel:{{ $enquiry->phone }}">{{ $enquiry->phone }}</a>
                                    @else
                                        Not provided
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <strong>Visa Category:</strong>
                                <p>
                                    @if ($enquiry->visa_category)
                                        <span class="badge bg-info">{{ $enquiry->visa_category }}</span>
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong>Message:</strong>
                            <div class="alert alert-light border mt-2">
                                <p class="mb-0">{{ $enquiry->message }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <strong>Submitted:</strong>
                                <p>{{ $enquiry->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>IP Address:</strong>
                                <p>{{ $enquiry->user_ip ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Response Section -->
                @if ($enquiry->status === 'responded')
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Your Response</h5>
                        </div>
                        <div class="card-body">
                            <p>{{ $enquiry->admin_response }}</p>
                            <small class="text-muted">
                                Responded: {{ $enquiry->responded_at->format('d M Y, h:i A') }}
                            </small>
                        </div>
                    </div>
                @endif


            </div>
        </div>
    </div>

    <script>
        // Change Status
        $('#statusForm').on('submit', function(e) {
            e.preventDefault();
            const status = $('#statusSelect').val();

            $.ajax({
                url: `/admin/boat-widget-enquiries/{{ $enquiry->id }}/change-status`,
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
        });

        // Assign
        $('#assignForm').on('submit', function(e) {
            e.preventDefault();
            const assignedTo = $('#assignSelect').val();

            $.ajax({
                url: `/admin/boat-widget-enquiries/{{ $enquiry->id }}/assign-user`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: {
                    assigned_to: assignedTo || null
                },
                success: function(response) {
                    if (response.success) {
                        alert('Assigned successfully');
                        location.reload();
                    }
                },
                error: function() {
                    alert('Error assigning');
                }
            });
        });

        // Add Note
        $('#addNoteForm').on('submit', function(e) {
            e.preventDefault();
            const note = $('#noteInput').val().trim();

            if (!note) {
                alert('Please enter a note');
                return;
            }

            $.ajax({
                url: `/admin/boat-widget-enquiries/{{ $enquiry->id }}/add-notes`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: {
                    note: note
                },
                success: function(response) {
                    if (response.success) {
                        alert('Note added successfully');
                        location.reload();
                    }
                },
                error: function() {
                    alert('Error adding note');
                }
            });
        });

        // Send Response
        $('#responseForm').on('submit', function(e) {
            e.preventDefault();
            const response = $('#responseInput').val().trim();

            if (!response) {
                alert('Please enter a response');
                return;
            }

            $.ajax({
                url: `/admin/boat-widget-enquiries/{{ $enquiry->id }}/respond`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: {
                    response: response
                },
                success: function(response) {
                    if (response.success) {
                        alert('Response sent successfully');
                        location.reload();
                    }
                },
                error: function() {
                    alert('Error sending response');
                }
            });
        });
    </script>
@endsection
