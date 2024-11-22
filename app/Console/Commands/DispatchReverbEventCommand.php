<?php

namespace App\Console\Commands;

use App\Events\ReverbEvent;
use App\Events\QrloginEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DispatchReverbEventCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dispatch-reverb-event-command';


    protected $description = 'Command description';

    public function handle()
    {
        $name = rand(1000,9999);
        event(new QrloginEvent('123456',$name));
        event(new ReverbEvent($name));
    }
    private  function RandomString($length)
    {
        $key = '';
        $keys = array_merge(range('a', 'z'), range('A', 'Z'));
        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }
        return $key;
    }
}
