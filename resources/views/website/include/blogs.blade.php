   <div class="container-fluid bg-light py-5">
       <div class="container py-5">
           <!-- Section Title -->
           <div class="section-title text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">
               <div class="sub-style mb-2">
                   <h5 class="sub-title text-primary d-inline-block px-3 py-1 rounded-pill bg-white shadow-sm">
                       CHECK OUR BLOGS
                   </h5>
               </div>
               <h1 class="display-5 fw-bold mb-3">Explore Our Latest Travel Insights and Stories</h1>
               <p class="text-muted mb-0">
                   Discover inspiring travel tips, destination guides, and adventure stories from
                   <span class="fw-semibold text-primary">Get Journey Tours & Travels</span>.
                   Stay updated with the best ways to plan your next unforgettable trip.
               </p>
           </div>

           <!-- Blog Cards -->
           <div class="row g-4">
               @if (isset($blogs) && count($blogs) > 0)
                   @foreach ($blogs as $blog)
                       <div class="col-sm-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                           <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 blog-card">
                               <div class="position-relative">
                                   <img src="{{ asset($blog->image) }}" class="img-fluid w-100"
                                       alt="{{ $blog->title }}" style="height: 220px; object-fit: cover;">
                                   <div class="position-absolute top-0 start-0 m-3">
                                       <span class="badge bg-primary px-3 py-2 rounded-pill">
                                           {{ $blog->blog_category->name }}
                                       </span>
                                   </div>
                               </div>

                               <div class="card-body bg-white p-4 d-flex flex-column justify-content-between">
                                   <div>
                                       <a href="{{ route('website.blog-details', $blog->slug) }}"
                                           class="text-decoration-none">
                                           <h5 class="text-dark fw-bold mb-3 hover-primary">
                                               {{ $blog->title }}
                                           </h5>
                                       </a>
                                       <p class="text-muted small mb-3">
                                           {{ \Illuminate\Support\Str::limit(strip_tags($blog->description), 90, '...') }}
                                       </p>
                                   </div>

                                   <a href="{{ route('website.blog-details', $blog->slug) }}"
                                       class="text-primary fw-semibold d-inline-flex align-items-center">
                                       Read More
                                       <i class="fa fa-arrow-right ms-2"></i>
                                   </a>
                               </div>
                           </div>
                       </div>
                   @endforeach
               @else
                   <div class="col-12">
                       <div class="alert alert-info text-center">
                           No blogs found.
                       </div>
                   </div>
               @endif
               <div class="col-12 text-center">
                   <a class="btn btn-primary border-secondary rounded-pill py-3 px-5 wow fadeInUp" data-wow-delay="0.1s"
                       href="{{ route('website.blogs') }}">View All Blogs</a>
               </div>
           </div>
       </div>
