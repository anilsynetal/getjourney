<div class="tab-content">

    <!-- 1. Country Fact Finder -->
    <div class="tab-pane fade show {{ $tab == 'factFinder' || !$tab ? 'active' : '' }}" id="factFinder">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4">
                <h3 class="mb-3">
                    {{ $visa_information ? $visa_information->country->country : 'Country' }} –
                    General Information</h3>
                @if ($visa_information)
                    <p>
                        {{ $visa_information ? $visa_information->description : 'General information about the country will be displayed here.' }}
                    </p>
                @else
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle me-2"></i>
                        For information on the searched country, please get in touch with us on
                        <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a> and
                        please mention your location.
                    </div>
                @endif
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
                    <ul class="nav nav-pills mb-4 visa-category-tabs gap-2 flex-wrap" role="tablist">
                        @foreach ($visa_categories as $category)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                    id="category-tab-{{ $category->id }}" data-bs-toggle="tab"
                                    data-bs-target="#categoryTab{{ $category->id }}" type="button" role="tab"
                                    aria-controls="categoryTab{{ $category->id }}"
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                    <i class="fas fa-passport me-2"></i>
                                    {{ $category->name }}
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Category Tab Content -->
                    <div class="tab-content category-tab-content">
                        @foreach ($visa_categories as $category)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                id="categoryTab{{ $category->id }}" role="tabpanel"
                                aria-labelledby="category-tab-{{ $category->id }}">
                                <!-- Accordion for this category only -->
                                <div class="accordion" id="accordionCategory{{ $category->id }}">
                                    @foreach ($visa_details->where('visa_category_id', $category->id) as $detail)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading{{ $detail->id }}">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapse{{ $detail->id }}" aria-expanded="false"
                                                    aria-controls="collapse{{ $detail->id }}">
                                                    {{ $detail->city }}
                                                </button>
                                            </h2>

                                            <div id="collapse{{ $detail->id }}" class="accordion-collapse collapse"
                                                aria-labelledby="heading{{ $detail->id }}"
                                                data-bs-parent="#accordionCategory{{ $category->id }}">

                                                <div class="accordion-body visa-body">
                                                    <strong>Visa Fees:</strong>
                                                    {!! $detail->visa_fees !!}<br>
                                                    <strong>Logistic Fees:</strong>
                                                    {!! $detail->logistic_charges !!} <br>
                                                    <span>* These charges are levied by logistic
                                                        partners of Diplomatic mission for e.g :
                                                        VFS, BLS etc.</span><br>
                                                    <strong>Processing Time:</strong>
                                                    {!! $detail->processing_time !!}<br><br>

                                                    <h5 class="mt-4 mb-3">Mandatory Documents</h5>

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
                                                                <i class="fas fa-check" style="font-size: 0.9rem;"></i>
                                                            </div>

                                                            <div style="padding-top: 2px;">
                                                                <strong
                                                                    style="color: #003366; font-size: 0.95rem;">{{ $document->title }}:</strong>
                                                                <div
                                                                    style="color: #555; font-size: 0.9rem; margin-top: 2px;">
                                                                    @if ($document->description)
                                                                        @php
                                                                            $description = $document->description;
                                                                            $isUrl = filter_var(
                                                                                $description,
                                                                                FILTER_VALIDATE_URL,
                                                                            );
                                                                        @endphp

                                                                        @if ($isUrl && !empty($document->url))
                                                                            {!! e($description) !!}
                                                                        @elseif($isUrl)
                                                                            <a href="{{ $description }}"
                                                                                target="_blank">Click here to view</a>
                                                                        @else
                                                                            {!! e($description) !!}
                                                                        @endif
                                                                    @endif
                                                                    @if ($document->file)
                                                                        <div class="mt-1">
                                                                            <a href="{{ asset('storage/' . $document->file) }}"
                                                                                target="_blank"
                                                                                class="btn btn-sm btn-outline-primary"
                                                                                style="color: #003366; border-color: #003366;">
                                                                                <i class="fas fa-download"></i>
                                                                                Download Document
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                    <!-- Share Info -->
                                                    <div class="mt-3 share-info-container"
                                                        data-country="{{ json_encode($visa_information->country ?? []) }}"
                                                        data-visacategory="{{ $category->name }}"
                                                        data-city="{{ $detail->city }}">
                                                        <button class="btn btn-link p-0" data-bs-toggle="modal"
                                                            data-bs-target="#shareModal">
                                                            <i class="fas fa-share-alt me-2" style="color:#d32f2f;"></i>
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
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle me-2"></i>
                        For information on the searched country, please get in touch with us on
                        <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a> and
                        please mention your location.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- 3. Download Visa Forms -->
    <div class="tab-pane fade {{ $tab == 'visaForms' ? 'show active' : '' }}" id="visaForms">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4">
                <h4>{{ $visa_information ? $visa_information->country->country : 'Country' }} –
                    Download Visa Forms </h4>
                @if ($visa_forms->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach ($visa_forms as $form)
                            <li class="list-group-item d-flex justify-content-between">
                                {{ $form->city }} - {{ $form->visa_category->name }}
                                @if ($form->visa_form)
                                    <a href="{{ asset('storage/visa_forms/' . $form->visa_form) }}"
                                        class="btn btn-primary btn-sm" target="_blank">VISA APPLICATION FORM</a>
                                @endif
                                @if ($form->application_form_url)
                                    <a href="{{ $form->application_form_url }}" class="btn btn-success btn-sm"
                                        target="_blank">APPLICATION FORM LINK</a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle me-2"></i>
                        For information on the searched country, please get in touch with us on
                        <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a> and
                        please mention your location.
                    </div>
                @endif
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
                                        <div class="col m-auto">
                                            <h5 style="color: #003366; font-weight: 700; margin: 0;">
                                                {{ $rep->office_name }}
                                            </h5>
                                        </div>
                                    </div>

                                    <div class="row mt-3 mb-3">
                                        <div class="col">
                                            <p class="mb-0" style="color: #666; font-size: 0.95rem;">
                                                <i class="fas fa-map-pin"
                                                    style="color: #d32f2f; margin-right: 8px;"></i>
                                                {{ $rep->address }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
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

                                        @if ($rep->fax_number)
                                            <div class="col-auto">
                                                <span
                                                    style="color: #666; font-size: 0.9rem; display: flex; align-items: center; gap: 4px;">
                                                    <i class="fas fa-fax" style="color: #d32f2f;"></i>
                                                    {{ $rep->fax_number }}
                                                </span>
                                            </div>
                                        @endif

                                        @if ($rep->email)
                                            <div class="col-auto">
                                                <a href="mailto:{{ $rep->email }}" class="text-decoration-none"
                                                    style="color: #003366; font-size: 0.9rem; display: flex; align-items: center; gap: 4px;">
                                                    <i class="fas fa-envelope" style="color: #d32f2f;"></i>
                                                    {{ $rep->email }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    @php
                                        $opening_hours = json_decode($rep->opening_hours, true) ?? [];
                                    @endphp
                                    @if (count(array_filter($opening_hours)) > 0)
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <p class="mb-2"
                                                    style="font-size: 0.85rem; color: #666; font-weight: 600;">
                                                    <i class="fas fa-clock" style="color: #d32f2f;"></i> Hours : Open
                                                    · Closes
                                                </p>
                                            </div>
                                            <div class="col-12">
                                                <div
                                                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(110px, 1fr)); gap: 12px; font-size: 0.85rem;">
                                                    @foreach (['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'] as $day => $fullDay)
                                                        @if (!empty($opening_hours[$day]))
                                                            <div style="border-right: 1px solid #ddd;">
                                                                <div style="color: #003366; font-weight: 600;">
                                                                    {{ $fullDay }}</div>
                                                                <div style="color: #666;">{{ $opening_hours[$day] }}
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
                        <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a> and
                        please mention your location.
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
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle me-2"></i>
                        For information on the searched country, please get in touch with us on
                        <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a> and
                        please mention your location.
                    </div>
                @else
                    @if ($visa_information)
                        <h4 class="mb-3">{{ $visa_information->country->country }} – International Help Contacts
                        </h4>
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
                                        <div class="col m-auto">
                                            <h5 style="color: #003366; font-weight: 700; margin: 0;">
                                                {{ $logistic->office_name }}
                                            </h5>
                                        </div>
                                    </div>

                                    <div class="row mt-3 mb-3">
                                        <div class="col">
                                            <p class="mb-0" style="color: #666; font-size: 0.95rem;">
                                                <i class="fas fa-map-pin"
                                                    style="color: #d32f2f; margin-right: 8px;"></i>
                                                {{ $logistic->address }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
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
                                                    <a href="{{ $logistic->website }}" target="_blank"
                                                        class="text-decoration-none"
                                                        style="color: #003366; font-size: 0.9rem; display: flex; align-items: center; gap: 4px;">
                                                        <i class="fas fa-globe" style="color: #d32f2f;"></i>
                                                        {{ $logistic->website }}
                                                    </a>
                                                @endif
                                            </div>
                                        </div>

                                        @if ($logistic->fax_number)
                                            <div class="col-auto">
                                                <span
                                                    style="color: #666; font-size: 0.9rem; display: flex; align-items: center; gap: 4px;">
                                                    <i class="fas fa-fax" style="color: #d32f2f;"></i>
                                                    {{ $logistic->fax_number }}
                                                </span>
                                            </div>
                                        @endif

                                        @if ($logistic->email)
                                            <div class="col-auto">
                                                <a href="mailto:{{ $logistic->email }}" class="text-decoration-none"
                                                    style="color: #003366; font-size: 0.9rem; display: flex; align-items: center; gap: 4px;">
                                                    <i class="fas fa-envelope" style="color: #d32f2f;"></i>
                                                    {{ $logistic->email }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    @php
                                        $opening_hours = json_decode($logistic->opening_hours, true) ?? [];
                                    @endphp
                                    @if (count(array_filter($opening_hours)) > 0)
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <p class="mb-2"
                                                    style="font-size: 0.85rem; color: #666; font-weight: 600;">
                                                    <i class="fas fa-clock" style="color: #d32f2f;"></i> Hours : Open
                                                    · Closes
                                                </p>
                                            </div>
                                            <div class="col-12">
                                                <div
                                                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(110px, 1fr)); gap: 12px; font-size: 0.85rem;">
                                                    @foreach (['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'] as $day => $fullDay)
                                                        @if (!empty($opening_hours[$day]))
                                                            <div style="border-right: 1px solid #ddd;">
                                                                <div style="color: #003366; font-weight: 600;">
                                                                    {{ $fullDay }}</div>
                                                                <div style="color: #666;">{{ $opening_hours[$day] }}
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
                        <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a> and
                        please mention your location.
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
