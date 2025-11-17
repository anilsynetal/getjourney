<?php

namespace App\Http\Controllers\Installer;

use Illuminate\Routing\Controller;
use App\Http\Helpers\Installer\InstalledFileManager;
use App\Models\Setting;
use App\Models\User;
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
        //Run npm install
        exec('npm install');

        //Run npm run build
        exec('npm run build');

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
        print_r($response);
        if ($response['status'] != 200) {
            return redirect()->route('install.verify')->with(['error', $response['msg']]);
        } else {
            User::where('id', 1)->update(['name' => $request->name, 'mobile' => $request->mobile, 'email' => $request->email, 'password' => Hash::make($request->password)]);
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
        $client = new Client();
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];

        $body = json_encode($payload);
        $request = $client->post(env('SUPER_ADMIN_URL') . 'api/validate-licence', [
            'headers' => $headers,
            'body' => $body
        ]);
        $response = $request->getBody()->getContents();
        $data = json_decode($response, true);
        return $data;
    }
}
