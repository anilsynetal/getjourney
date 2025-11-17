@extends('website.layout.app')
@section('title', config('app.name') . ' – Start Your Journey to Wellness')
@section('content')
    <main>
        <!-- Header Start -->
        <div class="container-fluid bg-breadcrumb">
            <div class="container text-center py-5" style="max-width: 900px;">
                <h3 class="text-white display-3 mb-4 wow fadeInDown" data-wow-delay="0.1s">Contact Us</h3>
                <ol class="breadcrumb justify-content-center text-white mb-0 wow fadeInDown" data-wow-delay="0.3s">
                    <li class="breadcrumb-item"><a href="{{ route('website.index') }}" class="text-white">Home</a></li>
                    <li class="breadcrumb-item active text-secondary">Contact</li>
                </ol>
            </div>
        </div>
        <!-- Header End -->

        <!-- Contact Start -->
        <div class="container-fluid contact overflow-hidden py-5">
            <div class="container py-5">
                <div class="row g-5 mb-5">
                    <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.1s">
                        <div class="sub-style">
                            <h5 class="sub-title text-primary pe-3">Quick Contact</h5>
                        </div>
                        <h1 class="display-5 mb-4">Have Questions? Don't Hesitate to Contact Us</h1>
                        <p class="mb-5">We're here to help you plan your perfect wellness retreat. Whether you have
                            questions about our tours, need booking assistance, or just want to chat about your travel
                            dreams, our team is ready to assist you every step of the way.</p>
                        <div class="d-flex border-bottom mb-4 pb-4">
                            <i class="fas fa-map-marked-alt fa-4x text-primary bg-light p-3 rounded"></i>
                            <div class="ps-3">
                                <h5>Location</h5>
                                <p>{{ $contact->address }}</p>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-xl-6">
                                <div class="d-flex">
                                    <i class="fas fa-tty fa-3x text-primary"></i>
                                    <div class="ps-3">
                                        <h5 class="mb-3">Quick Contact</h5>
                                        <div class="mb-3">
                                            <h6 class="mb-0">Phone:</h6>
                                            <a href="tel:+91-{{ $contact->phone }}"
                                                class="fs-5 text-primary">+91-{{ $contact->phone }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 mt-5 mt-xl-5">
                                <div class="mb-3 mt-2">
                                    <h6 class="mb-0">Email:</h6>
                                    <a href="mailto:{{ $contact->email }}"
                                        class="fs-5 text-primary">{{ $contact->email }}</a>
                                </div>
                            </div>

                            <div class="col-xl-12 mt-5 mt-xl-0">
                                <div class="d-flex">
                                    <i class="fas fa-clone fa-3x text-primary"></i>
                                    <div class="ps-3">
                                        <h5 class="mb-3">Opening Hrs</h5>
                                        @php
                                            $days = [
                                                'sunday' => $contact->sunday ?? 'Closed',
                                                'monday' => $contact->monday ?? 'Closed',
                                                'tuesday' => $contact->tuesday ?? 'Closed',
                                                'wednesday' => $contact->wednesday ?? 'Closed',
                                                'thursday' => $contact->thursday ?? 'Closed',
                                                'friday' => $contact->friday ?? 'Closed',
                                                'saturday' => $contact->saturday ?? 'Closed',
                                            ];
                                        @endphp
                                        <div class="row g-2">
                                            @foreach ($days as $day => $hours)
                                                <div class="col-6">
                                                    <small class="d-block text-muted mb-1">{{ ucfirst($day) }}</small>
                                                    <a href="#"
                                                        class="text-primary fw-semibold d-block">{{ $hours }}</a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.3">
                        <div class="sub-style">
                            <h5 class="sub-title text-primary pe-3">Let’s Connect</h5>
                        </div>
                        <h1 class="display-5 mb-4">Send Your Message</h1>
                        <form name="form_action" action="{{ route('website.enquiry.store') }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-lg-12 col-xl-6">
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
                                <div class="col-lg-12 col-xl-6">
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
                                <div class="col-lg-12 col-xl-6">
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
                                <div class="col-lg-12 col-xl-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                            id="subject" name="subject" value="{{ old('subject') }}"
                                            placeholder="Subject" required>
                                        <label for="subject">Subject</label>
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control @error('message') is-invalid @enderror" placeholder="Leave a message here"
                                            id="message" name="message" style="height: 160px" required>{{ old('message') }}</textarea>
                                        <label for="message">Message</label>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100 py-3">Send Message</button>
                                </div>
                                <div class="response mt-2"></div>
                            </div>
                        </form>
                        <div class="d-flex align-items-center pt-3 mt-4">
                            <div class="me-4">
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                    style="width: 90px; height: 90px; border-radius: 10px;"><i
                                        class="fas fa-share fa-3x text-primary"></i></div>
                            </div>
                            <div class="d-flex">
                                @if ($contact->facebook)
                                    <a class="btn btn-secondary border-secondary me-2 p-0"
                                        href="{{ $contact->facebook }}">facebook <i
                                            class="fas fa-chevron-circle-right"></i></a>
                                @endif
                                @if ($contact->twitter)
                                    <a class="btn btn-secondary border-secondary mx-2 p-0"
                                        href="{{ $contact->twitter }}">twitter
                                        <i class="fas fa-chevron-circle-right"></i></a>
                                @endif
                                @if ($contact->instagram)
                                    <a class="btn btn-secondary border-secondary mx-2 p-0"
                                        href="{{ $contact->instagram }}">instagram <i
                                            class="fas fa-chevron-circle-right"></i></a>
                                @endif
                                @if ($contact->linkedin)
                                    <a class="btn btn-secondary border-secondary ms-2 p-0"
                                        href="{{ $contact->linkedin }}">linkedin <i
                                            class="fas fa-chevron-circle-right"></i></a>
                                @endif
                                @if ($contact->youtube)
                                    <a class="btn btn-secondary border-secondary ms-2 p-0"
                                        href="{{ $contact->youtube }}">youtube <i
                                            class="fas fa-chevron-circle-right"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Google Map Embed --}}
                <div class="col-12">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3680.2084419121206!2d75.88265387498305!3d22.720492627534302!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3962fd5e44dc60bf%3A0x2994cb959615bd10!2sGet%20Journey%20Tours%20%26%20Travels!5e0!3m2!1sen!2sin!4v1762350646509!5m2!1sen!2sin"
                        width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>

            </div>
        </div>
        <!-- Contact End -->
    </main>
@endsection
