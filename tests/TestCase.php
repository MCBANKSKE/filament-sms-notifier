<?php

namespace MCBANKSKE\FilamentSmsNotifier\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use MCBANKSKE\FilamentSmsNotifier\SmsNotifierServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Load the test configuration
        $this->app['config']->set('smsnotifier', [
            'api_key' => 'test-api-key',
            'partner_id' => 'test-partner-id',
            'shortcode' => 'TEST',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            SmsNotifierServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Set up any environment variables or configurations needed for testing
    }
}
