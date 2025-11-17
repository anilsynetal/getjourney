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
        <div class="container-fluid overflow-hidden py-5">
            <div class="container py-5">

                <!-- Country Search Dropdown -->
                <div class="row mb-4">
                    <div class="col-lg-8 mx-auto text-center">
                        <form method="GET" action="{{ route('website.visa-information') }}">
                            <select class="form-control selectpicker" data-live-search="true" name="country_id"
                                onchange="this.form.submit()">
                                <option selected disabled>Search Country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}"
                                        {{ request()->country_id == $country->id ? 'selected' : '' }}>
                                        {{ $country->country }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
                @if (!request()->has('country_id'))
                    <div class="row">
                        <div class="col">
                            <div class="card text-center p-4">
                                <div class="card-body">
                                    <h5 class="card-title">Apply Visa Online In 3 Simple Steps</h5>
                                    <p class="card-text">We Take Care Of Your Online Visa Application</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 mt-2">
                        <div class="col-md-4">
                            <div class="card text-center p-4">
                                <div class="card-body">
                                    <img src="{{ asset('website/assets/img/searchcountry.png') }}" alt="Step 1"
                                        class="mb-3">
                                    <h6 class="card-title">Search the Country</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center p-4">
                                <div class="card-body">
                                    <img src="{{ asset('website/assets/img/readvisainfo.png') }}" alt="Step 2"
                                        class="mb-3">
                                    <h6 class="card-title">Read Visa Information</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center p-4">
                                <div class="card-body">
                                    <img src="{{ asset('website/assets/img/dwndvisa.png') }}" alt="Step 3" class="mb-3">
                                    <h6 class="card-title">Download Visa Forms</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row g-4">

                        <!-- LEFT MENU -->
                        <div class="col-lg-3">
                            <div class="list-group">
                                @if ($visa_information)
                                    <a class="list-group-item list-group-item-action {{ request()->tab == 'factFinder' || !request()->has('tab') ? 'active' : '' }}"
                                        href="{{ route('website.visa-information', ['country_id' => request()->country_id, 'tab' => 'factFinder']) }}">Country
                                        Fact Finder</a>
                                @endif
                                @if ($visa_details->count() > 0)
                                    <a class="list-group-item list-group-item-action {{ request()->tab == 'visaNotes' ? 'active' : '' }}"
                                        href="{{ route('website.visa-information', ['country_id' => request()->country_id, 'tab' => 'visaNotes']) }}">Visa
                                        Notes & Fees</a>
                                @endif
                                @if ($visa_forms->count() > 0)
                                    <a class="list-group-item list-group-item-action {{ request()->tab == 'visaForms' ? 'active' : '' }}"
                                        href="{{ route('website.visa-information', ['country_id' => request()->country_id, 'tab' => 'visaForms']) }}">Download
                                        Visa Forms</a>
                                @endif
                                @if ($diplomatic_representations->count() > 0)
                                    <a class="list-group-item list-group-item-action {{ request()->tab == 'diplomatic' ? 'active' : '' }}"
                                        href="{{ route('website.visa-information', ['country_id' => request()->country_id, 'tab' => 'diplomatic']) }}">Diplomatic
                                        Representation</a>
                                @endif
                                @if ($international_help_addresses->count() > 0)
                                    <a class="list-group-item list-group-item-action {{ request()->tab == 'helpAddress' ? 'active' : '' }}"
                                        href="{{ route('website.visa-information', ['country_id' => request()->country_id, 'tab' => 'helpAddress']) }}">International
                                        Help Address</a>
                                @endif
                                @if ($logistic_partners->count() > 0)
                                    <a class="list-group-item list-group-item-action {{ request()->tab == 'logistic' ? 'active' : '' }}"
                                        href="{{ route('website.visa-information', ['country_id' => request()->country_id, 'tab' => 'logistic']) }}">Logistic
                                        Partner</a>
                                @endif
                            </div>
                        </div>

                        <!-- RIGHT CONTENT -->
                        <div class="col-lg-9 ps-lg-4">

                            <div class="tab-content">

                                <!-- 1. Country Fact Finder -->
                                <div class="tab-pane fade show {{ request()->tab == 'factFinder' || !request()->has('tab') ? 'active' : '' }}"
                                    id="factFinder">
                                    <div class="card p-4">
                                        <h3 class="mb-3">
                                            {{ $visa_information ? $visa_information->country->country : 'Country' }} –
                                            General Information</h3>
                                        <p>
                                            {{ $visa_information ? $visa_information->description : 'General information about the country will be displayed here.' }}
                                        </p>
                                    </div>
                                </div>
                                <!-- 2. Visa Notes & Fees -->
                                <div class="tab-pane fade {{ request()->tab == 'visaNotes' ? 'show active' : '' }}"
                                    id="visaNotes">
                                    <div class="card p-4">
                                        <h3 class="mb-3">
                                            {{ $visa_information ? $visa_information->country->country : 'Country' }} –
                                            Visa Notes & Fees
                                        </h3>

                                        @if ($visa_details->count() > 0)

                                            <!-- Category Tabs -->
                                            <ul class="nav nav-tabs mb-3">
                                                @foreach ($visa_categories as $index => $category)
                                                    <li class="nav-item">
                                                        <a class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                                            data-bs-toggle="tab" href="#categoryTab{{ $category->id }}">
                                                            {{ $category->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>

                                            <!-- Category Tab Content -->
                                            <div class="tab-content">

                                                @foreach ($visa_categories as $index => $category)
                                                    <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"
                                                        id="categoryTab{{ $category->id }}">

                                                        <!-- Accordion for this category only -->
                                                        <div class="accordion" id="accordionCategory{{ $category->id }}">
                                                            @foreach ($visa_details->where('visa_category_id', $category->id) as $detail)
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header"
                                                                        id="heading{{ $detail->id }}">
                                                                        <button class="accordion-button collapsed"
                                                                            type="button" data-bs-toggle="collapse"
                                                                            data-bs-target="#collapse{{ $detail->id }}"
                                                                            aria-expanded="false"
                                                                            aria-controls="collapse{{ $detail->id }}">
                                                                            {{ $detail->city }}
                                                                        </button>
                                                                    </h2>

                                                                    <div id="collapse{{ $detail->id }}"
                                                                        class="accordion-collapse collapse"
                                                                        aria-labelledby="heading{{ $detail->id }}"
                                                                        data-bs-parent="#accordionCategory{{ $category->id }}">

                                                                        <div class="accordion-body visa-body">

                                                                            <strong>Visa Fees:</strong>
                                                                            {!! $detail->visa_fees !!}<br>
                                                                            <strong>Logistic Fees:</strong>
                                                                            {!! $detail->logistic_charges !!}<br><br>
                                                                            <strong>Processing Time:</strong>
                                                                            {!! $detail->processing_time !!}<br><br>

                                                                            <h5 class="mt-4 mb-3">Mandatory Documents</h5>

                                                                            @foreach ($detail->documents as $document)
                                                                                <div
                                                                                    class="d-flex gap-3 mb-3 align-items-start">
                                                                                    <div
                                                                                        style="background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%);
                                                                color: #fff;
                                                                border-radius: 50%;
                                                                width: 28px;
                                                                height: 28px;
                                                                display: flex;
                                                                align-items: center;
                                                                justify-content: center;
                                                                flex-shrink: 0;
                                                                font-weight: bold;
                                                                box-shadow: 0 2px 8px rgba(211, 47, 47, 0.3);">
                                                                                        <i class="fas fa-check"
                                                                                            style="font-size: 0.9rem;"></i>
                                                                                    </div>

                                                                                    <div style="padding-top: 2px;">
                                                                                        <strong
                                                                                            style="color: #003366; font-size: 0.95rem;">{{ $document->title }}:</strong>
                                                                                        <div
                                                                                            style="color: #555; font-size: 0.9rem; margin-top: 2px;">
                                                                                            @if ($document->link)
                                                                                                <a href="{{ $document->link }}"
                                                                                                    target="_blank"
                                                                                                    style="color: #003366; text-decoration: none; border-bottom: 1px solid #003366;">
                                                                                                    {{ $document->description }}
                                                                                                </a>
                                                                                            @else
                                                                                                {{ $document->description }}
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                            <!-- Share Info -->
                                                                            <div class="mt-3 share-info-container"
                                                                                data-country="{{ json_encode($visa_information->country) }}"
                                                                                data-visacategory="{{ $category->name }}"
                                                                                data-city="{{ $detail->city }}">
                                                                                <button class="btn btn-link p-0"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#shareModal">
                                                                                    <i class="fas fa-share-alt me-2"
                                                                                        style="color:#d32f2f;"></i>
                                                                                    Share Information via Email
                                                                                </button>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>
                                        @else
                                            <p>No visa details available.</p>
                                        @endif
                                    </div>
                                </div>


                                <!-- 3. Download Visa Forms -->
                                <div class="tab-pane fade {{ request()->tab == 'visaForms' ? 'show active' : '' }}"
                                    id="visaForms">
                                    <div class="card p-4">
                                        <h4>{{ $visa_information ? $visa_information->country->country : 'Country' }} –
                                            Download Visa Forms</h4>
                                        <ul class="list-group">
                                            @foreach ($visa_forms as $form)
                                                <li class="list-group-item d-flex justify-content-between">
                                                    {{ $form->city }} - {{ $form->visa_category->name }}
                                                    @if ($form->visa_form)
                                                        <a href="{{ asset('storage/visa_forms/' . $form->visa_form) }}"
                                                            class="btn btn-primary btn-sm" target="_blank">VISA
                                                            APPLICATION FORM</a>
                                                    @endif
                                                    @if ($form->application_form_url)
                                                        <a href="{{ $form->application_form_url }}"
                                                            class="btn btn-success btn-sm" target="_blank">APPLICATION
                                                            FORM LINK</a>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <!-- 4. Diplomatic Representation -->
                                <div class="tab-pane fade {{ request()->tab == 'diplomatic' ? 'show active' : '' }}"
                                    id="diplomatic">
                                    <div class="card p-4">
                                        <h4>{{ $visa_information ? $visa_information->country->country : 'Country' }} –
                                            Diplomatic Representation</h4>
                                        @if ($diplomatic_representations->count() > 0)
                                            <div class="space-y-3">
                                                @foreach ($diplomatic_representations as $rep)
                                                    <div class="card border-0 shadow-sm"
                                                        style="background-color: #f8f9fa;">
                                                        <div class="card-body p-4">
                                                            <div class="row align-items-start">
                                                                <!-- City Badge -->
                                                                <div class="col-auto">
                                                                    <div
                                                                        style="background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%);
                                                                            border-radius: 20px;
                                                                            padding: 8px 16px;
                                                                            color: white;
                                                                            font-weight: 600;
                                                                            white-space: nowrap;">
                                                                        {{ $rep->city }}
                                                                    </div>
                                                                </div>

                                                                <!-- Office Info -->
                                                                <div class="col m-auto">
                                                                    <h5
                                                                        style="color: #003366; font-weight: 700; margin: 0;">
                                                                        {{ $rep->office_name }}
                                                                    </h5>
                                                                </div>
                                                            </div>

                                                            <!-- Address -->
                                                            <div class="row mt-3 mb-3">
                                                                <div class="col">
                                                                    <p class="mb-0"
                                                                        style="color: #666; font-size: 0.95rem;">
                                                                        <i class="fas fa-map-pin"
                                                                            style="color: #d32f2f; margin-right: 8px;"></i>
                                                                        {{ $rep->address }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <!-- Contact Details Row -->
                                                            <div class="row g-3 mb-3">
                                                                <!-- Phone Numbers -->
                                                                <div class="col-auto">
                                                                    <div style="display: flex; gap: 8px;">
                                                                        @if ($rep->contact_number1)
                                                                            <a href="tel:{{ $rep->contact_number1 }}"
                                                                                class="text-decoration-none"
                                                                                style="color: #003366; font-size: 0.9rem; display: flex; align-items: center; gap: 4px;">
                                                                                <i class="fas fa-phone"
                                                                                    style="color: #d32f2f;"></i>
                                                                                {{ $rep->contact_number1 }}
                                                                            </a>
                                                                        @endif
                                                                        @if ($rep->contact_number2)
                                                                            <span style="color: #ccc;">|</span>
                                                                            <a href="tel:{{ $rep->contact_number2 }}"
                                                                                class="text-decoration-none"
                                                                                style="color: #003366; font-size: 0.9rem; display: flex; align-items: center; gap: 4px;">
                                                                                <i class="fas fa-phone"
                                                                                    style="color: #d32f2f;"></i>
                                                                                {{ $rep->contact_number2 }}
                                                                            </a>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <!-- Fax -->
                                                                @if ($rep->fax_number)
                                                                    <div class="col-auto">
                                                                        <span
                                                                            style="color: #666; font-size: 0.9rem; display: flex; align-items: center; gap: 4px;">
                                                                            <i class="fas fa-fax"
                                                                                style="color: #d32f2f;"></i>
                                                                            {{ $rep->fax_number }}
                                                                        </span>
                                                                    </div>
                                                                @endif

                                                                <!-- Email -->
                                                                @if ($rep->email)
                                                                    <div class="col-auto">
                                                                        <a href="javascript:void(0);"
                                                                            class="text-decoration-none"
                                                                            style="color: #003366; font-size: 0.9rem; display: flex; align-items: center; gap: 4px;">
                                                                            <i class="fas fa-envelope"
                                                                                style="color: #d32f2f;"></i>
                                                                            {{ $rep->email }}
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <!-- Opening Hours -->
                                                            @php
                                                                $opening_hours =
                                                                    json_decode($rep->opening_hours, true) ?? [];
                                                            @endphp
                                                            @if (count(array_filter($opening_hours)) > 0)
                                                                <div class="row g-2">
                                                                    <div class="col-12">
                                                                        <p class="mb-2"
                                                                            style="font-size: 0.85rem; color: #666; font-weight: 600;">
                                                                            <i class="fas fa-clock"
                                                                                style="color: #d32f2f;"></i> Hours : Open ·
                                                                            Closes
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div
                                                                            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(110px, 1fr)); gap: 12px; font-size: 0.85rem;">
                                                                            @foreach (['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'] as $day => $fullDay)
                                                                                @if (!empty($opening_hours[$day]))
                                                                                    <div
                                                                                        style="border-right: 1px solid #ddd; ">
                                                                                        <div
                                                                                            style="color: #003366; font-weight: 600;">
                                                                                            {{ $fullDay }}</div>
                                                                                        <div style="color: #666;">
                                                                                            {{ $opening_hours[$day] }}
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i> No diplomatic representation
                                                information available.
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- 5. International Help Address -->
                                <div class="tab-pane fade {{ request()->tab == 'helpAddress' ? 'show active' : '' }}"
                                    id="helpAddress">
                                    <div class="card p-4">
                                        @if ($international_help_addresses->count() == 0)
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i> No international help address
                                                information available.
                                            </div>
                                        @else
                                            @if ($visa_information)
                                                <h4 class="mb-3">{{ $visa_information->country->country }} –
                                                    International Help
                                                    Contacts</h4>
                                            @endif
                                            <ol class="list-group-numbered mt-3">
                                                @foreach ($international_help_addresses as $help)
                                                    <li>
                                                        <a href="{{ $help->link }}" target="_blank">
                                                            <u>{{ $help->title }}</u>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ol>
                                        @endif
                                    </div>
                                </div>

                                <!-- 6. Logistic Partner -->
                                <div class="tab-pane fade {{ request()->tab == 'logistic' ? 'show active' : '' }}"
                                    id="logistic">
                                    <div class="card p-4">
                                        <h4>{{ $visa_information ? $visa_information->country->country : 'Country' }} –
                                            Logistic Partner</h4>
                                        @if ($logistic_partners->count() > 0)
                                            <div class="space-y-3">
                                                @foreach ($logistic_partners as $logistic)
                                                    <div class="card border-0 shadow-sm"
                                                        style="background-color: #f8f9fa;">
                                                        <div class="card-body p-4">
                                                            <div class="row align-items-start">
                                                                <!-- City Badge -->
                                                                <div class="col-auto">
                                                                    <div
                                                                        style="background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%);
                                                                            border-radius: 20px;
                                                                            padding: 8px 16px;
                                                                            color: white;
                                                                            font-weight: 600;
                                                                            white-space: nowrap;">
                                                                        {{ $logistic->city }}
                                                                    </div>
                                                                </div>

                                                                <!-- Office Info -->
                                                                <div class="col m-auto">
                                                                    <h5
                                                                        style="color: #003366; font-weight: 700; margin: 0;">
                                                                        {{ $logistic->office_name }}
                                                                    </h5>
                                                                </div>
                                                            </div>

                                                            <!-- Address -->
                                                            <div class="row mt-3 mb-3">
                                                                <div class="col">
                                                                    <p class="mb-0"
                                                                        style="color: #666; font-size: 0.95rem;">
                                                                        <i class="fas fa-map-pin"
                                                                            style="color: #d32f2f; margin-right: 8px;"></i>
                                                                        {{ $logistic->address }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <!-- Contact Details Row -->
                                                            <div class="row g-3 mb-3">
                                                                <!-- Phone Numbers -->
                                                                <div class="col-auto">
                                                                    <div style="display: flex; gap: 8px;">
                                                                        @if ($logistic->contact_number)
                                                                            <a href="tel:{{ $logistic->contact_number }}"
                                                                                class="text-decoration-none"
                                                                                style="color: #003366; font-size: 0.9rem; display: flex; align-items: center; gap: 4px;">
                                                                                <i class="fas fa-phone"
                                                                                    style="color: #d32f2f;"></i>
                                                                                {{ $logistic->contact_number }}
                                                                            </a>
                                                                        @endif
                                                                        @if ($logistic->website)
                                                                            <span style="color: #ccc;">|</span>
                                                                            <a href="tel:{{ $logistic->website }}"
                                                                                class="text-decoration-none"
                                                                                style="color: #003366; font-size: 0.9rem; display: flex; align-items: center; gap: 4px;">
                                                                                <i class="fas fa-phone"
                                                                                    style="color: #d32f2f;"></i>
                                                                                {{ $logistic->website }}
                                                                            </a>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <!-- Fax -->
                                                                @if ($logistic->fax_number)
                                                                    <div class="col-auto">
                                                                        <span
                                                                            style="color: #666; font-size: 0.9rem; display: flex; align-items: center; gap: 4px;">
                                                                            <i class="fas fa-fax"
                                                                                style="color: #d32f2f;"></i>
                                                                            {{ $logistic->fax_number }}
                                                                        </span>
                                                                    </div>
                                                                @endif

                                                                <!-- Email -->
                                                                @if ($logistic->email)
                                                                    <div class="col-auto">
                                                                        <a href="javascript:void(0);"
                                                                            class="text-decoration-none"
                                                                            style="color: #003366; font-size: 0.9rem; display: flex; align-items: center; gap: 4px;">
                                                                            <i class="fas fa-envelope"
                                                                                style="color: #d32f2f;"></i>
                                                                            {{ $logistic->email }}
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <!-- Opening Hours -->
                                                            @php
                                                                $opening_hours =
                                                                    json_decode($logistic->opening_hours, true) ?? [];
                                                            @endphp
                                                            @if (count(array_filter($opening_hours)) > 0)
                                                                <div class="row g-2">
                                                                    <div class="col-12">
                                                                        <p class="mb-2"
                                                                            style="font-size: 0.85rem; color: #666; font-weight: 600;">
                                                                            <i class="fas fa-clock"
                                                                                style="color: #d32f2f;"></i> Hours : Open ·
                                                                            Closes
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div
                                                                            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(110px, 1fr)); gap: 12px; font-size: 0.85rem;">
                                                                            @foreach (['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'] as $day => $fullDay)
                                                                                @if (!empty($opening_hours[$day]))
                                                                                    <div
                                                                                        style="border-right: 1px solid #ddd; ">
                                                                                        <div
                                                                                            style="color: #003366; font-weight: 600;">
                                                                                            {{ $fullDay }}</div>
                                                                                        <div style="color: #666;">
                                                                                            {{ $opening_hours[$day] }}
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i> No diplomatic representation
                                                information available.
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                @endif
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
                        <strong>Click on</strong> <i class="fab fa-whatsapp"
                            style="color: #25D366; font-size: 1.1rem;"></i>
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
    </script>
@endsection
