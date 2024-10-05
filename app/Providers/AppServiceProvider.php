<?php

namespace App\Providers;

use App\Livewire\ViewInvoice;
use App\Models\User;
use App\Services\PlugAndPayOrderService;
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
