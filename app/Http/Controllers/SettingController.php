<?php

namespace App\Http\Controllers;

use App\Facades\UtilityFacades;
use App\Mail\TestMail;
use App\Models\Setting;
use App\Services\SMSService;
use App\Services\WhatsAppService;
use App\Utils\Util;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Spatie\MailTemplates\Models\MailTemplate;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    protected $smsService;
    protected $whatsappService;

    public function __construct(SMSService $smsService, WhatsAppService $whatsappService)
    {
        $this->smsService = $smsService;
        $this->whatsappService = $whatsappService;
    }

    public function index()
    {
        $page_title = __('Settings');
        return view('settings.index', compact('page_title'));
    }

    public function appNameUpdate(Request $request)
    {
        request()->validate([
            'app_logo'          => 'image|max:2048',
            'app_dark_logo'     => 'image|max:2048',
            'favicon_logo'      => 'image|max:2048',
            'app_name'          => 'required|max:50',
        ]);
        $data = [
            'app_name' => $request->app_name,
        ];
        try {
            DB::beginTransaction();
            if ($request->app_logo) {
                Storage::delete(UtilityFacades::getsettings('app_logo'));
                $appLogoName        = 'app-logo.' . $request->app_logo->extension();
                $request->app_logo->storeAs('logo', $appLogoName, 'public');
                $data['app_logo']   = 'logo/' . $appLogoName;
            }
            if ($request->app_dark_logo) {
                Storage::delete(UtilityFacades::getsettings('app_dark_logo'));
                $appDarkLogoName        = 'app-dark-logo.' . $request->app_dark_logo->extension();
                $request->app_dark_logo->storeAs('logo', $appDarkLogoName, 'public');
                $data['app_dark_logo']  = 'logo/' . $appDarkLogoName;
            }
            if ($request->favicon_logo) {
                Storage::delete(UtilityFacades::getsettings('favicon_logo'));
                $faviconLogoName        = 'app-favicon-logo.' . $request->favicon_logo->extension();
                $request->favicon_logo->storeAs('logo', $faviconLogoName, 'public');
                $data['favicon_logo']   = 'logo/' . $faviconLogoName;
            }
            foreach ($data as $key => $value) {
                UtilityFacades::storesettings([
                    'key'   => $key,
                    'value' => $value,
                ]);
            }
            // Clear cache
            Cache::flush();

            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.app_setting_UpdatedSuccessfully'), 'redirect' => true, 'url' => route('settings.index')];
            $status_code = 200;
        } catch (\Exception $th) {
            //Log error
            Util::generateErrorLog($th);
            $response = ['status' => 'error', 'message' => $th->getMessage()];
            $status_code = 500;
        }
        return response()->json($response, $status_code);
    }

    public function emailSettingUpdate(Request $request)
    {
        request()->validate([
            'mail_mailer'       => 'required',
            'mail_host'         => 'required',
            'mail_port'         => 'required',
            'mail_username'     => 'required',
            'mail_password'     => 'required',
            'mail_encryption'   => 'required',
            'mail_from_address' => 'required',
            'mail_from_name'    => 'required',
        ]);
        $data = [
            'email_setting_enable'  => $request->email_setting_enable == 'on' ? 'on' : 'off',
            'mail_mailer'           => $request->mail_mailer,
            'mail_host'             => $request->mail_host,
            'mail_port'             => $request->mail_port,
            'mail_username'         => $request->mail_username,
            'mail_password'         => $request->mail_password,
            'mail_encryption'       => $request->mail_encryption,
            'mail_from_address'     => $request->mail_from_address,
            'mail_from_name'        => $request->mail_from_name,
        ];

        $env_data = [
            'MAIL_MAILER' => $request->mail_mailer,
            'MAIL_HOST' => $request->mail_host,
            'MAIL_PORT' => $request->mail_port,
            'MAIL_USERNAME' => $request->mail_username,
            'MAIL_PASSWORD' => $request->mail_password,
            'MAIL_ENCRYPTION' => $request->mail_encryption,
            'MAIL_FROM_ADDRESS' => '"' . $request->mail_from_address . '"',
            'MAIL_FROM_NAME' => '"' . $request->mail_from_name . '"',
        ];

        // Write database credentials to .env
        Util::updateEnvFile($env_data);

        foreach ($data as $key => $value) {
            UtilityFacades::storesettings([
                'key'   => $key,
                'value' => $value,
            ]);
        }
        $response = ['status' => 'success', 'message' => __('translation.email_setting_UpdatedSuccessfully')];
        $status_code = 200;
        return response()->json($response, $status_code);
    }

    public function bankAccountDetailsSettingUpdate(Request $request)
    {
        request()->validate([
            'account_holder'       => 'required',
            'bank_name'         => 'required',
            'account_number'         => 'required',
            'ifsc_code'     => 'required',
            'branch'     => 'required',
            'qr_code'          => 'image|max:2048',
        ]);
        $data = [
            'bank_account_details_setting_enable'  => $request->bank_account_details_setting_enable == 'on' ? 'on' : 'off',
            'account_holder'             => $request->account_holder,
            'bank_name'         => $request->bank_name,
            'account_number'         => $request->account_number,
            'ifsc_code'       => $request->ifsc_code,
            'branch'     => $request->branch,
        ];
        if ($request->qr_code) {
            Storage::delete(UtilityFacades::getsettings('qr_code'));
            $qrCode        = 'qr-code.' . $request->qr_code->extension();
            $request->qr_code->storeAs('qrCode', $qrCode, 'public');
            $data['qr_code']   = 'qrCode/' . $qrCode;
        }
        foreach ($data as $key => $value) {
            UtilityFacades::storesettings([
                'key'   => $key,
                'value' => $value,
            ]);
        }
        $response = ['status' => 'success', 'message' => __('translation.bank_account_details_setting_UpdatedSuccessfully')];
        $status_code = 200;
        return response()->json($response, $status_code);
    }

    public function testMail()
    {
        $page_title = 'Test Mail';
        return view('settings.test-mail', compact('page_title'))->render();
    }


    public function testSendMail(Request $request)
    {
        request()->validate([
            'email'     => 'required|email'
        ]);

        if (UtilityFacades::getsettings('email_setting_enable') == 'on') {
            if (MailTemplate::where('mailable', TestMail::class)->first()) {
                try {
                    Mail::to($request->email)->send(new TestMail());
                } catch (\Exception $e) {
                    return redirect()->back()->with('errors', $e->getMessage());
                }
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => __('translation.email_send_successfully'),
            'redirect' => true,
            'url' => route('settings.index')
        ], 200);
    }

    //Google Recaptcha Update
    public function google_recaptcha_update()
    {
        $data = [
            'google_recaptcha_status' => request()->google_recaptcha_status ? 'on' : 'off',
            'google_recaptcha_site_key' => request()->google_recaptcha_site_key,
            'google_recaptcha_secret_key' => request()->google_recaptcha_secret_key,
        ];
        foreach ($data as $key => $value) {
            UtilityFacades::storesettings([
                'key'   => $key,
                'value' => $value,
            ]);
        }
        $response = ['status' => 'success', 'message' => __('translation.google_recaptcha_UpdatedSuccessfully')];
        $status_code = 200;
        return response()->json($response, $status_code);
    }

    //two factor update
    public function two_factor_update()
    {
        $check = Setting::where('key', request()->key)->first();
        if ($check) {
            $check->value = request()->value;
            $check->save();
        } else {
            Setting::create([
                'key' => request()->key,
                'value' => request()->value,
                'created_by' => Auth::user()->id
            ]);
        }

        $response = ['status' => 'success', 'message' => __('translation.two_factor_auth_UpdatedSuccessfully'), 'redirect' => true, 'url' => route('settings.index')];
        $status_code = 200;
        return response()->json($response, $status_code);
    }

    //Social Login Update
    public function social_login_update()
    {
        $data = [
            'facebook_login' => request()->facebook_login ? 'on' : 'off',
            'facebook_client_id' => request()->facebook_client_id,
            'facebook_client_secret' => request()->facebook_client_secret,
            'facebook_redirect' => request()->facebook_redirect,
            'google_login' => request()->google_login ? 'on' : 'off',
            'google_client_id' => request()->google_client_id,
            'google_client_secret' => request()->google_client_secret,
            'google_redirect' => request()->google_redirect
        ];

        $env_data = [
            'FACEBOOK_CLIENT_ID' => request()->facebook_client_id,
            'FACEBOOK_CLIENT_SECRET' => request()->facebook_client_secret,
            'FACEBOOK_REDIRECT_URI' => request()->facebook_redirect,
            'GOOGLE_CLIENT_ID' => request()->google_client_id,
            'GOOGLE_CLIENT_SECRET' => request()->google_client_secret,
            'GOOGLE_REDIRECT_URI' => request()->google_redirect,
        ];

        // Write database credentials to .env
        Util::updateEnvFile($env_data);

        foreach ($data as $key => $value) {
            UtilityFacades::storesettings([
                'key'   => $key,
                'value' => $value,
            ]);
        }
        $response = ['status' => 'success', 'message' => __('translation.social_login_UpdatedSuccessfully')];
        $status_code = 200;
        return response()->json($response, $status_code);
    }

    public function create_backup()
    {
        // Ensure backup directory exists or create it
        $backupDirectory = public_path('db-backup/');
        if (!is_dir($backupDirectory)) {
            mkdir($backupDirectory, 0755, true);
        }

        // Database credentials
        $mysqlHostName = env('DB_HOST');
        $mysqlPort = env('DB_PORT');
        $mysqlUserName = env('DB_USERNAME');
        $mysqlPassword = env('DB_PASSWORD');
        $DbName = env('DB_DATABASE');

        try {
            $connect = new \PDO("mysql:host=$mysqlHostName:$mysqlPort;dbname=$DbName;charset=utf8", $mysqlUserName, $mysqlPassword, [
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
            ]);

            // Fetch tables
            $get_all_table_query = "SHOW TABLES";
            $statement = $connect->prepare($get_all_table_query);
            $statement->execute();
            $result = $statement->fetchAll();
            $tables = array_column($result, 'Tables_in_' . $DbName);

            $output = '';
            foreach ($tables as $table) {
                // Get table structure
                $show_table_query = "SHOW CREATE TABLE $table";
                $statement = $connect->prepare($show_table_query);
                $statement->execute();
                $show_table_result = $statement->fetchAll();

                foreach ($show_table_result as $show_table_row) {
                    $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
                }

                // Get table data
                $select_query = "SELECT * FROM $table";
                $statement = $connect->prepare($select_query);
                $statement->execute();

                while ($single_result = $statement->fetch(\PDO::FETCH_ASSOC)) {
                    $table_column_array = array_keys($single_result);
                    $table_value_array = array_map(fn($value) => addslashes($value), array_values($single_result));
                    $output .= "\nINSERT INTO $table (";
                    $output .= implode(", ", $table_column_array) . ") VALUES ('";
                    $output .= implode("','", $table_value_array) . "');\n";
                }
            }

            // Save file
            $file_name = 'backup-' . date('Ymd_His') . '.sql';
            $file_path = $backupDirectory . $file_name;
            if (file_put_contents($file_path, $output) === false) {
                throw new \Exception("Failed to write backup file.");
            }
            if (request()->has('drive') &&  request()->get('drive') == true) {
                // Upload SQL file to Google Drive
                $destinationPath = 'db-backup/' . basename($file_path); // Ensure it goes inside db-backup

                Storage::disk('gcs')->put($destinationPath, file_get_contents($file_path), 'public');

                // Get the public URL (if needed)
                $fileUrl = "https://storage.googleapis.com/" . env('GOOGLE_CLOUD_STORAGE_BUCKET') . "/" . $destinationPath;
                Log::info('Google Storage Link: ' . $fileUrl);

                //Remove From Local Storage
                unlink($file_path);
            }

            return redirect()->back()->with('success', 'Backup created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete_backup($file)
    {
        try {
            $backupDirectory = base_path('public/db-backup/');
            $path = $backupDirectory . $file;

            // Check if file exists before attempting deletion
            if (!file_exists($path)) {
                throw new \Exception(__('translation.file_not_found'));
            }

            // Attempt to delete the file
            if (!unlink($path)) {
                throw new \Exception(__('translation.file_delete_failed'));
            }

            $response = ['status' => 'success', 'message' => __('translation.file_DeletedSuccessfully')];
            $status_code = 200;
        } catch (\Exception $e) {
            // Handle any exceptions that occur
            $response = ['status' => 'error', 'message' => $e->getMessage()];
            $status_code = 500; // Internal Server Error
        }

        return response()->json($response, $status_code);
    }

    //Get the ajax data
    public function getBackupAjaxData()
    {
        if (request()->ajax()) {
            $backupDirectory = public_path('db-backup/'); // Use public_path for correct file access

            // Ensure directory exists
            if (!is_dir($backupDirectory)) {
                return response()->json(['data' => []]); // Return empty data if directory doesn't exist
            }

            // Get all files in the directory
            $files = array_diff(scandir($backupDirectory), ['.', '..']);

            // Prepare data for DataTables
            $data = [];
            foreach ($files as $file) {
                $filePath = $backupDirectory . $file;
                $data[] = [
                    'file_name' => $file,
                    'created_at' => date('d/m/Y h:i A', filemtime($filePath)), // Get file modification time
                    'file' => $file // Pass file name for actions
                ];
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('file_name', function ($row) {
                    return $row['file_name'];
                })
                ->addColumn('created_at', function ($row) {
                    return $row['created_at'];
                })
                ->addColumn('action', function ($row) {
                    $btn = '';

                    if (auth()->user()->can('settings.database-backup.delete')) {
                        $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete_record" data-url="' . route('settings.delete-backup', $row['file']) . '" title="' . __('translation.Delete') . '">
                                <i class="fas fa-trash"></i>
                            </button>&nbsp;';
                    }

                    if (auth()->user()->can('settings.database-backup.download')) {
                        $btn .= '<a href="' . asset('db-backup/' . $row['file']) . '" download class="btn btn-outline-primary btn-sm" title="' . __('translation.Download') . '">
                                <i class="fas fa-download"></i>
                            </a>&nbsp;';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
