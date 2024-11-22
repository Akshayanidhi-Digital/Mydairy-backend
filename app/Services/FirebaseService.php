<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $serviceAccountPath = storage_path(env('FIREBASE_CREDENTIALS'));
        if (file_exists($serviceAccountPath)) {
            $fileContents = file_get_contents($serviceAccountPath);
            $factory = (new Factory)->withServiceAccount($fileContents);
            $this->messaging = $factory->createMessaging();
        }else{
            $factory = (new Factory)->withServiceAccount($serviceAccountPath);
            $this->messaging = $factory->createMessaging();
        }
    }
    public function sendNotification($title, $body, $token, $data, $platform = 'android')
    {
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(['title' => $title, 'body' => $body])
            ->withData($data);
    }
}
