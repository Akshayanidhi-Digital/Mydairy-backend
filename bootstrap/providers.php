<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\UserProviders::class,
    Barryvdh\DomPDF\ServiceProvider::class,
    Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
    Heyharpreetsingh\FCM\Providers\FCMServiceProvider::class,
    LaravelPWA\Providers\LaravelPWAServiceProvider::class,
    SocialiteProviders\Manager\ServiceProvider::class,
];
