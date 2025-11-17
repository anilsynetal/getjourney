<?php

namespace App\Http\Controllers\Installer;

use Illuminate\Routing\Controller;
use App\Http\Helpers\Installer\EnvironmentManager;
use Illuminate\Http\Request;

/**
 * Class EnvironmentController
 * @package App\Http\Controllers\Installer
 */
class EnvironmentController extends Controller
{
    /**
     * @var EnvironmentManager
     */
    protected $environmentManager;

    /**
     * @param EnvironmentManager $environmentManager
     */
    public function __construct(EnvironmentManager $environmentManager)
    {
        $this->environmentManager = $environmentManager;
    }

    /**
     * Display the Environment page.
     *
     * @return \Illuminate\View\View
     */
    public function environment()
    {
        $envConfig = $this->environmentManager->getEnvContent();

        return view('install.environment', compact('envConfig'));
    }

    /**
     * Save the environment settings, including custom fields.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request)
    {

        $message = $this->environmentManager->saveFile($request);
        // Check if response contains redirect action
        if (isset($message['status']) && $message['status'] === 'success' && isset($message['action']) && $message['action'] === 'redirect') {
            return redirect()->to($message['url']);
        } else {
            return redirect()->route('installer.environment')->with('error', $message['message']);
        }
    }
    /**
     * Update the .env file with new key-value pairs.
     *
     * @param array $data
     */
    protected function updateEnvironmentFile(array $data)
    {
        $envFile = base_path('.env');

        foreach ($data as $key => $value) {
            file_put_contents($envFile, "\n{$key}={$value}", FILE_APPEND);
        }
    }
}
