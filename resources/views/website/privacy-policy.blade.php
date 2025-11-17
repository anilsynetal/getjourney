@extends('website.layout.app')
@section('title', config('app.name') . ' – Privacy Policy')
@section('content')
    <main>
        <section class="page-hero-ss privacy-policy-header">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="page-content text-center">
                            <h1 class="page-title">Privacy Policy</h1>
                            <ul class="breadcrumb-link">
                                <li class="breadcrumb-item"><a href="{{ route('website.index') }}" class="text-white">Home</a>
                                </li>
                                <li class="active">Privacy Policy</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="privacy-policy-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h3>Our Privacy Obligations</h3>
                        <p>We respect every person’s privacy about his health and the diseases if any, so we strive to
                            maintain the privacy of medical and health information of every individual for whom we provide
                            our services for the sake of brevity we used the word PHI for the term of “Protected Health
                            Information”.</p>
                        <p>In order to provide you our services, you need to disclose your all required information about
                            your health, hence we require your authorization, given upon an executed release form, to use or
                            disclose PHI. After we receive your authorization, we will use and disclose PHI to provide our
                            services to you. This authorization will allow us to collect information from hospitals and
                            doctors. We may also disclose PHI to other medical institutions or medical professionals who are
                            involved in the delivery of our services to you.</p>
                        <p>We may disclose PHI to the physician(s) or medical institutions you identify on your
                            authorization when such PHI is appropriate for them to continue your treatment or conduct
                            certain health care operations, such as quality assessment and improvement activities, reviewing
                            the quality and competence of health care professionals, or for health care fraud and abuse
                            detection or compliance.</p>
                        <p>We will not disclose PHI to a family member, relative, friend, or any other person unless they
                            are specifically identified by you on your authorization as appropriate to receive PHI. If you
                            object to such uses or disclosures, please notify the Office Manager.</p>

                        <h3>Marketing Communications</h3>
                        <p>We will never use your PHI for any marketing materials without first receiving a written
                            authorization, a testimonial release. We will never require your execution of a testimonial
                            release before you may receive our service. We will also never use your PHI for mass marketing
                            purposes.</p>

                        <h3>Exception</h3>
                        <p>We are not required to receive an authorization from you for the following uses and disclosures:
                        </p>

                        <h4>Operations</h4>
                        <p>We may use and disclose PHI for our service operations, which include internal administration and
                            planning and various activities that improve the quality and cost effectiveness of the services
                            that we deliver to you. For example, we may use PHI to evaluate the quality and competence of
                            our personnel and consulting medical institutions and medical professionals. We may disclose PHI
                            to our management in order to resolve any complaints you may have and ensure that you receive
                            the highest quality services.</p>

                        <h4>Governmental Proceedings</h4>
                        <p>We may disclose PHI for the following governmental proceedings to report:</p>
                        <ul>
                            <li>in the course of a judicial or administrative proceeding in response to a legal order or
                                other lawful process;</li>
                            <li>to the police or other law enforcement officials as required or permitted by law or in
                                compliance with a court order;</li>
                            <li>to a coroner or medical examiner as authorized by law;</li>
                            <li>as authorized by and to the extent necessary to comply with laws relating to workers’
                                compensation or other similar programs; and</li>
                            <li>when required to do so by any other law not already referred to in the preceding categories.
                            </li>
                        </ul>

                        <h3>Email Address & Opt-out</h3>
                        <p>We provide you the opportunity to opt-in to receive promotional email communications and
                            newsletters. If you are receiving our email communication and no longer wish to be on our email
                            list, you may opt-out by clicking the unsubscribe link located in each email or by emailing us.
                        </p>

                        <h3>Online Privacy Policy</h3>
                        <p>Your right to privacy is very important to us and we recognize that when you choose to provide us
                            with information about yourself, you trust us to act in a responsible manner. We believe this
                            information should only be used to help us provide you with better service and that's why we
                            have put a policy in place to protect your personal information. We will not provide any of your
                            personal information obtained via the web to other companies or individuals unless required to
                            by law. Whenever you provide sensitive information (for example, a debit/credit card number to
                            make a payment) we will ensure all reasonable steps to protect it; such as encrypting your card
                            number. We will also take security measures to protect your personal information in storage.
                            Debit/Credit card numbers are used only for payment processing and are not retained for
                            marketing purposes. Our websites may provide links to third party sites. Since we do not control
                            those websites, we encourage you to review the privacy policies of these third party sites. This
                            privacy policy is subject to the general Terms of Use.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
