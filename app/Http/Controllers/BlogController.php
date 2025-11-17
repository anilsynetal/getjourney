<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use App\Utils\Util;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage_blogs.blogs.list|manage_blogs.blogs.add|manage_blogs.blogs.edit|manage_blogs.blogs.delete|manage_blogs.blogs.status', ['only' => ['index', 'show']]);
        $this->middleware('permission:manage_blogs.blogs.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:manage_blogs.blogs.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:manage_blogs.blogs.delete', ['only' => ['destroy']]);
        $this->middleware('permission:manage_blogs.blogs.status', ['only' => ['status']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $common_data = Blog::getTableData();
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
        $route_action = route('manage-blogs.blogs.store');
        $page_title = __('translation.Add') . ' ' . __('translation.Blog');
        $fields = Blog::fields();
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
                'blog_category_id' => 'required',
                'title' => 'required',
                'description' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ],
            [
                'blog_category_id.required' => 'The blog category field is required'
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
            $store = new Blog();
            foreach ($requested_data as $key => $value) {
                $store->$key = $value;
            }
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image_name = Util::uploadFile($image, 'blogs');
                $store->image = $image_name;
            }
            $store->slug = Str::slug($request->title);
            $store->created_by = Auth::user()->id;
            $store->created_by_ip = $request->ip();
            $store->save();
            Util::activityLog('Blog', 'Created', $store);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.CreatedSuccessfully'), 'redirect' => false, 'url' => route('manage-blogs.blogs.index')];
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
        $result = Blog::find($id);
        $fields = Blog::fields($id);
        $route_action = route('manage-blogs.blogs.update', $id);
        $page_title = __('translation.Edit') . ' ' . __('translation.Blog');
        return view('common.modal.edit', compact('result', 'route_action', 'page_title', 'fields'));
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
                'blog_category_id' => 'required',
                'title' => 'required',
                'description' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ],
            [
                'blog_category_id.required' => 'The blog category field is required.'
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
            $update = Blog::find($id);
            if ($request->hasFile('image')) {
                //Delete old image
                Util::unlinkFile($update->image);
                $image = $request->file('image');
                $image_name = Util::uploadFile($image, 'blogs');
                $update->image = $image_name;
            }
            foreach ($requested_data as $key => $value) {
                $update->$key = $value;
            }
            $update->slug = Str::slug($request->title);
            $update->updated_by = Auth::user()->id;
            $update->updated_by_ip = $request->ip();
            $update->save();

            Util::activityLog('Blog', 'Updated', $update);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.UpdatedSuccessfully'), 'redirect' => false, 'url' => route('manage-blogs.blogs.index')];
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
            $delete = Blog::find($id);
            Util::activityLog('Blog', 'Deleted', $delete);
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
            $status = Blog::find($id);
            $status->status = ($status->status == 1) ? 0 : 1;
            $status->status_updated_by = Auth::user()->id;
            $status->status_updated_by_ip = request()->ip();
            $status->save();
            Util::activityLog('Blog', 'Status Updated', $status);
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

            $query =  Blog::with('created_by_user', 'blog_category');
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
                    return '<img src="' . asset($row->image) . '" class="img-thumbnail" width="50" height="50" />';
                })
                ->editColumn('blog_category_id', function ($row) {
                    return $row->blog_category->name;
                })

                ->editColumn('created_by', function ($row) {
                    return $row->created_by_user->full_name;
                })
                ->editColumn('status', function ($row) {
                    if (auth()->user()->can('manage_blogs.blogs.status')) {
                        if ($row->status == 0) {
                            $status = '<button type="button" class="btn btn-outline-danger btn-sm update_status" data-url="' . route('manage-blogs.blogs.status', $row->id) . '"> ' . __('translation.Inactive') . ' </button>';
                        } else {
                            $status = '<button type="button" class="btn btn-outline-success btn-sm update_status" data-url="' . route('manage-blogs.blogs.status', $row->id) . '"> ' . __('translation.Active') .
                                ' </button>';
                        }
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('manage_blogs.blogs.edit')) {
                        $btn .= '<a href="javascript:void(0);" data-url="' . route('manage-blogs.blogs.edit', $row->id) . '" class="btn btn-outline-warning btn-sm loadRecordModalLarge" title="' . __('translation.Edit') . '"> <i class="fas fa-pencil-alt"></i></a>&nbsp;';
                    }
                    if (auth()->user()->can('manage_blogs.blogs.delete')) {
                        $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete_record" data-url="' . route('manage-blogs.blogs.destroy', $row->id) . '" title="' . __('translation.Delete') . '"> <i class="fas fa-trash"></i></button>&nbsp;';
                    }
                    return $btn;
                })
                ->rawColumns(['DT_RowIndex', 'created_at', 'created_by', 'blog_category_id', 'status', 'action', 'image'])
                ->make(true);
        }
    }
}
