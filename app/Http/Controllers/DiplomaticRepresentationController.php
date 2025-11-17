<?php

namespace App\Http\Controllers;

use App\Models\DiplomaticRepresentation;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use Illuminate\Support\Facades\Gate;

class DiplomaticRepresentationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage_visa.diplomatic_representations.list|manage_visa.diplomatic_representations.add|manage_visa.diplomatic_representations.edit|manage_visa.diplomatic_representations.delete|manage_visa.diplomatic_representations.status', ['only' => ['index', 'show']]);
        $this->middleware('permission:manage_visa.diplomatic_representations.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:manage_visa.diplomatic_representations.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:manage_visa.diplomatic_representations.delete', ['only' => ['destroy']]);
        $this->middleware('permission:manage_visa.diplomatic_representations.status', ['only' => ['status']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $common_data = DiplomaticRepresentation::getTableData();
        $ajax_url = $common_data['ajax_url'];
        return view('common.index', compact('common_data', 'ajax_url'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $route_action = route('manage-visa.diplomatic-representations.store');
        $page_title = __('translation.Add') . ' ' . __('translation.DiplomaticRepresentation');
        $countries = Country::all()->pluck('country', 'id');
        $cities = DiplomaticRepresentation::distinct()->pluck('city')->sort()->values();
        return view('manage-visa.diplomatic-representations.create', compact('route_action', 'page_title', 'countries', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'country_id' => 'required|integer',
                'city' => 'required|string',
                'office_name' => 'required|string',
                'address' => 'nullable|string',
                'contact_number1' => 'nullable|string',
                'contact_number2' => 'nullable|string',
                'fax_number' => 'nullable|string',
                'email' => 'nullable',
                'opening_hours' => 'nullable|array',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        $requested_data = $request->except('_token');
        try {
            DB::beginTransaction();
            $store = new DiplomaticRepresentation();
            foreach ($requested_data as $key => $value) {
                if ($key === 'opening_hours' && is_array($value)) {
                    $store->opening_hours = json_encode($value);
                } else {
                    $store->$key = $value;
                }
            }
            $store->created_by = Auth::user()->id;
            $store->created_by_ip = $request->ip();
            $store->save();
            Util::activityLog('DiplomaticRepresentation', 'Created', $store);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.CreatedSuccessfully'), 'redirect' => false, 'url' => route('manage-visa.diplomatic-representations.index')];
            $status_code = 200;
        } catch (\Exception $th) {
            Util::generateErrorLog($th);
            $response = ['status' => 'error', 'message' => $th->getMessage()];
            $status_code = 500;
        }
        return response()->json($response, $status_code);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $result = DiplomaticRepresentation::find($id);
        $route_action = route('manage-visa.diplomatic-representations.update', $id);
        $page_title = __('translation.Edit') . ' ' . __('translation.DiplomaticRepresentation');
        $opening_hours = [];
        if (!empty($result->opening_hours)) {
            $decoded = json_decode($result->opening_hours, true);
            if (is_array($decoded)) {
                $opening_hours = $decoded;
            }
        }
        $countries = Country::all()->pluck('country', 'id');
        $cities = DiplomaticRepresentation::distinct()->pluck('city')->sort()->values();
        return view('manage-visa.diplomatic-representations.edit', compact('result', 'route_action', 'page_title', 'opening_hours', 'countries', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $requested_data = $request->except('_token', '_method');
        $validator = Validator::make(
            $request->all(),
            [
                'country_id' => 'required|integer',
                'city' => 'required|string',
                'office_name' => 'required|string',
                'address' => 'nullable|string',
                'contact_number1' => 'nullable|string',
                'contact_number2' => 'nullable|string',
                'fax_number' => 'nullable|string',
                'email' => 'nullable',
                'opening_hours' => 'nullable|array',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            DB::beginTransaction();
            $update = DiplomaticRepresentation::find($id);
            foreach ($requested_data as $key => $value) {
                if ($key === 'opening_hours' && is_array($value)) {
                    $update->opening_hours = json_encode($value);
                } else {
                    $update->$key = $value;
                }
            }
            $update->updated_by = Auth::user()->id;
            $update->updated_by_ip = $request->ip();
            $update->save();

            Util::activityLog('DiplomaticRepresentation', 'Updated', $update);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.UpdatedSuccessfully'), 'redirect' => true, 'url' => route('manage-visa.diplomatic-representations.index')];
            $status_code = 200;
        } catch (\Exception $th) {
            Util::generateErrorLog($th);
            $response = ['status' => 'error', 'message' => $th->getMessage()];
            $status_code = 500;
        }
        return response()->json($response, $status_code);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $delete = DiplomaticRepresentation::find($id);
            Util::activityLog('DiplomaticRepresentation', 'Deleted', $delete);
            $delete->delete();
            $response = ['status' => 'success', 'message' => __('translation.DeletedSuccessfully')];
            $status_code = 200;
        } catch (\Exception $th) {
            Util::generateErrorLog($th);
            $response = ['status' => 'error', 'message' => $th->getMessage()];
            $status_code = 500;
        }
        return response()->json($response, $status_code);
    }

    /**
     * Change the status of the record
     */
    public function status(string $id)
    {
        try {
            $status = DiplomaticRepresentation::find($id);
            $status->status = ($status->status == 1) ? 0 : 1;
            $status->status_updated_by = Auth::user()->id;
            $status->status_updated_by_ip = request()->ip();
            $status->save();
            Util::activityLog('DiplomaticRepresentation', 'Status Updated', $status);
            $response = ['status' => 'success', 'message' => __('translation.StatusUpdatedSuccessfully')];
            $status_code = 200;
        } catch (\Exception $th) {
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

            $query =  DiplomaticRepresentation::with(['created_by_user', 'country']);
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
            $data = $query->where('created_by', Auth::user()->id)->orderBy('id', 'desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return date('d/m/Y h:i A', strtotime($row->created_at));
                })
                ->editColumn('created_by', function ($row) {
                    return $row->created_by_user->full_name;
                })
                ->editColumn('country', function ($row) {
                    return $row->country->country;
                })
                ->editColumn('status', function ($row) {
                    if (Gate::allows('manage_visa.diplomatic_representations.status')) {
                        if ($row->status == 0) {
                            $status = '<button type="button" class="btn btn-outline-danger btn-sm update_status" data-url="' . route('manage-visa.diplomatic-representations.status', $row->id) . '"> ' . __('translation.Inactive') . ' </button>';
                        } else {
                            $status = '<button type="button" class="btn btn-outline-success btn-sm update_status" data-url="' . route('manage-visa.diplomatic-representations.status', $row->id) . '"> ' . __('translation.Active') .
                                ' </button>';
                        }
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (Gate::allows('manage_visa.diplomatic_representations.edit')) {
                        $btn .= '<a href="' . route('manage-visa.diplomatic-representations.edit', $row->id) . '"  class="btn btn-outline-warning btn-sm" title="' . __('translation.Edit') . '"> <i class="fas fa-pencil-alt"></i></a>&nbsp;';
                    }
                    if (Gate::allows('manage_visa.diplomatic_representations.delete')) {
                        $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete_record" data-url="' . route('manage-visa.diplomatic-representations.destroy', $row->id) . '" title="' . __('translation.Delete') . '"> <i class="fas fa-trash"></i></button>&nbsp;';
                    }
                    return $btn;
                })
                ->rawColumns(['DT_RowIndex', 'created_at', 'created_by', 'status', 'action', 'country'])
                ->make(true);
        }
    }
}
