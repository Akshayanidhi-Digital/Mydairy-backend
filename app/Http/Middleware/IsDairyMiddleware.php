<?php

namespace App\Http\Middleware;

use App\Helper\Helper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsDairyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->is('api/*')) {
            if (auth()->user() && !auth()->user()->is_subdairy()) {
                return $next($request);
            } else {
                return redirect()->route('user.dashboard')->with('error', __('message.UNAUTHORIZED_PLAN_ACCESS'));
            }
        } else {
            if ($request->user() && !$request->user()->is_subdairy()) {
                return $next($request);
            } else {
                return response()->json(['success' => FALSE, 'status' => STATUS_FORBIDDEN, 'message' => __('message.ACCESS_NOT_ALLOWED')], STATUS_FORBIDDEN);
            }
        }
    }
}
