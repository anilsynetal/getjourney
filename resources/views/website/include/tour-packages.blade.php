 <div class="container-fluid tours overflow-hidden py-5 bg-light">
     <div class="container py-5">
         <div class="section-title text-center wow fadeInUp" data-wow-delay="0.1s" style="margin-bottom: 70px;">
             <div class="sub-style">
                 <h5 class="sub-title text-primary px-3">TOURS WE OFFER</h5>
             </div>
             <h1 class="display-5 mb-4">Discover Amazing Journeys with Our Curated Tour Packages</h1>
             <p class="mb-0">Embark on unforgettable adventures with {{ config('app.name') }}. From exotic beach
                 escapes to cultural explorations, our custom-designed tour packages ensure hassle-free travel and
                 lifelong
                 memories tailored just for you.</p>
         </div>
         <div class="row g-4">
             @forelse($tour_packages as $index => $tour_package)
                 <div class="col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="{{ 0.1 + $index * 0.2 }}s">
                     <div class="tour-item h-100">
                         <div class="tour-inner h-100 d-flex flex-column">
                             <div class="tour-img rounded position-relative">
                                 @if ($tour_package->image)
                                     <img src="{{ asset($tour_package->image) }}" class="img-fluid w-100"
                                         alt="{{ $tour_package->name }} Image"
                                         style="height: 250px; object-fit: cover;">
                                 @else
                                     <img src="{{ asset('website/assets/img/default-tour.jpg') }}"
                                         class="img-fluid w-100" alt="{{ $tour_package->name }} Image"
                                         style="height: 250px; object-fit: cover;">
                                 @endif
                                 <div class="position-absolute top-0 start-0 m-3">
                                     @if ($tour_package->country)
                                         <span class="badge bg-primary px-3 py-2 rounded-pill me-1">
                                             <i class="fas fa-globe me-1"></i>{{ $tour_package->country->country }}
                                         </span>
                                     @endif
                                 </div>
                                 @if ($tour_package->duration_days)
                                     <div class="position-absolute top-0 end-0 m-3">
                                         <span class="badge bg-success px-3 py-2 rounded-pill">
                                             <i class="fas fa-calendar-alt me-1"></i>{{ $tour_package->duration_days }}
                                             Days
                                         </span>
                                     </div>
                                 @endif
                             </div>
                             <div class="tour-content bg-secondary rounded-bottom p-4 flex-grow-1 d-flex flex-column">
                                 <a href="{{ route('website.tour-package-details', $tour_package->slug) }}"
                                     class="text-decoration-none">
                                     <h4 class="text-white mb-3">{{ $tour_package->name }}</h4>
                                 </a>
                                 <p class="mb-3 flex-grow-1 text-white">
                                     {{ Str::limit(html_entity_decode(strip_tags($tour_package->description)), 100) }}
                                 </p>

                                 <div class="d-flex justify-content-between align-items-center mt-auto">
                                     <div class="tour-info text-white">
                                         @if ($tour_package->duration_days)
                                             <small><i class="fas fa-clock me-1"></i>{{ $tour_package->duration_days }}
                                                 Days</small>
                                         @endif

                                     </div>
                                     <a class="btn btn-primary rounded-pill text-white py-2 px-4"
                                         href="{{ route('website.tour-package-details', $tour_package->slug) }}">
                                         View Details <i class="fa fa-arrow-right ms-2"></i>
                                     </a>
                                 </div>
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
                 <a class="btn btn-primary border-secondary rounded-pill py-3 px-5 wow fadeInUp" data-wow-delay="0.1s"
                     href="{{ route('website.tours') }}">View All Tour Packages</a>
             </div>
         </div>
     </div>
 </div>
