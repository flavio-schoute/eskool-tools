<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use PlugAndPay\Sdk\Service\Client;
use PlugAndPay\Sdk\Service\OrderService;

class PlugAndPayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Client::class, function (): Client {
            /** @var string $accessToken */
            $accessToken = config('services.plug_and_pay.api_key');

            return new Client($accessToken);
        });

        $this->app->singleton(OrderService::class, fn (): \PlugAndPay\Sdk\Service\OrderService => new OrderService($this->app->make(Client::class)));
    }

    public function boot(): void
    {
        //
    }
}
