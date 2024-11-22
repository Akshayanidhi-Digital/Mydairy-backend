<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Events\Message;
use App\Models\MessagesAlert;
use Illuminate\Console\Command;
use App\Notifications\AccountActivated;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    protected $description = 'Command description';
    public function handle()
    {
        // $message = MessagesAlert::create([
        //     'user_id'=>'MYDAIRY_001',
        //     'message'=>'Today server will be down between 1 PM to 3 PM.',
        // ]);
        // broadcast(new Message($message));
        $user = User::where('user_id','MYDAIRY_001')->first();
        $user->notify(new AccountActivated);

    }
}
