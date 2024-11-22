<?php

namespace App\Notifications;

use Heyharpreetsingh\FCM\Facades\FCMFacade;
use Illuminate\Support\Facades\Log;

class FirebaseMessage
{
    public static function sendNotification(string $token, string $message)
    {
        try {

            $res = FCMFacade::send([
                "message" => [
                    "token" => $token,
                    "notification" => [
                        "body" => $message,
                        "title" => "Mydairy",
                    ]
                ]
            ]);
            Log::info('FCM Response:', ['response' => $res]);
            return $res;
        } catch (\Exception $e) {
            Log::error('FCM Error:', ['error' => $e->getMessage()]);
            return null;
        }
    }
    public static function sendNotificationWithImage(string $token, string $message, string $image)
    {
        try {
            $res = FCMFacade::send([
                "message" => [
                    "token" => $token,
                    "android" => [
                        "notification" => [
                            "image" => $image
                        ]
                    ],
                    "notification" => [
                        "body" => $message,
                        "title" => "Mydairy",
                    ]
                ],
            ]);
            Log::info('FCM Response:', ['response' => $res]);
            return $res;
        } catch (\Exception $e) {
            Log::error('FCM Error:', ['error' => $e->getMessage()]);
            return null;
        }
    }
    public static function sendNotificationWithImageData(string $token, string $message, string $image,array $data)
    {
        try {
            $res = FCMFacade::send([
                "message" => [
                    "token" => $token,
                    "data"=>$data,
                    "android" => [
                        "notification" => [
                            "image" => $image
                        ]
                    ],
                    "notification" => [
                        "body" => $message,
                        "title" => "Mydairy",
                    ]
                ],
            ]);
            Log::info('FCM Response:', ['response' => $res]);
            return $res;
        } catch (\Exception $e) {
            Log::error('FCM Error:', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
