<?php

namespace MCBANKSKE\FilamentSmsNotifier\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array send(string $to, string $message)
 * 
 * @see \MCBANKSKE\FilamentSmsNotifier\Services\SmsService
 */
class SmsNotifier extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'filament-sms-notifier';
    }
}
