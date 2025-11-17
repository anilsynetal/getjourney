<?php

namespace App\Http\Controllers;

use App\Models\MainMenu;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Utils\Util;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users_and_roles.role-master.list|users_and_roles.role-master.add|users_and_roles.role-master.edit|users_and_roles.role-master.delete|users_and_roles.role-master.status|users_and_roles.role-master.restore', ['only' => ['index', 'show']]);
        $this->middleware('permission:users_and_roles.role-master.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:users_and_roles.role-master.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users_and_roles.role-master.delete', ['only' => ['destroy']]);
        $this->middleware('permission:users_and_roles.role-master.status', ['only' => ['status']]);
        $this->middleware('permission:users_and_roles.role-master.restore', ['only' => ['restore']]);
    }
    public function index()
    {
        $results = Role::get();
        $page_title = __('translation.RoleList');
        return view('roles.index', compact('results', 'page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $route_action = route('user-managements.roles.store');
        $page_title = __('translation.Add') . ' ' . __('translation.Role');
        $main_menus = MainMenu::with('sub_menus')->where('status', 1)->orderBy('order')->get();
        return view('roles.create', compact('route_action', 'page_title', 'main_menus'));
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
                'name' => 'required|unique:roles,name'
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        if (empty($request->permissions)) {
            return response()->json([
                'status' => 'error',
                'message' => __('translation.permission_field_is_required')
            ], 500);
        }
        try {
            DB::beginTransaction();
            $store = new Role();
            $store->name = $request->name;
            $store->guard_name = 'web';
            $store->save();

            //Create permission if not exists
            $this->__createPermissionIfNotExists($request->permissions);

            //Sync permission to role
            if (!empty($request->permissions)) {
                $store->syncPermissions($request->permissions);
            }

            //Sync permission for admin
            $admin = Role::findByName('Admin');
            $admin->syncPermissions(Permission::all());
            Util::activityLog('Role', 'Created', $store);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.CreatedSuccessfully'), 'redirect' => true, 'url' => route('user-managements.roles.index')];
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
        $route_action = route('user-managements.roles.update', $id);
        $page_title = __('translation.Edit') . ' ' . __('translation.Role');
        $result = Role::with(['permissions'])->find($id);
        $main_menus = MainMenu::with('sub_menus')->where('status', 1)->orderBy('order')->get();
        return view('roles.edit', compact('result', 'route_action', 'page_title', 'main_menus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:roles,name,' . $id
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        if (empty($request->permissions)) {
            return response()->json([
                'status' => 'error',
                'message' => __('translation.permission_field_is_required')
            ], 500);
        }

        try {
            DB::beginTransaction();
            $update = Role::find($id);
            $update->name = $request->name;
            $update->save();

            //Create permission if not exists
            $this->__createPermissionIfNotExists($request->permissions);

            //Sync permission to role
            if (!empty($request->permissions)) {
                $update->syncPermissions($request->permissions);
            }

            //Sync permission for admin
            $admin = Role::findByName('Admin');
            $admin->syncPermissions(Permission::all());

            Util::activityLog('Role', 'Updated', $update);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.UpdatedSuccessfully'), 'redirect' => true, 'url' => route('user-managements.roles.index')];
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
            $delete = Role::find($id);
            //Check if role is assigned to any user
            if ($delete->users()->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('translation.role_assigned_to_user')
                ], 500);
            }
            Util::activityLog('Role', 'Deleted', $delete);
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


    private function __createPermissionIfNotExists($permissions)
    {
        $exising_permissions = Permission::whereIn('name', $permissions)
            ->pluck('name')
            ->toArray();

        $non_existing_permissions = array_diff($permissions, $exising_permissions);

        if (! empty($non_existing_permissions)) {
            foreach ($non_existing_permissions as $new_permission) {
                Permission::create([
                    'name' => $new_permission,
                    'guard_name' => 'web',
                ]);
            }
        }
    }
}
