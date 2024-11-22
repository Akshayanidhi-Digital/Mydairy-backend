<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DealerShipAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user() && !auth()->user()->is_single()){
            return $next($request);
        }else{
            return redirect()->route('user.dashboard')->with('error',__('message.UNAUTHORIZED_PLAN_ACCESS'));
        }
    }
}
