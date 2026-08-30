<?php

namespace App\Providers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

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
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        }

        // Register custom Brevo HTTP API transport driver
        Mail::extend('brevo', function () {
            $key = config('services.brevo.key');
            return (new BrevoTransportFactory())->create(
                Dsn::fromString("brevo+api://{$key}@default")
            );
        });
    }
}