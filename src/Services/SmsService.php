<?php

namespace MCBANKSKE\FilamentSmsNotifier\Services;

use MCBANKSKE\FilamentSmsNotifier\Contracts\SmsGatewayDriver;

class SmsService
{
    protected SmsGatewayDriver $driver;

    public function __construct(SmsGatewayDriver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Send an SMS message.
     *
     * @param string $to
     * @param string $message
     * @return bool
     */
    public function send(string $to, string $message): bool
    {
        return $this->driver->send($to, $message);
    }
}
