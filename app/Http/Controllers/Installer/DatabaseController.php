<?php

namespace App\Http\Controllers\Installer;

use App\Http\Helpers\Installer\DatabaseManager;
use Illuminate\Routing\Controller;

class DatabaseController extends Controller
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    /**
     * DatabaseController constructor.
     *
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    /**
     * Run database migrations and seed data.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function database()
    {
        // Remove execution time limit in case migrations take long
        set_time_limit(0);

        // Run migrations and seed data
        $response = $this->databaseManager->migrateAndSeed();

        // Redirect to final installation step with message
        return redirect()->route('installer.verify')->with(['message' => $response]);
    }
}
