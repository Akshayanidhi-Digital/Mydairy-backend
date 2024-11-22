<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Schedule::command('app:dispatch-reverb-event-command')
//     ->everyFiveSeconds()->runInBackground()->withoutOverlapping();
Schedule::command('app:send-notification')
    ->everySecond()->runInBackground()->withoutOverlapping();
