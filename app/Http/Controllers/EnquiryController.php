<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class EnquiryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:enquiries.list|enquiries.add|enquiries.edit|enquiries.delete|enquiries.status', ['only' => ['index', 'show']]);
        $this->middleware('permission:enquiries.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:enquiries.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:enquiries.delete', ['only' => ['destroy']]);
        $this->middleware('permission:enquiries.status', ['only' => ['status']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $common_data = Enquiry::getTableData();
        $ajax_url = $common_data['ajax_url'];
        return view('enquiries.index', compact('common_data', 'ajax_url'));
    }

    //Service Enquiries
    public function service_enquiries()
    {
        $common_data = Enquiry::getTableDataServiceEnquiry();
        $common_data['title'] = __('translation.ServiceEnquiryList');
        $common_data['active_page'] = __('translation.ManagerServiceEnquiries');
        $ajax_url = route('enquiries.get-ajax-data') . '?type=service';
        return view('enquiries.index', compact('common_data', 'ajax_url'));
    }

    //Tour Enquiries
    public function tour_enquiries()
    {
        $common_data = Enquiry::getTableDataTourEnquiry();
        $common_data['title'] = __('translation.TourEnquiryList');
        $common_data['active_page'] = __('translation.ManagerTourEnquiries');
        $ajax_url = route('enquiries.get-ajax-data') . '?type=tour';
        return view('enquiries.index', compact('common_data', 'ajax_url'));
    }

    //Tour Package Enquiries
    public function tour_package_enquiries()
    {
        $common_data = Enquiry::getTableDataTourPackageEnquiry();
        $common_data['title'] = __('translation.TourPackageEnquiryList');
        $common_data['active_page'] = __('translation.ManagerTourPackageEnquiries');
        $ajax_url = route('enquiries.get-ajax-data') . '?type=tour_package';
        return view('enquiries.index', compact('common_data', 'ajax_url'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $delete = Enquiry::find($id);
            Util::activityLog('Enquiry', 'Deleted', $delete);
            $delete->delete();
            $response = ['status' => 'success', 'message' => __('translation.DeletedSuccessfully')];
            $status_code = 200;
        } catch (\Exception $th) {
            //Log error
            Util::generateErrorLog($th);
            $response = ['status' => 'error', 'message' => $th->getMessage()];
            $status_code = 500;
        }
        return response()->json($response, $status_code);
    }

    //Get the ajax data
    public function getAjaxData()
    {
        if (request()->ajax()) {

            $query =  Enquiry::orderBy('id', 'desc');
            if (request()->has('status_filter') && request()->status_filter != 'all') {
                switch (request()->status_filter) {
                    case 0:
                        $query->where('status', 0);
                        break;
                    case 1:
                        $query->where('status', 1);
                        break;
                    case 2:
                        $query->onlyTrashed();
                    default:
                        break;
                }
            }
            if (request()->has('type') && request()->type == 'service') {
                $query->whereNotNull('service_id');
            }
            if (request()->has('type') && request()->type == 'tour') {
                $query->whereNotNull('tour_id');
            }
            if (request()->has('type') && request()->type == 'tour_package') {
                $query->whereNotNull('tour_package_id');
            }
            $data = $query->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return date('d/m/Y h:i A', strtotime($row->created_at));
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('enquiries.delete')) {
                        $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete_record" data-url="' . route('enquiries.destroy', $row->id) . '" title="' . __('translation.Delete') . '"> <i class="fas fa-trash"></i></button>&nbsp;';
                    }
                    return $btn;
                })
                ->rawColumns(['DT_RowIndex', 'created_at', 'action'])
                ->make(true);
        }
    }
}
