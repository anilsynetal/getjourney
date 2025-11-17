<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Drive;

class GoogleCalendarService
{
    protected $client;
    protected $service;
    protected $calendarId;

    public function __construct()
    {   
        $this->client = new Google_Client();
        $this->client->setHttpClient(new \GuzzleHttp\Client([
            'verify' => false,
        ])); 
        $this->client->setAuthConfig(storage_path('app/public/google_drive/google_drive_key.json')); // Use OAuth credentials
        $this->client->setScopes(Google_Service_Calendar::CALENDAR , Google_Service_Drive::DRIVE);
        $this->client->setAccessType('offline'); // Get refresh token
        $this->client->setPrompt('consent');

        // Check if we have an access token
        $tokenPath = storage_path('app/public/google_drive/token.json');
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $this->client->setAccessToken($accessToken);
        }

        // If token is expired, refresh it
        if ($this->client->isAccessTokenExpired()) {
            if ($this->client->getRefreshToken()) {
                $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
            } else {
                // Redirect user to get new access token
                header('Location: ' . $this->client->createAuthUrl());
                exit;
            }
            file_put_contents($tokenPath, json_encode($this->client->getAccessToken()));
        }

        $this->service = new Google_Service_Calendar($this->client);
        $this->calendarId = "primary"; // Use your Gmail calendar
    }

    public static function createEvent($userEmail , $date , $case_id=null)
    {   
        $googleCalendar = new self();
        $event = new Google_Service_Calendar_Event([
            'summary' => 'Case Hearing',
            'location' => 'Court',
            'description' => 'Case have hearing on ' . $date,
            'start' => [
                'dateTime' => $date.'T07:00:00',
                'timeZone' => 'Asia/Kolkata',
            ],
            'end' => [
                'dateTime' => $date.'T19:00:00',
                'timeZone' => 'Asia/Kolkata',
            ],
            'attendees' => [
                ['email' => $userEmail],
            ],
            'reminders' => [
                'useDefault' => false,
                'overrides' => [
                    ['method' => 'email', 'minutes' => 24 * 60],
                    ['method' => 'popup', 'minutes' => 10],
                ],
            ],
        ]);

        $event = $googleCalendar->service->events->insert($googleCalendar->calendarId, $event);

        return  $event->htmlLink;
    }
}
