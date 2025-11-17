<?php

namespace App\Http\Controllers;

use App\Models\LogisticPartner;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;

class LogisticPartnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage_visa.logistic_partners.list|manage_visa.logistic_partners.add|manage_visa.logistic_partners.edit|manage_visa.logistic_partners.delete|manage_visa.logistic_partners.status', ['only' => ['index', 'show']]);
        $this->middleware('permission:manage_visa.logistic_partners.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:manage_visa.logistic_partners.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:manage_visa.logistic_partners.delete', ['only' => ['destroy']]);
        $this->middleware('permission:manage_visa.logistic_partners.status', ['only' => ['status']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $common_data = LogisticPartner::getTableData();
        $ajax_url = $common_data['ajax_url'];
        return view('common.index', compact('common_data', 'ajax_url'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::orderBy('country', 'asc')->pluck('country', 'id');
        $cities = LogisticPartner::distinct()->pluck('city')->sort()->values();
        return view('manage-visa.logistic-partners.create', compact('countries', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'country_id' => 'required|exists:countries,id',
                'city' => 'required|string|max:150',
                'office_name' => 'required|string|max:200',
                'contact_number' => 'required|string|max:50',
                'website' => 'nullable',
                'email' => 'nullable|email|max:150',
            ],
            [
                'country_id.required' => __('translation.CountryIsRequired'),
                'country_id.exists' => __('translation.CountryIsInvalid'),
                'city.required' => __('translation.CityIsRequired'),
                'office_name.required' => __('translation.OfficeNameIsRequired'),
                'contact_number.required' => __('translation.ContactNumberIsRequired'),
                'website.url' => __('translation.WebsiteMustBeValidUrl'),
                'email.email' => __('translation.EmailMustBeValid'),
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
            $store = new LogisticPartner();
            foreach ($requested_data as $key => $value) {
                if ($key === 'opening_hours' && !empty($value)) {
                    $store->$key = json_encode($value);
                } else {
                    $store->$key = $value;
                }
            }
            $store->created_by = Auth::user()->id;
            $store->created_by_ip = $request->ip();
            $store->save();
            Util::activityLog('LogisticPartner', 'Created', $store);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.CreatedSuccessfully'), 'redirect' => true, 'url' => route('manage-visa.logistic-partners.index')];
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
        $result = LogisticPartner::find($id);
        if (!$result) {
            return abort(404);
        }
        $countries = Country::orderBy('country', 'asc')->pluck('country', 'id');
        $cities = LogisticPartner::distinct()->pluck('city')->sort()->values();
        return view('manage-visa.logistic-partners.edit', compact('result', 'countries', 'cities'));
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
                'country_id' => 'required|exists:countries,id',
                'city' => 'required|string|max:150',
                'office_name' => 'required|string|max:200',
                'contact_number' => 'required|string|max:50',
                'website' => 'nullable',
                'email' => 'nullable|email|max:150',
            ],
            [
                'country_id.required' => __('translation.CountryIsRequired'),
                'country_id.exists' => __('translation.CountryIsInvalid'),
                'city.required' => __('translation.CityIsRequired'),
                'office_name.required' => __('translation.OfficeNameIsRequired'),
                'contact_number.required' => __('translation.ContactNumberIsRequired'),
                'website.url' => __('translation.WebsiteMustBeValidUrl'),
                'email.email' => __('translation.EmailMustBeValid'),
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
            $update = LogisticPartner::find($id);
            if (!$update) {
                return response()->json(['status' => 'error', 'message' => __('translation.RecordNotFound')], 404);
            }
            foreach ($requested_data as $key => $value) {
                if ($key === 'opening_hours' && !empty($value)) {
                    $update->$key = json_encode($value);
                } else {
                    $update->$key = $value;
                }
            }
            $update->updated_by = Auth::user()->id;
            $update->updated_by_ip = $request->ip();
            $update->save();

            Util::activityLog('LogisticPartner', 'Updated', $update);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.UpdatedSuccessfully'), 'redirect' => true, 'url' => route('manage-visa.logistic-partners.index')];
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
            $delete = LogisticPartner::find($id);
            if (!$delete) {
                return response()->json(['status' => 'error', 'message' => __('translation.RecordNotFound')], 404);
            }
            Util::activityLog('LogisticPartner', 'Deleted', $delete);
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
            $status = LogisticPartner::find($id);
            if (!$status) {
                return response()->json(['status' => 'error', 'message' => __('translation.RecordNotFound')], 404);
            }
            $status->status = ($status->status == 1) ? 0 : 1;
            $status->status_updated_by = Auth::user()->id;
            $status->status_updated_by_ip = request()->ip();
            $status->save();
            Util::activityLog('LogisticPartner', 'Status Updated', $status);
            $response = ['status' => 'success', 'message' => __('translation.StatusUpdatedSuccessfully')];
            $status_code = 200;
        } catch (\Exception $th) {
            Util::generateErrorLog($th);
            $response = ['status' => 'error', 'message' => $th->getMessage()];
            $status_code = 500;
        }
        return response()->json($response, $status_code);
    }

    /**
     * Get the ajax data for DataTables
     */
    public function getAjaxData()
    {
        if (request()->ajax()) {
            $query = LogisticPartner::with('created_by_user', 'country');

            if (request()->has('status_filter') && request()->status_filter != 'all') {
                switch (request()->status_filter) {
                    case 0:
                        $query->where('status', 0);
                        break;
                    case 1:
                        $query->where('status', 1);
                        break;
                    default:
                        break;
                }
            }

            $data = $query->orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('country', function ($row) {
                    return $row->country->country ?? 'N/A';
                })
                ->editColumn('created_at', function ($row) {
                    return date('d/m/Y h:i A', strtotime($row->created_at));
                })
                ->editColumn('created_by', function ($row) {
                    return $row->created_by_user->full_name ?? 'N/A';
                })
                ->editColumn('status', function ($row) {
                    if (Gate::allows('manage_visa.logistic_partners.status')) {
                        if ($row->status == 0) {
                            $status = '<button type="button" class="btn btn-outline-danger btn-sm update_status" data-url="' . route('manage-visa.logistic-partners.status', $row->id) . '"> ' . __('translation.Inactive') . ' </button>';
                        } else {
                            $status = '<button type="button" class="btn btn-outline-success btn-sm update_status" data-url="' . route('manage-visa.logistic-partners.status', $row->id) . '"> ' . __('translation.Active') . ' </button>';
                        }
                    } else {
                        if ($row->status == 0) {
                            $status = '<span class="badge badge-danger">' . __('translation.Inactive') . '</span>';
                        } else {
                            $status = '<span class="badge badge-success">' . __('translation.Active') . '</span>';
                        }
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (Gate::allows('manage_visa.logistic_partners.edit')) {
                        $btn .= '<a href="' . route('manage-visa.logistic-partners.edit', $row->id) . '" class="btn btn-outline-warning btn-sm" title="' . __('translation.Edit') . '"> <i class="fas fa-pencil-alt"></i></a>&nbsp;';
                    }
                    if (Gate::allows('manage_visa.logistic_partners.delete')) {
                        $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete_record" data-url="' . route('manage-visa.logistic-partners.destroy', $row->id) . '" title="' . __('translation.Delete') . '"> <i class="fas fa-trash"></i></button>&nbsp;';
                    }
                    return $btn;
                })
                ->rawColumns(['DT_RowIndex', 'country', 'created_at', 'created_by', 'status', 'action'])
                ->make(true);
        }
    }
}
