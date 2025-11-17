<?php

namespace App\Http\Controllers;

use App\Models\MainMenu;
use App\Models\SubMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;

class SubMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:settings.sub-menu.list|settings.sub-menu.add|settings.sub-menu.edit|settings.sub-menu.delete|settings.sub-menu.status|settings.sub-menu.restore', ['only' => ['index', 'show']]);
        $this->middleware('permission:settings.sub-menu.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:settings.sub-menu.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:settings.sub-menu.delete', ['only' => ['destroy']]);
        $this->middleware('permission:settings.sub-menu.status', ['only' => ['status']]);
        $this->middleware('permission:settings.sub-menu.restore', ['only' => ['restore']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $common_data = SubMenu::getTableData();
        $ajax_url = $common_data['ajax_url'];
        return view('sub-menus.index', compact('common_data', 'ajax_url'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $route_action = route('settings.sub-menus.store');
        $page_title = __('translation.Add') . ' ' . __('translation.SubMenu');
        $main_menus = MainMenu::where('status', 1)->where('created_by', Auth::user()->id)->get();
        $icons = Util::getIconList();
        return view('sub-menus.create', compact('route_action', 'page_title', 'main_menus', 'icons'));
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
                'menu_name' => 'required|unique:sub_menus,menu_name',
                'route_name' => 'required',
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
            $store = new SubMenu();
            foreach ($requested_data as $key => $value) {
                $store->$key = $value;
            }
            $store->created_by = Auth::user()->id;
            $store->created_by_ip = $request->ip();
            $store->save();
            Util::activityLog('SubMenu', 'Created', $store);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.CreatedSuccessfully'), 'redirect' => true, 'url' => route('settings.sub-menus.index')];
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
        $result = SubMenu::find($id);
        $route_action = route('settings.sub-menus.update', $id);
        $page_title = __('translation.Edit') . ' ' . __('translation.SubMenu');
        $main_menus = MainMenu::where('status', 1)->where('created_by', Auth::user()->id)->get();
        $icons = Util::getIconList();
        return view('sub-menus.edit', compact('result', 'route_action', 'page_title', 'main_menus', 'icons'));
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
                'menu_name' => 'required',
                'route_name' => 'required',
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
            $update = SubMenu::find($id);
            foreach ($requested_data as $key => $value) {
                $update->$key = $value;
            }
            $update->updated_by = Auth::user()->id;
            $update->updated_by_ip = $request->ip();
            $update->save();

            Util::activityLog('SubMenu', 'Updated', $update);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.UpdatedSuccessfully'), 'redirect' => true, 'url' => route('settings.sub-menus.index')];
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
            $delete = SubMenu::find($id);
            Util::activityLog('SubMenu', 'Deleted', $delete);
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
            $status = SubMenu::find($id);
            $status->status = ($status->status == 1) ? 0 : 1;
            $status->status_updated_by = Auth::user()->id;
            $status->status_updated_by_ip = request()->ip();
            $status->save();
            Util::activityLog('SubMenu', 'Status Updated', $status);
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

            $query =  SubMenu::with('created_by_user', 'main_menu')->whereNotIn('table_name', Util::exceptTables());
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
                ->addColumn('main_menu', function ($row) {
                    return $row->main_menu->menu_name;
                })
                ->editColumn('status', function ($row) {
                    if (auth()->user()->can('settings.sub-menu.status')) {
                        if ($row->status == 0) {
                            $status = '<button type="button" class="btn btn-outline-danger btn-sm update_status" data-url="' . route('settings.sub-menus.status', $row->id) . '"> ' . __('translation.Inactive') . ' </button>';
                        } else {
                            $status = '<button type="button" class="btn btn-outline-success btn-sm update_status" data-url="' . route('settings.sub-menus.status', $row->id) . '"> ' . __('translation.Active') .
                                ' </button>';
                        }
                    }
                    return $row->deleted_at == null ? $status : '<span class="badge bg-danger">' . __('translation.Deleted') . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if ($row->deleted_at == null) {
                        if (auth()->user()->can('settings.sub-menu.edit')) {
                            $btn .= '<a href="' . route('settings.sub-menus.edit', $row->id) . '" class="btn btn-outline-warning btn-sm" title="' . __('translation.Edit') . '"> <i class="fas fa-pencil-alt"></i></a>&nbsp;';
                        }
                        if (auth()->user()->can('settings.sub-menu.delete')) {
                            $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete_record" data-url="' . route('settings.sub-menus.destroy', $row->id) . '" title="' . __('translation.Delete') . '"> <i class="fas fa-trash"></i></button>&nbsp;';
                        }
                    } else {
                        if (auth()->user()->can('settings.sub-menu.restore')) {
                            $btn .= '<button type="button" class="btn btn-outline-success btn-sm restore_record" data-url="' . route('settings.sub-menus.restore', $row->id) . '" title="' . __('translation.Restore') . '"> <i class="fas fa-trash-restore"></i></button>&nbsp;';
                        }
                    }
                    return $btn;
                })
                ->rawColumns(['DT_RowIndex', 'created_at', 'created_by', 'status', 'action', 'menu_icon', 'main_menu'])
                ->make(true);
        }
    }

    public function restore($id)
    {
        try {
            $restore = SubMenu::withTrashed()->find($id);
            $restore->restore();
            Util::activityLog('SubMenu', 'Restored', $restore);
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
                $update = SubMenu::find($value);
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

    // get sub menu by main menu
    public function getSubMenuByMainMenu(Request $request)
    {
        $whereNotInTable = Util::getCustomFieldTableNames();
        $sub_menus = SubMenu::select('menu_name as name', 'id')->whereNotIn('table_name', $whereNotInTable)->where('main_menu_id', $request->id)->whereNotNull('table_name')->where('status', 1)->orderBy('menu_name', 'asc')->get();
        return response()->json($sub_menus, 200);
    }
}
