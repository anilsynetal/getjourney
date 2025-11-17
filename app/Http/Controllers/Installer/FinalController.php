<?php

namespace App\Http\Controllers\Installer;

use Illuminate\Routing\Controller;
use App\Http\Helpers\Installer\InstalledFileManager;
use App\Models\Setting;
use App\Models\User;
use App\Utils\Util;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request as FacadesRequest;

class FinalController extends Controller
{

    public function __construct()
    {
        if (file_exists(storage_path('installed'))) {
            return redirect()->route('root');
        }
    }
    /**
     * Update installed file, clear cache, and display finished view.
     *
     * @param InstalledFileManager $fileManager
     * @return \Illuminate\View\View
     */
    public function finish(InstalledFileManager $fileManager)
    {
        // Mark installation as completed
        $fileManager->update();

        // Clear application cache after installation
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        return view('install.finished');
    }


    public function verify()
    {
        return view('install.verify');
    }

    //Validate installation
    public function validate(Request $request)
    {
        $request->validate([
            'license_code' => 'required',
            'name' => 'required',
            'mobile' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'app_name' => 'required'
        ]);

        $response = $this->verifyCode($request->all());
        if (isset($response['errors'])) {
            //Redirect back with error message
            return redirect()->back()->withInput()->withErrors(['error' => $response['message']]);
        } else if ($response['status'] != true) {
            //Redirect back with error message
            return redirect()->back()->withInput()->withErrors(['error' => $response['message']]);
        } else {
            User::where('id', 1)->update(['name' => $request->name, 'mobile' => $request->mobile, 'email' => $request->email, 'password' => Hash::make($request->password)]);
            $env_data = [
                'APP_PRODUCT_ID'       => $response['product_id'],
            ];
            Util::updateEnvFile($env_data);
            Setting::where('key', 'app_name')->update(['value' => $request->app_name]);
            return redirect()->route('installer.finish');
        }
    }

    //API call to validate verification code
    public function verifyCode($request_data)
    {
        //Get IP address using domain
        $domain = FacadesRequest::getHost();
        // Get the IP address
        $ipAddress = FacadesRequest::ip();
        $payload = array(
            'license_code' => $request_data['license_code'],
            'name' => $request_data['name'],
            'email' => $request_data['email'],
            'phone' => $request_data['mobile'],
            'domain' => $domain,
            'ip' => $ipAddress
        );
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('SUPER_ADMIN_URL') . 'api/validate-licence',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $data = json_decode($response, true);
        return $data;
    }
}
