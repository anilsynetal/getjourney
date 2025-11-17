<?php

namespace App\Http\Controllers;

use App\Models\InternationalHelpAddress;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;

class InternationalHelpAddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage_visa.international-help-addresses.list|manage_visa.international-help-addresses.add|manage_visa.international-help-addresses.edit|manage_visa.international-help-addresses.delete|manage_visa.international-help-addresses.status', ['only' => ['index', 'show']]);
        $this->middleware('permission:manage_visa.international-help-addresses.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:manage_visa.international-help-addresses.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:manage_visa.international-help-addresses.delete', ['only' => ['destroy']]);
        $this->middleware('permission:manage_visa.international-help-addresses.status', ['only' => ['status']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $common_data = InternationalHelpAddress::getTableData();
        $ajax_url = $common_data['ajax_url'];
        return view('common.index', compact('common_data', 'ajax_url'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $route_action = route('manage-visa.international-help-addresses.store');
        $page_title = __('translation.Add') . ' ' . __('translation.InternationalHelpAddress');
        $fields = InternationalHelpAddress::fields();
        return view('common.modal.create', compact('route_action', 'page_title', 'fields'));
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
                'title' => 'required|string|max:255',
                'link' => 'required|url',
            ],
            [
                'country_id.required' => __('translation.CountryIsRequired'),
                'country_id.exists' => __('translation.CountryIsInvalid'),
                'title.required' => __('translation.TitleIsRequired'),
                'link.required' => __('translation.LinkIsRequired'),
                'link.url' => __('translation.LinkMustBeValidUrl'),
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
            $store = new InternationalHelpAddress();
            foreach ($requested_data as $key => $value) {
                $store->$key = $value;
            }
            $store->created_by = Auth::user()->id;
            $store->created_by_ip = $request->ip();
            $store->save();
            Util::activityLog('InternationalHelpAddress', 'Created', $store);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.CreatedSuccessfully'), 'redirect' => false, 'url' => route('manage-visa.international-help-addresses.index')];
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
        $result = InternationalHelpAddress::find($id);
        if (!$result) {
            return abort(404);
        }
        $fields = InternationalHelpAddress::fields($id);
        $route_action = route('manage-visa.international-help-addresses.update', $id);
        $page_title = __('translation.Edit') . ' ' . __('translation.InternationalHelpAddress');

        return view('common.modal.edit', compact('result', 'route_action', 'page_title', 'fields'));
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
                'title' => 'required|string|max:255',
                'link' => 'required|url',
            ],
            [
                'country_id.required' => __('translation.CountryIsRequired'),
                'country_id.exists' => __('translation.CountryIsInvalid'),
                'title.required' => __('translation.TitleIsRequired'),
                'link.required' => __('translation.LinkIsRequired'),
                'link.url' => __('translation.LinkMustBeValidUrl'),
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
            $update = InternationalHelpAddress::find($id);
            if (!$update) {
                return response()->json(['status' => 'error', 'message' => __('translation.RecordNotFound')], 404);
            }
            foreach ($requested_data as $key => $value) {
                $update->$key = $value;
            }
            $update->updated_by = Auth::user()->id;
            $update->updated_by_ip = $request->ip();
            $update->save();

            Util::activityLog('InternationalHelpAddress', 'Updated', $update);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.UpdatedSuccessfully'), 'redirect' => false, 'url' => route('manage-visa.international-help-addresses.index')];
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
            $delete = InternationalHelpAddress::find($id);
            if (!$delete) {
                return response()->json(['status' => 'error', 'message' => __('translation.RecordNotFound')], 404);
            }
            Util::activityLog('InternationalHelpAddress', 'Deleted', $delete);
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
            $status = InternationalHelpAddress::find($id);
            if (!$status) {
                return response()->json(['status' => 'error', 'message' => __('translation.RecordNotFound')], 404);
            }
            $status->status = ($status->status == 1) ? 0 : 1;
            $status->status_updated_by = Auth::user()->id;
            $status->status_updated_by_ip = request()->ip();
            $status->save();
            Util::activityLog('InternationalHelpAddress', 'Status Updated', $status);
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
            $query = InternationalHelpAddress::with('created_by_user', 'country');

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
                ->editColumn('country_name', function ($row) {
                    return $row->country->country ?? 'N/A';
                })
                ->editColumn('created_at', function ($row) {
                    return date('d/m/Y h:i A', strtotime($row->created_at));
                })
                ->editColumn('created_by', function ($row) {
                    return $row->created_by_user->full_name ?? 'N/A';
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 0) {
                        $status = '<button type="button" class="btn btn-outline-danger btn-sm update_status" data-url="' . route('manage-visa.international-help-addresses.status', $row->id) . '"> ' . __('translation.Inactive') . ' </button>';
                    } else {
                        $status = '<button type="button" class="btn btn-outline-success btn-sm update_status" data-url="' . route('manage-visa.international-help-addresses.status', $row->id) . '"> ' . __('translation.Active') . ' </button>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('manage_visa.international-help-addresses.edit')) {
                        $btn .= '<a href="javascript:void(0);" data-url="' . route('manage-visa.international-help-addresses.edit', $row->id) . '" class="btn btn-outline-warning btn-sm loadRecordModal" title="' . __('translation.Edit') . '"> <i class="fas fa-pencil-alt"></i></a>&nbsp;';
                    }
                    if (auth()->user()->can('manage_visa.international-help-addresses.delete')) {
                        $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete_record" data-url="' . route('manage-visa.international-help-addresses.destroy', $row->id) . '" title="' . __('translation.Delete') . '"> <i class="fas fa-trash"></i></button>&nbsp;';
                    }
                    return $btn;
                })
                ->rawColumns(['DT_RowIndex', 'country_name', 'created_at', 'created_by', 'status', 'action'])
                ->make(true);
        }
    }
}
