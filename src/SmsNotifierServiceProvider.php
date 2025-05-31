<?php

namespace MCBANKSKE\FilamentSmsNotifier;

use Spatie\LaravelPackageTools\PackageServiceProvider;
use MCBANKSKE\FilamentSmsNotifier\Services\SmsService;
use MCBANKSKE\FilamentSmsNotifier\Drivers\TextSmsGatewayDriver;
use MCBANKSKE\FilamentSmsNotifier\Contracts\SmsGatewayDriver;
use Spatie\LaravelPackageTools\Package;

class SmsNotifierServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-sms-notifier';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(self::$name)
            ->hasConfigFile()
            ->hasViews();
    }

    public function packageBooted(): void
    {
        $this->app->singleton(SmsGatewayDriver::class, function () {
            return new TextSmsGatewayDriver(
                config('smsnotifier.api_key', ''),
                config('smsnotifier.partner_id', ''),
                config('smsnotifier.shortcode', 'TextSMS')
            );
        });

        $this->app->singleton(SmsService::class, function ($app) {
            return new SmsService($app->make(SmsGatewayDriver::class));
        });
    }
}
