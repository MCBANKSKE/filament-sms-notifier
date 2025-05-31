
# Filament SMS Notifier

**Filament SMS Notifier** is a flexible and reusable [Filament](https://filamentphp.com) plugin that allows you to send SMS messages using configurable drivers. It includes a simple widget for sending test SMS directly from the Filament admin panel.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mcbankske/filament-sms-notifier.svg?style=flat-square)](https://packagist.org/packages/mcbankske/filament-sms-notifier)
[![Total Downloads](https://img.shields.io/packagist/dt/mcbankske/filament-sms-notifier.svg?style=flat-square)](https://packagist.org/packages/mcbankske/filament-sms-notifier)
[![License](https://img.shields.io/github/license/mcbankske/filament-sms-notifier)](LICENSE.md)
[![PHP Version](https://img.shields.io/packagist/php-v/mcbankske/filament-sms-notifier)](composer.json)

## ✨ Features

- ✅ Send SMS from any part of your Laravel application
- 📦 Built-in support for [TextSMS](https://textsms.co.ke)
- 🔌 Easily extendable with custom drivers
- 🛠️ Includes Filament widget for manual SMS testing
- ⚙️ Configurable via `.env` and `config/smsnotifier.php`
- 🚀 Seamless integration with Filament v3

## 🚀 Installation

You can install the package via composer:

```bash
composer require mcbankske/filament-sms-notifier
```

Publish the config file with:

```bash
php artisan vendor:publish --tag=filament-sms-notifier-config
```

## ⚙️ Configuration

Add your TextSMS API credentials to your `.env` file:

```env
SMSNOTIFIER_API_KEY=your_api_key_here
SMSNOTIFIER_PARTNER_ID=your_partner_id_here
SMSNOTIFIER_SHORTCODE=YourBrandName  # Optional, defaults to 'TextSMS'
```

### Automatic Package Discovery

This package supports Laravel's package auto-discovery, so the service provider and facade will be automatically registered for you.

If you're using Laravel 5.5+ with package auto-discovery disabled, you'll need to manually register the service provider in `config/app.php`:

```php
'providers' => [
    // ...
    MCBANKSKE\FilamentSmsNotifier\SmsNotifierServiceProvider::class,
],
```

And if you want to use the facade, add this to your `aliases` array:

```php
'aliases' => [
    // ...
    'SmsNotifier' => MCBANKSKE\FilamentSmsNotifier\Facades\SmsNotifier::class,
],
```

### Requirements

- PHP 8.1 or higher
- Laravel 9.0 or higher
- Filament 3.0 or higher
- GuzzleHTTP (will be installed automatically)

## ⚙️ Configuration

Set your default driver and credentials in `.env`:

```env
SMSNOTIFIER_DRIVER=textsms
SMSNOTIFIER_API_KEY=your-api-key
SMSNOTIFIER_PARTNER_ID=your-partner-id
SMSNOTIFIER_SHORTCODE=YourShortCode
```

You can also modify the default configuration in `config/smsnotifier.php`:

```php
return [
    'default' => env('SMSNOTIFIER_DRIVER', 'textsms'),
    
    'drivers' => [
        'textsms' => [
            'api_key' => env('SMSNOTIFIER_API_KEY'),
            'partner_id' => env('SMSNOTIFIER_PARTNER_ID'),
            'shortcode' => env('SMSNOTIFIER_SHORTCODE'),
        ],
        // Add your custom drivers here
    ],
];
```

## 💡 Usage

### Using the Facade

You can use the `SmsNotifier` facade to send SMS messages:

```php
use MCBANKSKE\FilamentSmsNotifier\Facades\SmsNotifier;

// Send an SMS
$response = SmsNotifier::send('254700000000', 'Hello from Filament SMS Notifier!');

if ($response['success']) {
    // SMS sent successfully
} else {
    // Handle error
    logger()->error('Failed to send SMS: ' . $response['message']);
}
```

### Using the Helper Function

For convenience, you can also use the `sms()` helper function:

```php
// Send an SMS using the helper function
$response = sms('254700000000', 'Hello from the helper function!');

if ($response['success']) {
    // SMS sent successfully
} else {
    // Handle error
    logger()->error('Failed to send SMS: ' . $response['message']);
}
```

### Using Dependency Injection

You can also inject the `SmsService` directly:

```php
use MCBANKSKE\FilamentSmsNotifier\Services\SmsService;

public function sendWelcomeSms(SmsService $smsService)
{
    $phoneNumber = '254700000000'; // E.164 format
    $message = 'Welcome to our service!';
    
    $response = $smsService->send($phoneNumber, $message);
    
    if ($response['success']) {
        // SMS sent successfully
    } else {
        // Handle error
        logger()->error('Failed to send SMS: ' . $response['message']);
    }
}
```

## 📊 Filament Widget

The package includes a ready-to-use widget for sending test SMS messages directly from your Filament admin panel.

### Adding the Widget to a Page

Add the widget to your Filament page's `getHeaderWidgets` or `getFooterWidgets` method:

```php
use MCBANKSKE\FilamentSmsNotifier\Filament\Widgets\SendTestSmsWidget;

protected function getHeaderWidgets(): array
{
    return [
        SendTestSmsWidget::class,
    ];
}
```

## 🧩 Creating Your Own Driver

1. Create a new class that implements `MCBANKSKE\FilamentSmsNotifier\Contracts\SmsGatewayDriver`:

```php
namespace App\SmsDrivers;

use MCBANKSKE\FilamentSmsNotifier\Contracts\SmsGatewayDriver;

class MyCustomDriver implements SmsGatewayDriver
{
    public function send(string $to, string $message): array
    {
        // Your implementation here
        
        return [
            'success' => true,
            'message' => 'SMS sent successfully',
            'data' => [/* response data */]
        ];
    }
}
```

2. Register your driver in a service provider's `boot` method:

```php
use App\SmsDrivers\MyCustomDriver;
use MCBANKSKE\FilamentSmsNotifier\Services\SmsService;

public function boot()
{
    app()->when(MyCustomDriver::class)
        ->needs('$config')
        ->giveConfig('smsnotifier.drivers.my-custom');
        
    app(SmsService::class)->extend('my-custom', function ($app) {
        return new MyCustomDriver(config('smsnotifier.drivers.my-custom'));
    });
}
```

## 🧱 Package Structure

```
filament-sms-notifier/
├── config/                    # Configuration files
│   └── smsnotifier.php        # Main configuration
├── src/                       # Source files
│   ├── Contracts/             # Interfaces
│   │   └── SmsGatewayDriver.php
│   ├── Drivers/               # Built-in drivers
│   │   └── TextSmsGatewayDriver.php
│   ├── Services/              # Core services
│   │   └── SmsService.php
│   ├── Filament/              # Filament integration
│   │   └── Widgets/
│   │       └── SendTestSmsWidget.php
│   └── SmsNotifierServiceProvider.php  # Service provider
└── resources/                 # Views and assets
    └── views/
        └── widgets/
            └── send-test-sms-widget.blade.php
```

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is open-sourced under the [MIT License](LICENSE.md).

## 🔗 Links

- [GitHub Repository](https://github.com/mcbankske/filament-sms-notifier)
- [Packagist](https://packagist.org/packages/mcbankske/filament-sms-notifier)
- [Filament Documentation](https://filamentphp.com/docs)