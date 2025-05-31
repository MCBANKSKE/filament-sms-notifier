# Changelog

All notable changes to `mcbankske/filament-sms-notifier` will be documented in this file.

## [1.1.0] - 2025-05-31

### Added
- Added `SmsNotifier` facade for easier access to SMS sending functionality
- Added global `sms()` helper function for quick SMS sending
- Added comprehensive test suite with PHPUnit
- Added proper error handling and response formatting
- Added phone number validation and formatting
- Added retry mechanism for failed API requests

### Changed
- Updated service provider to extend Spatie's PackageServiceProvider
- Improved error messages and validation
- Updated README with better documentation and examples
- Refactored code for better maintainability

### Fixed
- Fixed JSON structure in composer.json
- Fixed duplicate script definitions
- Fixed service provider registration
- Fixed configuration loading

## [1.0.0] - 2025-05-30

### Added
- Initial release of Filament SMS Notifier package
- Support for TextSMS gateway
- Basic SMS sending functionality
- Filament widget for sending test messages
