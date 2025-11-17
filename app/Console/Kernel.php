<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {   
        // $schedule->command('inspire')->hourly();
        $schedulerHearingStatus = \App\Utils\Util::getSettingValue('scheduler_hearing_status'); 
        if (strtolower($schedulerHearingStatus) === 'on') {
            $schedulerHearingTime = \App\Utils\Util::getSettingValue('scheduler_hearing_time');
            $defaultTime = '00:01';
            $scheduledTime = !empty($schedulerHearingTime) ? $schedulerHearingTime : $defaultTime;
            [$hour, $minute] = explode(':', $scheduledTime);
            $schedule->command('case:get-upcoming-case-hearings')
                     ->dailyAt("$hour:$minute");
        }
        $schedulerDbBackupStatus = \App\Utils\Util::getSettingValue('scheduler_db_backup_status'); 
        if (strtolower($schedulerDbBackupStatus) === 'on') {
            $schedulerDbBackupTime = \App\Utils\Util::getSettingValue('scheduler_db_backup_time');
            $defaultTime = '00:01';
            $scheduledTime = !empty($schedulerDbBackupTime) ? $schedulerDbBackupTime : $defaultTime;
            [$hour, $minute] = explode(':', $scheduledTime);
            $schedule->command('app:database-backup')->dailyAt("$hour:$minute");
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
