<?php

namespace MCBANKSKE\FilamentSmsNotifier;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider as BasePackageServiceProvider;
use MCBANKSKE\FilamentSmsNotifier\Services\SmsService;
use MCBANKSKE\FilamentSmsNotifier\Drivers\TextSmsGatewayDriver;
use MCBANKSKE\FilamentSmsNotifier\Contracts\SmsGatewayDriver;

class SmsNotifierServiceProvider extends BasePackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-sms-notifier')
            ->hasConfigFile('smsnotifier')
            ->hasViews()
            ->hasTranslations();
    }

    public function packageRegistered()
    {
        $this->app->singleton(SmsGatewayDriver::class, function () {
            $config = config('smsnotifier');
            
            if (empty($config['api_key']) || empty($config['partner_id'])) {
                throw new \RuntimeException('SMS API credentials are not configured. Please set SMSNOTIFIER_API_KEY and SMSNOTIFIER_PARTNER_ID in your .env file.');
            }
            
            return new TextSmsGatewayDriver(
                $config['api_key'] ?? '',
                $config['partner_id'] ?? '',
                $config['shortcode'] ?? 'TextSMS'
            );
        });

        $this->app->bind('filament-sms-notifier', function () {
            return new SmsService($this->app->make(SmsGatewayDriver::class));
        });
    }

    public function packageBooted(): void
    {
        parent::packageBooted();

        $this->app->singleton(SmsGatewayDriver::class, function () {
            $config = config('smsnotifier');
            
            if (empty($config['api_key']) || empty($config['partner_id'])) {
                throw new \RuntimeException('SMS API credentials are not configured. Please set SMSNOTIFIER_API_KEY and SMSNOTIFIER_PARTNER_ID in your .env file.');
            }
            
            return new TextSmsGatewayDriver(
                $config['api_key'] ?? '',
                $config['partner_id'] ?? '',
                $config['shortcode'] ?? 'TextSMS'
            );
        });

        $this->app->bind(SmsService::class, function ($app) {
            return new SmsService($app->make(SmsGatewayDriver::class));
        });

        // Register the facade
        $this->app->bind('filament-sms-notifier', function ($app) {
            return $app->make(SmsService::class);
        });
    }
    
    public function packageRegistered(): void
    {
        parent::packageRegistered();
        
        $this->mergeConfigFrom(
            __DIR__.'/../../config/smsnotifier.php', 'smsnotifier'
        );
        
        // Register the helper file
        if (file_exists($helpers = __DIR__.'/helpers.php')) {
            require $helpers;
        }
    }
}
