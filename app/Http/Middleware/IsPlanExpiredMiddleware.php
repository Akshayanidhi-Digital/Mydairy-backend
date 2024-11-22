<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsPlanExpiredMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->is('api/*')) {
            if (auth()->user() && auth()->user()->isPlanExpired()) {

                if (auth()->user()->is_subdairy()) {
                    return redirect()->route('user.dashboard')->with('error', __('message.ASK_FOR_RENEW_PLAN'));
                }

                return redirect()->route('user.plans.list')->with('error', __('message.PLAN_EXPIRED'));
            } else {
                return $next($request);
            }
        } else {
            return $next($request);
        }
    }
}
