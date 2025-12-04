@extends('website.layout.app')
@section('title', config('app.name') . ' – Visa Information')
@section('content')
    <main>

        <!-- Header Start -->
        <div class="container-fluid bg-breadcrumb">
            <div class="container text-center py-5" style="max-width: 900px;">
                <h3 class="text-white display-3 mb-4 wow fadeInDown" data-wow-delay="0.1s">Visa Information</h3>
                <ol class="breadcrumb justify-content-center text-white mb-0 wow fadeInDown" data-wow-delay="0.3s">
                    <li class="breadcrumb-item">
                        <a href="{{ route('website.index') }}" class="text-white">Home</a>
                    </li>
                    <li class="breadcrumb-item active text-secondary">Visa Information</li>
                </ol>
            </div>
        </div>
        <!-- Header End -->


        <!-- Visa Information Start -->
        <div class="container-fluid bg-light overflow-hidden py-5 vinfo">
            <div class="container py-5">

                <!-- Country Search Dropdown -->
                <div class="row mb-4">
                    <div class="col-lg-10 mx-auto">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-body p-4 p-md-5">
                                <div class="row align-items-center g-3">
                                    <div class="col-md-5">
                                        <h5 class="mb-1" style="color:#003366; font-weight:700;">Search Visa by Country
                                        </h5>
                                        <p class="mb-0 text-muted" style="font-size:0.95rem;">Select a country to view
                                            requirements, fees and forms.</p>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-white border-end-0"><i
                                                    class="fas fa-search text-muted"></i></span>
                                            <select class="form-control selectpicker border-start-0" data-live-search="true"
                                                data-live-search-placeholder="Search country..." data-size="7"
                                                data-style="btn-lg bg-white border-start-0" data-container="body"
                                                name="country_id" id="countrySelect" title="Search country...">

                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}">
                                                        {{ $country->country }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Initial Instructions (shown when no country selected) -->
                <div id="initialInstructions" style="display: {{ !request()->has('country_id') ? 'block' : 'none' }};">
                    <div class="row">
                        <div class="col">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden text-center">
                                <div class="card-body p-4">
                                    <h5 class="card-title">Apply Visa Online In 3 Simple Steps</h5>
                                    <p class="card-text">We Take Care Of Your Online Visa Application</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 mt-2">
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 text-center">
                                <div class="card-body p-4">
                                    <img src="{{ asset('website/assets/img/searchcountry.png') }}" alt="Step 1"
                                        class="mb-3">
                                    <h6 class="card-title">Search the Country</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 text-center">
                                <div class="card-body p-4">
                                    <img src="{{ asset('website/assets/img/readvisainfo.png') }}" alt="Step 2"
                                        class="mb-3">
                                    <h6 class="card-title">Read Visa Information</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 text-center">
                                <div class="card-body p-4">
                                    <img src="{{ asset('website/assets/img/dwndvisa.png') }}" alt="Step 3" class="mb-3">
                                    <h6 class="card-title">Download Visa Forms</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visa Content Area (shown when country is selected) -->
                <div id="visaContentArea" class="row g-4"
                    style="display: {{ request()->has('country_id') ? 'flex' : 'none' }};">
                    <!-- LEFT MENU -->
                    <div class="col-lg-3" id="leftMenuContainer">
                        @if (request()->has('country_id'))
                            @include('website.partials.visa-left-menu', [
                                'country_id' => request()->country_id,
                                'tab' => request()->tab ?? 'factFinder',
                            ])
                        @endif
                    </div>

                    <!-- RIGHT CONTENT -->
                    <div class="col-lg-9 ps-lg-4" id="tabContentContainer">
                        @if (request()->has('country_id'))
                            @include('website.partials.visa-tab-content', [
                                'visa_information' => $visa_information,
                                'visa_details' => $visa_details,
                                'visa_categories' => $visa_categories,
                                'visa_forms' => $visa_forms,
                                'diplomatic_representations' => $diplomatic_representations,
                                'international_help_addresses' => $international_help_addresses,
                                'logistic_partners' => $logistic_partners,
                                'contact' => $contact,
                                'tab' => request()->tab ?? 'factFinder',
                            ])
                        @endif
                    </div>
                </div>
            </div>


        </div>
        <!-- Visa Information End -->

    </main>
    <!-- Share Information Modal -->
    <div class="modal fade" id="shareModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" style="color: #003366; font-weight: 700;">SHARE INFORMATION VIA MAIL</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-3">
                    <!-- Loader -->
                    <div id="emailLoader"
                        style="
                        display: none !important;
                        position: absolute;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background: rgba(255, 255, 255, 0.95);
                        z-index: 1000;
                        border-radius: 0.375rem;
                        align-items: center;
                        justify-content: center;
                        flex-direction: column;
                    ">
                        <div class="spinner-border" role="status" style="color: #d32f2f; width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p style="margin-top: 15px; color: #003366; font-weight: 500;">Processing your request...</p>
                    </div>

                    <p style="color: #d32f2f; font-size: 0.9rem; margin-bottom: 20px;">
                        <i class="fab fa-whatsapp" style="color: #25D366; font-size: 1.1rem;"></i>
                        <strong>Click on</strong> <i class="fab fa-whatsapp" style="color: #25D366; font-size: 1.1rem;"></i>
                        <strong>icon to send downloaded checklist pdf via whatsapp.</strong>
                    </p>

                    <form id="shareForm" novalidate>
                        <div class="mb-3">
                            <label id="visaInfoLabel" style="color: #003366; font-weight: 600; font-size: 0.9rem;">
                                Visa Information
                            </label>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="recipientName" class="form-label"
                                    style="color: #333; font-size: 0.9rem;">Recipient Name :</label>
                                <input type="text" class="form-control" id="recipientName" name="recipient_name"
                                    placeholder="Enter your full name" minlength="2" maxlength="100" required
                                    style="background-color: #f0f0f0; border: none;">
                                <div class="invalid-feedback" style="color: #d32f2f; font-size: 0.85rem;">
                                    Please enter a valid name (2-100 characters).
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="recipientEmail" class="form-label"
                                    style="color: #333; font-size: 0.9rem;">Recipient Email Address :</label>
                                <input type="email" class="form-control" id="recipientEmail" name="recipient_email"
                                    placeholder="Enter your email address" maxlength="100" required
                                    style="background-color: #f0f0f0; border: none;">
                                <div class="invalid-feedback" style="color: #d32f2f; font-size: 0.85rem;">
                                    Please enter a valid email address.
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="contactNo" class="form-label" style="color: #333; font-size: 0.9rem;">Contact
                                    No :</label>
                                <div class="input-group">
                                    <input type="tel" class="form-control" id="contactNo" name="contact_number"
                                        placeholder="e.g., (123) 456-7890 or +1 123 456 7890"
                                        pattern="^[0-9\s\-\+\(\)]{10,20}$"
                                        title="Please enter a valid phone number (10-20 characters with digits, spaces, dashes, +, or parentheses)"
                                        minlength="10" maxlength="20" required
                                        style="background-color: #f0f0f0; border: none;">
                                    <button class="btn" type="button" id="whatsappShareBtn"
                                        style="background-color: #f0f0f0; border: none; cursor: pointer;">
                                        <i class="fab fa-whatsapp" style="color: #25D366; font-size: 1.2rem;"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" style="color: #d32f2f; font-size: 0.85rem;">
                                    Please enter a valid phone number (10-20 characters with digits, spaces, dashes, +, or
                                    parentheses).
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="serviceCharges" class="form-label"
                                    style="color: #333; font-size: 0.9rem;">Service Charges (* GST will be
                                    additional):</label>
                                <input type="text" class="form-control" id="serviceCharges" name="service_charges"
                                    placeholder="e.g., ₹5000 or To be confirmed" maxlength="50"
                                    style="background-color: #f0f0f0; border: none;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="additionalInfo" class="form-label"
                                style="color: #333; font-size: 0.9rem;">Additional Info :</label>
                            <textarea class="form-control" id="additionalInfo" name="additional_info" rows="5"
                                placeholder="Enter any additional information (optional)" maxlength="1000"
                                style="background-color: #f0f0f0; border: none;"></textarea>
                            <small style="color: #999; font-size: 0.85rem;">Maximum 1000 characters</small>
                        </div>

                        <div class="row g-2 mt-4">
                            <div class="col-12 d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-outline-danger px-4" data-bs-dismiss="modal"
                                    style="color: #d32f2f; border-color: #d32f2f; font-weight: 600;">
                                    CLOSE
                                </button>
                                <button type="button" id="downloadPdfBtn" class="btn px-4"
                                    style="background-color: #f8f9fa; color: #333; border: 1px solid #ddd; font-weight: 600;">
                                    <i class="fas fa-download" style="margin-right: 6px;"></i> DOWNLOAD PDF
                                </button>
                                <button type="submit" class="btn btn-danger px-4"
                                    style="background-color: #d32f2f; border: none; font-weight: 600;">
                                    SEND EMAIL
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('styles')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/css/bootstrap-select.min.css" />
    <style>
        /* Subtle hover for cards on this page only */
        .vinfo .card.border-0.shadow-sm.rounded-4 {
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .vinfo .card.border-0.shadow-sm.rounded-4:hover {
            transform: translateY(-3px);
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, .08) !important;
        }

        /* Align bootstrap-select inside input-group */
        .vinfo .input-group .bootstrap-select>.dropdown-toggle {
            border-left: 0;
            border-color: #ced4da;
            height: 100%;
        }

        .vinfo .input-group-text+.bootstrap-select>.dropdown-toggle {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .vinfo .bootstrap-select .dropdown-menu {
            z-index: 2200 !important;
            /* above input-group adornments */
            max-height: 320px;
            overflow-y: auto;
        }

        .vinfo .bootstrap-select .bs-searchbox .form-control {
            border-radius: .5rem;
        }

        /* Ensure full width within input-group */
        .vinfo .input-group .bootstrap-select {
            flex: 1 1 auto;
        }

        /* Ensure dropdown overlays input-group icon even when appended to body */
        .bs-container .dropdown-menu {
            z-index: 2200 !important;
        }

        .bootstrap-select .dropdown-menu {
            z-index: 2200 !important;
        }

        /* Make the leading icon non-interactive so clicks reach the select */
        .vinfo .input-group-text {
            pointer-events: none;
        }

        /* Enhanced Visa Category Tabs Styling */
        .visa-category-tabs {
            border-bottom: none !important;
            padding: 0;
        }

        .visa-category-tabs .nav-link {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px solid #dee2e6;
            color: #495057;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 12px 24px;
            border-radius: 12px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: capitalize;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .visa-category-tabs .nav-link:hover {
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
            border-color: #adb5bd;
            color: #212529;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .visa-category-tabs .nav-link.active {
            background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%);
            border-color: #d32f2f;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.4);
            transform: translateY(-2px);
        }

        .visa-category-tabs .nav-link.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
            pointer-events: none;
        }

        .visa-category-tabs .nav-link i {
            font-size: 1rem;
            transition: transform 0.3s ease;
        }

        .visa-category-tabs .nav-link.active i {
            transform: scale(1.1);
        }

        /* Responsive adjustments for tabs */
        @media (max-width: 768px) {
            .visa-category-tabs .nav-link {
                padding: 10px 16px;
                font-size: 0.85rem;
            }

            .visa-category-tabs {
                gap: 8px !important;
            }
        }

        /* Tab content animation */
        .tab-content>.tab-pane {
            animation: fadeIn 0.3s ease-in;
        }

        /* Fix blank space in category tab content */
        .category-tab-content {
            overflow: hidden;
        }

        .category-tab-content>.tab-pane {
            padding: 0;
            margin: 0;
        }

        /* Remove transition delays that cause blank space */
        .category-tab-content>.tab-pane.fade {
            transition: opacity 0.15s linear;
        }

        .category-tab-content>.tab-pane.fade:not(.show) {
            display: none;
        }

        .category-tab-content>.tab-pane.fade.show {
            display: block;
            opacity: 1;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Enhance accordion styling within tabs */
        #visaNotes .accordion-item {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        #visaNotes .accordion-item:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        #visaNotes .accordion-button {
            background-color: #f8f9fa;
            color: #003366;
            font-weight: 600;
            padding: 16px 20px;
            border: none;
            transition: all 0.3s ease;
        }

        #visaNotes .accordion-button:not(.collapsed) {
            background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%);
            color: #ffffff;
            box-shadow: none;
        }

        #visaNotes .accordion-button:focus {
            box-shadow: none;
            border-color: transparent;
        }

        #visaNotes .accordion-button::after {
            transition: transform 0.3s ease;
        }

        #visaNotes .accordion-body {
            padding: 24px;
            background-color: #ffffff;
        }

        .btn-outline-primary:hover {
            color: #fff !important;
            background-color: #053651;
            border-color: #053651;
        }

        /* Loading state styles */
        #visaContentArea {
            min-height: 400px;
            transition: opacity 0.3s ease;
        }

        #leftMenuContainer,
        #tabContentContainer {
            transition: opacity 0.3s ease;
        }

        /* Prevent dropdown menu from staying visible */
        .bootstrap-select.show {
            display: block !important;
        }

        .bootstrap-select:not(.show) .dropdown-menu {
            display: none !important;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/js/bootstrap-select.min.js"></script>
    <script>
        // Toast notification function
        function showToast(message, type = 'info', duration = 4000) {
            const toastContainer = document.getElementById('toastContainer') || createToastContainer();

            const toastId = 'toast-' + Date.now();
            const toastHTML = `
                <div id="${toastId}" class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true" style="
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    min-width: 300px;
                    z-index: 9999;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                ">
                    <div class="toast-header" style="
                        background-color: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : type === 'warning' ? '#ffc107' : '#17a2b8'};
                        color: white;
                        border: none;
                    ">
                        <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-times-circle' : type === 'warning' ? 'fa-exclamation-circle' : 'fa-info-circle'}" style="margin-right: 8px;"></i>
                        <strong class="me-auto">${type.charAt(0).toUpperCase() + type.slice(1)}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" style="filter: brightness(0) invert(1);" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        ${message}
                    </div>
                </div>
            `;

            toastContainer.insertAdjacentHTML('beforeend', toastHTML);

            const toastElement = document.getElementById(toastId);
            setTimeout(() => {
                toastElement.classList.remove('show');
                setTimeout(() => toastElement.remove(), 300);
            }, duration);
        }

        // Create toast container
        function createToastContainer() {
            const container = document.createElement('div');
            container.id = 'toastContainer';
            container.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 9999;
                pointer-events: none;
            `;
            document.body.appendChild(container);
            return container;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Handle Share Button Click to Populate Modal Data
            const shareButtons = document.querySelectorAll('[data-bs-target="#shareModal"]');
            shareButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const container = this.closest('.share-info-container');
                    if (container) {
                        const country = container.getAttribute('data-country');
                        const visaCategory = container.getAttribute('data-visacategory');
                        const city = container.getAttribute('data-city');

                        // Update modal label
                        const label = document.getElementById('visaInfoLabel');
                        if (label) {
                            label.textContent = `${visaCategory} - ${city}`;
                        }

                        // Store current visa info in window for form submission
                        window.currentVisaInfo = {
                            country: country,
                            visaCategory: visaCategory,
                            city: city
                        };

                        // Clear validation states when modal opens
                        const shareForm = document.getElementById('shareForm');
                        if (shareForm) {
                            shareForm.classList.remove('was-validated');
                            const formFields = shareForm.querySelectorAll('input, textarea');
                            formFields.forEach(field => {
                                field.classList.remove('is-invalid', 'is-valid');
                                field.value = ''; // Clear previous values
                            });
                        }
                    }
                });
            });

            // Handle Share Form Submission
            const shareForm = document.getElementById('shareForm');

            // Real-time validation - remove errors as user types/corrects
            if (shareForm) {
                const formFields = shareForm.querySelectorAll('input, textarea');
                formFields.forEach(field => {
                    field.addEventListener('input', function() {
                        // Check if field is valid
                        if (this.value && this.checkValidity && this.checkValidity()) {
                            this.classList.remove('is-invalid');
                            this.classList.add('is-valid');
                        } else if (!this.value && !this.required) {
                            // Optional field, empty is ok
                            this.classList.remove('is-invalid');
                            this.classList.remove('is-valid');
                        } else if (!this.value) {
                            // Required field is empty
                            this.classList.remove('is-valid');
                            this.classList.add('is-invalid');
                        }
                    });

                    field.addEventListener('change', function() {
                        // Check if field is valid on blur
                        if (this.checkValidity) {
                            if (this.checkValidity()) {
                                this.classList.remove('is-invalid');
                                this.classList.add('is-valid');
                            } else if (this.required) {
                                this.classList.add('is-invalid');
                                this.classList.remove('is-valid');
                            }
                        }
                    });
                });
            }

            if (shareForm) {
                shareForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    // Validate form using HTML5 validation
                    if (!shareForm.checkValidity()) {
                        e.stopPropagation();
                        shareForm.classList.add('was-validated');

                        // Show validation errors
                        const invalidFields = shareForm.querySelectorAll(
                            'input:invalid, textarea:invalid');
                        if (invalidFields.length > 0) {
                            invalidFields[0].focus();
                        }
                        return;
                    }

                    // Additional custom validation for contact number
                    const contactNumber = document.getElementById('contactNo').value.trim();
                    const phoneRegex = /^[0-9\s\-\+\(\)]{10,20}$/;

                    if (!phoneRegex.test(contactNumber)) {
                        showToast(
                            'Please enter a valid phone number (10-20 characters with digits, spaces, dashes, +, or parentheses)',
                            'warning');
                        document.getElementById('contactNo').focus();
                        return;
                    }

                    // Validate email format
                    const emailInput = document.getElementById('recipientEmail').value.trim();
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(emailInput)) {
                        showToast('Please enter a valid email address', 'warning');
                        document.getElementById('recipientEmail').focus();
                        return;
                    }

                    // Validate name length
                    const nameInput = document.getElementById('recipientName').value.trim();
                    if (nameInput.length < 2 || nameInput.length > 100) {
                        showToast('Name must be between 2 and 100 characters', 'warning');
                        document.getElementById('recipientName').focus();
                        return;
                    }

                    const formData = new FormData(this);

                    // Add visa info to form data
                    if (window.currentVisaInfo) {
                        formData.append('visa_category', window.currentVisaInfo.visaCategory);
                        formData.append('city', window.currentVisaInfo.city);
                    }

                    // Show loader and disable submit button
                    const emailLoader = document.getElementById('emailLoader');
                    const submitBtn = shareForm.querySelector('button[type="submit"]');
                    const downloadBtn = document.getElementById('downloadPdfBtn');
                    const closeBtn = shareForm.querySelector('button[data-bs-dismiss="modal"]');

                    emailLoader.style.display = 'flex';
                    submitBtn.disabled = true;
                    downloadBtn.disabled = true;
                    closeBtn.disabled = true;

                    try {
                        const response = await fetch('{{ route('website.share-visa-info') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]')?.content || '',
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (response.ok && data.status) {
                            showToast(data.message || 'Information shared successfully!', 'success',
                                3000);
                            setTimeout(() => {
                                emailLoader.style.display = 'none';
                                document.getElementById('shareModal').querySelector(
                                    '.btn-close').click();
                                shareForm.reset();
                                shareForm.classList.remove('was-validated');
                                submitBtn.disabled = false;
                                downloadBtn.disabled = false;
                                closeBtn.disabled = false;
                            }, 500);
                        } else {
                            // Show server validation errors
                            if (data.errors) {
                                let errorMessages = [];
                                for (const [field, messages] of Object.entries(data.errors)) {
                                    errorMessages.push(`${field}: ${messages.join(', ')}`);
                                }
                                showToast(errorMessages.join('\n'), 'error', 5000);
                            } else {
                                showToast(data.message ||
                                    'Error sharing information. Please try again.', 'error', 4000);
                            }
                            emailLoader.style.display = 'none';
                            submitBtn.disabled = false;
                            downloadBtn.disabled = false;
                            closeBtn.disabled = false;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showToast(
                            'Error sharing information. Please check your connection and try again.',
                            'error', 4000);
                        emailLoader.style.display = 'none';
                        submitBtn.disabled = false;
                        downloadBtn.disabled = false;
                        closeBtn.disabled = false;
                    }
                });
            }

            // Handle Download PDF
            const downloadPdfBtn = document.getElementById('downloadPdfBtn');
            if (downloadPdfBtn) {
                downloadPdfBtn.addEventListener('click', function() {
                    generatePDF();
                });
            }

            // Handle WhatsApp Share
            const whatsappShareBtn = document.getElementById('whatsappShareBtn');
            if (whatsappShareBtn) {
                whatsappShareBtn.addEventListener('click', function() {
                    const contactNo = document.getElementById('contactNo').value;
                    if (!contactNo) {
                        showToast('Please enter contact number', 'warning');
                        return;
                    }

                    const visaInfo = window.currentVisaInfo ?
                        `${window.currentVisaInfo.visaCategory} - ${window.currentVisaInfo.city}` :
                        'Visa Information';

                    const message =
                        `I would like to share visa information for ${visaInfo}. Contact: ${contactNo}`;
                    const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;
                    window.open(whatsappUrl, '_blank');
                    showToast('Opening WhatsApp...', 'info', 2000);
                });
            }
        });

        function generatePDF() {
            // Get the visa category
            if (!window.currentVisaInfo) {
                showToast('Please select a visa type first.', 'warning');
                return;
            }

            const visaCategory = window.currentVisaInfo.visaCategory;
            const city = window.currentVisaInfo.city;

            // Find the currently visible accordion body
            const activeAccordion = document.querySelector('.accordion-collapse.show .accordion-body');
            if (!activeAccordion) {
                showToast('Please expand a visa detail before downloading PDF.', 'warning');
                return;
            }

            // Clone the element to avoid modifying the DOM
            const clonedElement = activeAccordion.cloneNode(true);
            // Remove share button or any action buttons from the clone
            const shareButton = clonedElement.querySelector('.share-info-container');
            if (shareButton) shareButton.remove();
            clonedElement.querySelectorAll('button, .btn').forEach(btn => btn.remove());

            // Wrap the content to retain layout when rendered in PDF
            const wrapper = document.createElement('div');
            wrapper.style.padding = '8px 0';
            wrapper.style.fontFamily = 'Arial, sans-serif';
            wrapper.innerHTML = clonedElement.innerHTML;

            // Show loader
            const loader = document.getElementById('processingLoader');
            if (loader) {
                loader.style.display = 'flex';
                // Disable buttons
                document.querySelectorAll('.btn-submit-visa, .btn-download-pdf, .btn-close-modal').forEach(btn => {
                    btn.disabled = true;
                });
            }

            // Send request to backend for PDF generation
            fetch('{{ route('website.download-visa-pdf') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        visa_category: visaCategory,
                        city: city,
                        content_html: wrapper.innerHTML
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => Promise.reject(data));
                    }
                    return response.blob().then(blob => ({
                        status: 'success',
                        blob: blob,
                        headers: response.headers
                    }));
                })
                .then(data => {
                    if (data.status === 'success') {
                        // Create download link
                        const url = window.URL.createObjectURL(data.blob);
                        const link = document.createElement('a');
                        link.href = url;

                        const visaCategoryFormatted = visaCategory.toUpperCase().replace(/\s+/g, '_');
                        link.download = `VisaCheckList_${visaCategoryFormatted}_${city}.pdf`;

                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        window.URL.revokeObjectURL(url);

                        showToast('PDF downloaded successfully!', 'success', 2000);
                    }
                })
                .catch(error => {
                    console.error('PDF download error:', error);
                    const errorMsg = error.message || 'Error generating PDF. Please try again.';
                    showToast(errorMsg, 'error', 3000);
                })
                .finally(() => {
                    // Hide loader
                    if (loader) {
                        loader.style.display = 'none';
                        // Enable buttons
                        document.querySelectorAll('.btn-submit-visa, .btn-download-pdf, .btn-close-modal').forEach(
                            btn => {
                                btn.disabled = false;
                            });
                    }
                });
        }

        // AJAX Country Filter Functionality
        let currentCountryId = {{ request()->country_id ?? 'null' }};
        let currentTab = '{{ request()->tab ?? 'factFinder' }}';

        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded');

            // Initialize bootstrap-select
            if (typeof $ !== 'undefined' && typeof $.fn.selectpicker !== 'undefined') {
                $('.selectpicker').selectpicker('refresh');
                console.log('Bootstrap-select initialized');
            }

            // Country select change handler - Using jQuery for bootstrap-select
            if (typeof $ !== 'undefined') {
                // Remove any existing event handlers
                $('#countrySelect').off('changed.bs.select');

                // Attach new event handler
                $('#countrySelect').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
                    const selectedCountryId = $(this).val();
                    console.log('Country selected via changed.bs.select:', selectedCountryId);
                    if (selectedCountryId && selectedCountryId !== '' && selectedCountryId !==
                        'Search Country') {
                        console.log('Filtering for country:', selectedCountryId);
                        filterVisaInformation(selectedCountryId, 'factFinder');
                    } else {
                        console.log('Invalid country selection:', selectedCountryId);
                    }
                });

                // Also add a regular change handler as fallback
                $('#countrySelect').on('change', function(e) {
                    const selectedCountryId = $(this).val();
                    console.log('Country selected via change:', selectedCountryId);
                    if (selectedCountryId && selectedCountryId !== '' && selectedCountryId !==
                        'Search Country') {
                        console.log('Filtering for country (fallback):', selectedCountryId);
                        filterVisaInformation(selectedCountryId, 'factFinder');
                    }
                });

                console.log('Country select event handlers attached');
            }

            // Tab click handlers (delegated event on document - works even after AJAX updates)
            document.addEventListener('click', function(e) {
                // Check if click is within leftMenuContainer
                if (!e.target.closest('#leftMenuContainer')) {
                    return; // Not in left menu, ignore
                }

                // Check if click is on a menu item or its child
                const menuItem = e.target.closest('.list-group-item[data-tab]');

                if (menuItem) {
                    e.preventDefault();
                    e.stopPropagation();

                    const tab = menuItem.getAttribute('data-tab');
                    console.log('Menu item clicked, tab:', tab, 'currentCountryId:', currentCountryId);

                    if (currentCountryId) {
                        filterVisaInformation(currentCountryId, tab);
                    } else {
                        console.warn('No country selected yet');
                        showToast('Please select a country first', 'warning', 2000);
                    }
                }
            });
        });

        function filterVisaInformation(countryId, tab = 'factFinder') {
            console.log('Filtering visa information for country:', countryId, 'tab:', tab);

            // Show loading state immediately
            showLoading();

            // Add small delay to ensure loader is visible
            setTimeout(() => {
                fetch('{{ route('website.visa-information.filter') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({
                            country_id: countryId,
                            tab: tab
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('AJAX Response:', data);

                        if (data.status) {
                            // Hide initial instructions and show visa content area
                            const initialInstructions = document.getElementById('initialInstructions');
                            const visaContentArea = document.getElementById('visaContentArea');

                            if (initialInstructions) {
                                initialInstructions.style.display = 'none';
                            }
                            if (visaContentArea) {
                                visaContentArea.style.display = 'flex';
                            }

                            // Update left menu
                            const leftMenuContainer = document.getElementById('leftMenuContainer');
                            if (leftMenuContainer) {
                                leftMenuContainer.innerHTML = data.html.leftMenu;
                                console.log('Left menu updated');
                            }

                            // Update tab content
                            const tabContentContainer = document.getElementById('tabContentContainer');
                            if (tabContentContainer) {
                                tabContentContainer.innerHTML = data.html.tabContent;
                                console.log('Tab content updated');
                            }

                            // Update current state
                            currentCountryId = data.country_id;
                            currentTab = data.tab;

                            // Hide loading
                            hideLoading();

                            // Use setTimeout to ensure DOM has fully updated
                            setTimeout(() => {
                                // Reinitialize bootstrap components if needed
                                reinitializeComponents();

                                // Manually activate the current tab (Bootstrap classes need this)
                                activateCurrentTab(tab);
                            }, 100);

                            // Update and refresh country selectpicker to show selected country
                            if (typeof $ !== 'undefined' && typeof $.fn.selectpicker !== 'undefined') {
                                try {
                                    const $select = $('#countrySelect');
                                    // Destroy the selectpicker instance
                                    $select.selectpicker('destroy');
                                    // Set the value
                                    $select.val(countryId);
                                    // Reinitialize selectpicker
                                    $select.selectpicker('render');
                                    console.log('Country selector updated to:', countryId);
                                } catch (e) {
                                    console.error('Error refreshing selectpicker:', e);
                                }
                            }

                            // Optional success notification (commented out for cleaner UX)
                            // showToast('Visa information loaded successfully', 'success', 1500);
                        } else {
                            hideLoading();
                            showToast('Error loading visa information', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('AJAX Error:', error);
                        hideLoading();
                        showToast('Error loading visa information. Please try again.', 'error');
                    });
            }, 100); // Small delay to ensure loader shows
        }

        function showLoading() {
            console.log('Showing loader...');

            // Close dropdown menu if it's open (but keep the select visible)
            if (typeof $ !== 'undefined' && $('.selectpicker').length) {
                try {
                    // Only close the dropdown menu, don't hide the select element
                    $('.bootstrap-select').removeClass('open show');
                    $('.bootstrap-select .dropdown-menu').removeClass('show');
                    $('body').removeClass('modal-open'); // Remove body class if dropdown was open
                } catch (e) {
                    console.log('Error closing dropdown:', e);
                }
            }

            // Hide initial instructions
            const initialInstructions = document.getElementById('initialInstructions');
            if (initialInstructions) {
                initialInstructions.style.display = 'none';
            }

            const visaContentArea = document.getElementById('visaContentArea');
            const leftMenu = document.getElementById('leftMenuContainer');
            const tabContent = document.getElementById('tabContentContainer');

            // Show the visa content area if hidden
            if (visaContentArea) {
                visaContentArea.style.display = 'flex';
            }

            // Clear and show loader in left menu
            if (leftMenu) {
                leftMenu.innerHTML = `
                    <div class="text-center py-4">
                        <div class="spinner-border text-danger" role="status" style="width: 2rem; height: 2rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `;
            }

            // Clear and show loader in tab content
            if (tabContent) {
                tabContent.innerHTML = `
                    <div class="text-center py-5">
                        <div class="spinner-border text-danger" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3" style="color: #003366; font-weight: 500;">Loading visa information...</p>
                    </div>
                `;
            }
        }

        function hideLoading() {
            console.log('Hiding loader...');
            // Content is already updated by AJAX, just log
        }

        function activateCurrentTab(tabName) {
            console.log('Activating tab:', tabName);

            // Find the tab pane to activate
            const tabPane = document.getElementById(tabName);

            if (tabPane) {
                // Remove active and show classes from all tab panes
                document.querySelectorAll('#tabContentContainer .tab-pane').forEach(pane => {
                    pane.classList.remove('show', 'active');
                });

                // Add active and show classes to the current tab
                tabPane.classList.add('show', 'active');
                console.log('Tab activated:', tabName);

                // Scroll to tab content smoothly
                setTimeout(() => {
                    tabPane.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }, 100);
            } else {
                console.error('Tab pane not found:', tabName);
            }

            // Update menu active state
            document.querySelectorAll('#leftMenuContainer .list-group-item').forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('data-tab') === tabName) {
                    item.classList.add('active');
                }
            });
        }

        function reinitializeComponents() {
            console.log('Reinitializing Bootstrap components...');

            // Reinitialize Bootstrap tabs (including nested category tabs)
            const tabElements = document.querySelectorAll('[data-bs-toggle="tab"]');
            console.log('Found tab elements:', tabElements.length);
            tabElements.forEach(tabEl => {
                if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
                    // Remove existing instance if any
                    const existingTab = bootstrap.Tab.getInstance(tabEl);
                    if (existingTab) {
                        existingTab.dispose();
                    }
                    // Create new instance and enable it
                    const tabInstance = new bootstrap.Tab(tabEl);

                    // Add click event listener for nested category tabs
                    if (tabEl.closest('.visa-category-tabs')) {
                        tabEl.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            console.log('Category tab clicked:', tabEl.id);

                            // Remove inline styles from all category tab panes before showing new one
                            const allCategoryPanes = document.querySelectorAll(
                                '.category-tab-content .tab-pane');
                            allCategoryPanes.forEach(pane => {
                                pane.style.display = '';
                                pane.style.opacity = '';
                                pane.classList.remove('show', 'active');
                            });

                            tabInstance.show();
                        });
                    }
                }
            });

            // After reinitializing, trigger the first active category tab to show its content
            setTimeout(() => {
                const firstActiveCategoryTab = document.querySelector('.visa-category-tabs .nav-link.active');
                if (firstActiveCategoryTab) {
                    console.log('Found first active category tab:', firstActiveCategoryTab.textContent);
                    const targetId = firstActiveCategoryTab.getAttribute('data-bs-target');
                    console.log('Target pane ID:', targetId);

                    if (targetId) {
                        // Remove active from all category tab panes first
                        document.querySelectorAll('.category-tab-content .tab-pane').forEach(pane => {
                            pane.classList.remove('show', 'active');
                        });

                        const targetPane = document.querySelector(targetId);
                        if (targetPane) {
                            // Force the pane to be visible
                            targetPane.classList.add('show', 'active');
                            targetPane.style.display = 'block';
                            targetPane.style.opacity = '1';
                            console.log('First category tab content activated and forced visible:', targetId);
                        } else {
                            console.error('Target pane not found:', targetId);
                        }
                    }
                } else {
                    console.log('No active category tab found');
                }
            }, 50);

            // Reinitialize Bootstrap collapse/accordion
            const collapseElements = document.querySelectorAll('[data-bs-toggle="collapse"]');
            console.log('Found collapse elements:', collapseElements.length);
            collapseElements.forEach(collapseEl => {
                if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
                    const existingCollapse = bootstrap.Collapse.getInstance(collapseEl);
                    if (existingCollapse) {
                        existingCollapse.dispose();
                    }
                    // Don't auto-toggle
                    new bootstrap.Collapse(collapseEl, {
                        toggle: false
                    });
                }
            });

            // Reinitialize modals
            const modalElements = document.querySelectorAll('.modal');
            modalElements.forEach(modalEl => {
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const existingModal = bootstrap.Modal.getInstance(modalEl);
                    if (existingModal) {
                        existingModal.dispose();
                    }
                    new bootstrap.Modal(modalEl);
                }
            });

            console.log('Bootstrap components reinitialized');
        }
    </script>
@endsection
