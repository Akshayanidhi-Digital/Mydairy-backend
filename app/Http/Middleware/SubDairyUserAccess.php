<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class SubDairyUserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!$request->is('api/*')) {
            if (auth()->user() && auth()->user()->is_subdairy()) {
                if(auth()->user()->is_permission()){
                    return $next($request);
                }else{
                    return redirect()->route('user.dashboard')->with('error', __('message.UNAUTHORIZED_PLAN_ACCESS'));
                }
            } else {
                return $next($request);
            }
        } else {
            $routeName = Route::currentRouteName();
            if (strpos($routeName, 'api.') === 0) {
                $routeName = substr($routeName, 4);
            }
            if ($request->user() && $request->user()->is_subdairy()) {
                if($request->user()->is_permission_Route($routeName)){
                    return $next($request);
                }else{
                    return response()->json(['success' => FALSE, 'status' => STATUS_FORBIDDEN, 'message' => __('message.ACCESS_NOT_ALLOWED')], STATUS_FORBIDDEN);
                }
            } else {
                return $next($request);
            }
        }
    }
}
