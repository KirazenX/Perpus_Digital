<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Gate checks for convenience
        Gate::define('is-admin', fn($user) => $user->isAdministrator());
        Gate::define('is-staff', fn($user) => $user->isStaff());
        Gate::define('is-peminjam', fn($user) => $user->isPeminjam());
    }
}