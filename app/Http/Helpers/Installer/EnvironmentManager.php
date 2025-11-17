<?php

namespace  App\Http\Helpers\Installer;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnvironmentManager
{
    /**
     * @var string
     */
    private $envPath;

    /**
     * @var string
     */
    private $envExamplePath;

    /**
     * Set the .env and .env.example paths.
     */
    public function __construct()
    {
        $this->envPath = base_path('.env');
        $this->envExamplePath = base_path('.env.example');
    }

    /**
     * Get the content of the .env file.
     *
     * @return string
     */
    public function getEnvContent()
    {
        if (!file_exists($this->envPath)) {
            if (file_exists($this->envExamplePath)) {
                copy($this->envExamplePath, $this->envPath);
            } else {
                touch($this->envPath);
            }
        }

        return file_get_contents($this->envPath);
    }

    /**
     * Save the edited content to the file.
     *
     * @param Request $input
     * @return string
     */
    public function saveFile(Request $input)
    {
        $message = trans('messages.environment.success');

        $env = $this->getEnvContent();
        $db_connection = $input->get('db_connection');
        $db_host = $input->get('db_host');
        $db_port = $input->get('db_port');
        $db_database = $input->get('db_database');
        $db_username = $input->get('db_username');
        $db_password = $input->get('db_password');

        if ($db_connection === 'mysql') {
            $host_name = $db_host;
            if ($db_host == 'localhost') {
                $host_name = '127.0.0.1';
            }
            // MySQL database settings
            $databaseSetting = 'DB_CONNECTION=' . $db_connection . '
DB_HOST=' . $host_name . '
DB_PORT=' . $db_port . '
DB_DATABASE=' . $db_database . '
DB_USERNAME=' . $db_username . '
DB_PASSWORD="' . $db_password . '"
APP_URL="' . request()->getSchemeAndHttpHost() . '"
';
        } elseif ($db_connection === 'sqlsrv') {
            // SQL Server database settings
            $databaseSetting = 'DB_CONNECTION=' . $db_connection . '
DB_HOST=' . $db_host . '
DB_PORT=' . $db_port . '
DB_DATABASE=' . $db_database . '
DB_USERNAME=' . $db_username . '
DB_PASSWORD="' . $db_password . '"
DB_ENCRYPT=no
DB_TRUST_SERVER_CERTIFICATE=true
APP_URL="' . request()->getSchemeAndHttpHost() . '"
';
        } else {
            return Reply::error('Unsupported database connection type: ' . $db_connection);
        }

        // Remove existing database settings from the environment file
        $rows       = explode("\n", $env);
        $unwanted   = "DB_CONNECTION|DB_HOST|DB_PORT|DB_DATABASE|DB_USERNAME|DB_PASSWORD|APP_URL";
        $cleanArray = preg_grep("/$unwanted/i", $rows, PREG_GREP_INVERT);
        $cleanString = implode("\n", $cleanArray);

        // Combine cleaned environment with new database settings
        $env = $cleanString . $databaseSetting;

        try {
            if ($db_connection === 'mysql') {
                $dbh = new \PDO('mysql:host=' . $db_host . ':' . $db_port, $db_username, $db_password);
            } elseif ($db_connection === 'sqlsrv') {
                $dbh = new \PDO('sqlsrv:Server=' . $db_host . ',' . $db_port, $db_username, $db_password);
            } else {
                return Reply::error('Unsupported database connection type: ' . $db_connection);
            }

            $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // First check if database exists (for MySQL, you create database; for SQL Server, you create database query might differ)
            if ($db_connection === 'mysql') {
                $stmt = $dbh->query('CREATE DATABASE IF NOT EXISTS `' . $db_database . '` CHARACTER SET utf8 COLLATE utf8_general_ci;');
            } elseif ($db_connection === 'sqlsrv') {
                // Example for SQL Server create database statement
                $stmt = $dbh->query('IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = N\'' . $db_database . '\') CREATE DATABASE [' . $db_database . '];');
            }

            // Save settings in session
            $_SESSION['db_connection'] = $db_connection;
            $_SESSION['db_port'] = $db_port;
            $_SESSION['db_username'] = $db_username;
            $_SESSION['db_password'] = $db_password;
            $_SESSION['db_name']     = $db_database;
            $_SESSION['db_host']     = $db_host;
            $_SESSION['db_success']  = true;
            $message = 'Database settings correct';

            // Write updated environment file
            try {
                file_put_contents($this->envPath, $env);
            } catch (Exception $e) {
                $message = trans('messages.environment.errors');
            }
            return Reply::redirect(route('installer.requirements'), $message);
        } catch (\PDOException $e) {
            return Reply::error('DB Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            return Reply::error('Error: ' . $e->getMessage());
        }
    }
}
