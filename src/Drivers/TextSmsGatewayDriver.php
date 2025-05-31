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

    public function send(string $phoneNumber, string $message): bool
    {
        $response = Http::asJson()->post('https://sms.textsms.co.ke/api/services/sendsms/', [
            'apikey'    => $this->apiKey,
            'partnerID' => $this->partnerID,
            'shortcode' => $this->shortcode,
            'mobile'    => $phoneNumber,
            'message'   => $message,
        ]);

        return $response->successful();
    }
}
