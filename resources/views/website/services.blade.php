@extends('website.layout.app')
@section('title', config('app.name') . ' – Our Immigration & Visa Services')
@section('content')
    <main>
        <!-- Header Start -->
        <div class="container-fluid bg-breadcrumb">
            <div class="container text-center py-5" style="max-width: 900px;">
                <h3 class="text-white display-3 mb-4 wow fadeInDown" data-wow-delay="0.1s">Our Services</h3>
                <ol class="breadcrumb justify-content-center text-white mb-0 wow fadeInDown" data-wow-delay="0.3s">
                    <li class="breadcrumb-item"><a href="{{ route('website.index') }}" class="text-white">Home</a></li>
                    <li class="breadcrumb-item active text-secondary">Services</li>
                </ol>
            </div>
        </div>
        <!-- Header End -->

        <!-- Services Start -->
        <div class="container-fluid service overflow-hidden py-5">
            <div class="container py-5">
                <div class="section-title text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="sub-style">
                        <h5 class="sub-title text-primary px-3">Visa Categories</h5>
                    </div>
                    <h1 class="display-5 mb-4">Enabling Your Immigration Journey Successfully</h1>
                    <p class="mb-0">At {{ config('app.name') }}, we specialize in seamless visa processing for global
                        opportunities. Whether you're seeking employment, education, or residency abroad, our expert team
                        guides you through every step with personalized support and high success rates.</p>
                </div>
                <div class="row g-4">
                    @forelse($services as $index => $service)
                        <div class="col-lg-6 col-xl-4 wow fadeInUp" data-wow-delay="{{ 0.1 + $index * 0.2 }}s">
                            <div class="service-item">
                                <div class="service-inner">
                                    <div class="service-img">
                                        @if ($service->image)
                                            <img src="{{ asset($service->image) }}" class="img-fluid w-100 rounded"
                                                alt="{{ $service->name }} Image">
                                        @else
                                            <img src="{{ asset('website/assets/img/default-service.jpg') }}"
                                                class="img-fluid w-100 rounded" alt="{{ $service->title }} Image">
                                        @endif
                                    </div>
                                    <div class="service-title">
                                        <div class="service-title-name">
                                            <div class="bg-primary text-center rounded p-3 mx-5 mb-4">
                                                <a href="{{ route('website.service-details', $service->slug) }}"
                                                    class="h4 text-white mb-0">{{ $service->title }}</a>
                                            </div>
                                            <a class="btn bg-light text-secondary rounded-pill py-3 px-5 mb-4"
                                                href="{{ route('website.service-details', $service->slug) }}">Explore
                                                More</a>
                                        </div>
                                        <div class="service-content pb-4">
                                            <a href="{{ route('website.service-details', $service->slug) }}">
                                                <h4 class="text-white mb-4 py-3">{{ $service->name }}</h4>
                                            </a>
                                            <div class="px-4">
                                                <p class="mb-4 text-white">
                                                    {{ $service->short_description ?? Str::limit(strip_tags($service->description), 150) }}
                                                </p>
                                                <a class="btn btn-primary border-secondary rounded-pill text-white py-3 px-5"
                                                    href="{{ route('website.service-details', $service->slug) }}">Explore
                                                    More</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p>No services available at the moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <!-- Services End -->

        <!-- Tours We Offer Start -->
        <div class="container-fluid tours overflow-hidden py-5">
            <div class="container py-5">
                <div class="section-title text-center wow fadeInUp" data-wow-delay="0.1s" style="margin-bottom: 70px;">
                    <div class="sub-style">
                        <h5 class="sub-title text-primary px-3">TOUR PACKAGES WE OFFER</h5>
                    </div>
                    <h1 class="display-5 mb-4">Discover Amazing Journeys with Our Custom Tour Packages</h1>
                    <p class="mb-0">Embark on unforgettable adventures with {{ config('app.name') }}. From exotic beach
                        escapes to cultural explorations, our custom-designed tour packages ensure hassle-free travel and
                        lifelong
                        memories tailored just for you.</p>
                </div>
                <div class="row g-4">
                    @forelse($tour_packages as $index => $tour_package)
                        <div class="col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="{{ 0.1 + $index * 0.2 }}s">
                            <div class="tour-item">
                                <div class="tour-inner">
                                    <div class="tour-img rounded">
                                        @if ($tour_package->image)
                                            <img src="{{ asset($tour_package->image) }}" class="img-fluid w-100"
                                                alt="{{ $tour_package->name }} Image">
                                        @else
                                            <img src="{{ asset('website/assets/img/default-tour.jpg') }}"
                                                class="img-fluid w-100" alt="{{ $tour_package->name }} Image">
                                        @endif
                                        <div class="position-absolute top-0 start-0 m-3">
                                            <span class="badge bg-info px-3 py-2 rounded-pill">
                                                Package
                                            </span>
                                        </div>
                                        @if ($tour_package->duration_days)
                                            <div class="position-absolute top-0 end-0 m-3">
                                                <span class="badge bg-success px-3 py-2 rounded-pill">
                                                    <i
                                                        class="fas fa-calendar-alt me-1"></i>{{ $tour_package->duration_days }}
                                                    Days
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="tour-content bg-secondary rounded-bottom p-4">
                                        <a href="{{ route('website.tour-package-details', $tour_package->slug) }}">
                                            <h4 class="text-white mb-3">{{ $tour_package->name }}</h4>
                                        </a>
                                        <p class="text-white mb-3">
                                            {{ Str::limit(strip_tags($tour_package->highlights ?? $tour_package->description), 80) }}
                                        </p>
                                        <a class="btn btn-primary rounded-pill text-white py-2 px-4"
                                            href="{{ route('website.tour-package-details', $tour_package->slug) }}">View
                                            Details <i class="fa fa-arrow-right ms-2"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p>No tour packages available at the moment.</p>
                        </div>
                    @endforelse
                    <div class="col-12 text-center">
                        <a class="btn btn-primary border-secondary rounded-pill py-3 px-5 wow fadeInUp"
                            data-wow-delay="0.1s" href="{{ route('website.tours') }}">View All Tour Packages</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tours We Offer End -->
    </main>
@endsection
