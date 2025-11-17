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
  <!-- Footer Start -->
  <div class="container-fluid footer py-3 wow fadeIn" data-wow-delay="0.2s">
      <div class="container py-5">
          <div class="row g-5">
              <div class="col-md-6 col-lg-6 col-xl-3">
                  <div class="footer-item d-flex flex-column">
                      <div class="d-flex align-items-center mb-3">
                          <img src="{{ asset('website/assets/img/brand-logo-light.png') }}"
                              alt="{{ $contact->app_name }}" class="me-3" style="height: 50px;">
                      </div>

                      <a href="javascript:void(0);"><i class="fa fa-map-marker-alt me-2"></i>{{ $contact->address }}</a>
                      <a href="mailto:{{ $contact->email }}"><i class="fas fa-envelope me-2"></i>
                          {{ $contact->email }}</a>
                      <a href="tel:+91-{{ $contact->phone }}"><i class="fas fa-phone me-2"></i>
                          +91-{{ $contact->phone }}</a>
                      <div class="d-flex align-items-center">
                          <i class="fas fa-share fa-2x text-secondary me-2"></i>
                          @if ($contact->facebook)
                              <a target="_blank" class="btn mx-1" href="{{ $contact->facebook }}"><i
                                      class="fab fa-facebook-f"></i></a>
                          @endif
                          @if ($contact->twitter)
                              <a target="_blank" class="btn mx-1" href="{{ $contact->twitter }}"><i
                                      class="fab fa-twitter"></i></a>
                          @endif
                          @if ($contact->instagram)
                              <a target="_blank" class="btn mx-1" href="{{ $contact->instagram }}"><i
                                      class="fab fa-instagram"></i></a>
                          @endif
                          @if ($contact->linkedin)
                              <a target="_blank" class="btn mx-1" href="{{ $contact->linkedin }}"><i
                                      class="fab fa-linkedin-in"></i></a>
                          @endif
                          @if ($contact->youtube)
                              <a target="_blank" class="btn mx-1" href="{{ $contact->youtube }}"><i
                                      class="fab fa-youtube"></i></a>
                          @endif
                      </div>
                  </div>
              </div>
              <div class="col-lg-6 col-xl-3">
                  <div class="footer-item d-flex flex-column">
                      <h4 class="text-secondary mb-4">Quick Links</h4>
                      <a href="{{ route('website.index') }}" class="footer-link mb-2">
                          <i class="fas fa-angle-right me-2"></i>Home
                      </a>
                      <a href="{{ route('website.about') }}" class="footer-link mb-2">
                          <i class="fas fa-angle-right me-2"></i>About Us
                      </a>
                      <a href="{{ route('website.tours') }}" class="footer-link mb-2">
                          <i class="fas fa-angle-right me-2"></i>Tours & Packages
                      </a>
                      <a href="{{ route('website.services') }}" class="footer-link mb-2">
                          <i class="fas fa-angle-right me-2"></i>Our Services
                      </a>
                      <a href="{{ route('website.contact') }}" class="footer-link mb-2">
                          <i class="fas fa-angle-right me-2"></i>Contact Us
                      </a>
                  </div>
              </div>
              <div class="col-lg-6 col-xl-3">
                  <div class="footer-item d-flex flex-column">
                      <h4 class="text-secondary mb-4">Travel Services</h4>
                      @if (!empty($footer_services) && $footer_services->count() > 0)
                          @foreach ($footer_services as $service)
                              <a href="{{ route('website.service-details', $service->slug) }}"
                                  class="footer-link mb-2">
                                  <i class="fas fa-angle-right me-2"></i>{{ $service->title }}
                              </a>
                          @endforeach
                      @else
                          <a href="{{ route('website.tours') }}" class="footer-link mb-2">
                              <i class="fas fa-angle-right me-2"></i>Tour Packages
                          </a>
                          <a href="{{ route('website.services') }}" class="footer-link mb-2">
                              <i class="fas fa-angle-right me-2"></i>Travel Planning
                          </a>
                          <a href="{{ route('website.services') }}" class="footer-link mb-2">
                              <i class="fas fa-angle-right me-2"></i>Adventure Tours
                          </a>
                          <a href="{{ route('website.services') }}" class="footer-link mb-2">
                              <i class="fas fa-angle-right me-2"></i>Cultural Tours
                          </a>
                          <a href="{{ route('website.services') }}" class="footer-link mb-2">
                              <i class="fas fa-angle-right me-2"></i>Group Tours
                          </a>
                          <a href="{{ route('website.services') }}" class="footer-link mb-2">
                              <i class="fas fa-angle-right me-2"></i>Hotel Bookings
                          </a>
                      @endif
                  </div>
              </div>
              <div class="col-md-6 col-lg-6 col-xl-3">
                  <div class="footer-item">
                      <h4 class="text-secondary mb-4">Newsletter</h4>
                      <p class="text-white mb-3">Subscribe to receive exclusive travel deals, destination guides, and
                          early access to our special tour packages.</p>
                      <div class="position-relative mx-auto rounded-pill">
                          <form name="form_action" action="{{ route('website.newsletter.subscribe') }}"
                              method="POST">
                              @csrf
                              <input class="form-control border-0 rounded-pill w-100 py-3 ps-4 pe-5" type="email"
                                  placeholder="Enter your email" name="email" required />
                              <button type="submit"
                                  class="btn btn-primary rounded-pill position-absolute top-0 end-0 py-2 mt-2 me-2">Subscribe</button>
                          </form>
                          <div class="subscription-response mt-2"></div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- Footer End -->


  <!-- Copyright Start -->
  <div class="container-fluid copyright py-4">
      <div class="container">
          <div class="row g-4 align-items-center">
              <div class="col-md-6 text-center text-md-start mb-md-0">
                  <span class="text-white"><a href="#" class="border-bottom text-white"><i
                              class="fas fa-copyright text-light me-2"></i>{{ config('app.name') }}</a>, All right
                      reserved.</span>
              </div>
              <div class="col-md-6 text-center text-md-end text-white">
                  Designed By <a class="border-bottom text-white" href="https://aparkitsolutions.com"
                      target="_blank">APARK IT Solutions</a>
              </div>
          </div>
      </div>
  </div>
  <!-- Copyright End -->
