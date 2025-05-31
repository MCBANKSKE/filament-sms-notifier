<?php

namespace MCBANKSKE\FilamentSmsNotifier\Filament\Widgets;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\App;
use Filament\Notifications\Notification;
use MCBANKSKE\FilamentSmsNotifier\Services\SmsService;

class SendTestSmsWidget extends Widget
{
    protected static string $view = 'filament-sms-notifier::widgets.send-test-sms-widget';

    public ?string $phone = null;
    public ?string $message = null;

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('phone')
                ->label('Phone Number')
                ->required()
                ->tel(),

            Textarea::make('message')
                ->label('Message')
                ->required(),
        ];
    }

    public function send(): void
    {
        $this->validate();

        $service = App::make(SmsService::class);

        $success = $service->send($this->phone, $this->message);

        Notification::make()
            ->title($success ? 'SMS Sent Successfully' : 'Failed to Send SMS')
            ->success($success)
            ->danger(!$success)
            ->send();

        $this->phone = '';
        $this->message = '';
    }
}
