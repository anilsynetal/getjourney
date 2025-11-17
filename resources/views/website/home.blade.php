@extends('website.layout.app')
@section('title', config('app.name') . ' – Start Your Journey to Wellness')
@section('content')
    <main>

        <!-- Carousel Start -->
        @if (isset($sliders) && count($sliders) > 0)
            <div class="carousel-header">
                <div id="carouselId" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                    @if (count($sliders) > 1)
                        <ol class="carousel-indicators">
                            @foreach ($sliders as $index => $slider)
                                <li data-bs-target="#carouselId" data-bs-slide-to="{{ $index }}"
                                    class="{{ $index == 0 ? 'active' : '' }}"></li>
                            @endforeach
                        </ol>
                    @endif

                    <div class="carousel-inner" role="listbox">
                        @foreach ($sliders as $index => $slider)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                @if ($slider->image)
                                    <img src="{{ asset($slider->image) }}" class="img-fluid w-100"
                                        alt="{{ $slider->title }}" style="height: 100vh; object-fit: cover;">
                                @else
                                    <img src="{{ asset('website/assets/img/carousel-1.jpg') }}" class="img-fluid w-100"
                                        alt="{{ $slider->title }}" style="height: 100vh; object-fit: cover;">
                                @endif

                                <div class="carousel-caption">
                                    <div class="text-center p-4" style="max-width: 900px;">
                                        <h4 class="text-white text-uppercase fw-bold mb-3 mb-md-4 wow fadeInUp"
                                            data-wow-delay="0.1s">{{ config('app.name') }}</h4>
                                        <h1 class="display-1 text-capitalize text-white mb-3 mb-md-4 wow fadeInUp"
                                            data-wow-delay="0.3s">{{ $slider->title }}</h1>
                                        <p class="text-white mb-4 mb-md-5 fs-5 wow fadeInUp" data-wow-delay="0.5s">
                                            {{ $slider->description }}
                                        </p>
                                        @if ($slider->button_text && $slider->button_link)
                                            <a class="btn btn-primary border-secondary rounded-pill text-white py-3 px-5 wow fadeInUp"
                                                data-wow-delay="0.7s" href="{{ $slider->button_link }}">
                                                {{ $slider->button_text }}
                                            </a>
                                        @elseif($slider->button_text)
                                            <a class="btn btn-primary border-secondary rounded-pill text-white py-3 px-5 wow fadeInUp"
                                                data-wow-delay="0.7s" href="{{ route('website.services') }}">
                                                {{ $slider->button_text }}
                                            </a>
                                        @else
                                            <a class="btn btn-primary border-secondary rounded-pill text-white py-3 px-5 wow fadeInUp"
                                                data-wow-delay="0.7s" href="{{ route('website.services') }}">
                                                Explore Our Services
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if (count($sliders) > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselId"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon bg-secondary wow fadeInLeft" data-wow-delay="0.2s"
                                aria-hidden="false"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselId"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon bg-secondary wow fadeInRight" data-wow-delay="0.2s"
                                aria-hidden="false"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>
            </div>
        @else
            <!-- Fallback slider when no slides are available -->
            <div class="carousel-header">
                <div class="carousel-item-fallback position-relative">
                    <img src="{{ asset('website/assets/img/carousel-1.jpg') }}" class="img-fluid w-100" alt="Welcome"
                        style="height: 100vh; object-fit: cover;">
                    <div class="carousel-caption">
                        <div class="text-center p-4" style="max-width: 900px;">
                            <h4 class="text-white text-uppercase fw-bold mb-3 mb-md-4 wow fadeInUp" data-wow-delay="0.1s">
                                {{ config('app.name') }}</h4>
                            <h1 class="display-1 text-capitalize text-white mb-3 mb-md-4 wow fadeInUp"
                                data-wow-delay="0.3s">Welcome to Your Journey</h1>
                            <p class="text-white mb-4 mb-md-5 fs-5 wow fadeInUp" data-wow-delay="0.5s">
                                Discover amazing travel experiences and create unforgettable memories with our expert travel
                                services.
                            </p>
                            <a class="btn btn-primary border-secondary rounded-pill text-white py-3 px-5 wow fadeInUp"
                                data-wow-delay="0.7s" href="{{ route('website.services') }}">
                                Explore Our Services
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- Carousel End -->


        <!-- Modal Search Start -->
        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h4 class="modal-title text-secondary mb-0" id="exampleModalLabel">Search by keyword</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex align-items-center">
                        <div class="input-group w-75 mx-auto d-flex">
                            <input type="search" class="form-control p-3" placeholder="keywords"
                                aria-describedby="search-icon-1">
                            <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Search End -->



        <!-- About Start -->
        @if (isset($about))
            @include('website.include.about', ['about' => $about])
        @endif
        <!-- About End -->


        <!-- Counter Facts Start -->
        @if (isset($counters) && count($counters) > 0)
            @include('website.include.counters', ['counters' => $counters])
        @endif
        <!-- Counter Facts End -->


        <!-- Services Start -->
        @if (isset($services) && count($services) > 0)
            @include('website.include.services', ['services' => $services])
        @endif
        <!-- Services End -->


        {{-- Tours Start --}}
        @if (isset($tour_packages) && count($tour_packages) > 0)
            @include('website.include.tour-packages', ['tour_packages' => $tour_packages])
        @endif
        {{-- Tours End --}}


        <!-- Testimonial Start -->
        @if (isset($testimonials) && count($testimonials) > 0)
            @include('website.include.testimonials', ['testimonials' => $testimonials])
        @endif
        <!-- Testimonial End -->

        <!-- Blogs Start -->
        @if (isset($blogs) && count($blogs) > 0)
            @include('website.include.blogs', ['blogs' => $blogs])
        @endif
        <!-- Blogs End -->
    </main>
@endsection
