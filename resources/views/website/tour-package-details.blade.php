@extends('website.layout.app')
@section('title', config('app.name') . ' – ' . $tour_package->name)
@section('content')
    <main>
        <!-- Header Start -->
        <div class="container-fluid bg-breadcrumb">
            <div class="container text-center py-5" style="max-width: 900px;">
                <h3 class="text-white display-3 mb-4 wow fadeInDown" data-wow-delay="0.1s">Tour Package Details</h3>
                <ol class="breadcrumb justify-content-center text-white mb-0 wow fadeInDown" data-wow-delay="0.3s">
                    <li class="breadcrumb-item"><a href="{{ route('website.index') }}" class="text-white">Home</a></li>
                    <li class="breadcrumb-item active text-secondary">{{ $tour_package->name }}</li>
                </ol>
            </div>
        </div>
        <!-- Header End -->

        <!-- Tour Package Details Start -->
        <div class="container-fluid py-5">
            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-8">
                        <!-- Tour Package Detail -->
                        <article class="mb-5">
                            <div class="position-relative mb-4">
                                @if ($tour_package->image)
                                    <img class="img-fluid w-100" src="{{ asset($tour_package->image) }}"
                                        alt="{{ $tour_package->name }}" style="height: 400px; object-fit: cover;">
                                @else
                                    <img class="img-fluid w-100" src="{{ asset('website/assets/img/default-tour.jpg') }}"
                                        alt="{{ $tour_package->name }}" style="height: 400px; object-fit: cover;">
                                @endif
                            </div>
                            <div class="bg-light p-4 rounded">
                                <h1 class="display-6 mb-4">{{ $tour_package->name }}</h1>
                                @if ($tour_package->duration_days)
                                    <div class="mb-3">
                                        <h5 class="text-primary">{{ $tour_package->duration_days }} Days /
                                            {{ $tour_package->duration_days - 1 }} Nights</h5>
                                    </div>
                                @endif
                                @if ($tour_package->highlights)
                                    <div class="mb-4">
                                        <h5>Highlights</h5>
                                        <p>{!! $tour_package->highlights !!}</p>
                                    </div>
                                @endif
                                @if ($tour_package->short_description)
                                    <div class="mb-4">
                                        <h5>Overview</h5>
                                        <p>{{ $tour_package->short_description }}</p>
                                    </div>
                                @endif
                                @if ($tour_package->description)
                                    <div class="mb-4">
                                        <h5>Description</h5>
                                        <p class="lead">
                                            {!! $tour_package->description !!}
                                        </p>
                                    </div>
                                @endif
                                @if ($tour_package->itinerary)
                                    <div class="mb-4">
                                        <h5>Itinerary</h5>
                                        <div>
                                            {!! $tour_package->itinerary !!}
                                        </div>
                                    </div>
                                @endif
                                @if ($tour_package->inclusions)
                                    <div class="mb-4">
                                        <h5>Inclusions</h5>
                                        <div>
                                            {!! $tour_package->inclusions !!}
                                        </div>
                                    </div>
                                @endif
                                @if ($tour_package->exclusions)
                                    <div class="mb-4">
                                        <h5>Exclusions</h5>
                                        <div>
                                            {!! $tour_package->exclusions !!}
                                        </div>
                                    </div>
                                @endif
                                @if ($tour_package->pricing)
                                    <div class="mb-4">
                                        <h5>Travel Dates & Prices</h5>
                                        <div>
                                            {!! $tour_package->pricing !!}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </article>
                    </div>
                    <div class="col-lg-4">
                        <!-- Enquiry Form Sidebar -->
                        <div class="bg-light p-4 rounded h-100">
                            <div class="sub-style mb-4">
                                <h5 class="sub-title text-primary pe-3">Book This Package</h5>
                            </div>
                            <h4 class="mb-4">Enquire About {{ $tour_package->name }}</h4>
                            <p class="mb-4">Ready to embark on your {{ strtolower($tour_package->name) }} adventure? Fill
                                out the
                                form below for availability, customized itineraries, and a free consultation with our travel
                                experts.</p>
                            <form name="form_action" action="{{ route('website.enquiry.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="tour_package_id" value="{{ $tour_package->id }}">
                                <div class="mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}"
                                            placeholder="Your Name" required>
                                        <label for="name">Your Name</label>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-floating">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}"
                                            placeholder="Your Email" required>
                                        <label for="email">Your Email</label>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control @error('mobile') is-invalid @enderror"
                                            id="mobile" name="mobile" value="{{ old('mobile') }}" placeholder="Mobile"
                                            required>
                                        <label for="mobile">Your Mobile</label>
                                        @error('mobile')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                            id="subject" name="subject"
                                            value="{{ old('subject', 'Enquiry for ' . $tour_package->name) }}"
                                            placeholder="Subject" required>
                                        <label for="subject">Subject</label>
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-floating">
                                        <textarea class="form-control @error('message') is-invalid @enderror"
                                            placeholder="Tell us more about your travel dates, group size, or special requirements..." id="message"
                                            name="message" style="height: 120px" required>{{ old('message') }}</textarea>
                                        <label for="message">Message</label>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary py-3">Send Enquiry</button>
                                </div>
                                <div class="response mt-2"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tour Package Details End -->
    </main>
@endsection
