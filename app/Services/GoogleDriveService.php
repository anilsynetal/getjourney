<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\Permission;
use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
    protected $client;
    protected $drive;
    protected $service;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setHttpClient(new \GuzzleHttp\Client([
            'verify' => false,
        ])); 
        $this->client->setAuthConfig(storage_path('app/public/google_drive/google_drive_key.json'));
        
        // Load token from storage
        $tokenPath = storage_path('app/public/google_drive/token.json');
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $this->client->setAccessToken($accessToken);
        }

        // Refresh token if expired
        if ($this->client->isAccessTokenExpired()) {
            if ($this->client->getRefreshToken()) {
                $newToken = $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                file_put_contents($tokenPath, json_encode($newToken));
            }
        }

        $this->drive = new Drive($this->client);
        $this->service = new Drive($this->client);
    }

    public function uploadFile($file , $storagePath = null)
    {
        try {
            $folderId = config('filesystems.disks.google.folderId');
            $fileName = $file->getFilename(); 
            //$fileName = $file->getClientOriginalName();

            if($storagePath){
                $backupFolderId = $this->getOrCreateFolder($storagePath, $folderId);
            }else{
                $backupFolderId = $folderId;
            }

            
            $fileMetadata = new DriveFile([
                'name' => $fileName,
                'parents' => [$backupFolderId]
            ]);

            $content = file_get_contents($file->getRealPath());
            $mimeType = mime_content_type($file->getRealPath());
            // $mimeType =$file->getClientMimeType();
            $uploadedFile = $this->drive->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $mimeType,
                'uploadType' => 'multipart'
            ]);

            return $fileId = $uploadedFile->id;

            // Set public permissions
            $permission = new Permission([
                'type' => 'anyone',
                'role' => 'reader'
            ]);
            $this->service->permissions->create($fileId, $permission);

            // Get the file URL
            $fileDetails = $this->service->files->get($fileId, ['fields' => 'webViewLink']);

            return $fileDetails->webViewLink; // Return only the file URL

        } catch (\Exception $e) {
            Log::error('Google Drive Upload Failed: ' . $e->getMessage());
            return null;
        }
    }

    public function getOrCreateFolder($folderName, $parentFolderId)
{
    // Check if folder exists
    $query = "name='$folderName' and mimeType='application/vnd.google-apps.folder' and '$parentFolderId' in parents";
    $folders = $this->drive->files->listFiles(['q' => $query])->getFiles();

    if (!empty($folders)) {
        return $folders[0]->id; // Return existing folder ID
    }

    // Create folder if not found
    $folderMetadata = new DriveFile([
        'name' => $folderName,
        'mimeType' => 'application/vnd.google-apps.folder',
        'parents' => [$parentFolderId]
    ]);

    $folder = $this->drive->files->create($folderMetadata, ['fields' => 'id']);

    return $folder->id;
}

}
