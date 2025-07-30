<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::define('is-superadmin', fn ($user) => $user->system_role === 'superadmin');

        Gate::define('is-staff', fn ($user) => $user->system_role === 'staff');
    }
}
