<?php

namespace MCBANKSKE\FilamentSmsNotifier\Tests\Unit;

use Illuminate\Support\Facades\Http;
use MCBANKSKE\FilamentSmsNotifier\Contracts\SmsGatewayDriver;
use MCBANKSKE\FilamentSmsNotifier\Drivers\TextSmsGatewayDriver;
use MCBANKSKE\FilamentSmsNotifier\Services\SmsService;
use MCBANKSKE\FilamentSmsNotifier\Tests\TestCase;

class SmsServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock the HTTP client
        Http::fake([
            'sms.textsms.co.ke/api/services/sendsms/' => Http::response(['success' => true, 'message' => 'SMS sent']),
        ]);
    }
    
    /** @test */
    public function it_can_send_sms()
    {
        $driver = new TextSmsGatewayDriver('test-api-key', 'test-partner-id', 'TEST');
        $service = new SmsService($driver);
        
        $response = $service->send('254700000000', 'Test message');
        
        $this->assertTrue($response['success']);
        $this->assertEquals('SMS sent successfully', $response['message']);
    }
    
    /** @test */
    public function it_handles_failed_requests()
    {
        Http::fake([
            'sms.textsms.co.ke/api/services/sendsms/' => Http::response([
                'success' => false,
                'message' => 'Invalid API key'
            ], 401),
        ]);
        
        $driver = new TextSmsGatewayDriver('invalid-key', 'test-partner-id', 'TEST');
        $service = new SmsService($driver);
        
        $response = $service->send('254700000000', 'Test message');
        
        $this->assertFalse($response['success']);
        $this->assertStringContainsString('Invalid API key', $response['message']);
    }
}
