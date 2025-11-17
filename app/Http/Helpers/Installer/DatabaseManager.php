<?php

namespace App\Http\Helpers\Installer;

use Exception;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseManager
{
    /**
     * Migrate and seed the database.
     *
     * @return array
     */
    public function migrateAndSeed()
    {
        $this->sqlite(); // Ensure SQLite file exists if applicable

        $migrationResponse = $this->migrate();
        if ($migrationResponse['status'] === 'danger') {
            return $migrationResponse; // Return error if migration fails
        }

        return $this->seed(); // Run seeder after migration
    }

    /**
     * Run the migration.
     *
     * @return array
     */
    private function migrate()
    {
        try {
            Artisan::call('migrate:fresh', ["--force" => true]);
            return $this->response("Database migration completed successfully.", 'success');
        } catch (Exception $e) {
            return $this->response($e->getMessage());
        }
    }

    /**
     * Seed the database.
     *
     * @return array
     */
    private function seed()
    {
        try {
            Log::info("Starting database seeding...");
            Artisan::call('db:seed', ["--force" => true]);
            Log::info("Seeding completed successfully.");

            return $this->response("Database seeding completed successfully.", 'success');
        } catch (Exception $e) {
            Log::error("Seeding failed: " . $e->getMessage());
            return $this->response($e->getMessage());
        }
    }

    /**
     * Return a formatted response message.
     *
     * @param string $message
     * @param string $status
     * @return array
     */
    private function response($message, $status = 'danger')
    {
        return [
            'status' => $status,
            'message' => $message
        ];
    }

    /**
     * Check database type. If SQLite, create the database file if it doesn't exist.
     */
    private function sqlite()
    {
        if (DB::connection() instanceof SQLiteConnection) {
            $database = DB::connection()->getDatabaseName();
            if (!file_exists($database)) {
                touch($database);
                DB::reconnect(Config::get('database.default'));
                Log::info("SQLite database file created: " . $database);
            }
        }
    }
}
