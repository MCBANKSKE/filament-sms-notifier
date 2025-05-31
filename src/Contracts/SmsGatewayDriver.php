<?php

namespace MCBANKSKE\FilamentSmsNotifier\Contracts;

interface SmsGatewayDriver
{
    /**
     * Send an SMS message.
     *
     * @param string $phoneNumber The phone number to send the message to (in E.164 format)
     * @param string $message The message to send
     * @return bool Whether the message was sent successfully
     * @throws \Exception If there's an error sending the message
     */
    public function send(string $phoneNumber, string $message): bool;
}
