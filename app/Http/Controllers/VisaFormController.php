<?php

namespace App\Http\Controllers;

use App\Models\VisaForm;
use App\Models\Country;
use App\Models\VisaCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;

class VisaFormController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage_visa.visa_forms.list|manage_visa.visa_forms.add|manage_visa.visa_forms.edit|manage_visa.visa_forms.delete|manage_visa.visa_forms.status', ['only' => ['index', 'show']]);
        $this->middleware('permission:manage_visa.visa_forms.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:manage_visa.visa_forms.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:manage_visa.visa_forms.delete', ['only' => ['destroy']]);
        $this->middleware('permission:manage_visa.visa_forms.status', ['only' => ['status']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $common_data = VisaForm::getTableData();
        $ajax_url = $common_data['ajax_url'];
        return view('common.index', compact('common_data', 'ajax_url'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $route_action = route('manage-visa.visa-forms.store');
        $page_title = __('translation.Add') . ' ' . __('translation.VisaForm');
        $fields = VisaForm::fields();
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
                'city' => 'required|string|max:150',
                'visa_category_id' => 'required|exists:visa_categories,id',
                'visa_form' => 'nullable|mimes:pdf|max:5120',
                'application_form_url' => 'nullable|url',
            ],
            [
                'country_id.required' => __('translation.CountryIsRequired'),
                'country_id.exists' => __('translation.CountryIsInvalid'),
                'city.required' => __('translation.CityIsRequired'),
                'visa_category_id.required' => __('translation.VisaCategoryIsRequired'),
                'visa_category_id.exists' => __('translation.VisaCategoryIsInvalid'),
                'visa_form.mimes' => __('translation.VisaFormMustBePDF'),
                'visa_form.max' => __('translation.VisaFormMaxSize'),
                'application_form_url.url' => __('translation.ApplicationFormURLMustBeValid'),
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
            $store = new VisaForm();

            $store->country_id = $request->country_id;
            $store->city = $request->city;
            $store->visa_category_id = $request->visa_category_id;
            $store->application_form_url = $request->application_form_url;

            // Handle file upload
            if ($request->hasFile('visa_form')) {
                $file = $request->file('visa_form');
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('visa-forms', $filename, 'public');
                $store->visa_form = $filePath;
            }

            $store->created_by = Auth::user()->id;
            $store->created_by_ip = $request->ip();
            $store->save();

            Util::activityLog('VisaForm', 'Created', $store);
            DB::commit();

            $response = ['status' => 'success', 'message' => __('translation.CreatedSuccessfully'), 'redirect' => false, 'url' => route('manage-visa.visa-forms.index')];
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
        $result = VisaForm::find($id);
        if (!$result) {
            return abort(404);
        }
        $route_action = route('manage-visa.visa-forms.update', $id);
        $page_title = __('translation.Edit') . ' ' . __('translation.VisaForm');
        $fields = VisaForm::fields($id);
        return view('common.modal.edit', compact('result', 'route_action', 'page_title', 'fields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'country_id' => 'required|exists:countries,id',
                'city' => 'required|string|max:150',
                'visa_category_id' => 'required|exists:visa_categories,id',
                'visa_form' => 'nullable|mimes:pdf|max:5120',
                'application_form_url' => 'nullable|url',
            ],
            [
                'country_id.required' => __('translation.CountryIsRequired'),
                'country_id.exists' => __('translation.CountryIsInvalid'),
                'city.required' => __('translation.CityIsRequired'),
                'visa_category_id.required' => __('translation.VisaCategoryIsRequired'),
                'visa_category_id.exists' => __('translation.VisaCategoryIsInvalid'),
                'visa_form.mimes' => __('translation.VisaFormMustBePDF'),
                'visa_form.max' => __('translation.VisaFormMaxSize'),
                'application_form_url.url' => __('translation.ApplicationFormURLMustBeValid'),
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
            $update = VisaForm::find($id);
            if (!$update) {
                return response()->json(['status' => 'error', 'message' => __('translation.RecordNotFound')], 404);
            }

            $update->country_id = $request->country_id;
            $update->city = $request->city;
            $update->visa_category_id = $request->visa_category_id;
            $update->application_form_url = $request->application_form_url;

            // Handle file upload
            if ($request->hasFile('visa_form')) {
                // Delete old file if exists
                if ($update->visa_form && Storage::disk('public')->exists($update->visa_form)) {
                    Storage::disk('public')->delete($update->visa_form);
                }

                $file = $request->file('visa_form');
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('visa-forms', $filename, 'public');
                $update->visa_form = $filePath;
            }

            $update->updated_by = Auth::user()->id;
            $update->updated_by_ip = $request->ip();
            $update->save();

            Util::activityLog('VisaForm', 'Updated', $update);
            DB::commit();

            $response = ['status' => 'success', 'message' => __('translation.UpdatedSuccessfully'), 'redirect' => false, 'url' => route('manage-visa.visa-forms.index')];
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
            $delete = VisaForm::find($id);
            if (!$delete) {
                return response()->json(['status' => 'error', 'message' => __('translation.RecordNotFound')], 404);
            }

            // Delete file if exists
            if ($delete->visa_form && Storage::disk('public')->exists($delete->visa_form)) {
                Storage::disk('public')->delete($delete->visa_form);
            }

            Util::activityLog('VisaForm', 'Deleted', $delete);
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
            $status = VisaForm::find($id);
            if (!$status) {
                return response()->json(['status' => 'error', 'message' => __('translation.RecordNotFound')], 404);
            }
            $status->status = ($status->status == 1) ? 0 : 1;
            $status->status_updated_by = Auth::user()->id;
            $status->status_updated_by_ip = request()->ip();
            $status->save();

            Util::activityLog('VisaForm', 'Status Updated', $status);
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
            $query = VisaForm::with('created_by_user', 'country', 'visa_category');

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
                ->editColumn('category', function ($row) {
                    return $row->visa_category->name ?? 'N/A';
                })
                ->editColumn('visa_form', function ($row) {
                    if ($row->visa_form) {
                        $url = asset('storage/' . $row->visa_form);
                        return '<a href="' . $url . '" target="_blank" class="btn btn-outline-info btn-sm"><i class="fas fa-download"></i>  &nbsp;' . __('translation.Download') . '</a>';
                    }
                    return '<span class="badge badge-warning">' . __('translation.NotUploaded') . '</span>';
                })
                ->editColumn('application_form_url', function ($row) {
                    if ($row->application_form_url) {
                        return '<a href="' . $row->application_form_url . '" target="_blank" class="text-primary"><i class="fas fa-link"></i> ' . __('translation.View') . '</a>';
                    }
                    return '<span class="badge badge-secondary">N/A</span>';
                })
                ->editColumn('created_by', function ($row) {
                    return $row->created_by_user->full_name ?? 'N/A';
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 0) {
                        $status = '<button type="button" class="btn btn-outline-danger btn-sm update_status" data-url="' . route('manage-visa.visa-forms.status', $row->id) . '"> ' . __('translation.Inactive') . ' </button>';
                    } else {
                        $status = '<button type="button" class="btn btn-outline-success btn-sm update_status" data-url="' . route('manage-visa.visa-forms.status', $row->id) . '"> ' . __('translation.Active') . ' </button>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= '<a href="javascript:void(0);" data-url="' . route('manage-visa.visa-forms.edit', $row->id) . '" class="btn btn-outline-warning btn-sm loadRecordModal" title="' . __('translation.Edit') . '"> <i class="fas fa-pencil-alt"></i></a>&nbsp;';
                    $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete_record" data-url="' . route('manage-visa.visa-forms.destroy', $row->id) . '" title="' . __('translation.Delete') . '"> <i class="fas fa-trash"></i></button>&nbsp;';
                    return $btn;
                })
                ->rawColumns(['DT_RowIndex', 'country', 'category', 'visa_form', 'application_form_url', 'created_by', 'status', 'action'])
                ->make(true);
        }
    }
}
