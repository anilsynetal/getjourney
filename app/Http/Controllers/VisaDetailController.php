<?php

namespace App\Http\Controllers;

use App\Models\VisaDetail;
use App\Models\VisaDetailDocument;
use App\Models\Country;
use App\Models\VisaCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;

class VisaDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage_visa.visa_details.list|manage_visa.visa_details.add|manage_visa.visa_details.edit|manage_visa.visa_details.delete|manage_visa.visa_details.status', ['only' => ['index', 'show']]);
        $this->middleware('permission:manage_visa.visa_details.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:manage_visa.visa_details.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:manage_visa.visa_details.delete', ['only' => ['destroy']]);
        $this->middleware('permission:manage_visa.visa_details.status', ['only' => ['status']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $common_data = VisaDetail::getTableData();
        $ajax_url = $common_data['ajax_url'];
        return view('common.index', compact('common_data', 'ajax_url'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $route_action = route('manage-visa.visa-details.store');
        $page_title = __('translation.Add') . ' ' . __('translation.VisaDetail');
        $countries = Country::orderBy('country', 'asc')->pluck('country', 'id');
        $visa_categories = VisaCategory::where('status', 1)->orderBy('name', 'asc')->pluck('name', 'id');
        $cities = VisaDetail::distinct()->pluck('city')->sort()->values();
        return view('manage-visa.visa-details.create', compact('route_action', 'page_title', 'countries', 'visa_categories', 'cities'));
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
                'visa_fees' => 'nullable|string',
                'logistic_charges' => 'nullable|string',
                'processing_time' => 'nullable|string|max:255',
            ],
            [
                'country_id.required' => __('translation.CountryIsRequired'),
                'country_id.exists' => __('translation.CountryIsInvalid'),
                'city.required' => __('translation.CityIsRequired'),
                'visa_category_id.required' => __('translation.VisaCategoryIsRequired'),
                'visa_category_id.exists' => __('translation.VisaCategoryIsInvalid'),
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
            $store = new VisaDetail();
            $store->country_id = $request->country_id;
            $store->city = $request->city;
            $store->visa_category_id = $request->visa_category_id;
            $store->visa_fees = $request->visa_fees;
            $store->logistic_charges = $request->logistic_charges;
            $store->processing_time = $request->processing_time;
            $store->created_by = Auth::user()->id;
            $store->created_by_ip = $request->ip();
            $store->save();

            if ($request->has('document_title')) {
                $titles = $request->document_title;
                $descriptions = $request->document_description;
                $files = $request->file('document_file');

                foreach ($titles as $index => $title) {
                    if (!empty($title)) {
                        $document = new VisaDetailDocument();
                        $document->visa_detail_id = $store->id;
                        $document->title = $title;
                        $document->description = $descriptions[$index] ?? null;

                        // Handle file upload
                        if (isset($files[$index]) && $files[$index]->isValid()) {
                            $file = $files[$index];
                            $fileName = time() . '_' . $index . '_' . $file->getClientOriginalName();
                            $filePath = $file->storeAs('uploads/visa_documents', $fileName, 'public');
                            $document->file = $filePath;
                        }

                        $document->created_by = Auth::user()->id;
                        $document->created_by_ip = $request->ip();
                        $document->save();

                        Util::activityLog('VisaDetailDocument', 'Created', $document);
                    }
                }
            }

            Util::activityLog('VisaDetail', 'Created', $store);
            DB::commit();

            $response = ['status' => 'success', 'message' => __('translation.CreatedSuccessfully'), 'redirect' => true, 'url' => route('manage-visa.visa-details.index')];
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
        $result = VisaDetail::with('documents')->find($id);
        if (!$result) {
            return abort(404);
        }
        $route_action = route('manage-visa.visa-details.update', $id);
        $page_title = __('translation.Edit') . ' ' . __('translation.VisaDetail');
        $countries = Country::orderBy('country', 'asc')->pluck('country', 'id');
        $visa_categories = VisaCategory::where('status', 1)->orderBy('name', 'asc')->pluck('name', 'id');
        $cities = VisaDetail::distinct()->pluck('city')->sort()->values();
        return view('manage-visa.visa-details.edit', compact('result', 'route_action', 'page_title', 'countries', 'visa_categories', 'cities'));
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
                'visa_fees' => 'nullable|string',
                'logistic_charges' => 'nullable|string',
                'processing_time' => 'nullable|string|max:255',
            ],
            [
                'country_id.required' => __('translation.CountryIsRequired'),
                'country_id.exists' => __('translation.CountryIsInvalid'),
                'city.required' => __('translation.CityIsRequired'),
                'visa_category_id.required' => __('translation.VisaCategoryIsRequired'),
                'visa_category_id.exists' => __('translation.VisaCategoryIsInvalid'),
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
            $update = VisaDetail::find($id);
            if (!$update) {
                return response()->json(['status' => 'error', 'message' => __('translation.RecordNotFound')], 404);
            }

            $update->country_id = $request->country_id;
            $update->city = $request->city;
            $update->visa_category_id = $request->visa_category_id;
            $update->visa_fees = $request->visa_fees;
            $update->logistic_charges = $request->logistic_charges;
            $update->processing_time = $request->processing_time;
            $update->updated_by = Auth::user()->id;
            $update->updated_by_ip = $request->ip();
            $update->save();
            // Delete old documents and their files
            $oldDocuments = $update->documents;
            foreach ($oldDocuments as $oldDoc) {
                if ($oldDoc->file && Storage::disk('public')->exists($oldDoc->file)) {
                    Storage::disk('public')->delete($oldDoc->file);
                }
            }
            $update->documents()->delete();

            if ($request->has('document_title')) {
                $titles = $request->document_title;
                $descriptions = $request->document_description;
                $files = $request->file('document_file');

                foreach ($titles as $index => $title) {
                    if (!empty($title)) {
                        $document = new VisaDetailDocument();
                        $document->visa_detail_id = $update->id;
                        $document->title = $title;
                        $document->description = $descriptions[$index] ?? null;

                        // Handle file upload
                        if (isset($files[$index]) && $files[$index]->isValid()) {
                            $file = $files[$index];
                            $fileName = time() . '_' . $index . '_' . $file->getClientOriginalName();
                            $filePath = $file->storeAs('uploads/visa_documents', $fileName, 'public');
                            $document->file = $filePath;
                        }

                        $document->created_by = Auth::user()->id;
                        $document->created_by_ip = $request->ip();
                        $document->save();

                        Util::activityLog('VisaDetailDocument', 'Created', $document);
                    }
                }
            }

            Util::activityLog('VisaDetail', 'Updated', $update);
            DB::commit();

            $response = ['status' => 'success', 'message' => __('translation.UpdatedSuccessfully'), 'redirect' => true, 'url' => route('manage-visa.visa-details.index')];
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
            $delete = VisaDetail::find($id);
            if (!$delete) {
                return response()->json(['status' => 'error', 'message' => __('translation.RecordNotFound')], 404);
            }

            // Delete associated document files
            foreach ($delete->documents as $document) {
                if ($document->file && Storage::disk('public')->exists($document->file)) {
                    Storage::disk('public')->delete($document->file);
                }
            }

            Util::activityLog('VisaDetail', 'Deleted', $delete);
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
            $status = VisaDetail::find($id);
            if (!$status) {
                return response()->json(['status' => 'error', 'message' => __('translation.RecordNotFound')], 404);
            }
            $status->status = ($status->status == 1) ? 0 : 1;
            $status->status_updated_by = Auth::user()->id;
            $status->status_updated_by_ip = request()->ip();
            $status->save();

            Util::activityLog('VisaDetail', 'Status Updated', $status);
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
            $query = VisaDetail::with('created_by_user', 'country', 'visa_category', 'documents');

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
                ->editColumn('visa_category', function ($row) {
                    return $row->visa_category->name ?? 'N/A';
                })
                ->editColumn('visa_fees', function ($row) {
                    return $row->visa_fees ? '<span class="badge badge-primary">Done</span>' : '<span class="badge badge-secondary">N/A</span>';
                })
                ->editColumn('logistic_charges', function ($row) {
                    return $row->logistic_charges ? '<span class="badge badge-primary">Done</span>' : '<span class="badge badge-secondary">N/A</span>';
                })
                ->addColumn('documents_count', function ($row) {
                    return '<span class="badge badge-info">' . $row->documents->count() . '</span>';
                })
                ->editColumn('created_by', function ($row) {
                    return $row->created_by_user->full_name ?? 'N/A';
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 0) {
                        $status = '<button type="button" class="btn btn-outline-danger btn-sm update_status" data-url="' . route('manage-visa.visa-details.status', $row->id) . '"> ' . __('translation.Inactive') . ' </button>';
                    } else {
                        $status = '<button type="button" class="btn btn-outline-success btn-sm update_status" data-url="' . route('manage-visa.visa-details.status', $row->id) . '"> ' . __('translation.Active') . ' </button>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= '<a href="' . route('manage-visa.visa-details.edit', $row->id) . '" class="btn btn-outline-warning btn-sm" title="' . __('translation.Edit') . '"> <i class="fas fa-pencil-alt"></i></a>&nbsp;';
                    $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete_record" data-url="' . route('manage-visa.visa-details.destroy', $row->id) . '" title="' . __('translation.Delete') . '"> <i class="fas fa-trash"></i></button>&nbsp;';
                    return $btn;
                })
                ->rawColumns(['DT_RowIndex', 'country', 'visa_category', 'visa_fees', 'logistic_charges', 'documents_count', 'created_by', 'status', 'action'])
                ->make(true);
        }
    }
}
