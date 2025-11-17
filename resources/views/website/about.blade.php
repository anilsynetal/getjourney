@extends('website.layout.app')
@section('title', config('app.name') . ' – Start Your Journey to Wellness')
@section('content')
    <main>
        <!-- Header Start -->
        <div class="container-fluid bg-breadcrumb">
            <div class="container text-center py-5" style="max-width: 900px;">
                <h3 class="text-white display-3 mb-4 wow fadeInDown" data-wow-delay="0.1s">About Us</h3>
                <ol class="breadcrumb justify-content-center text-white mb-0 wow fadeInDown" data-wow-delay="0.3s">
                    <li class="breadcrumb-item"><a href="{{ route('website.index') }}" class="text-white">Home</a></li>
                    <li class="breadcrumb-item active text-secondary">About</li>
                </ol>
            </div>
        </div>
        <!-- Header End -->

        <!-- About Start -->
        <div class="container-fluid overflow-hidden py-5">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-xl-5 wow fadeInLeft" data-wow-delay="0.1s">
                        <div class="bg-light rounded">
                            <img src="{{ asset($about->image1) }}" class="img-fluid w-100" style="margin-bottom: -7px;"
                                alt="Image">
                            <img src="{{ asset($about->image2) }}"
                                class="img-fluid w-100 border-bottom border-5 border-primary"
                                style="border-top-right-radius: 300px; border-top-left-radius: 300px;" alt="Image">
                        </div>
                    </div>
                    <div class="col-xl-7 wow fadeInRight" data-wow-delay="0.3s">
                        <h5 class="sub-title pe-3">About the company</h5>
                        <h1 class="display-5 mb-4">{{ $about->title }}</h1>
                        <p class="mb-4">{{ $about->description }}</p>
                        <div class="row gy-4 align-items-center">
                            <div class="col-12 col-sm-6 d-flex align-items-center">
                                <i class="fas fa-map-marked-alt fa-3x text-secondary"></i>
                                <h5 class="ms-4">Curated Travel Itineraries</h5>
                            </div>
                            <div class="col-12 col-sm-6 d-flex align-items-center">
                                <i class="fas fa-plane fa-3x text-secondary"></i>
                                <h5 class="ms-4">Seamless Booking Assistance</h5>
                            </div>
                            <div class="col-4 col-md-3">
                                <div class="bg-light text-center rounded p-3">
                                    <div class="mb-2">
                                        <i class="fas fa-ticket-alt fa-4x text-primary"></i>
                                    </div>
                                    <h1 class="display-5 fw-bold mb-2">
                                        @php
                                            $startYear = $about->established_year ?? date('Y');
                                            $currentYear = date('Y');
                                            $yearsOfExperience = $currentYear - $startYear;
                                            echo $yearsOfExperience;
                                        @endphp

                                    </h1>
                                    <p class="text-muted mb-0">Years of Experience</p>
                                </div>
                            </div>
                            <div class="col-8 col-md-9">
                                <div class="mb-5">
                                    <p class="text-primary h6 mb-3"><i class="fa fa-check-circle text-secondary me-2"></i>
                                        {{ $about->highlighted_text1 }}</p>
                                    <p class="text-primary h6 mb-3"><i class="fa fa-check-circle text-secondary me-2"></i>
                                        {{ $about->highlighted_text2 }}</p>
                                    <p class="text-primary h6 mb-3"><i class="fa fa-check-circle text-secondary me-2"></i>
                                        {{ $about->highlighted_text3 }}</p>
                                </div>
                                <div class="d-flex flex-wrap">
                                    <div id="phone-tada" class="d-flex align-items-center justify-content-center me-4">
                                        <a href="" class="position-relative wow tada" data-wow-delay=".9s">
                                            <i class="fa fa-phone-alt text-primary fa-3x"></i>
                                            <div class="position-absolute" style="top: 0; left: 25px;">
                                                <span><i class="fa fa-comment-dots text-secondary"></i></span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <span class="text-primary">Have any questions?</span>
                                        <span class="text-secondary fw-bold fs-5" style="letter-spacing: 2px;">Free:
                                            +91-{{ $contact->phone }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->

        <div class="container-fluid features overflow-hidden py-5">
            <div class="container">
                <div class="section-title text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="sub-style">
                        <h5 class="sub-title text-primary px-3">Why Choose Us</h5>
                    </div>
                    <h1 class="display-5 mb-4">Offer Tailor Made Services That Our Client Requires</h1>
                    <p class="mb-0">At Get Journey Tours & Travels, we pride ourselves on delivering exceptional,
                        personalized services designed to meet your unique travel needs, from dream vacations to adventure
                        escapes.</p>
                </div>
                <div class="row g-4 justify-content-center text-center">
                    @if ($why_choose_us && count($why_choose_us) > 0)
                        @foreach ($why_choose_us as $index => $benefit)
                            <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="{{ 0.1 + $index * 0.2 }}s">
                                <div class="feature-item text-center p-4">
                                    <div class="feature-icon p-3 mb-4">
                                        <i class="{{ $benefit->icon }} fa-4x text-primary"></i>
                                    </div>
                                    <div class="feature-content d-flex flex-column">
                                        <h5 class="mb-3">{{ $benefit->title }}</h5>
                                        <p class="mb-3">{{ $benefit->description }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="row justify-content-center">
                            <div class="col-lg-8 text-center">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Why Choose Us information will be available soon. Stay tuned!
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Our Teams Start -->
        <div class="container-fluid team overflow-hidden py-5"
            style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
            <div class="container py-5">
                <div class="section-title text-center wow fadeInUp" data-wow-delay="0.1s" style="margin-bottom: 70px;">
                    <div class="sub-style">
                        <h5 class="sub-title text-primary px-3">OUR TEAM</h5>
                    </div>
                    <h1 class="display-5 mb-4">Meet Our Expert Travel Professionals</h1>
                    <p class="mb-0">Our dedicated team of travel experts is committed to making your journey memorable
                        with
                        personalized service, insider knowledge, and unwavering attention to detail.</p>
                </div>

                @if ($teams && count($teams) > 0)
                    <div class="row g-4 justify-content-center">
                        @foreach ($teams as $index => $team)
                            <div class="col-lg-6 col-xl-3 mb-4 wow fadeInUp" data-wow-delay="{{ 0.1 + $index * 0.2 }}s">
                                <div class="team-item bg-white rounded shadow-sm overflow-hidden">
                                    <div class="team-img position-relative overflow-hidden">
                                        <img src="{{ asset($team->image ?? 'website/assets/img/default-avatar.jpg') }}"
                                            class="img-fluid w-100" alt="{{ $team->name }}"
                                            style="height: 300px; object-fit: cover;">
                                        <div
                                            class="team-overlay position-absolute w-100 h-100 d-flex align-items-center justify-content-center">
                                            <div class="team-social d-flex">
                                                @if ($team->linkedin)
                                                    <a href="{{ $team->linkedin }}" target="_blank"
                                                        class="btn btn-primary btn-sm rounded-circle mx-1">
                                                        <i class="fab fa-linkedin-in"></i>
                                                    </a>
                                                @endif
                                                @if ($team->email)
                                                    <a href="mailto:{{ $team->email }}"
                                                        class="btn btn-primary btn-sm rounded-circle mx-1">
                                                        <i class="fas fa-envelope"></i>
                                                    </a>
                                                @endif
                                                @if ($team->mobile)
                                                    <a href="tel:{{ $team->mobile }}"
                                                        class="btn btn-primary btn-sm rounded-circle mx-1">
                                                        <i class="fas fa-phone"></i>
                                                    </a>
                                                @endif
                                                @if ($team->linkedin)
                                                    <a href="{{ $team->linkedin }}" target="_blank"
                                                        class="btn btn-primary btn-sm rounded-circle mx-1">
                                                        <i class="fab fa-linkedin-in"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="team-content text-center p-4">
                                        <h5 class="mb-2">{{ $team->name }}</h5>
                                        <p class="text-primary mb-0">{{ $team->designation }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="row justify-content-center">
                        <div class="col-lg-8 text-center">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Our team information will be available soon. Stay tuned!
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- Our Teams End -->
    </main>
@endsection
