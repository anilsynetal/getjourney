 <div class="container-fluid tours overflow-hidden py-5">
     <div class="container py-5">
         <div class="section-title text-center wow fadeInUp" data-wow-delay="0.1s" style="margin-bottom: 70px;">
             <div class="sub-style">
                 <h5 class="sub-title text-primary px-3">TOURS WE OFFER</h5>
             </div>
             <h1 class="display-5 mb-4">Discover Amazing Journeys with Our Curated Tours</h1>
             <p class="mb-0">Embark on unforgettable adventures with {{ config('app.name') }}. From exotic beach
                 escapes to cultural explorations, our handpicked tours ensure hassle-free travel and lifelong
                 memories.</p>
         </div>
         <div class="row g-4">
             @forelse($tours as $index => $tour)
                 <div class="col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="{{ 0.1 + $index * 0.2 }}s">
                     <div class="tour-item">
                         <div class="tour-inner">
                             <div class="tour-img rounded">
                                 @if ($tour->image)
                                     <img src="{{ asset($tour->image) }}" class="img-fluid w-100"
                                         alt="{{ $tour->name }} Image">
                                 @else
                                     <img src="{{ asset('website/assets/img/default-tour.jpg') }}"
                                         class="img-fluid w-100" alt="{{ $tour->name }} Image">
                                 @endif
                                 <div class="position-absolute top-0 start-0 m-3">
                                     <span class="badge bg-primary px-3 py-2 rounded-pill">
                                         {{ $tour->country->country }}
                                     </span>
                                 </div>
                             </div>
                             <div class="tour-content bg-secondary rounded-bottom p-4">
                                 <a href="{{ route('website.tour-details', $tour->slug) }}">
                                     <h4 class="text-white mb-3">{{ $tour->name }}</h4>
                                 </a>
                                 <p class="text-white mb-3">
                                     {{ $tour->short_description ?? Str::limit(strip_tags($tour->description), 80) }}
                                 </p>
                                 <a class="btn btn-primary rounded-pill text-white py-2 px-4"
                                     href="{{ route('website.tour-details', $tour->slug) }}">Book Now <i
                                         class="fa fa-arrow-right ms-2"></i></a>
                             </div>
                         </div>
                     </div>
                 </div>
             @empty
                 <div class="col-12 text-center">
                     <p>No tours available at the moment.</p>
                 </div>
             @endforelse
             <div class="col-12 text-center">
                 <a class="btn btn-primary border-secondary rounded-pill py-3 px-5 wow fadeInUp" data-wow-delay="0.1s"
                     href="{{ route('website.tours') }}">View All Tours</a>
             </div>
         </div>
     </div>
 </div>
