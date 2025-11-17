<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Benefit;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\CaseStudy;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Counter;
use App\Models\Country;
use App\Models\DiplomaticRepresentation;
use App\Models\Enquiry;
use App\Models\Feature;
use App\Models\InternationalHelpAddress;
use App\Models\LogisticPartner;
use App\Models\NewsLetter;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Team;
use App\Models\Testimonial;
use App\Models\TourPackage;
use App\Models\VisaCategory;
use App\Models\VisaDetail;
use App\Models\VisaForm;
use App\Models\VisaInformation;
use App\Mail\VisaInfoShareMail;
use App\Utils\Util;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($slug = null)
    {
        $sliders = Slider::where('status', 1)->get();
        $about = About::where('status', 1)->first();
        $blogs = Blog::where('status', 1)->orderBy('id', 'desc')->take(4)->get();
        $services = Service::where('status', 1)->orderBy('id', 'desc')->take(3)->get();
        $testimonials = Testimonial::where('status', 1)->orderBy('id', 'desc')->get();
        $counters = Counter::where('status', 1)->get();
        $why_choose_us = Benefit::where('status', 1)->orderBy('id', 'desc')->get();
        $tour_packages = TourPackage::where('status', 1)->orderBy('name', 'asc')->take(4)->get();
        return view('website.home', compact('sliders', 'blogs', 'services', 'why_choose_us', 'about', 'testimonials', 'counters', 'tour_packages'));
    }

    //About Page
    public function about()
    {
        $about = About::where('status', 1)->first();
        $contact = Contact::where('status', 1)->first();
        $teams = Team::where('status', 1)->orderBy('id', 'desc')->take(8)->get();
        $why_choose_us = Benefit::where('status', 1)->orderBy('id', 'desc')->get();
        return view('website.about', compact('about', 'contact', 'teams', 'why_choose_us'));
    }

    //Services Page
    public function services()
    {
        $services = Service::where('status', 1)->orderBy('title', 'asc')->get();
        $tour_packages = TourPackage::where('status', 1)->orderBy('name', 'asc')->take(8)->get();
        return view('website.services', compact('services', 'tour_packages'));
    }

    //Service Details
    public function service_details($slug)
    {
        $service = Service::where('slug', $slug)->first();
        if (!$service) {
            return redirect()->route('home')->with('error', 'Service not found');
        }
        return view('website.service-details', compact('service'));
    }

    //Tours Page
    public function tours()
    {
        $tour_package_query = TourPackage::with('country')->where('status', 1);

        // Search functionality for tour packages
        if (request()->has('search') && !empty(request()->search)) {
            $search = request()->search;
            $tour_package_query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('highlights', 'like', '%' . $search . '%')
                    ->orWhereHas('country', function ($q) use ($search) {
                        $q->where('country', 'like', '%' . $search . '%');
                    });
            });
        }

        // Duration filter for tour packages
        if (request()->has('duration') && request()->duration != 'all' && !empty(request()->duration)) {
            switch (request()->duration) {
                case '1-3':
                    $tour_package_query->whereBetween('duration_days', [1, 3]);
                    break;
                case '4-7':
                    $tour_package_query->whereBetween('duration_days', [4, 7]);
                    break;
                case '8-14':
                    $tour_package_query->whereBetween('duration_days', [8, 14]);
                    break;
                case '15+':
                    $tour_package_query->where('duration_days', '>=', 15);
                    break;
            }
        }

        // Country filter for tour packages
        if (request()->has('country') && request()->country != 'all' && !empty(request()->country)) {
            $tour_package_query->where('country_id', request()->country);
        }

        // Sorting for tour packages
        $sort_by = request()->get('sort_by', 'name');
        $sort_order = request()->get('sort_order', 'asc');

        switch ($sort_by) {
            case 'duration':
                $tour_package_query->orderBy('duration_days', $sort_order);
                break;
            case 'name':
            default:
                $tour_package_query->orderBy('name', $sort_order);
                break;
        }

        $tour_packages = $tour_package_query->paginate(12);
        $tour_packages->appends(request()->query());

        // Get countries that have tour packages for filter dropdown
        $countries = Country::whereHas('tours', function ($query) {
            $query->where('status', 1);
        })->orderBy('country', 'asc')->get();

        return view('website.tours', compact('tour_packages', 'countries'));
    }

    //Tour Details
    public function tour_details($slug)
    {
        $tour = TourPackage::with('country')->where('slug', $slug)->first();
        if (!$tour) {
            return redirect()->route('website.index')->with('error', 'Tour Package not found');
        }
        return view('website.tour-details', compact('tour'));
    }

    //Tour Package Details
    public function tour_package_details($slug)
    {
        $tour_package = TourPackage::where('slug', $slug)->first();
        if (!$tour_package) {
            return redirect()->route('website.index')->with('error', 'Tour Package not found');
        }
        return view('website.tour-package-details', compact('tour_package'));
    }

    //Contact Page
    public function contact()
    {
        return view('website.contact');
    }

    //Blogs Page
    public function blogs()
    {
        $blog_query = Blog::with('blog_category')->where('status', 1);
        if (request()->has('category_id') && request()->category_id != 'all') {
            $blog_query->where('blog_category_id', request()->category_id);
        }
        $blogs = $blog_query->orderBy('created_at', 'desc')->paginate(8);

        return view('website.blogs', compact('blogs'));
    }

    //Blog Details
    public function blog_details($slug)
    {
        $blog = Blog::with('blog_category')->where('slug', $slug)->first();
        if (!$blog) {
            return redirect()->route('website.index')->with('error', 'Blog not found');
        }
        $related_blogs = Blog::where('status', 1)
            ->where('blog_category_id', $blog->blog_category_id)
            ->where('id', '!=', $blog->id)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        return view('website.blog-details', compact('blog', 'related_blogs'));
    }

    //Store Enquiry
    public function enquiry_store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:100',
                'email' => 'required|email|max:100',
                'mobile' => 'required|regex:/^[0-9]{10}$/|max:20',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            $enquiry = new Enquiry();
            $enquiry->name = $request->name;
            $enquiry->email = $request->email;
            $enquiry->mobile = preg_replace('/[^0-9]/', '', $request->mobile);
            $enquiry->subject = $request->subject;
            $enquiry->message = $request->message;
            $enquiry->tour_id = $request->tour_id ?? null;
            $enquiry->service_id = $request->service_id ?? null;
            $enquiry->tour_package_id = $request->tour_package_id ?? null;
            $enquiry->ip_address = $request->ip();
            $enquiry->save();
            // Send email notification to admin
            $admin_email = Setting::where('key', 'mail_from_address')->value('value');
            $data = [
                'type' => 'enquiry',
                'name' => $enquiry->name,
                'email' => $enquiry->email,
                'mobile' => $enquiry->mobile,
                'subject' => $request->subject ?? __('translation.NewEnquiry'),
                'message' => $enquiry->message,
                'ip_address' => $enquiry->ip_address,
            ];
            if ($admin_email) {
                Mail::to($admin_email)->send(new \App\Mail\EnquiryNotification($data));
            }
            // Send email notification to user
            if ($enquiry->email) {
                $data['type'] = 'acknowledgment';
                $data['subject'] =  __('translation.EnquiryAcknowledgmentSubject');
                Mail::to($enquiry->email)->send(new \App\Mail\EnquiryAcknowledgment($data));
            }

            return response()->json([
                'status' => true,
                'type' => 'contact',
                'message' => __('translation.EnquirySubmittedSuccessfully')
            ], 200);
        } catch (\Exception $e) {
            Util::generateErrorLog($e);
            return response()->json([
                'status' => false,
                'type' => 'contact',
                'message' => __('translation.SomethingWentWrong'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //Store Newsletter Subscription
    public function subscribe_newsletter(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|max:100|unique:news_letters,email',
            ],
            [
                'email.unique' => __('translation.EmailAlreadySubscribed'),
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            $store = new NewsLetter();
            $store->email = $request->email;
            $store->save();
            return response()->json([
                'status' => true,
                'type' => 'subscription',
                'message' => __('translation.NewsletterSubscribedSuccessfully')
            ], 200);
        } catch (\Exception $e) {
            Util::generateErrorLog($e);
            return response()->json([
                'status' => false,
                'type' => 'subscription',
                'message' => __('translation.SomethingWentWrong'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //Privacy Policy
    public function privacy_policy()
    {
        return view('website.privacy-policy');
    }

    //Visa information page
    public function visa_information()
    {
        $countries = Country::whereHas('visaInformation', function ($query) {
            $query->where('status', 1);
        })->get();
        $visa_details = [];
        $visa_information = null;
        $visa_forms = [];
        $diplomatic_representations = [];
        $international_help_addresses = [];
        $logistic_partners = [];
        $visa_categories = [];
        if (request()->has('country_id')) {
            $visa_details = VisaDetail::where('status', 1)
                ->where('country_id', request()->country_id)
                ->with(['country', 'visa_category', 'documents'])
                ->get();
            $visa_information = VisaInformation::where('country_id', request()->country_id)->first();
            $visa_forms = VisaForm::where('country_id', request()->country_id, 'visa_category')->get();

            $diplomatic_representations = DiplomaticRepresentation::where('country_id', request()->country_id)->get();
            $international_help_addresses = InternationalHelpAddress::where('country_id', request()->country_id)->get();

            $logistic_partners = LogisticPartner::where('country_id', request()->country_id)
                ->where('status', 1)
                ->get();

            $visa_categories = VisaCategory::whereHas('visaDetails', function ($query) {
                $query->where('status', 1);
            })->get();
        }

        return view('website.visa-information', compact('visa_details', 'countries', 'visa_information', 'visa_forms', 'diplomatic_representations', 'international_help_addresses', 'logistic_partners', 'visa_categories'));
    }

    // Share Visa Information via Email
    public function share_visa_info(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'recipient_name' => 'required|string|max:100',
                'recipient_email' => 'required|email|max:100',
                'contact_number' => 'required|regex:/^[0-9\s\-\+\(\)]{10,20}$/',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create a comprehensive enquiry record for admin follow-up
            $enquiry = new Enquiry();
            $enquiry->name = $request->recipient_name;
            $enquiry->email = $request->recipient_email;
            $enquiry->mobile = preg_replace('/[^0-9]/', '', $request->contact_number);

            // Build detailed message with all visa information for follow-up
            $detailedMessage = "**VISA INFORMATION INQUIRY**\n\n";
            $detailedMessage .= "Visa Category: " . ($request->visa_category ?? 'Not specified') . "\n";
            $detailedMessage .= "City: " . ($request->city ?? 'Not specified') . "\n";
            $detailedMessage .= "Service Charges: " . ($request->service_charges ?? 'To be confirmed') . "\n";
            $detailedMessage .= "Additional Info: " . ($request->additional_info ?? 'None') . "\n";
            $detailedMessage .= "\n---\n" . ($request->additional_info ?? '');

            $enquiry->message = $detailedMessage;
            $enquiry->subject = 'Visa Information Share - ' .
                ($request->visa_category ?? 'Visa Information') . ' - ' .
                ($request->city ?? '') .
                ($request->service_charges ? (' - ' . $request->service_charges) : '');
            $enquiry->type = 'visa_information';
            $enquiry->country_id = request()->country_id ?? null;
            $enquiry->status = 'pending';
            $enquiry->ip_address = $request->ip();
            $enquiry->save();

            // Prepare email data
            $mailData = [
                'name' => $request->recipient_name,
                'email' => $request->recipient_email,
                'contact' => $request->contact_number,
                'visa_category' => $request->visa_category ?? 'Visa Information',
                'city' => $request->city ?? '',
                'service_charges' => $request->service_charges ?? 'To be confirmed',
                'additional_info' => $request->additional_info ?? '',
                'ip_address' => $request->ip(),
            ];

            // Send email notification to admin
            $admin_email = Setting::where('key', 'mail_from_address')->value('value');
            if ($admin_email) {
                try {
                    $adminData = array_merge($mailData, [
                        'type' => 'visa_enquiry_admin',
                        'subject' => 'New Visa Information Request - ' . ($request->visa_category ?? 'Visa Details')
                    ]);
                    Mail::to($admin_email)->send(new VisaInfoShareMail($adminData));
                    Log::info('Admin visa info notification sent to: ' . $admin_email);
                } catch (\Exception $adminMailException) {
                    Log::warning('Admin notification failed: ' . $adminMailException->getMessage());
                }
            }

            // Send email notification to user (recipient)
            if ($enquiry->email) {
                try {
                    $userData = array_merge($mailData, [
                        'type' => 'visa_enquiry_user',
                        'subject' => 'Visa Information Shared - ' . ($request->visa_category ?? 'Visa Details')
                    ]);
                    Mail::to($enquiry->email)->send(new VisaInfoShareMail($userData));
                    Log::info('User visa info confirmation sent to: ' . $enquiry->email);
                } catch (\Exception $userMailException) {
                    Log::warning('User confirmation failed: ' . $userMailException->getMessage());
                    // Enquiry is still saved for admin to follow up
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Visa information request saved successfully! Our team will contact you soon.'
            ], 200);
        } catch (\Exception $e) {
            Util::generateErrorLog($e);
            Log::error('Visa info share error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'Error processing your request. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function download_visa_pdf(Request $request)
    {
        try {
            $visaCategory = $request->input('visa_category');
            $city = $request->input('city');
            $contentHtml = $request->input('content_html');

            if (!$visaCategory || !$city) {
                return response()->json([
                    'status' => false,
                    'message' => 'Visa category and city are required.'
                ], 400);
            }

            // If needed, verify the visa category exists
            $visaInfo = VisaCategory::where('name', $visaCategory)->first();
            if (!$visaInfo) {
                Log::warning('Visa information not found for category', ['category' => $visaCategory]);
            }

            // Sanitize user-provided HTML content to avoid scripts
            $sanitizedContent = '';
            if (!empty($contentHtml)) {
                $sanitizedContent = $contentHtml;
                // Remove script and style tags completely
                $sanitizedContent = preg_replace('#<script\b[^<]*(?:(?!</script>)<[^<]*)*</script>#is', '', $sanitizedContent);
                $sanitizedContent = preg_replace('#<style\b[^<]*(?:(?!</style>)<[^<]*)*</style>#is', '', $sanitizedContent);
                // Remove dangerous embed tags
                $sanitizedContent = preg_replace('#</?(iframe|object|embed|applet)[^>]*>#is', '', $sanitizedContent);
                // Remove inline event handlers like onclick, onload, etc.
                $sanitizedContent = preg_replace('/\s+on\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $sanitizedContent);
            }

            // Get the accordion items content (this would typically come from your accordion data)
            // For now, we'll build a professional PDF document with visa checklist

            $generatedDate = Carbon::now()->format('d-m-Y');

            $html = <<<HTML
            <html>
                <head>
                    <meta charset="UTF-8">
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            color: #333;
                            line-height: 1.6;
                            margin: 20px;
                        }
                        h1 {
                            color: #003366;
                            text-align: center;
                            border-bottom: 3px solid #003366;
                            padding-bottom: 10px;
                        }
                        h2 {
                            color: #003366;
                            margin-top: 25px;
                            border-left: 5px solid #003366;
                            padding-left: 10px;
                        }
                        .header-info {
                            background-color: #f0f8ff;
                            padding: 15px;
                            border-radius: 5px;
                            margin-bottom: 20px;
                        }
                        .content {
                            margin: 12px 0;
                        }
                        .footer {
                            margin-top: 40px;
                            padding-top: 15px;
                            border-top: 1px solid #ddd;
                            font-size: 12px;
                            color: #666;
                            text-align: center;
                        }
                        .section-description {
                            color: #666;
                            font-style: italic;
                            margin: 10px 0 15px 0;
                        }
                        /* Generic content styles for embedded accordion HTML */
                        ul, ol { padding-left: 20px; margin: 8px 0; }
                        li { margin: 6px 0; }
                        p { margin: 8px 0; }
                        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
                        th, td { border: 1px solid #ddd; padding: 8px; }
                        h3, h4 { color: #003366; margin-top: 16px; }
                        a { color: #003366; text-decoration: none; }
                        img { max-width: 100%; height: auto; }
                    </style>
                </head>
                <body>
                    <h1>Visa Checklist - {$visaCategory}</h1>
                    <div class="header-info">
                        <p><strong>Destination:</strong> {$city}</p>
                        <p><strong>Document Type:</strong> Visa Information Checklist</p>
                        <p><strong>Generated Date:</strong> {$generatedDate}</p>
                    </div>

                    <h2>Visa Details</h2>
                    <div class="content">
                        {$sanitizedContent}
                    </div>

                    <div class="footer">
                        <p><strong>Generated by Get Journey Tours</strong></p>
                        <p>This document is intended as a general guide. Please contact our travel consultants for specific visa requirements.</p>
                        <p style="margin-top: 20px; color: #999;">© 2024 Get Journey Tours. All Rights Reserved.</p>
                    </div>
                </body>
            </html>
            HTML;

            // Generate PDF
            $pdf = Pdf::loadHTML($html);
            $pdf->setPaper('A4');
            $fileName = 'VisaCheckList_' . str_replace(' ', '_', $visaCategory) . '_' . str_replace(' ', '_', $city) . '.pdf';

            return $pdf->download($fileName);
        } catch (\Exception $e) {
            Util::generateErrorLog($e);
            Log::error('PDF download error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'Error generating PDF. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
