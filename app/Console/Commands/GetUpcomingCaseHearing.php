<?php

namespace App\Console\Commands;

use App\Models\CaseHearingDetails;
use App\Services\NotificationService;
use App\Services\GoogleCalendarService;
use Carbon\Carbon;
use GPBMetadata\Google\Api\Auth;
use App\Utils\Util;
use Illuminate\Console\Command;

class GetUpcomingCaseHearing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'case:get-upcoming-case-hearings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {   
        $schedulerHearingStatus = \App\Utils\Util::getSettingValue('scheduler_hearing_status');
        if (strtolower($schedulerHearingStatus) === 'on') {
                $schedulerHearingDays = \App\Utils\Util::getSettingValue('scheduler_hearing_days');
                $days = (int) ($schedulerHearingDays ?: 2);
                $startDate = Carbon::now()->format('Y-m-d');
                $endDate = Carbon::now()->addDays($days)->format('Y-m-d');
                $schedulerHearingStatus = \App\Utils\Util::getSettingValue('scheduler_hearing_status'); 
            
                $cases = CaseHearingDetails::with('case_details')
                    ->whereBetween('hearing_date', [$startDate, $endDate])
                    ->whereNull('deleted_at')
                    ->get();

            if ($cases->isEmpty()) {
                $this->info('No upcoming case hearings in the next 2 days.');
            } else {
                $this->info('Upcoming Case Hearings:');
                foreach ($cases as $case) {

                    $filling_number = $case->case_details->case_unique_number;
                    $caseDetailsId = $case->id;
                    $hearing_date = date('Y-m-d', strtotime($case->hearing_date)); 
                    $details['email'] = [
                        'subject' => 'Hearing Date On  ' . $filling_number,
                        'message' => '<p>Case have hearing on ' . $hearing_date . ' <a href="' . route('case-details.case-details.edit', $caseDetailsId) . '">Click Here</a> to view the case details.</p>',
                        'template' => 'notification',
                    ];
                    $details['sms'] = [
                        'message' => 'New Case Added <a href="' . route('case-details.case-details.edit', $caseDetailsId) . '">Click Here</a> to view the case details.',
                    ];
                    $details['whatsapp'] = [
                        'message' => 'New Case Added <a href="' . route('case-details.case-details.edit', $caseDetailsId) . '">Click Here</a> to view the case details. ',
                    ];
                    // NotificationService::sendNotification($caseDetailsId, 'hearing-date', $details);

                    $google_calendar_status = Util::getSettingValue('google_calendar_status');
                    if($google_calendar_status == 'on'){
                        GoogleCalendarService::createEvent('nitish.synetalsolutions@gmail.com', $hearing_date);
                    }

                    $this->line("Case ID: {$case->id}, Case Number: {$case->case_details->case_unique_number}, Hearing Date: {$case->hearing_date} , 'test': {$google_calendar_status}");
                }
            }
        }
    }
}
