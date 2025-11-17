 <div class="container-fluid counter-facts py-5">
     <div class="container py-5">
         <div class="row g-4">
             @if (isset($counters) && count($counters) > 0)
                 @foreach ($counters as $counter)
                     <div class="col-12 col-sm-6 col-md-6 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                         <div class="counter">
                             <div class="counter-icon">
                                 <i class="{{ $counter['icon'] }}"></i>
                             </div>
                             <div class="counter-content">
                                 <h3>{{ $counter['title'] }}</h3>
                                 <div class="d-flex align-items-center justify-content-center">
                                     <h4 class="text-secondary mb-0" style="font-weight: 600; font-size: 25px;">
                                         {{ $counter['count'] }}</h4>
                                 </div>
                             </div>
                         </div>
                     </div>
                 @endforeach
             @endif
         </div>
     </div>
 </div>
