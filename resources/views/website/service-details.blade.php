@extends('website.layout.app')
@section('title', config('app.name') . ' – ' . $service->title)
@section('content')
    <main>
        <!-- Header Start -->
        <div class="container-fluid bg-breadcrumb">
            <div class="container text-center py-5" style="max-width: 900px;">
                <h3 class="text-white display-3 mb-4 wow fadeInDown" data-wow-delay="0.1s">Service Details</h3>
                <ol class="breadcrumb justify-content-center text-white mb-0 wow fadeInDown" data-wow-delay="0.3s">
                    <li class="breadcrumb-item"><a href="{{ route('website.index') }}" class="text-white">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('website.services') }}" class="text-white">Services</a>
                    </li>
                    <li class="breadcrumb-item active text-secondary">{{ $service->title }}</li>
                </ol>
            </div>
        </div>
        <!-- Header End -->

        <!-- Service Details Start -->
        <div class="container-fluid py-5">
            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-8">
                        <!-- Service Detail -->
                        <article class="mb-5">
                            <div class="position-relative mb-4">
                                @if ($service->image)
                                    <img class="img-fluid w-100" src="{{ asset($service->image) }}"
                                        alt="{{ $service->title }}" style="height:  object-fit: cover;">
                                @else
                                    <img class="img-fluid w-100" src="{{ asset('website/assets/img/default-service.jpg') }}"
                                        alt="{{ $service->title }}" style="height: 400px; object-fit: cover;">
                                @endif
                            </div>
                            <div class="bg-light p-4 rounded">
                                <h1 class="display-6 mb-4">{{ $service->title }}</h1>
                                <p class="lead mb-4">
                                    {!! $service->description !!}
                                </p>
                            </div>
                        </article>
                    </div>
                    <div class="col-lg-4">
                        <!-- Enquiry Form Sidebar -->
                        <div class="bg-light p-4 rounded h-100">
                            <div class="sub-style mb-4">
                                <h5 class="sub-title text-primary pe-3">Get Consultation</h5>
                            </div>
                            <h4 class="mb-4">Enquire About {{ $service->title }}</h4>
                            <p class="mb-4">Ready to start your {{ strtolower($service->title) }} journey? Fill out the
                                form below for personalized guidance and a free initial consultation.</p>
                            <form name="form_action" action="{{ route('website.enquiry.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="service_id" value="{{ $service->id }}">
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
                                            value="{{ old('subject', 'Enquiry for ' . $service->title) }}"
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
                                            placeholder="Tell us more about your requirements..." id="message" name="message" style="height: 120px" required>{{ old('message') }}</textarea>
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
        <!-- Service Details End -->
    </main>
@endsection
