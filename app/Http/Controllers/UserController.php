<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users_and_roles.users.list|users_and_roles.users.add|users_and_roles.users.edit|users_and_roles.users.delete|users_and_roles.users.status|users_and_roles.users.restore', ['only' => ['index', 'show']]);
        $this->middleware('permission:users_and_roles.users.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:users_and_roles.users.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users_and_roles.users.delete', ['only' => ['destroy']]);
        $this->middleware('permission:users_and_roles.users.status', ['only' => ['status']]);
        $this->middleware('permission:users_and_roles.users.restore', ['only' => ['restore']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $common_data = User::getTableData();
        $ajax_url = $common_data['ajax_url'];
        return view('common.index', compact('common_data', 'ajax_url'));
    }

    public function create()
    {
        $fields = User::fields();
        $route_action = route('user-managements.users.store');
        $page_title = __('translation.Add') . ' ' . __('translation.Client');
        return view('common.modal.create', compact('fields', 'route_action', 'page_title'))->render();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required|string|min:2|max:20',
                'last_name' => 'nullable|string|min:2|max:20',
                'email' => 'required|email|unique:users,email',
                'mobile' => 'required',
                'designation' => 'required',
                'address' => 'required',
                'role_id' => 'required|exists:roles,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg, gif,svg|max:2048',
                'status' => 'required',
                'password' => [
                    'required',
                    'string',
                    'confirmed',
                    'min:8',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                    'regex:/[@$!%*#?&]/',
                ],
            ],
            [
                'mobile.required' => 'Mobile number is required',
                'role_id.required' => 'Role is required',
                'role_id.exists' => 'Selected role does not exist',
                'password.regex' => 'Password should be at least 8 characters, contain upper case, lower case, numbers and special characters (!@£$%^&)'
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $requested_data = $request->except('_token', 'role_id', 'image', 'password_confirmation');
        //remove special characters from mobile number
        $mobile =  preg_replace('/[^0-9]/', '', $request->mobile);
        try {
            DB::beginTransaction();
            //Get Unique Code From User Table
            $store = new User();
            $store->unique_code = Util::generateUniqueCode('ADS');
            foreach ($requested_data as $key => $value) {
                $store->$key = $value;
            }
            $store->mobile =  substr($mobile, -10);
            $store->country_code = $request->country_code;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image_name = Util::uploadFile($image, 'users');
                $store->image = $image_name;
            }
            $store->created_by = Auth::user()->id;
            $store->created_by_ip = $request->ip();
            $store->save();

            //Assign Role To User
            $role = Role::find($request->role_id);
            $store->assignRole($role->name);

            Util::activityLog('Client', 'Created', $store);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.CreatedSuccessfully')];
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
        $result = User::with('roles')->find($id);
        $fields = User::fields(true);
        $route_action = route('user-managements.users.update', $id);
        $page_title = __('translation.Edit') . ' ' . __('translation.Client');
        $result->role_id = $result->roles->first()->id;
        return view('common.modal.edit', compact('result', 'fields', 'route_action', 'page_title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required|string|min:2|max:20',
                'last_name' => 'nullable|string|min:2|max:20',
                'email' => 'required|email|unique:users,email,' . $id,
                'mobile' => 'required',
                'country_code' => 'required',
                'designation' => 'required',
                'address' => 'required',
                'role_id' => 'required|exists:roles,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg, gif,svg|max:2048',
                'status' => 'required',
            ],
            [
                'mobile.required' => 'Mobile number is required',
                'role_id.required' => 'Role is required',
                'role_id.exists' => 'Selected role does not exist',

            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        $requested_data = $request->except('_token', '_method', 'role_id', 'image');
        //remove special characters from mobile number
        $mobile =  preg_replace('/[^0-9]/', '', $request->mobile);
        try {
            DB::beginTransaction();
            $update = User::find($id);
            foreach ($requested_data as $key => $value) {
                $update->$key = $value;
            }
            $update->mobile = substr($mobile, -10);
            $update->country_code = $request->country_code;
            if ($request->hasFile('image')) {
                //Delete old image
                $update->image ?  Util::unlinkFile($update->image) : '';
                $image = $request->file('image');
                $image_name = Util::uploadFile($image, 'users');
                $update->image = $image_name;
            }
            $update->updated_by = Auth::user()->id;
            $update->updated_by_ip = $request->ip();
            $update->save();

            //Remove Previous Role
            $update->syncRoles([]); // Clear previous roles

            //Assign New Role
            $role = Role::find($request->role_id);
            $update->assignRole($role->name);

            Util::activityLog('Client', 'Updated', $update);
            DB::commit();
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $delete = User::find($id);
            Util::activityLog('Client', 'Deleted', $delete);
            // if ($delete->image) {
            //     Util::unlinkFile($delete->image);
            // }
            // if ($delete->aadhar) {
            //     Util::unlinkFile($delete->aadhar);
            // }
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
            $status = User::find($id);
            $status->status = ($status->status == 1) ? 0 : 1;
            $status->status_updated_by = Auth::user()->id;
            $status->status_updated_by_ip = request()->ip();
            $status->save();

            Util::activityLog('Client', 'Status Updated', $status);
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
            $query =  User::with('roles');
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
            if (Auth::user()->role != 'admin') {
                $data = $query->where('status', 1);
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
            $data = $query->orderBy('id', 'desc')->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return date('d/m/Y h:i A', strtotime($row->created_at));
                })
                ->editColumn('image', function ($row) {
                    return '<img src="' . $row->getImageUrlAttribute() . '" class="rounded-circle" width="50px" height="50px" />
                           ';
                })
                ->editColumn('aadhar', function ($row) {
                    if ($row->aadhar) {
                        return '<a href="' . $row->getAadharUrlAttribute() . '" target="_blank" class="btn btn-outline-primary btn-sm">' . __('translation.ViewAadhar') . '</a>';
                    }
                    return '-';
                })
                ->editColumn('address', function ($row) {
                    $address = '';
                    if ($row->address) {
                        $address .= $row->address;
                    }
                    if ($row->city) {
                        $address .= ', ' . $row->city->city;
                    }
                    if ($row->state) {
                        $address .= ', ' . $row->state->state;
                    }
                    if ($row->country) {
                        $address .= ', ' . $row->country->country;
                    }
                    if ($row->zip) {
                        $address .= ', ' . $row->zip;
                    }
                    return $address ? $address : '-';
                })
                ->editColumn('created_by', function ($row) {
                    return $row->created_by_user ? $row->created_by_user->full_name : 'self';
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 0) {
                        $status = '<button type="button" class="btn btn-outline-danger btn-sm update_status mb-2" data-url="' . route('user-managements.users.status', $row->id) . '"> ' . __('translation.Inactive') . ' </button>';
                    } else {
                        $status = '<button type="button" class="btn btn-outline-success btn-sm update_status" data-url="' . route('user-managements.users.status', $row->id) . '"> ' . __('translation.Active') .
                            ' </button>';
                    }
                    return $row->deleted_at == null ? $status : '<span class="badge bg-danger">' . __('translation.Deleted') . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if ($row->role != 'admin') {
                        if ($row->deleted_at == null) {
                            $btn .= '<a href="javascript:void(0);" data-url="' . route('user-managements.users.edit', $row->id) . '" class="btn btn-outline-warning btn-sm mb-2 loadRecordModalLarge" title="' . __('translation.Edit') . '"> <i class="fas fa-pencil-alt"></i></a>&nbsp;';
                            $btn .= '<button type="button" class="btn btn-outline-danger btn-sm mb-2 delete_record" data-url="' . route('user-managements.users.destroy', $row->id) . '" title="' . __('translation.Delete') . '"> <i class="fas fa-trash"></i></button>&nbsp;';
                            $btn .= '<button type="button" class="btn btn-outline-info btn-sm mb-2 loadRecordModal" data-url="' . route('user-managements.users.change-password', $row->id) . '" title="' . __('translation.ChangePassword') . '"> <i class="fas fa-key"></i></button>&nbsp;';
                        } else {
                            $btn .= '<button type="button" class="btn btn-outline-success btn-sm mb-2 restore_record" data-url="' . route('user-managements.users.restore', $row->id) . '" title="' . __('translation.Restore') . '"> <i class="fas fa-trash-restore"></i></button>&nbsp;';
                        }
                        //View button
                        $btn .= '<a  class="btn btn-outline-primary btn-sm" href="' . route('user-managements.users.dashboard', $row->id) . '" title="' . __('translation.Dashboard') . '">
                        <i class="fas fa-tachometer-alt"></i></a>&nbsp;';
                    } else {
                        $btn .= '--';
                    }
                    return $btn;
                })
                ->rawColumns(['DT_RowIndex',  'created_at', 'status', 'action', 'role', 'image', 'aadhar', 'address'])
                ->make(true);
        }
    }

    public function restore($id)
    {
        try {
            $restore = User::withTrashed()->find($id);
            $restore->restore();

            Util::activityLog('Client', 'Restored', $restore);
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

    //Change password
    public function change_password($id)
    {
        $route_action = route('user-managements.users.update-password', $id);
        return view('common.change-password', compact('route_action'))->render();
    }

    //Update password
    public function update_password(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'password' => [
                    'required',
                    'string',
                    'confirmed',
                    // 'min:10',
                    // 'regex:/[a-z]/',
                    // 'regex:/[A-Z]/',
                    // 'regex:/[0-9]/',
                    // 'regex:/[@$!%*#?&]/',
                ],
            ],
            [
                // 'password.regex' => 'Password should be at least 10 characters, contain upper case, lower case, numbers and special characters (!@£$%^&)'
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            $update  = User::find($id);
            $update->password = Hash::make($request->password);
            $update->is_password_updated = 0;
            $update->save();
            Util::activityLog('Client', 'Password Updated', $update);
            $response = ['status' => 'success', 'message' => __('translation.PasswordUpdatedSuccessfully')];
            $status_code = 200;
        } catch (\Throwable $th) {
            //Log error
            Util::generateErrorLog($th);
            $response = ['status' => 'error', 'message' => $th->getMessage()];
            $status_code = 500;
        }
        return response()->json($response, $status_code);
    }

    // get employee data by id
    public function getUserData(Request $request)
    {
        $data = User::where('user_id', request()->id)->first();
        return response()->json($data, 200);
    }

    //Login as user
    public function dashboard($id)
    {
        $admin_id = Auth::user()->id;
        $user = User::find($id);
        if ($user) {
            Auth::login($user);
            session()->put('admin_id', $admin_id);
            return redirect('/home');
        } else {
            return redirect()->back()->with('error', 'User not found');
        }
    }
}
