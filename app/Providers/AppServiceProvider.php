<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Gate::after(function (User $user) {
            return $user->hasRole('Super Admin');
        });
    }

    public function boot(): void
    {
        //
    }
}
