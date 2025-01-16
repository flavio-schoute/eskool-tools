<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Number;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Gate::after(fn (User $user): bool => $user->hasRole('Super Admin'));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureCommands();
        $this->configureDates();
        $this->configureModels();
        $this->configureCurrencies();
        $this->configureUrls();
        $this->configureVite();
        $this->configureLanguage();
    }

    /**
     * Configure the application's commands.
     */
    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(
            app()->isProduction()
        );
    }

    /**
     * Configure the dates.
     */
    private function configureDates(): void
    {
        Date::use(CarbonImmutable::class);
    }

    /**
     * Configure the models.
     */
    private function configureModels(): void
    {
        Model::shouldBeStrict(! app()->isProduction());
    }

    /**
     * Configure the currency.
     */
    private function configureCurrencies(): void
    {
        Number::useCurrency('EUR');
    }

    /**
     * Configure the application's URLs.
     */
    private function configureUrls(): void
    {
        if (app()->isProduction()) {
            URL::forceScheme('https');
        }
    }

    /**
     * Configure the application's Vite instance.
     */
    private function configureVite(): void
    {
        Vite::useAggressivePrefetching();
    }

    /**
     * Configure the application's language.
     */
    private function configureLanguage(): void
    {
        App::setLocale('NL');
    }
}
