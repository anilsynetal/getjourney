<?php

namespace App\Http\Controllers;

use App\Models\MainMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use Spatie\MailTemplates\Models\MailTemplate;

class MainMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:settings.main-menu.list|settings.main-menu.add|settings.main-menu.edit|settings.main-menu.delete|settings.main-menu.status|settings.main-menu.restore', ['only' => ['index', 'show']]);
        $this->middleware('permission:settings.main-menu.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:settings.main-menu.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:settings.main-menu.delete', ['only' => ['destroy']]);
        $this->middleware('permission:settings.main-menu.status', ['only' => ['status']]);
        $this->middleware('permission:settings.main-menu.restore', ['only' => ['restore']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $common_data = MainMenu::getTableData();
        $ajax_url = $common_data['ajax_url'];
        return view('main-menus.index', compact('common_data', 'ajax_url'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $route_action = route('settings.main-menus.store');
        $page_title = __('translation.Add') . ' ' . __('translation.MainMenu');
        $icons = Util::getIconList();
        return view('main-menus.create', compact('route_action', 'page_title', 'icons'));
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
                'menu_name' => 'required|unique:main_menus,menu_name',
                'route_name' => 'required',
                'menu_icon' => 'required',
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
            $order = MainMenu::max('order');
            $store = new MainMenu();
            foreach ($requested_data as $key => $value) {
                $store->$key = $value;
            }
            $store->order = $order + 1;
            $store->created_by = Auth::user()->id;
            $store->created_by_ip = $request->ip();
            $store->save();
            Util::activityLog('MainMenu', 'Created', $store);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.CreatedSuccessfully'), 'redirect' => true, 'url' => route('settings.main-menus.index')];
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
        $result = MainMenu::find($id);
        $route_action = route('settings.main-menus.update', $id);
        $page_title = __('translation.Edit') . ' ' . __('translation.MainMenu');
        $icons = Util::getIconList();
        return view('main-menus.edit', compact('result', 'route_action', 'page_title', 'icons'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $requested_data = $request->except('_token', '_method');
        $validator = Validator::make(
            $requested_data,
            [
                'menu_name' => 'required|unique:main_menus,menu_name,' . $id,
                'route_name' => 'required',
                'menu_icon' => 'required',
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
            $update = MainMenu::find($id);
            foreach ($requested_data as $key => $value) {
                $update->$key = $value;
            }
            $update->updated_by = Auth::user()->id;
            $update->updated_by_ip = $request->ip();
            $update->save();

            Util::activityLog('MainMenu', 'Updated', $update);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.UpdatedSuccessfully'), 'redirect' => true, 'url' => route('settings.main-menus.index')];
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
            $delete = MainMenu::find($id);
            Util::activityLog('MainMenu', 'Deleted', $delete);
            $delete->deleted_by = Auth::user()->id;
            $delete->deleted_by_ip = request()->ip();
            $delete->save();
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
            $status = MainMenu::find($id);
            $status->status = ($status->status == 1) ? 0 : 1;
            $status->status_updated_by = Auth::user()->id;
            $status->status_updated_by_ip = request()->ip();
            $status->save();
            Util::activityLog('MainMenu', 'Status Updated', $status);
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

            $query =  MainMenu::with('created_by_user');
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
            $data = $query->where('created_by', Auth::user()->id)->orderBy('order', 'asc')->get();
            return Datatables::of($data)
                // ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return date('d/m/Y h:i A', strtotime($row->created_at));
                })
                ->editColumn('menu_icon', function ($row) {
                    return '<i class="' . $row->menu_icon . '"></i>';
                })

                ->editColumn('created_by', function ($row) {
                    return $row->created_by_user->full_name;
                })
                ->editColumn('status', function ($row) {
                    if (auth()->user()->can('settings.main-menu.status')) {
                        if ($row->status == 0) {
                            $status = '<button type="button" class="btn btn-outline-danger btn-sm update_status" data-url="' . route('settings.main-menus.status', $row->id) . '"> ' . __('translation.Inactive') . ' </button>';
                        } else {
                            $status = '<button type="button" class="btn btn-outline-success btn-sm update_status" data-url="' . route('settings.main-menus.status', $row->id) . '"> ' . __('translation.Active') .
                                ' </button>';
                        }
                    }
                    return $row->deleted_at == null ? $status : '<span class="badge bg-danger">' . __('translation.Deleted') . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if ($row->deleted_at == null) {
                        if (auth()->user()->can('settings.main-menu.edit')) {
                            $btn .= '<a href="' . route('settings.main-menus.edit', $row->id) . '" class="btn btn-outline-warning btn-sm" title="' . __('translation.Edit') . '"> <i class="fas fa-pencil-alt"></i></a>&nbsp;';
                        }
                        if (auth()->user()->can('settings.main-menu.delete')) {
                            $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete_record" data-url="' . route('settings.main-menus.destroy', $row->id) . '" title="' . __('translation.Delete') . '"> <i class="fas fa-trash"></i></button>&nbsp;';
                        }
                    } else {
                        if (auth()->user()->can('settings.main-menu.restore')) {
                            $btn .= '<button type="button" class="btn btn-outline-success btn-sm restore_record" data-url="' . route('settings.main-menus.restore', $row->id) . '" title="' . __('translation.Restore') . '"> <i class="fas fa-trash-restore"></i></button>&nbsp;';
                        }
                    }
                    return $btn;
                })
                ->rawColumns(['DT_RowIndex', 'created_at', 'created_by', 'status', 'action', 'menu_icon'])
                ->make(true);
        }
    }

    public function restore($id)
    {
        try {
            $restore = MainMenu::withTrashed()->find($id);
            $restore->restore();
            Util::activityLog('MainMenu', 'Restored', $restore);
            $response = ['status' => 'success', 'message' => __('translation.RestoredSuccessfully')];
            $status_code = 200;
        } catch (\Exception $th) {
            //Log error
            Util::generateErrorLog($th);
            $response = ['status' => 'error', 'message' => $th->getMessage()];
            $status_code = 500;
        }
        return response()->json($response, $status_code);
    }

    //update the order
    public function update_order(Request $request)
    {
        try {
            $order = $request->order;
            foreach ($order as $key => $value) {
                $update = MainMenu::find($value);
                $update->order = $key + 1;
                $update->save();
            }
            $response = ['status' => 'success', 'message' => __('translation.UpdatedSuccessfully')];
            $status_code = 200;
        } catch (\Exception $th) {
            //Log error
            Util::generateErrorLog($th);
            $response = ['status' => 'error', 'message' => $th->getMessage()];
            $status_code = 500;
        }
        return response()->json($response, $status_code);
    }
}
