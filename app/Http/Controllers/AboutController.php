<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;

class AboutController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:settings.about-us.list|settings.about-us.add|settings.about-us.edit|settings.about-us.delete|settings.about-us.status', ['only' => ['index', 'show']]);
        $this->middleware('permission:settings.about-us.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:settings.about-us.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:settings.about-us.delete', ['only' => ['destroy']]);
        $this->middleware('permission:settings.about-us.status', ['only' => ['status']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $count = About::get()->count();
        $common_data = About::getTableData($count);
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
        $route_action = route('settings.about-us.store');
        $page_title = __('translation.Add') . ' ' . __('translation.About');
        $fields = About::fields();
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
                'title' => 'required',
                'description' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        $requested_data = $request->except('_token', 'image1', 'image2');
        try {
            DB::beginTransaction();
            $store = new About();
            foreach ($requested_data as $key => $value) {
                $store->$key = $value;
            }
            if ($request->hasFile('image1')) {
                $image = $request->file('image1');
                $image_name = Util::uploadFile($image, 'about-us');
                $store->image1 = $image_name;
            }
            if ($request->hasFile('image2')) {
                $image = $request->file('image2');
                $image_name = Util::uploadFile($image, 'about-us');
                $store->image2 = $image_name;
            }
            $store->created_by = Auth::user()->id;
            $store->created_by_ip = $request->ip();
            $store->save();
            Util::activityLog('About', 'Created', $store);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.CreatedSuccessfully'), 'redirect' => true, 'url' => route('settings.about-us.index')];
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
        $result = About::find($id);
        $fields = About::fields($id);
        $route_action = route('settings.about-us.update', $id);
        $page_title = __('translation.Edit') . ' ' . __('translation.About');
        return view('common.modal.edit', compact('result', 'route_action', 'page_title', 'fields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $requested_data = $request->except('_token', '_method', 'image1', 'image2');
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
            $update = About::find($id);
            if ($request->hasFile('image1')) {
                //Delete old image
                $update->image1 ? Util::unlinkFile($update->image1) : null;
                $image = $request->file('image1');
                $image_name = Util::uploadFile($image, 'about-us');
                $update->image1 = $image_name;
            }
            if ($request->hasFile('image2')) {
                //Delete old image
                $update->image2 ? Util::unlinkFile($update->image2) : null;
                $image = $request->file('image2');
                $image_name = Util::uploadFile($image, 'about-us');
                $update->image2 = $image_name;
            }
            foreach ($requested_data as $key => $value) {
                $update->$key = $value;
            }
            $update->updated_by = Auth::user()->id;
            $update->updated_by_ip = $request->ip();
            $update->save();

            Util::activityLog('About', 'Updated', $update);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.UpdatedSuccessfully'), 'redirect' => true, 'url' => route('settings.about-us.index')];
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
            $delete = About::find($id);
            Util::activityLog('About', 'Deleted', $delete);
            //Delete the image
            if ($delete->image1) {
                Util::unlinkFile($delete->image1);
            }
            if ($delete->image2) {
                Util::unlinkFile($delete->image2);
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
            $status = About::find($id);
            $status->status = ($status->status == 1) ? 0 : 1;
            $status->status_updated_by = Auth::user()->id;
            $status->status_updated_by_ip = request()->ip();
            $status->save();
            Util::activityLog('About', 'Status Updated', $status);
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

            $query =  About::with('created_by_user');
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
                ->editColumn('image1', function ($row) {
                    return $row->image1 ? '<img src="' . asset($row->image1) . '" class="img-thumbnail" width="50" height="50" />' : '';
                })
                ->editColumn('image2', function ($row) {
                    return $row->image2 ? '<img src="' . asset($row->image2) . '" class="img-thumbnail" width="50" height="50" />' : '';
                })

                ->editColumn('created_by', function ($row) {
                    return $row->created_by_user->full_name;
                })

                ->editColumn('status', function ($row) {
                    if (auth()->user()->can('settings.about-us.status')) {
                        if ($row->status == 0) {
                            $status = '<button type="button" class="btn btn-outline-danger btn-sm update_status" data-url="' . route('settings.about-us.status', $row->id) . '"> ' . __('translation.Inactive') . ' </button>';
                        } else {
                            $status = '<button type="button" class="btn btn-outline-success btn-sm update_status" data-url="' . route('settings.about-us.status', $row->id) . '"> ' . __('translation.Active') .
                                ' </button>';
                        }
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('settings.about-us.edit')) {
                        $btn .= '<a href="javascript:void(0);" data-url="' . route('settings.about-us.edit', $row->id) . '" class="btn btn-outline-warning btn-sm loadRecordModal" title="' . __('translation.Edit') . '"> <i class="fas fa-pencil-alt"></i></a>&nbsp;';
                    }
                    if (auth()->user()->can('settings.about-us.delete')) {
                        $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete_record" data-url="' . route('settings.about-us.destroy', $row->id) . '" title="' . __('translation.Delete') . '"> <i class="fas fa-trash"></i></button>&nbsp;';
                    }
                    return $btn;
                })
                ->rawColumns(['DT_RowIndex', 'created_at', 'created_by', 'status', 'action', 'image1', 'image2'])
                ->make(true);
        }
    }
}
