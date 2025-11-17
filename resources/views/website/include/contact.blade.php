  <section class="cta-sb bg_cover p-r z-1 pt-40 pb-40"
      style="background-image: url({{ asset('website/assets/images/bg/cta-bg1.jpg') }});" id="contact-section">
      <div class="container">
          <div class="row align-items-center">
              <div class="col-xl-12">
                  <!--=== Section Content Box ===-->
                  <div class="section-content-box">
                      <div class="section-title text-white">
                          <h2 class="text-anm">Acting together quickly <br>
                              leads to faster outcomes</h2>
                      </div>
                  </div>
                  <div class="col-xl-12">
                      <!--=== Contact Form ===-->
                      <div class="contact-form mt-20">
                          <form name="form_action" action="{{ route('enquiry.store') }}" method="POST">
                              @csrf
                              <div class="row">
                                  <div class="form-group col-md-6 mb-20">
                                      <input type="text" name="name" class="form-control" placeholder="Your Name"
                                          required maxlength="100">
                                  </div>
                                  <div class="form-group col-md-6 mb-20">
                                      <input type="email" name="email" class="form-control" placeholder="Your Email"
                                          required maxlength="100">
                                  </div>
                                  <div class="form-group col-md-6 mb-20">
                                      <input type="text" name="mobile" class="form-control"
                                          placeholder="Mobile Number" required maxlength="10" minlength="10"
                                          oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                  </div>
                                  <div class="form-group col-md-6 mb-20">
                                      <input type="text" name="company_name" class="form-control"
                                          placeholder="Company Name (Optional)" maxlength="255">
                                  </div>
                                  <div class="form-group  col-md-12 mb-20">
                                      <textarea name="message" class="form-control" placeholder="Your Message (Optional)" rows="5"></textarea>
                                  </div>
                              </div>
                              <div class="form-group mb-0">
                                  <button type="submit" class="theme-btn style-one w-100">Submit</button>
                              </div>
                              <div class="response mt-2"></div>
                          </form>
                      </div>
                  </div>
              </div>
              <div class="col-xl-4 d-none">
                  <div class="section-content-box mb-40">
                      <div class="row">
                          <div class="col-xl-12 col-md-6">
                              <!--=== Iconic Box ===-->
                              <div class="sasly-iconic-box style-two mb-10" data-aos="fade-down"
                                  data-aos-duration="1000">
                                  <div class="content">
                                      <h4 class="title"><i class="flaticon-award-symbol"></i><span>We Are Award
                                              Winning Company</span></h4>
                                      <p>Pinnacle <span>Business Solution</span> commitment
                                          excellence and innovation earned </p>
                                  </div>
                              </div>
                          </div>
                          <div class="col-xl-12 col-md-6 mt-5">
                              <!--=== Iconic Box ===-->
                              <div class="sasly-iconic-box style-two mb-10" data-aos="fade-up" data-aos-duration="1300">
                                  <div class="content">
                                      <h4 class="title"><i
                                              class="flaticon-professional-development"></i><span>Professionals and
                                              Expert Support</span></h4>
                                      <p>Pinnacle <span>Business Solution</span> commitment
                                          excellence and innovation earned </p>
                                  </div>
                              </div>
                          </div>

                      </div>
                  </div>
              </div>
          </div>
      </div>
  </section>
