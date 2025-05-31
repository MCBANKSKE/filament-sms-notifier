<?php

namespace MCBANKSKE\FilamentSmsNotifier\Drivers;

use Illuminate\Support\Facades\Http;
use MCBANKSKE\FilamentSmsNotifier\Contracts\SmsGatewayDriver;

class TextSmsGatewayDriver implements SmsGatewayDriver
{
    protected string $apiKey;
    protected string $partnerID;
    protected string $shortcode;

    public function __construct(
        string $apiKey,
        string $partnerID,
        string $shortcode = 'TextSMS'
    ) {
        $this->apiKey = $apiKey;
        $this->partnerID = $partnerID;
        $this->shortcode = $shortcode;
    }

    /**
     * @inheritDoc
     */
    public function send(string $phoneNumber, string $message): bool
    {
        if (empty($this->apiKey) || empty($this->partnerID)) {
            throw new \RuntimeException('SMS API credentials are not configured');
        }

        $response = Http::asJson()
            ->timeout(30)
            ->retry(2, 100)
            ->post('https://sms.textsms.co.ke/api/services/sendsms/', [
                'apikey'    => $this->apiKey,
                'partnerID' => $this->partnerID,
                'shortcode' => $this->shortcode,
                'mobile'    => $this->formatPhoneNumber($phoneNumber),
                'message'   => $message,
            ]);

        if ($response->successful()) {
            $responseData = $response->json();
            
            // Check if the API returned a success response
            if (isset($responseData['success']) && $responseData['success'] === true) {
                return true;
            }
            
            $error = $responseData['message'] ?? 'Unknown error from SMS gateway';
            throw new \RuntimeException("SMS sending failed: $error");
        }

        throw new \RuntimeException("Failed to connect to SMS gateway: " . $response->status());
    }
    
    /**
     * Format phone number to E.164 format if needed
     */
    protected function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove any non-numeric characters
        $phoneNumber = preg_replace('/[^0-9+]/', '', $phoneNumber);
        
        // If it doesn't start with +, assume it's a local number and add country code
        if (strpos($phoneNumber, '+') !== 0) {
            // Remove leading 0 if present and add country code
            $phoneNumber = ltrim($phoneNumber, '0');
            $phoneNumber = '+254' . $phoneNumber;
        }
        
        return $phoneNumber;
    }
}
