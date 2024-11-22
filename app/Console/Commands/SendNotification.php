<?php

namespace App\Console\Commands;

use App\Models\Transporters;
use App\Notifications\FirebaseMessage;
use Illuminate\Console\Command;

class SendNotification extends Command
{

    protected $signature = 'app:send-notification';
    protected $description = 'Command description';
    public function handle()
    {
        $users = Transporters::whereNotNull('fcm_token')->pluck('fcm_token');
        foreach($users as $user){
            FirebaseMessage::sendNotification($user,'I am testing for loop auto message.');
        }
    }
}
