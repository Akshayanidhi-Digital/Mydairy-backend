<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsProfileCompleteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->is('api/*')) {
            if (auth()->user() && !auth()->user()->isProfile()) {
                return redirect()->route('user.onboard')->with('error', __('message.COMPLETE_PROFILE'));
            } else {
                return $next($request);
            }
        } else {
            if ($request->user() && !$request->user()->isProfile()) {
                return $next($request);
            } else {
                return response()->json([
                    'success' => FALSE,
                    'status' => STATUS_FORBIDDEN, 'message' => __('message.ACCESS_NOT_ALLOWED')
                ], STATUS_FORBIDDEN);
            }
        }
    }
}
