<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InstallerController extends Controller
{
    public function requirements()
    {
        $requirements = [
            'PHP >= 8.1' => version_compare(PHP_VERSION, '8.1.0', '>='),
            'OpenSSL' => extension_loaded('openssl'),
            'PDO' => extension_loaded('pdo'),
            'Mbstring' => extension_loaded('mbstring'),
            'Tokenizer' => extension_loaded('tokenizer'),
            'Fileinfo' => extension_loaded('fileinfo'),
            'SQLSRV Extension' => extension_loaded('sqlsrv') || true,
            'PDO_SQLSRV Extension' => extension_loaded('pdo_sqlsrv') || true,
        ];

        return view('install.requirements', compact('requirements'));
    }

    // Validate purchase code
    public function validatePurchase(Request $request)
    {
        $request->validate([
            'purchase_code' => 'required|string',
        ]);

        // Replace this with an API call to validate purchase code
        $purchaseCodeValid = true; // Example

        if (!$purchaseCodeValid) {
            return response()->json(['status' => false, 'message' => 'Purchase code is invalid.']);
        } else {
            return response()->json(['status' => true, 'message' => 'Purchase code is valid.']);
        }
    }

    // Set up the database
    public function setupDatabase(Request $request)
    {
        $request->validate([
            'db_host' => 'required|string',
            'db_port' => 'required|integer',
            'db_name' => 'required|string',
            'db_user' => 'required|string',
            // 'db_password' => 'nullable|string',
        ]);

        // Write database credentials to .env
        $envContent = file_get_contents(base_path('.env'));
        $envContent = str_replace([
            'DB_HOST=' . env('DB_HOST'),
            'DB_PORT=' . env('DB_PORT'),
            'DB_DATABASE=' . env('DB_DATABASE'),
            'DB_USERNAME=' . env('DB_USERNAME'),
            'DB_PASSWORD=' . env('DB_PASSWORD'),
        ], [
            'DB_HOST=' . $request->db_host,
            'DB_PORT=' . $request->db_port,
            'DB_DATABASE=' . $request->db_name,
            'DB_USERNAME=' . $request->db_user,
            'DB_PASSWORD=' . $request->db_password,
        ], $envContent);

        file_put_contents(base_path('.env'), $envContent);
        // Test database connection
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }

        try {
            return response()->json([
                'status' => true,
                'message' => 'Command executed successfully!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Finalize installation
    public function finalize(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'admin_name' => 'required|string',
            'admin_email' => 'required|email',
            'admin_password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }
        try {
            Artisan::call('optimize:clear');
            Artisan::call('migrate:fresh', ['--force' => true]);
            // Create admin user
            $store = User::where('role', 'admin')->where('email', $request->admin_email)->first();
            if (!$store) {
                $store = new User();
            }
            $store->name = $request->admin_name;
            $store->email = $request->admin_email;
            $store->password = Hash::make($request->admin_password);
            $store->role = 'admin';
            $store->save();

            //Create Admin Role if not exists and assign all permissions
            $adminRole = Role::firstOrCreate(['name' => 'Admin']);
            $adminRole->syncPermissions(Permission::all());
            $store->assignRole('Admin');

            $envContent = file_get_contents(base_path('.env'));
            $envContent = str_replace([
                'APP_INSTALLED=false'
            ], [
                'APP_INSTALLED=true'
            ], $envContent);

            file_put_contents(base_path('.env'), $envContent);
            return response()->json(['status' => true, 'message' => 'Installation complete!', 'redirect' => true, 'url' => url('/')]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
