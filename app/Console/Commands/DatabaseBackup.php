<?php

namespace App\Console\Commands;

use App\Services\GoogleDriveService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:database-backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a backup of the database and store it locally or in Google Drive';

    /**
     * Execute the console command.
     */
    public function handle()
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
            $connect = new \PDO("mysql:host=$mysqlHostName;port=$mysqlPort;dbname=$DbName;charset=utf8", $mysqlUserName, $mysqlPassword, [
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
                    $output .= "\nINSERT INTO $table (" . implode(", ", $table_column_array) . ") VALUES ('" . implode("','", $table_value_array) . "');\n";
                }
            }

            // Save file
            $file_name = 'backup-' . Carbon::now()->format('Ymd_His') . '.sql';
            $file_path = $backupDirectory . $file_name;
            if (file_put_contents($file_path, $output) === false) {
                throw new \Exception("Failed to write backup file.");
            }

            // Check if Google Drive upload is requested
            $disk =  \App\Utils\Util::getSettingValue('storage_type');
            if ($disk === 'google') {
                $file = new \Illuminate\Http\File($file_path);
                $googleDriveService = app(GoogleDriveService::class);
                $filePath = $googleDriveService->uploadFile($file , 'db-backup');
                $filePath = 'https://drive.google.com/file/d/'.$filePath;

                //Remove From Local Storage
                if($filePath){
                    unlink($file_path);
                }
                
            }

            $this->line("Backup created successfully! {$filePath}");
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
