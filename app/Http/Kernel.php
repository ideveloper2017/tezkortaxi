<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\CheckLanguage::class,
            \App\Http\Middleware\Localization::class, // Our localization middleware
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'accesspage' => \App\Http\Middleware\CheckAccessPage::class,
        'account' => \App\Http\Middleware\RedirectIfNotAccount::class,
        'account.guest' => \App\Http\Middleware\RedirectIfAccount::class,
        'fleet' => \App\Http\Middleware\RedirectIfNotFleet::class,
        'fleet.guest' => \App\Http\Middleware\RedirectIfFleet::class,
        'dispatcher' => \App\Http\Middleware\RedirectIfNotDispatcher::class,
        'dispatcher.guest' => \App\Http\Middleware\RedirectIfDispatcher::class,
        'provider' => \App\Http\Middleware\RedirectIfNotProvider::class,
        'crm' => \App\Http\Middleware\RedirectIfNotCrm::class,
        'crm.guest' => \App\Http\Middleware\RedirectIfCrm::class,
        'cms' => \App\Http\Middleware\RedirectIfNotCms::class,
        'cms.guest' => \App\Http\Middleware\RedirectIfCms::class,
        'provider.guest' => \App\Http\Middleware\RedirectIfProvider::class,
        'provider.api' => \App\Http\Middleware\ProviderApiMiddleware::class,
        'admin' => \App\Http\Middleware\RedirectIfNotAdmin::class,
        'admin.guest' => \App\Http\Middleware\RedirectIfAdmin::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'support' => \App\Http\Middleware\RedirectIfNotSupport::class,
        'support.guest' => \App\Http\Middleware\RedirectIfSupport::class,
        'jwt.auth' => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
        'jwt.refresh' => \Tymon\JWTAuth\Http\Middleware\RefreshToken::class

 ];

}
