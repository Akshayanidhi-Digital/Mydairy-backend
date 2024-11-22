<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('message.{id}', function ($user, $id) {
    return $user->user_id === $id;
});

// Broadcast::channel('game.{gameType}', function ($user, $gameType) {
//     return Auth::check();
// });
// Broadcast::channel('jetgame', function ($user) {
//     return Auth::check();
// });
// Broadcast::channel('reverb-channel', function ($user, $id) {
//     return true;
// });

// Broadcast::channel('reverb-channel', function () {
//     return true;
// });
// Broadcast::channel('qrlogin-{browser_id}', function ($browser_id) {
//     return true;
// });
// Broadcast::channel('milk.{user_id}', function (User $user, $user_id) {
//     return $user->user_id === $user_id;
// });
