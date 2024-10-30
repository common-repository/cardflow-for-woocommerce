# Cardflow PHP SDK
[![Latest Stable Version](https://poser.pugx.org/cardflow/cardflow-php/v)](//packagist.org/packages/cardflow/cardflow-php)
![CI](https://github.com/cardflow/cardflow-php/workflows/CI/badge.svg?branch=master)
[![License](https://poser.pugx.org/cardflow/cardflow-php/license)](//packagist.org/packages/cardflow/cardflow-php)

PHP library for interacting with the Cardflow API. This SDK is using the public [Cardflow API](https://docs.cardflow.nl/api) and enables you to:
- Accept gift cards in your webshop
- Redeem and issue gift cards in your POS-system

## Requirements
- PHP 7.4.0 and later
- A valid API Key, that can be [generated](https://dashboard.cardflow.nl) in your Cardflow dashboard

## Installation
The SDK is published on Packagist and can be installed using Composer.

`composer require cardflow/cardflow-php`

## Getting Started
Before starting, it is recommended to read the documentation of the underlying [Cardflow API](https://docs.cardflow.nl/api) where all possible options to include are described.

Initializing the client and performing an API call is done as follows.

```php
$cardflow = new \Cardflow\Client\CardflowClient('eyJ0eXAi....');
$giftCard = $cardflow->giftCards->get('ABCDABCDABCDABCD');
```

### Retrieve a Gift Card

```php
$giftCard = $cardflow->giftCards->get('ABCDABCDABCDABCD');
```

### Redeem a Gift Card

```php
$transaction = $cardflow->giftCards->redeem(
  'ABCDABCDABCDABCD',
  [
    "amount" => 1250,
    "currency" => "EUR",
    "capture" => false
  ]
);
```

## Development
Clone the Git repository, so you have a local working copy.

`git clone https://github.com/cardflow/cardflow-php`

Install required (developing) dependencies using Composer.

`composer install`

Make sure you follow the PSR12 coding standards.

`composer phpstan` & `composer phpcs`
