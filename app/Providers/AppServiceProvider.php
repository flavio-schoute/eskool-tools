<?php

namespace App\Providers;

use App\Models\User;
use App\Repositories\AddressRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\Interfaces\AddressRepositoryInterface;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Services\CustomerService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Number;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Gate::after(function (User $user) {
            return $user->hasRole('Super Admin');
        });

        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(AddressRepositoryInterface::class, AddressRepository::class);

        // $this->app->bind(CustomerService::class, function ($app) {
        //     return new UserService($app->make(UserRepositoryInterface::class));
        // });
    }

    public function boot(): void
    {
        Model::shouldBeStrict(! app()->isProduction());

        Number::useCurrency('EUR');
    }
}
