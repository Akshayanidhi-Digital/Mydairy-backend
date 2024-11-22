<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class QrloginEvent  implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $name = 'Mydairy';
    public $message;
    public $browser_id;


    public function __construct($browser_id,$message)
    {
        $this->browser_id = $browser_id;
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('qrlogin-'.$this->browser_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'login';
    }
}
