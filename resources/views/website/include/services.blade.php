  <div class="container-fluid service overflow-hidden ">
      <div class="container py-5">
          <div class="section-title text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">
              <div class="sub-style">
                  <h5 class="sub-title text-primary px-3">Visa Categories</h5>
              </div>
              <h1 class="display-5 mb-4">Enabling Your Immigration Journey Successfully</h1>
              <p class="mb-0">At {{ config('app.name') }}, we specialize in seamless visa processing for global
                  opportunities. Whether you're seeking employment, education, or residency abroad, our expert team
                  guides you through every step with personalized support and high success rates.</p>
          </div>
          <div class="row g-4">
              @forelse($services as $index => $service)
                  <div class="col-lg-6 col-xl-4 wow fadeInUp" data-wow-delay="{{ 0.1 + $index * 0.2 }}s">
                      <div class="service-item">
                          <div class="service-inner">
                              <div class="service-img">
                                  @if ($service->image)
                                      <img src="{{ asset($service->image) }}" class="img-fluid w-100 rounded"
                                          alt="{{ $service->name }} Image">
                                  @else
                                      <img src="{{ asset('website/assets/img/default-service.jpg') }}"
                                          class="img-fluid w-100 rounded" alt="{{ $service->title }} Image">
                                  @endif
                              </div>
                              <div class="service-title">
                                  <div class="service-title-name">
                                      <div class="bg-primary text-center rounded p-3 mx-5 mb-4">
                                          <a href="{{ route('website.service-details', $service->slug) }}"
                                              class="h4 text-white mb-0">{{ $service->title }}</a>
                                      </div>
                                      <a class="btn bg-light text-secondary rounded-pill py-3 px-5 mb-4"
                                          href="{{ route('website.service-details', $service->slug) }}">Explore
                                          More</a>
                                  </div>
                                  <div class="service-content pb-4">
                                      <a href="{{ route('website.service-details', $service->slug) }}">
                                          <h4 class="text-white mb-4 py-3">{{ $service->name }}</h4>
                                      </a>
                                      <div class="px-4">
                                          <p class="mb-4 text-white">
                                              {{ $service->short_description ?? Str::limit(strip_tags($service->description), 150) }}
                                          </p>
                                          <a class="btn btn-primary border-secondary rounded-pill text-white py-3 px-5"
                                              href="{{ route('website.service-details', $service->slug) }}">Explore
                                              More</a>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              @empty
                  <div class="col-12 text-center">
                      <p>No services available at the moment.</p>
                  </div>
              @endforelse
              <div class="col-12 text-center">
                  <a class="btn btn-primary border-secondary rounded-pill py-3 px-5 wow fadeInUp" data-wow-delay="0.1s"
                      href="{{ route('website.services') }}">View All Services</a>
              </div>
          </div>
      </div>
  </div>
