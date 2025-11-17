@extends('website.layout.app')
@section('title', config('app.name') . ' – ' . $tour->name)
@section('content')
    <main>
        <!-- Header Start -->
        <div class="container-fluid bg-breadcrumb">
            <div class="container text-center py-5" style="max-width: 900px;">
                <h3 class="text-white display-3 mb-4 wow fadeInDown" data-wow-delay="0.1s">Tour Details</h3>
                <ol class="breadcrumb justify-content-center text-white mb-0 wow fadeInDown" data-wow-delay="0.3s">
                    <li class="breadcrumb-item"><a href="{{ route('website.index') }}" class="text-white">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('website.tours') }}" class="text-white">Tours</a></li>
                    <li class="breadcrumb-item active text-secondary">{{ $tour->name }}</li>
                </ol>
            </div>
        </div>
        <!-- Header End -->

        <!-- Tour Details Start -->
        <div class="container-fluid py-5">
            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-8">
                        <!-- Tour Detail -->
                        <article class="mb-5">
                            <div class="position-relative mb-4">
                                @if ($tour->image)
                                    <img class="img-fluid w-100" src="{{ asset($tour->image) }}" alt="{{ $tour->name }}"
                                        style="height: 400px; object-fit: cover;">
                                @else
                                    <img class="img-fluid w-100" src="{{ asset('website/assets/img/default-tour.jpg') }}"
                                        alt="{{ $tour->name }}" style="height: 400px; object-fit: cover;">
                                @endif
                                <div class="position-absolute top-0 start-0 m-3">
                                    @if ($tour->country)
                                        <span class="badge bg-primary px-3 py-2 rounded-pill me-2">
                                            {{ $tour->country->country }}
                                        </span>
                                    @endif
                                    @if ($tour->duration_days)
                                        <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                            {{ $tour->duration_days }} Days
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="bg-light p-4 rounded">
                                <h1 class="display-6 mb-4">{{ $tour->name }}</h1>
                                @if ($tour->duration_days)
                                    <div class="mb-3">
                                        <h5 class="text-primary">{{ $tour->duration_days }} Days /
                                            {{ $tour->duration_days - 1 }} Nights</h5>
                                    </div>
                                @endif
                                @if ($tour->highlights)
                                    <div class="mb-4">
                                        <h4 class="mb-3">Highlights</h4>
                                        <p>{!! $tour->highlights !!}</p>
                                    </div>
                                @endif
                                <div class="mb-4">
                                    <h5>Description</h5>
                                    <p class="lead">
                                        {!! $tour->description !!}
                                    </p>
                                </div>

                                @if ($tour->itinerary)
                                    <div class="mb-4">
                                        <h5>Itinerary</h5>
                                        <div class="itinerary-content">
                                            {!! $tour->itinerary !!}
                                        </div>
                                    </div>
                                @endif

                                @if ($tour->inclusions)
                                    <div class="mb-4">
                                        <h5>Inclusions</h5>
                                        <div class="inclusions-content">
                                            {!! $tour->inclusions !!}
                                        </div>
                                    </div>
                                @endif

                                @if ($tour->exclusions)
                                    <div class="mb-4">
                                        <h5>Exclusions</h5>
                                        <div class="exclusions-content">
                                            {!! $tour->exclusions !!}
                                        </div>
                                    </div>
                                @endif

                                @if ($tour->pricing)
                                    <div class="mb-4">
                                        <h5>Pricing</h5>
                                        <div class="pricing-content">
                                            {!! $tour->pricing !!}
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
                                <h5 class="sub-title text-primary pe-3">Book This Tour</h5>
                            </div>
                            <h4 class="mb-4">Enquire About {{ $tour->name }}</h4>
                            <p class="mb-4">Ready to embark on your {{ strtolower($tour->name) }} adventure? Fill out the
                                form below for availability, customized itineraries, and a free consultation with our travel
                                experts.</p>
                            <form name="form_action" action="{{ route('website.enquiry.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="tour_package_id" value="{{ $tour->id }}">
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
                                            value="{{ old('subject', 'Enquiry for ' . $tour->name) }}"
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
        <!-- Tour Details End -->
    </main>
@endsection
