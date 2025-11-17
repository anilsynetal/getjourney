<div class="container-fluid testimonial overflow-hidden pb-5">
    <div class="container py-5">
        <div class="section-title text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">
            <div class="sub-style">
                <h5 class="sub-title text-primary px-3">CLIENT TESTIMONIALS</h5>
            </div>
            <h1 class="display-5 mb-4">What Our Happy Travelers Say About Us</h1>
            <p class="mb-0">Discover the experiences of our valued clients who have traveled with
                {{ config('app.name') }}.
                Their stories and feedback inspire us to continue delivering exceptional travel services and creating
                unforgettable memories around the world.</p>
        </div>
        @if (isset($testimonials) && count($testimonials) > 0)
            <div class="owl-carousel testimonial-carousel wow zoomInDown" data-wow-delay="0.2s">
                @foreach ($testimonials as $testimonial)
                    <div class="testimonial-item">
                        <div class="testimonial-content p-4 mb-5">
                            <div class="d-flex align-items-start mb-3">
                                <i class="fas fa-quote-left text-primary fs-3 me-3 opacity-50"></i>
                                <p class="fs-5 mb-0 flex-grow-1">{{ $testimonial->description }}</p>
                            </div>
                            <div class="d-flex justify-content-end">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $testimonial->rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-muted"></i>
                                    @endif
                                @endfor
                                <span class="ms-2 text-muted small">({{ $testimonial->rating }}/5)</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle me-4 overflow-hidden" style="width: 100px; height: 100px;">
                                @if ($testimonial->image)
                                    <img class="img-fluid w-100 h-100" src="{{ asset($testimonial->image) }}"
                                        alt="{{ $testimonial->name }}" style="object-fit: cover;">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                        <i class="fas fa-user text-secondary fs-2"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="my-auto">
                                <h5 class="text-primary mb-1">{{ $testimonial->name }}</h5>
                                @if ($testimonial->designation)
                                    <p class="mb-0 text-muted">{{ $testimonial->designation }}</p>
                                @endif
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ $testimonial->created_at->format('M Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="alert alert-info border-0 rounded-3 py-4">
                        <i class="fas fa-info-circle fs-2 text-primary mb-3"></i>
                        <h5 class="text-primary mb-2">No Testimonials Yet</h5>
                        <p class="mb-0 text-muted">
                            We're working hard to gather feedback from our valued clients.
                            Check back soon to see what our happy travelers have to say about their experiences with us!
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
