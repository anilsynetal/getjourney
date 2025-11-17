@extends('website.layout.app')
@section('title', config('app.name') . ' – Start Your Journey to Wellness')
@section('content')
    <main>
        <!-- Header Start -->
        <div class="container-fluid bg-breadcrumb">
            <div class="container text-center py-5" style="max-width: 900px;">
                <h3 class="text-white display-3 mb-4 wow fadeInDown" data-wow-delay="0.1s">Blog Details</h3>
                <ol class="breadcrumb justify-content-center text-white mb-0 wow fadeInDown" data-wow-delay="0.3s">
                    <li class="breadcrumb-item"><a href="{{ route('website.index') }}" class="text-white">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('website.blogs') }}" class="text-white">Blogs</a></li>
                    <li class="breadcrumb-item active text-secondary">{{ $blog->title }}</li>
                </ol>
            </div>
        </div>
        <!-- Header End -->

        <!-- Blog Details Start -->
        <div class="container-fluid py-5">
            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-8">
                        <!-- Blog Detail -->
                        <article class="mb-5">
                            <div class="position-relative mb-4">
                                <img class="img-fluid w-100" src="{{ asset($blog->image) }}" alt="{{ $blog->title }}"
                                    style="height: 400px; object-fit: cover;">
                                <div
                                    class="position-absolute top-0 start-0 bg-primary text-white px-3 py-2 mt-3 ms-3 rounded">
                                    <small>{{ $blog->blog_category->name }}</small>
                                </div>
                            </div>
                            <div class="bg-light p-4 rounded">
                                <h1 class="display-6 mb-4">{{ $blog->title }}</h1>
                                <p class="lead mb-4">
                                    {!! $blog->description !!}
                                </p>
                            </div>
                        </article>
                    </div>
                    <div class="col-lg-4">
                        <!-- Related Posts Sidebar -->
                        <div class="bg-light p-4 rounded mb-4">
                            <h4 class="mb-3">Related Posts</h4>
                            @foreach ($related_blogs as $related_blog)
                                <div class="d-flex mb-3">
                                    <img class="img-fluid rounded me-3" src="{{ asset($related_blog->image) }}"
                                        alt="{{ $related_blog->title }}"
                                        style="width: 80px; height: 80px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><a
                                                href="{{ route('website.blog-details', $related_blog->slug) }}">{{ $related_blog->title }}</a>
                                        </h6>
                                        <small class="text-muted">{{ $related_blog->created_at->format('M d, Y') }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Newsletter Signup -->
                        <div class="bg-light p-4 rounded">
                            <h4 class="mb-3">Subscribe to Newsletter</h4>
                            <p class="mb-3">Get the latest travel tips and updates!</p>
                            <form name="form_action" action="{{ route('website.newsletter.subscribe') }}" method="POST">
                                @csrf
                                <div class="input-group">

                                    <input class="form-control" type="email" placeholder="Enter your email" name="email"
                                        required />
                                    <button type="submit" class="btn btn-primary">Subscribe</button>
                                </div>
                            </form>
                            <div class="subscription-response mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Blog Details End -->
    </main>
@endsection
