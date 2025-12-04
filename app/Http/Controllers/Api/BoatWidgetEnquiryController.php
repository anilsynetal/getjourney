<?php

namespace App\Http\Controllers\Api;

use App\Models\BoatWidgetEnquiry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BoatWidgetEnquiryController extends Controller
{
    /**
     * Store a new boat widget enquiry
     * POST /api/v1/boat-widget-enquiries
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'full_name' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'age' => 'nullable|integer',
                'nationality' => 'nullable|string|max:255',
                'qualification' => 'nullable|string|max:255',
                'work_experience' => 'nullable|integer',
                'current_occupation' => 'nullable|string|max:255',
                'company' => 'nullable|string|max:255',
                'destination_country' => 'nullable|string|max:255',
                'visa_type' => 'nullable|string|max:255',
                'purpose' => 'nullable|string|max:255',
                'travel_date' => 'nullable|string|max:255',
                'duration' => 'nullable|string|max:255',
                'previous_visas' => 'nullable|string',
                'family_status' => 'nullable|string|max:255',
                'assets' => 'nullable|string',
                'additional_info' => 'nullable|string',
                'message' => 'nullable|string',
            ]);

            $validated['user_ip'] = $request->ip();
            $validated['user_agent'] = $request->userAgent();
            $validated['status'] = 'new';

            $enquiry = BoatWidgetEnquiry::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Your enquiry has been submitted successfully! Our team will get back to you soon.',
                'data' => [
                    'id' => $enquiry->id,
                    'email' => $enquiry->email,
                    'created_at' => $enquiry->created_at->format('Y-m-d H:i:s'),
                ]
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error submitting enquiry. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get all boat widget enquiries (with filters)
     * GET /api/v1/boat-widget-enquiries
     */
    public function index(Request $request)
    {
        try {
            $query = BoatWidgetEnquiry::query();

            if ($request->has('status') && $request->status) {
                $query->byStatus($request->status);
            }

            if ($request->has('visa_category') && $request->visa_category) {
                $query->byCategory($request->visa_category);
            }

            if ($request->has('search') && $request->search) {
                $query->where(function ($q) use ($request) {
                    $q->where('message', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            }

            $enquiries = $query->recent()->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $enquiries
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching enquiries',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get a single boat widget enquiry
     * GET /api/v1/boat-widget-enquiries/{id}
     */
    public function show($id)
    {
        try {
            $enquiry = BoatWidgetEnquiry::findOrFail($id);

            if ($enquiry->status === 'new') {
                $enquiry->markAsRead();
            }

            return response()->json([
                'success' => true,
                'data' => $enquiry
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Enquiry not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching enquiry',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get statistics
     * GET /api/v1/boat-widget-enquiries/statistics
     */
    public function getStatistics()
    {
        try {
            $stats = [
                'total' => BoatWidgetEnquiry::count(),
                'new' => BoatWidgetEnquiry::new()->count(),
                'read' => BoatWidgetEnquiry::read()->count(),
                'responded' => BoatWidgetEnquiry::responded()->count(),
                'closed' => BoatWidgetEnquiry::closed()->count(),
                'unassigned' => BoatWidgetEnquiry::unassigned()->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching statistics',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
