<?php

namespace App\Services;

class WhatsAppService
{
    protected $baseUrl = 'http://bhashsms.com/api/sendmsg.php';

    public function sendWhatsAppMessage($phone, $text, $params = [])
    {
        $username = env('SMS_API_USER');
        $password = env('SMS_API_PASS');
        $sender = 'BUZWAP'; // Your WhatsApp sender ID
        $priority = 'wa'; // WhatsApp priority
        $stype = 'normal'; // Message type
        $paramsStr = http_build_query(array_merge(['phone' => $phone, 'text' => $text], $params));

        $url = "{$this->baseUrl}?user={$username}&pass={$password}&sender={$sender}&{$paramsStr}";

        // Make HTTP request to send WhatsApp message
        // Example using Guzzle HTTP client
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);

        return $response->getBody()->getContents();
    }

    public function sendWhatsAppNotificationMessage($phone, $text, $params = [])
    {
        $username = env('SMS_API_USER');
        $password = env('SMS_API_PASS');
        $sender = 'BUZWAP'; // Your WhatsApp sender ID
        $priority = 'wa'; // WhatsApp priority
        $stype = 'normal'; // Message type
        $paramsStr = http_build_query(array_merge(['phone' => $phone, 'text' => $text], $params));

        $url = "{$this->baseUrl}?user={$username}&pass={$password}&sender={$sender}&{$paramsStr}&priority={$priority}&stype={$stype}";

        // Make HTTP request to send WhatsApp message
        // Example using Guzzle HTTP client
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);

        return $response->getBody()->getContents();
    }
}
