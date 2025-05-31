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
     * @param string $to The phone number to send the message to (in E.164 format)
     * @param string $message The message to send
     * @return array{success: bool, message: string, data: array}
     */
    public function send(string $to, string $message): array
    {
        try {
            $result = $this->driver->send($to, $message);
            
            return [
                'success' => $result,
                'message' => $result ? 'SMS sent successfully' : 'Failed to send SMS',
                'data' => []
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            ];
        }
    }
}
