<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (!$request->is('api/*')) {
            if ($request->is('transport/*')) {
                return route('transport.login');
            } else {
                return route('login');
            }
            // $guard = $request->attributes->get('guard');
            // Log::info('Guard used for request: ' . $guard);
            // switch ($guard) {
            //     case 'transport':
            //         return route('transport.login');
            //     default:
            //         return route('login');
            // }
        } else {
            Log::info('i am api request.');
        }
    }
}
