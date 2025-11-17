<?php

namespace App\Http\Controllers\Installer;

use Illuminate\Routing\Controller;
use App\Http\Helpers\Installer\PermissionsChecker;

class PermissionsController extends Controller
{
    /**
     * @var PermissionsChecker
     */
    protected $permissions;

    /**
     * @param PermissionsChecker $checker
     */
    public function __construct(PermissionsChecker $checker)
    {
        $this->permissions = $checker;
    }

    /**
     * Display the permissions check page.
     *
     * @return \Illuminate\View\View
     */
    public function permissions()
    {
        // Retrieve the required permissions from the config file
        $requiredPermissions = config('installer.permissions', []);

        // Check folder/file permissions
        $permissions = $this->permissions->check($requiredPermissions);

        return view('install.permissions', compact('permissions'));
    }
}
