<?php

use MCBANKSKE\FilamentSmsNotifier\Facades\SmsNotifier;

if (! function_exists('sms')) {
    /**
     * Send an SMS message.
     *
     * @param  string  $to
     * @param  string  $message
     * @return array
     */
    function sms(string $to, string $message): array
    {
        return SmsNotifier::send($to, $message);
    }
}
