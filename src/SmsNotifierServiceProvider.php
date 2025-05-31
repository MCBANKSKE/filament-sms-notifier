<?php

namespace MCBANKSKE\FilamentSmsNotifier;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MCBANKSKE\FilamentSmsNotifier\Services\SmsService;
use MCBANKSKE\FilamentSmsNotifier\Drivers\TextSmsGatewayDriver;
use MCBANKSKE\FilamentSmsNotifier\Contracts\SmsGatewayDriver;

class SmsNotifierServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-sms-notifier')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        parent::packageBooted();

        $this->app->singleton(SmsGatewayDriver::class, function () {
            $config = config('smsnotifier');
            
            return new TextSmsGatewayDriver(
                $config['api_key'] ?? '',
                $config['partner_id'] ?? '',
                $config['shortcode'] ?? 'TextSMS'
            );
        });

        $this->app->bind(SmsService::class, function ($app) {
            return new SmsService($app->make(SmsGatewayDriver::class));
        });
    }
}
