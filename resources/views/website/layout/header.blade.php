 <!-- Topbar Start -->
 <div class="container-fluid bg-primary px-5 d-none d-lg-block">
     <div class="row gx-0 align-items-center">
         <div class="col-lg-5 text-center text-lg-start mb-lg-0">
             <div class="d-flex">
                 <a href="mailto:{{ $contact->email }}" class="text-white me-4"><i
                         class="fas fa-envelope text-secondary me-2"></i>{{ $contact->email }}</a>
                 <a href="tel:+91-{{ $contact->phone }}" class="text-white me-0"><i
                         class="fas fa-phone-alt text-secondary me-2"></i>+91-{{ $contact->phone }}</a>
             </div>
         </div>
         <div class="col-lg-3 row-cols-1 text-center mb-2 mb-lg-0">
             <div class="d-inline-flex align-items-center" style="height: 45px;">

             </div>
         </div>
         <div class="col-lg-4 text-center text-lg-end">
             <div class="d-inline-flex align-items-center" style="height: 45px;">
                 @if ($contact->twitter)
                     <a target="_blank" class="btn btn-sm btn-outline-light btn-square rounded-circle me-2"
                         href="{{ $contact->twitter }}"><i class="fab fa-twitter fw-normal text-secondary"></i></a>
                 @endif
                 @if ($contact->facebook)
                     <a target="_blank" class="btn btn-sm btn-outline-light btn-square rounded-circle me-2"
                         href="{{ $contact->facebook }}"><i class="fab fa-facebook-f fw-normal text-secondary"></i></a>
                 @endif
                 @if ($contact->linkedin)
                     <a target="_blank" class="btn btn-sm btn-outline-light btn-square rounded-circle me-2"
                         href="{{ $contact->linkedin }}"><i class="fab fa-linkedin-in fw-normal text-secondary"></i></a>
                 @endif
                 @if ($contact->instagram)
                     <a target="_blank" class="btn btn-sm btn-outline-light btn-square rounded-circle me-2"
                         href="{{ $contact->instagram }}"><i class="fab fa-instagram fw-normal text-secondary"></i></a>
                 @endif
                 @if ($contact->youtube)
                     <a target="_blank" class="btn btn-sm btn-outline-light btn-square rounded-circle me-2"
                         href="{{ $contact->youtube }}"><i class="fab fa-youtube fw-normal text-secondary"></i></a>
                 @endif
             </div>
         </div>
     </div>
 </div>
 <!-- Topbar End -->

 <!-- Navbar & Hero Start -->
 <div class="container-fluid nav-bar p-0">
     <nav class="navbar navbar-expand-lg navbar-light bg-white px-4 px-lg-5 py-3 py-lg-0">
         <a href="" class="navbar-brand p-0">
             <h1 class="display-5 text-secondary m-0"><img src="{{ asset('website/assets/img/brand-logo.png') }}"
                     class="img-fluid" alt=""></h1>
             <!-- <img src="img/logo.png" alt="Logo"> -->
         </a>
         <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
             <span class="fa fa-bars"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarCollapse">
             <div class="navbar-nav ms-auto py-0">
                 <a href="{{ route('website.index') }}" class="nav-item nav-link active">Home</a>
                 <a href="{{ route('website.about') }}" class="nav-item nav-link">About</a>
                 <a href="{{ route('website.services') }}" class="nav-item nav-link">Services</a>
                 <a href="{{ route('website.tours') }}" class="nav-item nav-link">Tours</a>
                 <a href="{{ route('website.visa-information') }}" class="nav-item nav-link">Visa Information</a>
                 <div class="nav-item dropdown">
                     <a href="{{ route('website.blogs') }}" class="nav-link"><span
                             class="dropdown-toggle">Blogs</span></a>
                     <div class="dropdown-menu m-0">
                         @foreach ($blog_categories as $category)
                             <a href="{{ route('website.blogs', ['category_id' => $category['id']]) }}"
                                 class="dropdown-item">{{ $category['name'] }}</a>
                         @endforeach
                     </div>
                 </div>
                 <a href="{{ route('website.contact') }}" class="nav-item nav-link">Contact</a>
             </div>
             {{-- <button class="btn btn-primary btn-md-square border-secondary mb-3 mb-md-3 mb-lg-0 me-3"
                 data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fas fa-search"></i></button>
             <a href="{{ route('website.quote') }}"
                 class="btn btn-primary border-secondary rounded-pill py-2 px-4 px-lg-3 mb-3 mb-md-3 mb-lg-0">Get A
                 Quote</a> --}}
         </div>
     </nav>
 </div>
 <!-- Navbar & Hero End -->
