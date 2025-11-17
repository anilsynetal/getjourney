<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:contacts.list|settings.contacts.add|settings.contacts.edit|settings.contacts.delete|settings.contacts.status', ['only' => ['index', 'show']]);
        $this->middleware('permission:settings.contacts.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:settings.contacts.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:settings.contacts.delete', ['only' => ['destroy']]);
        $this->middleware('permission:settings.contacts.status', ['only' => ['status']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contact_count = Contact::get()->count();
        $common_data = Contact::getTableData($contact_count);
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
        $route_action = route('settings.contacts.store');
        $page_title = __('translation.Add') . ' ' . __('translation.Contact');
        $fields = Contact::fields();
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
                'address' => 'required',
                'phone' => 'required',
                'email' => 'required|email',
                // 'facebook' => 'url',
                // 'twitter' => 'url',
                // 'linkedin' => 'url',
                // 'instagram' => 'url',
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
            $store = new Contact();
            foreach ($requested_data as $key => $value) {
                $store->$key = $value;
            }
            $store->created_by = Auth::user()->id;
            $store->created_by_ip = $request->ip();
            $store->save();
            Util::activityLog('Contact', 'Created', $store);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.CreatedSuccessfully'), 'redirect' => true, 'url' => route('settings.contacts.index')];
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
        $result = Contact::find($id);
        $fields = Contact::fields($id);
        $route_action = route('settings.contacts.update', $id);
        $page_title = __('translation.Edit') . ' ' . __('translation.Contact');
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
                'address' => 'required',
                'phone' => 'required',
                'email' => 'required|email',
                // 'facebook' => 'url',
                // 'twitter' => 'url',
                // 'linkedin' => 'url',
                // 'instagram' => 'url',
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
            $update = Contact::find($id);
            foreach ($requested_data as $key => $value) {
                $update->$key = $value;
            }
            $update->updated_by = Auth::user()->id;
            $update->updated_by_ip = $request->ip();
            $update->save();

            Util::activityLog('Contact', 'Updated', $update);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.UpdatedSuccessfully'), 'redirect' => true, 'url' => route('settings.contacts.index')];
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
            $delete = Contact::find($id);
            Util::activityLog('Contact', 'Deleted', $delete);
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
            $status = Contact::find($id);
            $status->status = ($status->status == 1) ? 0 : 1;
            $status->status_updated_by = Auth::user()->id;
            $status->status_updated_by_ip = request()->ip();
            $status->save();
            Util::activityLog('Contact', 'Status Updated', $status);
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

            $query =  Contact::with('created_by_user');
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
                // ->editColumn('map', function ($row) {
                //     return  $row->map;
                // })

                ->addColumn('opening_hours', function ($row) {
                    $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                    $hours_html = '<ul class="list-unstyled mb-0">';
                    foreach ($days as $day) {
                        if (!empty($row->$day)) {
                            $hours_html .= '<li><strong>' . ucfirst($day) . ':</strong> ' . $row->$day . '</li>';
                        }
                    }
                    $hours_html .= '</ul>';
                    return $hours_html;
                })


                ->editColumn('status', function ($row) {
                    if (auth()->user()->can('settings.contacts.status')) {
                        if ($row->status == 0) {
                            $status = '<button type="button" class="btn btn-outline-danger btn-sm update_status" data-url="' . route('settings.contacts.status', $row->id) . '"> ' . __('translation.Inactive') . ' </button>';
                        } else {
                            $status = '<button type="button" class="btn btn-outline-success btn-sm update_status" data-url="' . route('settings.contacts.status', $row->id) . '"> ' . __('translation.Active') .
                                ' </button>';
                        }
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('settings.contacts.edit')) {
                        $btn .= '<a href="javascript:void(0);" data-url="' . route('settings.contacts.edit', $row->id) . '" class="btn btn-outline-warning btn-sm loadRecordModalLarge" title="' . __('translation.Edit') . '"> <i class="fas fa-pencil-alt"></i></a>&nbsp;';
                    }
                    if (auth()->user()->can('settings.contacts.delete')) {
                        $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete_record" data-url="' . route('settings.contacts.destroy', $row->id) . '" title="' . __('translation.Delete') . '"> <i class="fas fa-trash"></i></button>&nbsp;';
                    }
                    return $btn;
                })
                ->rawColumns(['DT_RowIndex', 'created_at', 'created_by', 'status', 'action', 'image', 'map', 'opening_hours'])
                ->make(true);
        }
    }
}
