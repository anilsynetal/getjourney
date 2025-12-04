<?php

namespace App\Http\Controllers\Admin;

use App\Models\BoatWidgetEnquiry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BoatWidgetEnquiryController extends Controller
{
    /**
     * Display a listing of boat widget enquiries
     */
    public function index()
    {
        $enquiries = BoatWidgetEnquiry::recent()->paginate(20);
        $stats = $this->getStatistics();

        return view('admin.boat-widget-enquiries.index', compact('enquiries', 'stats'));
    }

    /**
     * Get AJAX data for DataTable
     */
    public function getAjaxData(Request $request)
    {
        $query = BoatWidgetEnquiry::query();

        if ($request->has('status') && $request->status) {
            $query->byStatus($request->status);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('message', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $enquiries = $query->recent()->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $enquiries->items(),
            'pagination' => [
                'total' => $enquiries->total(),
                'per_page' => $enquiries->perPage(),
                'current_page' => $enquiries->currentPage(),
                'last_page' => $enquiries->lastPage(),
            ]
        ]);
    }

    /**
     * Display the specified enquiry
     */
    public function show($id)
    {
        $enquiry = BoatWidgetEnquiry::findOrFail($id);

        if ($enquiry->status === 'new') {
            $enquiry->markAsRead();
        }

        return view('admin.boat-widget-enquiries.show', compact('enquiry'));
    }

    /**
     * Change enquiry status
     */
    public function changeStatus(Request $request, $id)
    {
        try {
            $enquiry = BoatWidgetEnquiry::findOrFail($id);

            $validated = $request->validate([
                'status' => 'required|in:new,read,responded,closed',
            ]);

            $enquiry->update(['status' => $validated['status']]);

            return response()->json([
                'success' => true,
                'message' => 'Status changed successfully',
                'data' => $enquiry
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error changing status',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Assign enquiry to admin
     */
    public function assignToUser(Request $request, $id)
    {
        try {
            $enquiry = BoatWidgetEnquiry::findOrFail($id);

            $validated = $request->validate([
                'assigned_to' => 'nullable|exists:users,id',
            ]);

            $enquiry->assignTo($validated['assigned_to']);

            return response()->json([
                'success' => true,
                'message' => 'Assigned successfully',
                'data' => $enquiry
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error assigning',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Add internal notes
     */
    public function addNotes(Request $request, $id)
    {
        try {
            $enquiry = BoatWidgetEnquiry::findOrFail($id);

            $validated = $request->validate([
                'note' => 'required|string|max:2000',
            ]);

            $enquiry->addInternalNote($validated['note']);

            return response()->json([
                'success' => true,
                'message' => 'Note added successfully',
                'data' => $enquiry
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding note',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Add admin response
     */
    public function respond(Request $request, $id)
    {
        try {
            $enquiry = BoatWidgetEnquiry::findOrFail($id);

            $validated = $request->validate([
                'response' => 'required|string|max:5000',
            ]);

            $enquiry->markAsResponded($validated['response']);

            return response()->json([
                'success' => true,
                'message' => 'Response saved successfully',
                'data' => $enquiry
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving response',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        return [
            'total' => BoatWidgetEnquiry::count(),
            'new' => BoatWidgetEnquiry::new()->count(),
            'read' => BoatWidgetEnquiry::read()->count(),
            'responded' => BoatWidgetEnquiry::responded()->count(),
            'closed' => BoatWidgetEnquiry::closed()->count(),
            'unassigned' => BoatWidgetEnquiry::unassigned()->count(),
        ];
    }

    /**
     * Export to CSV
     */
    public function export(Request $request)
    {
        try {
            $query = BoatWidgetEnquiry::query();

            if ($request->has('status') && $request->status) {
                $query->byStatus($request->status);
            }

            $enquiries = $query->get();

            $csv = "ID,Name,Email,Phone,Message,Visa Category,Status,Assigned To,Created At\n";

            foreach ($enquiries as $enquiry) {
                $csv .= sprintf(
                    '"%s","%s","%s","%s","%s","%s","%s","%s","%s"' . "\n",
                    $enquiry->id,
                    $enquiry->name ?? '',
                    $enquiry->email ?? '',
                    $enquiry->phone ?? '',
                    str_replace('"', '""', substr($enquiry->message, 0, 100)),
                    $enquiry->visa_category ?? '',
                    $enquiry->status,
                    $enquiry->assignedUser?->name ?? 'Unassigned',
                    $enquiry->created_at->format('Y-m-d H:i:s')
                );
            }

            return response($csv)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="boat-widget-enquiries-' . date('Y-m-d') . '.csv"');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error exporting',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
