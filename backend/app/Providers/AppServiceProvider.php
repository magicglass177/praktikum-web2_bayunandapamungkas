<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\{Ticket, User};
use App\Policies\TicketPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Gate, RateLimiter};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Gate::policy(Ticket::class, TicketPolicy::class);

Gate::define(
    'view-ticket-summary',
    fn (User $user) => (bool) $user->is_admin
);

RateLimiter::for('api-login', function (Request $request) {
    return Limit::perMinute(5)
        ->by('login-ip:' . $request->ip());
});

RateLimiter::for('api-v1', function (Request $request) {
    return Limit::perMinute(60)
        ->by('api-user:' . $request->user()->id);
});

        \Illuminate\Database\Eloquent\Model::preventLazyLoading(
    ! $this->app->isProduction()
    );
    }
}
