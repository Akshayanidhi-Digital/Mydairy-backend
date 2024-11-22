<?php

use Illuminate\Http\Request;
use App\Http\Middleware\UserBlocked;
use App\Http\Middleware\Localization;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Middleware\SubDairyUserAccess;
use Illuminate\Auth\AuthenticationException;
use App\Http\Middleware\AdminCheckMiddleware;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\IsPlanExpiredMiddleware;
use App\Http\Middleware\DealerShipAccessMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\IsProfileCompleteMiddleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Laravel\Passport\Http\Middleware\CreateFreshApiToken;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

$app =  Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: 'api/v1/',
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        // health: '/up',
        then: function () {
            Route::prefix('transport')
                ->as('transport.')
                ->middleware('web')
                ->group(base_path('routes/transport.php'));
            Route::prefix('api/v1/transport')
                // ->as('transport.')
                ->middleware('api')
                ->group(base_path('routes/transport_api.php'));
            Route::prefix('api/v1/driver')
                ->middleware('api')
                ->group(base_path('routes/driver_api.php'));
            Route::middleware('web')
                ->prefix('management')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth' => Authenticate::class,
            'isadminUser' => AdminCheckMiddleware::class,
            'ismultilevel' => DealerShipAccessMiddleware::class,
            'isSubuserPermission' => SubDairyUserAccess::class,
            'isBlocked' => UserBlocked::class,
            'Isprofile' => IsProfileCompleteMiddleware::class,
            'IsvalidPlan' => IsPlanExpiredMiddleware::class,
        ]);
        $middleware->append(
            Localization::class,
        );
        $middleware->group('api', [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:600,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            Localization::class,
        ]);
        $middleware->web(append: [
            CreateFreshApiToken::class,
            Localization::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (RouteNotFoundException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['success' => FALSE, 'status' => STATUS_NOT_FOUND, 'message' => __("message.PAGE_NOT_FOUND")], STATUS_NOT_FOUND);
            }
        });

        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['success' => FALSE, 'status' => TOO_MANY_REQUESTS, 'message' => __("message.TOO_MANY_REQUESTS")], TOO_MANY_REQUESTS);
            }
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['success' => FALSE, 'status' => STATUS_UNAUTHORIZED, 'message' => __("message.UNAUTHORIZED_ACCESS")], STATUS_UNAUTHORIZED);
            }
        });
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['success' => FALSE, 'status' => STATUS_NOT_FOUND, 'message' => __("message.PAGE_NOT_FOUND")], STATUS_NOT_FOUND);
            }
        });

        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['success' => FALSE, 'status' => STATUS_METHOD_NOT_ALLOWED, 'message' => __("message.METHOD_NOT_ALLOWED")], STATUS_METHOD_NOT_ALLOWED);
            }
        });
    })->create();

return $app;
