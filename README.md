# EasyPost PHP Client Library

[![CI](https://github.com/EasyPost/easypost-php/workflows/CI/badge.svg)](https://github.com/EasyPost/easypost-php/actions?query=workflow%3ACI)

EasyPost is a simple shipping API. You can sign up for an account at https://easypost.com.

## Installation

**NOTE:** This library relies on the [mbstring](http://php.net/manual/en/book.mbstring.php) extension. Ensure you have it [installed](http://www.php.net/manual/en/mbstring.installation.php) correctly before using the library.

### Install Client Library

**Install the Library via [Composer](http://getcomposer.org/):**

```shell
composer require easypost/easypost-php
```

Then, require the autoloader:

```php
require_once("/path/to/vendor/easypost/autoload.php");
```

**OR: Manually Install the Library**

Manually download this library and require it from your project:

```php
require_once("/path/to/lib/easypost.php");
```

### Install Dependencies

Run the following from the root directory of the library:

```shell
composer install
```

## Example

```php
require_once("/path/to/vendor/easypost/autoload.php");

\EasyPost\EasyPost::setApiKey('API_KEY');

$to_address = \EasyPost\Address::create([
    "name"    => "Dr. Steve Brule",
    "street1" => "179 N Harbor Dr",
    "city"    => "Redondo Beach",
    "state"   => "CA",
    "zip"     => "90277",
    "phone"   => "310-808-5243",
]);
$from_address = \EasyPost\Address::create([
    "company" => "EasyPost",
    "street1" => "118 2nd Street",
    "street2" => "4th Floor",
    "city"    => "San Francisco",
    "state"   => "CA",
    "zip"     => "94105",
    "phone"   => "415-456-7890",
]);
$parcel = \EasyPost\Parcel::create([
    "predefined_package" => "LargeFlatRateBox",
    "weight" => 76.9,
]);
$shipment = \EasyPost\Shipment::create([
    "to_address"   => $to_address,
    "from_address" => $from_address,
    "parcel"       => $parcel,
]);

$shipment->buy($shipment->lowest_rate());

$shipment->insure([
    'amount' => 100
]);

echo $shipment->postage_label->label_url;
```

## Documentation

Up-to-date documentation can be found at: https://www.easypost.com/docs.

## Testing & Development

**NOTE: Recording VCR cassettes only works with PHP 7.3 or 7.4. Once recorded, tests can be run on PHP 7 or 8.

Ensure dependencies are installed, then run any of the following:

```bash
# Lint project
composer lint

# Fix linting errors
composer fix

# Run tests
EASYPOST_TEST_API_KEY=123... EASYPOST_PROD_API_KEY=123... composer test

# Generate coverage reports (requires Xdebug)
EASYPOST_TEST_API_KEY=123... EASYPOST_PROD_API_KEY=123... composer coverage
```
