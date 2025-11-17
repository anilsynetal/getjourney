<?php

namespace App\Http\Controllers;

use App\Models\TourPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Utils\Util;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class TourPackageController extends Controller
{
    public function __construct()
    {
        // Set PHP upload limits at runtime
        ini_set('upload_max_filesize', '10M');
        ini_set('post_max_size', '20M');
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', '300');

        // Reconnect to database if connection lost
        try {
            DB::reconnect();
        } catch (\Exception $e) {
            // Ignore reconnection errors during construction
        }

        $this->middleware('permission:tour-packages.list|tour-packages.add|tour-packages.edit|tour-packages.delete|tour-packages.status', ['only' => ['index', 'show']]);
        $this->middleware('permission:tour-packages.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:tour-packages.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:tour-packages.delete', ['only' => ['destroy']]);
        $this->middleware('permission:tour-packages.status', ['only' => ['status']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $common_data = TourPackage::getTableData();
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
        $page_title = __('translation.Add') . ' ' . __('translation.TourPackage');
        $fields = TourPackage::fields();
        return view('tour-packages.create', compact('page_title', 'fields'));
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
                'name' => 'required|unique:tour_packages,name',
                'highlights' => 'required',
                'description' => 'required',
                'itinerary' => 'required',
                'inclusions' => 'required',
                'exclusions' => 'required',
                'duration_days' => 'nullable|integer',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240', // 10MB
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        $requested_data = $request->except('_token', 'image');
        try {
            DB::beginTransaction();
            $store = new TourPackage();
            foreach ($requested_data as $key => $value) {
                $store->$key = $value;
            }
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image_name = Util::uploadFile($image, 'tour-packages');
                $store->image = $image_name;
            }
            $store->slug = Str::slug($request->name);
            $store->created_by = Auth::user()->id;
            $store->created_by_ip = $request->ip();
            $store->save();
            // Util::activityLog('TourPackage', 'Created', $store);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.CreatedSuccessfully'), 'redirect' => true, 'url' => route('tour-packages.index')];
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
        $result = TourPackage::find($id);
        $fields = TourPackage::fields($id);
        $page_title = __('translation.Edit') . ' ' . __('translation.TourPackage');

        return view('tour-packages.edit', compact('result', 'page_title', 'fields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $requested_data = $request->except('_token', '_method', 'image');
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'highlights' => 'required',
                'description' => 'required',
                'itinerary' => 'required',
                'inclusions' => 'required',
                'exclusions' => 'required',
                'duration_days' => 'nullable|integer',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240', // 10MB
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle MySQL "server has gone away" error by retrying
        $maxRetries = 2;
        $attempt = 0;

        while ($attempt <= $maxRetries) {
            try {
                DB::beginTransaction();

                $update = TourPackage::find($id);
                if ($request->hasFile('image')) {
                    //Delete old image
                    $update->image ? Util::unlinkFile($update->image) : '';
                    $image = $request->file('image');
                    $image_name = Util::uploadFile($image, 'tour-packages');
                    $update->image = $image_name;
                }
                foreach ($requested_data as $key => $value) {
                    $update->$key = $value;
                }
                $update->slug = Str::slug($request->name);
                $update->updated_by = Auth::user()->id;
                $update->updated_by_ip = $request->ip();
                $update->save();

                // Util::activityLog('TourPackage', 'Updated', $update);
                DB::commit();
                $response = ['status' => 'success', 'message' => __('translation.UpdatedSuccessfully'), 'redirect' => false, 'url' => route('tour-packages.index')];
                $status_code = 200;
                break; // Success, exit retry loop

            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();

                // Check if it's a "MySQL server has gone away" error
                if (str_contains($e->getMessage(), 'MySQL server has gone away') && $attempt < $maxRetries) {
                    $attempt++;
                    DB::reconnect(); // Reconnect to database
                    sleep(1); // Wait 1 second before retry
                    continue; // Retry the operation
                }

                // If not a connection error or max retries reached, show the actual error
                Util::generateErrorLog($e);
                $response = ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
                $status_code = 500;
                break;
            } catch (\Exception $th) {
                DB::rollBack();
                //Log error
                Util::generateErrorLog($th);
                $response = ['status' => 'error', 'message' => $th->getMessage()];
                $status_code = 500;
                break;
            }
        }

        return response()->json($response, $status_code);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $delete = TourPackage::find($id);
            // Util::activityLog('TourPackage', 'Deleted', $delete);
            //Delete the image
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
            $status = TourPackage::find($id);
            $status->status = ($status->status == 1) ? 0 : 1;
            $status->status_updated_by = Auth::user()->id;
            $status->status_updated_by_ip = request()->ip();
            $status->save();
            // Util::activityLog('TourPackage', 'Status Updated', $status);
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

            $query =  TourPackage::with('created_by_user', 'country');
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
                ->editColumn('image', function ($row) {
                    if (!$row->image) {
                        return '<span class="text-muted">' . __('translation.NoImage') . '</span>';
                    }
                    return '<img src="' . asset($row->image) . '" class="img-thumbnail" width="50" height="50" />';
                })
                ->editColumn('description', function ($row) {
                    return Str::limit(strip_tags($row->description), 50, '...');
                })

                ->editColumn('created_by', function ($row) {
                    return $row->created_by_user->full_name;
                })
                ->editColumn('duration_days', function ($row) {
                    return $row->duration_days ? $row->duration_days . ' ' . __('translation.Days') : __('translation.NA');
                })
                ->editColumn('country', function ($row) {
                    return $row->country ? $row->country->country : __('translation.NA');
                })

                ->editColumn('status', function ($row) {
                    if (auth()->user()->can('tour-packages.status')) {
                        if ($row->status == 0) {
                            $status = '<button type="button" class="btn btn-outline-danger btn-sm update_status" data-url="' . route('tour-packages.status', $row->id) . '"> ' . __('translation.Inactive') . ' </button>';
                        } else {
                            $status = '<button type="button" class="btn btn-outline-success btn-sm update_status" data-url="' . route('tour-packages.status', $row->id) . '"> ' . __('translation.Active') .
                                ' </button>';
                        }
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('tour-packages.edit')) {
                        $btn .= '<a href="' . route('tour-packages.edit', $row->id) . '" class="btn btn-outline-warning btn-sm" title="' . __('translation.Edit') . '"> <i class="fas fa-pencil-alt"></i></a>&nbsp;';
                    }
                    if (auth()->user()->can('tour-packages.delete')) {
                        $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete_record" data-url="' . route('tour-packages.destroy', $row->id) . '" title="' . __('translation.Delete') . '"> <i class="fas fa-trash"></i></button>&nbsp;';
                    }
                    //View
                    $btn .= '<a target="_blank" href="' . route('website.tour-package-details', $row->slug) . '" class="btn btn-outline-info btn-sm" title="' . __('translation.View') . '"> <i class="fas fa-eye"></i></a>&nbsp;';
                    return $btn;
                })
                ->rawColumns(['DT_RowIndex', 'created_at', 'created_by', 'status', 'action', 'image', 'duration_days', 'description', 'country'])
                ->make(true);
        }
    }
}
