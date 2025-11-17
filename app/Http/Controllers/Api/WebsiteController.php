<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Contact;
use App\Models\Enquiry;
use App\Models\FAQ;
use App\Models\FeatureCategory;
use App\Models\NewsLetter;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Team;
use App\Models\Testimonial;
use App\Models\User;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $about = About::where('status', 1)->orderBy('id', 'desc')->first();
            $slider = Slider::where('status', 1)->orderBy('id', 'desc')->get();
            $services = Service::where('status', 1)->orderBy('id', 'desc')->take(6)->get();
            $testimonials = Testimonial::where('status', 1)->orderBy('id', 'desc')->get();
            $teams = Team::where('status', 1)->orderBy('id', 'desc')->take(6)->get();
            $clients = Role::where('name', 'Client')->exists()
                ? User::role('Client')->where('status', 1)->get()
                : collect();
            $contact = Contact::where('status', 1)->orderBy('id', 'desc')->first();
            $feature_category = FeatureCategory::with('features')
                ->whereHas('features', function ($query) {
                    $query->where('status', 1);
                })
                ->first();
            return response()->json([
                'status' => true,
                'message' => 'Data fetched successfully',
                'data' => [
                    'about' => $about,
                    'slider' => $slider,
                    'services' => $services,
                    'testimonials' => $testimonials,
                    'facts' => [],
                    'feature_category' => $feature_category,
                    'team' => $teams,
                    'vendors' => $clients,
                    'contact' => $contact

                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(), // optional: remove in production
            ], 500);
        }
    }

    //Get About Data
    public function about()
    {
        try {
            $about = About::where('status', 1)->orderBy('id', 'desc')->first();
            $teams = Team::where('status', 1)->orderBy('id', 'desc')->get();
            return response()->json([
                'status' => true,
                'message' => 'Data fetched successfully',
                'data' => [
                    'about' => $about,
                    'teams' => $teams,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(), // optional: remove in production
            ], 500);
        }
    }

    //Get Service Data
    public function services()
    {
        try {
            $results = Service::where('status', 1)->orderBy('id', 'desc')->get();
            return response()->json([
                'status' => true,
                'message' => 'Data fetched successfully',
                'data' => $results
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(), // optional: remove in production
            ], 500);
        }
    }

    //Get FAQs Data
    public function faqs()
    {
        try {
            $results = FAQ::where('status', 1)->orderBy('id', 'desc')->get();
            return response()->json([
                'status' => true,
                'message' => 'Data fetched successfully',
                'data' => $results
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(), // optional: remove in production
            ], 500);
        }
    }

    //Get Contact Data
    public function contact()
    {
        try {
            $result = Contact::where('status', 1)->orderBy('id', 'desc')->first();
            $result->app_name = Setting::where('key', 'app_name')->first()->value;
            $result->app_logo = asset('storage/' . Setting::where('key', 'app_logo')->first()->value);
            return response()->json([
                'status' => true,
                'message' => 'Data fetched successfully',
                'data' => $result
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(), // optional: remove in production
            ], 500);
        }
    }

    //Store Enquiry
    public function store_enquiry(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:100',
                'email' => 'required|email|max:100',
                'mobile' => 'required|regex:/^[0-9]{10}$/|max:20',
                'service' => 'required|exists:services,id',
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
            $enquiry->service_id = $request->service;
            $enquiry->message = $request->message;
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
                'service' => $enquiry->service?->title || 'NA',
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
                'message' => __('translation.EnquirySubmittedSuccessfully')
            ], 200);
        } catch (\Exception $e) {
            Util::generateErrorLog($e);
            return response()->json([
                'status' => false,
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
                'message' => __('translation.NewsletterSubscribedSuccessfully')
            ], 200);
        } catch (\Exception $e) {
            Util::generateErrorLog($e);
            return response()->json([
                'status' => false,
                'message' => __('translation.SomethingWentWrong'),
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
