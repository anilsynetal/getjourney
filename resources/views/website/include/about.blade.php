      <div class="container-fluid py-5">
          <div class="container py-5">
              <div class="row g-5">
                  <div class="col-xl-5 wow fadeInLeft" data-wow-delay="0.1s">
                      <div class="bg-light rounded">
                          <img src="{{ asset($about->image1) }}" class="img-fluid w-100" style="margin-bottom: -7px;"
                              alt="Image">
                          <img src="{{ asset($about->image2) }}"
                              class="img-fluid w-100 border-bottom border-5 border-primary"
                              style="border-top-right-radius: 300px; border-top-left-radius: 300px;" alt="Image">
                      </div>
                  </div>
                  <div class="col-xl-7 wow fadeInRight" data-wow-delay="0.3s">
                      <h5 class="sub-title pe-3">About the company</h5>
                      <h1 class="display-5 mb-4">{{ $about->title }}</h1>
                      <p class="mb-4">{{ $about->description }}</p>
                      <div class="row gy-4 align-items-center">
                          <div class="col-12 col-sm-6 d-flex align-items-center">
                              <i class="fas fa-map-marked-alt fa-3x text-secondary"></i>
                              <h5 class="ms-4">Curated Travel Itineraries</h5>
                          </div>
                          <div class="col-12 col-sm-6 d-flex align-items-center">
                              <i class="fas fa-plane fa-3x text-secondary"></i>
                              <h5 class="ms-4">Seamless Booking Assistance</h5>
                          </div>
                          <div class="col-4 col-md-3">
                              <div class="bg-light text-center rounded p-3">
                                  <div class="mb-2">
                                      <i class="fas fa-ticket-alt fa-4x text-primary"></i>
                                  </div>
                                  <h1 class="display-5 fw-bold mb-2">
                                      @php
                                          $startYear = $about->established_year ?? date('Y');
                                          $currentYear = date('Y');
                                          $yearsOfExperience = $currentYear - $startYear;
                                          echo $yearsOfExperience;
                                      @endphp

                                  </h1>
                                  <p class="text-muted mb-0">Years of Experience</p>
                              </div>
                          </div>
                          <div class="col-8 col-md-9">
                              <div class="mb-5">
                                  <p class="text-primary h6 mb-3"><i class="fa fa-check-circle text-secondary me-2"></i>
                                      {{ $about->highlighted_text1 }}</p>
                                  <p class="text-primary h6 mb-3"><i class="fa fa-check-circle text-secondary me-2"></i>
                                      {{ $about->highlighted_text2 }}</p>
                                  <p class="text-primary h6 mb-3"><i class="fa fa-check-circle text-secondary me-2"></i>
                                      {{ $about->highlighted_text3 }}</p>
                              </div>
                              <div class="d-flex flex-wrap">
                                  <div id="phone-tada" class="d-flex align-items-center justify-content-center me-4">
                                      <a href="" class="position-relative wow tada" data-wow-delay=".9s">
                                          <i class="fa fa-phone-alt text-primary fa-3x"></i>
                                          <div class="position-absolute" style="top: 0; left: 25px;">
                                              <span><i class="fa fa-comment-dots text-secondary"></i></span>
                                          </div>
                                      </a>
                                  </div>
                                  <div class="d-flex flex-column justify-content-center">
                                      <span class="text-primary">Have any questions?</span>
                                      <span class="text-secondary fw-bold fs-5" style="letter-spacing: 2px;">Free:
                                          +91-{{ $contact->phone }}</span>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
