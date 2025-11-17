<?php

namespace App\Http\Controllers\Installer;

use Illuminate\Routing\Controller;
use App\Http\Helpers\Installer\RequirementsChecker;

class RequirementsController extends Controller
{
    /**
     * @var RequirementsChecker
     */
    protected $requirements;

    /**
     * @param RequirementsChecker $checker
     */
    public function __construct(RequirementsChecker $checker)
    {
        $this->requirements = $checker;
    }

    /**
     * Display the system requirements check page.
     *
     * @return \Illuminate\View\View
     */
    public function requirements()
    {
        // Get the minimum required PHP version
        $minPhpVersion = config('installer.core.minPhpVersion', '8.0');

        // Check if the server meets the PHP version requirement
        $phpSupportInfo = $this->requirements->checkPHPversion($minPhpVersion);

        // Check required PHP extensions
        $requirements = $this->requirements->check(
            config('installer.requirements', [])
        );

        return view('install.requirements', compact('requirements', 'phpSupportInfo'));
    }
}
