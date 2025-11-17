<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use Illuminate\Support\Str;

class FeatureController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:features.list|features.add|features.edit|features.delete|features.status', ['only' => ['index', 'show']]);
        $this->middleware('permission:features.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:features.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:features.delete', ['only' => ['destroy']]);
        $this->middleware('permission:features.status', ['only' => ['status']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $common_data = Feature::getTableData();
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
        $route_action = route('features.store');
        $page_title = __('translation.Add') . ' ' . __('translation.Feature');
        $fields = Feature::fields();
        return view('common.modal.create', compact('route_action', 'page_title', 'fields'));
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
                'feature_name' => 'required|unique:features,feature_name',
                'description' => 'required',
                'icon' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        $requested_data = $request->except('_token', 'icon', 'image', 'is_core_feature');
        try {
            DB::beginTransaction();
            $store = new Feature();
            foreach ($requested_data as $key => $value) {
                $store->$key = $value;
            }
            if ($request->hasFile('icon')) {
                $icon = $request->file('icon');
                $icon_name = Util::uploadFile($icon, 'features');
                $store->icon = $icon_name;
            }
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image_name = Util::uploadFile($image, 'features');
                $store->image = $image_name;
            }
            $store->is_core_feature = $request->has('is_core_feature');
            $store->slug = Str::slug($store->feature_name);
            $store->created_by = Auth::user()->id;
            $store->created_by_ip = $request->ip();
            $store->save();
            Util::activityLog('Feature', 'Created', $store);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.CreatedSuccessfully'), 'redirect' => false, 'url' => route('features.index', ['type' => $store->type])];
            $status_code = 200;
        } catch (\Exception $th) {
            //Log error
            Util::generateErrorLog($th);
            $response = ['status' => 'error', 'message' => $th->getMessage()];
            $status_code = 500;
        }
        return response()->json($response, $status_code);
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
        $result = Feature::find($id);
        $fields = Feature::fields($id);
        $route_action = route('features.update', $id);
        $page_title = __('translation.Edit') . ' ' . __('translation.Feature');

        return view('common.modal.edit', compact('result', 'route_action', 'page_title', 'fields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $requested_data = $request->except('_token', '_method', 'icon', 'image', 'is_key_feature');
        $validator = Validator::make(
            $request->all(),
            [
                'feature_name' => 'required|unique:features,feature_name,' . $id,
                'description' => 'required',
                'icon' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ],
            [
                'feature_category_id.required' => __('translation.FeatureCategoryRequired'),
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
            $update = Feature::find($id);
            if ($request->hasFile('icon')) {
                //Delete old icon
                $update->icon ? Util::unlinkFile($update->icon) : '';
                $icon = $request->file('icon');
                $icon_name = Util::uploadFile($icon, 'features');
                $update->icon = $icon_name;
            }

            if ($request->hasFile('image')) {
                //Delete old image
                $update->image ? Util::unlinkFile($update->image) : '';
                $image = $request->file('image');
                $image_name = Util::uploadFile($image, 'features');
                $update->image = $image_name;
            }
            foreach ($requested_data as $key => $value) {
                $update->$key = $value;
            }
            $update->is_core_feature = $request->has('is_core_feature') ? 1 : 0;
            $update->slug = Str::slug($update->feature_name);
            $update->updated_by = Auth::user()->id;
            $update->updated_by_ip = $request->ip();
            $update->save();

            Util::activityLog('Feature', 'Updated', $update);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.UpdatedSuccessfully'), 'redirect' => false, 'url' => route('features.index', ['type' => $update->type])];
            $status_code = 200;
        } catch (\Exception $th) {
            //Log error
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
            $delete = Feature::find($id);
            Util::activityLog('Feature', 'Deleted', $delete);
            //Delete the image
            if ($delete->icon) {
                Util::unlinkFile($delete->icon);
            }
            if ($delete->image) {
                Util::unlinkFile($delete->image);
            }
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

    /**
     * Change the status of the record
     */
    public function status(string $id)
    {
        try {
            $status = Feature::find($id);
            $status->status = ($status->status == 1) ? 0 : 1;
            $status->status_updated_by = Auth::user()->id;
            $status->status_updated_by_ip = request()->ip();
            $status->save();
            Util::activityLog('Feature', 'Status Updated', $status);
            $response = ['status' => 'success', 'message' => __('translation.StatusUpdatedSuccessfully')];
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

            $query =  Feature::with('created_by_user');
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
                ->editColumn('icon', function ($row) {
                    if ($row->icon) {
                        return '<img src="' . asset($row->icon) . '" class="img-thumbnail" width="50" height="50" />';
                    }
                    return '';
                })
                ->editColumn('image', function ($row) {
                    if ($row->image) {
                        return '<img src="' . asset($row->image) . '" class="img-thumbnail" width="50" height="50" />';
                    }
                    return '';
                })

                ->editColumn('created_by', function ($row) {
                    return $row->created_by_user->full_name;
                })
                ->editColumn('is_core_feature', function ($row) {
                    return $row->is_core_feature ? 'Yes' : 'No';
                })

                ->editColumn('status', function ($row) {
                    if (auth()->user()->can('features.status')) {
                        if ($row->status == 0) {
                            $status = '<button type="button" class="btn btn-outline-danger btn-sm update_status" data-url="' . route('features.status', $row->id) . '"> ' . __('translation.Inactive') . ' </button>';
                        } else {
                            $status = '<button type="button" class="btn btn-outline-success btn-sm update_status" data-url="' . route('features.status', $row->id) . '"> ' . __('translation.Active') .
                                ' </button>';
                        }
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('features.edit')) {
                        $btn .= '<a href="javascript:void(0);" data-url="' . route('features.edit', $row->id) . '" class="btn btn-outline-warning btn-sm loadRecordModal" title="' . __('translation.Edit') . '"> <i class="fas fa-pencil-alt"></i></a>&nbsp;';
                    }
                    if (auth()->user()->can('features.delete')) {
                        $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete_record" data-url="' . route('features.destroy', $row->id) . '" title="' . __('translation.Delete') . '"> <i class="fas fa-trash"></i></button>&nbsp;';
                    }
                    return $btn;
                })
                ->rawColumns(['DT_RowIndex', 'created_at', 'created_by', 'status', 'action', 'icon', 'image'])
                ->make(true);
        }
    }
}
