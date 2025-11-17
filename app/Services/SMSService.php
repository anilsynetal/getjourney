<?php

namespace App\Services;

class SMSService
{
    protected $baseUrl = 'http://bhashsms.com/api/sendmsg.php';

    public function sendSms($phone, $message)
    {
        $username = env('WHATSAPP_API_USER');
        $password = env('WHATSAPP_API_PASS');
        $sender = 'SFASMS'; // Your sender ID
        $priority = 'ndnd'; // Message priority
        $stype = 'normal'; // Message type

        $url = "{$this->baseUrl}?user={$username}&pass={$password}&sender={$sender}&phone={$phone}&text={$message}&priority={$priority}&stype={$stype}";

        // Make HTTP request to send SMS
        // Example using Guzzle HTTP client
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);

        return $response->getBody()->getContents();
    }

    public function sendNotificationSms($phone, $message)
    {
        $username = env('WHATSAPP_API_USER');
        $password = env('WHATSAPP_API_PASS');
        $sender = 'SFASMS'; // Your sender ID
        $priority = 'ndnd'; // Message priority
        $stype = 'normal'; // Message type

        $url = "{$this->baseUrl}?user={$username}&pass={$password}&sender={$sender}&phone={$phone}&text={$message}&priority={$priority}&stype={$stype}";

        // Make HTTP request to send SMS
        // Example using Guzzle HTTP client
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);

        return $response->getBody()->getContents();
    }
}
