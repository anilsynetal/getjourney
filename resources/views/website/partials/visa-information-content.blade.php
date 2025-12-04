<div class="row g-4">

    <!-- LEFT MENU -->
    <div class="col-lg-3">
        <div class="list-group">
            @if ($visa_information)
                <a class="list-group-item list-group-item-action {{ $tab == 'factFinder' || !isset($tab) ? 'active' : '' }}"
                    href="javascript:void(0);" data-tab="factFinder">Country
                    Fact Finder</a>
            @endif
            @if ($visa_details->count() > 0)
                <a class="list-group-item list-group-item-action {{ $tab == 'visaNotes' ? 'active' : '' }}"
                    href="javascript:void(0);" data-tab="visaNotes">Visa
                    Notes & Fees</a>
            @endif
            @if ($visa_forms->count() > 0)
                <a class="list-group-item list-group-item-action {{ $tab == 'visaForms' ? 'active' : '' }}"
                    href="javascript:void(0);" data-tab="visaForms">Download
                    Visa Forms</a>
            @endif
            @if ($diplomatic_representations->count() > 0)
                <a class="list-group-item list-group-item-action {{ $tab == 'diplomatic' ? 'active' : '' }}"
                    href="javascript:void(0);" data-tab="diplomatic">Diplomatic
                    Representation</a>
            @endif
            @if ($international_help_addresses->count() > 0)
                <a class="list-group-item list-group-item-action {{ $tab == 'helpAddress' ? 'active' : '' }}"
                    href="javascript:void(0);" data-tab="helpAddress">International
                    Help Address</a>
            @endif

            <a class="list-group-item list-group-item-action {{ $tab == 'logistic' ? 'active' : '' }}"
                href="javascript:void(0);" data-tab="logistic">Logistic
                Partner</a>
        </div>
    </div>

    <!-- RIGHT CONTENT -->
    <div class="col-lg-9 ps-lg-4">

        <div class="tab-content">

            <!-- 1. Country Fact Finder -->
            <div class="tab-pane fade {{ $tab == 'factFinder' || !isset($tab) ? 'show active' : '' }}" id="factFinder">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-4">
                        <h3 class="mb-3">
                            {{ $visa_information ? $visa_information->country->country : 'Country' }} –
                            General Information</h3>
                        <p>
                            {{ $visa_information ? $visa_information->description : 'General information about the country will be displayed here.' }}
                        </p>
                    </div>
                </div>
            </div>
            <!-- 2. Visa Notes & Fees -->
            <div class="tab-pane fade {{ $tab == 'visaNotes' ? 'show active' : '' }}" id="visaNotes">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-4">
                        <h3 class="mb-3">
                            {{ $visa_information ? $visa_information->country->country : 'Country' }} –
                            Visa Notes & Fees
                        </h3>

                        @if ($visa_details->count() > 0)

                            <!-- Category Tabs -->
                            <ul class="nav nav-tabs mb-3">
                                @foreach ($visa_categories as $index => $category)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $index == 0 ? 'active' : '' }}" data-bs-toggle="tab"
                                            href="#categoryTab{{ $category->id }}">
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
                                                    <h2 class="accordion-header" id="heading{{ $detail->id }}">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
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

                                                            <h5 class="mt-4 mb-3">Mandatory Documents
                                                            </h5>

                                                            @foreach ($detail->documents as $document)
                                                                <div class="d-flex gap-3 mb-3 align-items-start">
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
                                                                            @if ($document->description)
                                                                                {{ $document->description }}
                                                                            @endif
                                                                            @if ($document->file)
                                                                                <div class="mt-1">
                                                                                    <a href="{{ asset('storage/' . $document->file) }}"
                                                                                        target="_blank"
                                                                                        class="btn btn-sm btn-outline-primary"
                                                                                        style="color: #003366; border-color: #003366;">
                                                                                        <i class="fas fa-download"></i>
                                                                                        Download
                                                                                        Document
                                                                                    </a>
                                                                                </div>
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
                                                                <button class="btn btn-link p-0" data-bs-toggle="modal"
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
            </div>


            <!-- 3. Download Visa Forms -->
            <div class="tab-pane fade {{ $tab == 'visaForms' ? 'show active' : '' }}" id="visaForms">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-4">
                        <h4>{{ $visa_information ? $visa_information->country->country : 'Country' }} –
                            Download Visa Forms</h4>
                        <ul class="list-group list-group-flush">

                        </ul>
                    </div>
                </div>
            </div>

            <!-- 4. Diplomatic Representation -->
            <div class="tab-pane fade {{ $tab == 'diplomatic' ? 'show active' : '' }}" id="diplomatic">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-4">
                        <h4>{{ $visa_information ? $visa_information->country->country : 'Country' }} –
                            Diplomatic Representation</h4>
                        @if ($diplomatic_representations->count() > 0)
                            <div class="space-y-3">
                                @foreach ($diplomatic_representations as $rep)
                                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden"
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
                                                    <h5 style="color: #003366; font-weight: 700; margin: 0;">
                                                        {{ $rep->office_name }}
                                                    </h5>
                                                </div>
                                            </div>

                                            <!-- Address -->
                                            <div class="row mt-3 mb-3">
                                                <div class="col">
                                                    <p class="mb-0" style="color: #666; font-size: 0.95rem;">
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
                                                                <i class="fas fa-phone" style="color: #d32f2f;"></i>
                                                                {{ $rep->contact_number1 }}
                                                            </a>
                                                        @endif
                                                        @if ($rep->contact_number2)
                                                            <span style="color: #ccc;">|</span>
                                                            <a href="tel:{{ $rep->contact_number2 }}"
                                                                class="text-decoration-none"
                                                                style="color: #003366; font-size: 0.9rem; display: flex; align-items: center; gap: 4px;">
                                                                <i class="fas fa-phone" style="color: #d32f2f;"></i>
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
                                                            <i class="fas fa-fax" style="color: #d32f2f;"></i>
                                                            {{ $rep->fax_number }}
                                                        </span>
                                                    </div>
                                                @endif

                                                <!-- Email -->
                                                @if ($rep->email)
                                                    <div class="col-auto">
                                                        <a href="javascript:void(0);" class="text-decoration-none"
                                                            style="color: #003366; font-size: 0.9rem; display: flex; align-items: center; gap: 4px;">
                                                            <i class="fas fa-envelope" style="color: #d32f2f;"></i>
                                                            {{ $rep->email }}
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Opening Hours -->
                                            @php
                                                $opening_hours = json_decode($rep->opening_hours, true) ?? [];
                                            @endphp
                                            @if (count(array_filter($opening_hours)) > 0)
                                                <div class="row g-2">
                                                    <div class="col-12">
                                                        <p class="mb-2"
                                                            style="font-size: 0.85rem; color: #666; font-weight: 600;">
                                                            <i class="fas fa-clock" style="color: #d32f2f;"></i> Hours
                                                            :
                                                            Open ·
                                                            Closes
                                                        </p>
                                                    </div>
                                                    <div class="col-12">
                                                        <div
                                                            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(110px, 1fr)); gap: 12px; font-size: 0.85rem;">
                                                            @foreach (['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'] as $day => $fullDay)
                                                                @if (!empty($opening_hours[$day]))
                                                                    <div style="border-right: 1px solid #ddd; ">
                                                                        <div style="color: #003366; font-weight: 600;">
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

            <!-- 5. International Help Address -->
            <div class="tab-pane fade {{ $tab == 'helpAddress' ? 'show active' : '' }}" id="helpAddress">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-4">
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
            </div>

            <!-- 6. Logistic Partner -->
            <div class="tab-pane fade {{ $tab == 'logistic' ? 'show active' : '' }}" id="logistic">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-4">
                        <h4>{{ $visa_information ? $visa_information->country->country : 'Country' }} –
                            Logistic Partner</h4>
                        @if ($logistic_partners->count() > 0)
                            <div class="space-y-3">
                                @foreach ($logistic_partners as $logistic)
                                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden"
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
                                                    <h5 style="color: #003366; font-weight: 700; margin: 0;">
                                                        {{ $logistic->office_name }}
                                                    </h5>
                                                </div>
                                            </div>

                                            <!-- Address -->
                                            <div class="row mt-3 mb-3">
                                                <div class="col">
                                                    <p class="mb-0" style="color: #666; font-size: 0.95rem;">
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
                                                                <i class="fas fa-phone" style="color: #d32f2f;"></i>
                                                                {{ $logistic->contact_number }}
                                                            </a>
                                                        @endif
                                                        @if ($logistic->website)
                                                            <span style="color: #ccc;">|</span>
                                                            <a href="tel:{{ $logistic->website }}"
                                                                class="text-decoration-none"
                                                                style="color: #003366; font-size: 0.9rem; display: flex; align-items: center; gap: 4px;">
                                                                <i class="fas fa-phone" style="color: #d32f2f;"></i>
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
                                                            <i class="fas fa-fax" style="color: #d32f2f;"></i>
                                                            {{ $logistic->fax_number }}
                                                        </span>
                                                    </div>
                                                @endif

                                                <!-- Email -->
                                                @if ($logistic->email)
                                                    <div class="col-auto">
                                                        <a href="javascript:void(0);" class="text-decoration-none"
                                                            style="color: #003366; font-size: 0.9rem; display: flex; align-items: center; gap: 4px;">
                                                            <i class="fas fa-envelope" style="color: #d32f2f;"></i>
                                                            {{ $logistic->email }}
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Opening Hours -->
                                            @php
                                                $opening_hours = json_decode($logistic->opening_hours, true) ?? [];
                                            @endphp
                                            @if (count(array_filter($opening_hours)) > 0)
                                                <div class="row g-2">
                                                    <div class="col-12">
                                                        <p class="mb-2"
                                                            style="font-size: 0.85rem; color: #666; font-weight: 600;">
                                                            <i class="fas fa-clock" style="color: #d32f2f;"></i> Hours
                                                            :
                                                            Open ·
                                                            Closes
                                                        </p>
                                                    </div>
                                                    <div class="col-12">
                                                        <div
                                                            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(110px, 1fr)); gap: 12px; font-size: 0.85rem;">
                                                            @foreach (['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'] as $day => $fullDay)
                                                                @if (!empty($opening_hours[$day]))
                                                                    <div style="border-right: 1px solid #ddd; ">
                                                                        <div style="color: #003366; font-weight: 600;">
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
                            <div class="alert alert-info mt-4">
                                <i class="fas fa-info-circle me-2"></i>

                                For information on the searched country, please get in touch with us on
                                <a href="mailto:{{ $contact['email'] ?? '' }}">{{ $contact['email'] ?? '' }}</a> and
                                please mention your location.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
