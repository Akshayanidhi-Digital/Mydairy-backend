<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Heyharpreetsingh\FCM\Facades\FCMFacade;


class SendNotificationTest extends Controller
{
    public function send(Request $request)
    {

        $res =   FCMFacade::send([
            "message" => [
                "token" => $request->token,
                "data" => [
                    "image" => 'https://mydairy.tech/assets/ecom/images/web_banner.png'
                ],
                "notification" => [
                    "body" => $request->messgae,
                    "title" => "Mydairy",
                ]
            ]
        ]);
        return $res;
    }
}
