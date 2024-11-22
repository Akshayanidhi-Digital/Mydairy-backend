<?php

namespace App\Http\Middleware;

use App\Models\UserSettings;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class Localization
{
    public function handle(Request $request, Closure $next)
    {
        $defaultLanguage = Config::get('app.locale');
        $supportedLanguages = array_keys(Config::get('app.supported_locales'));

        // Check if it's an API request
        if (!$request->is('api/*')) {
            if (auth()->check()) {
                $userLanguage = UserSettings::where('user_id', auth()->user()->user_id)->value('lang');
                if (!empty($userLanguage) && in_array($userLanguage, $supportedLanguages)) {
                    App::setLocale($userLanguage);
                } else {
                    App::setLocale($defaultLanguage);
                }
            } else {
                App::setLocale($defaultLanguage);
            }
        } else {
            if ($request->hasHeader("Accept-Language")) {
                $preferredLanguage = $request->header("Accept-Language");
                $preferredLanguage = strtolower(substr($preferredLanguage, 0, 2)); // Handle cases where Accept-Language includes region codes
                if (in_array($preferredLanguage, $supportedLanguages)) {
                    App::setLocale($preferredLanguage);
                } else {
                    App::setLocale($defaultLanguage);
                }
            } else {
                if (auth()->check()) {
                    $userLanguage = UserSettings::where('user_id', auth()->user()->user_id)->value('lang');
                    if (!empty($userLanguage) && in_array($userLanguage, $supportedLanguages)) {
                        App::setLocale($userLanguage);
                    } else {
                        App::setLocale($defaultLanguage);
                    }
                } else {
                    App::setLocale($defaultLanguage);
                }
            }
        }

        if ($request->input('lang') && in_array($request->input('lang'), $supportedLanguages)) {
            App::setLocale($request->input('lang'));
        }
        return $next($request);
    }
}
