# AjaxSystemsApi

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Quality Score][ico-code-quality]][link-code-quality]

API client for [ajax.systems](https://ajax.systems/) security system

**Warning** 
This library is based on grey/reverse engineered Ajax Systems **Web API** which was closed at 2018.
So currently this library doesn't work.

**Future upgrade**
New non-public **Ajax Systems Enterprise API** is under development right now.
Please, star this project if you interested to be announced once 
**Enterprise API** become public and available to all clients.

## Cases current library covers

(Sublists contain cases when library potentially can be used) 

- [x] [Turn on/off WallSwitches](examples/wallswitch)
  - Turn on/off lights/boiler/irrigation connected to WallSwitch with CRON
  - Ignite a TNT connected to WallSwitch on some external event
- [x] [Arm/disarm/partially arm (night mode)](examples/arm)
  - Arm/disarm your Hub with CRON
- [x] [Send panic](examples/panic)
  - Send panic to Hub on some external event
- [x] [Get Hub's raw data](examples/hubs)
  - Get battery charge of all sensors daily and send notification to user's email with CRON 
  - Get temperature of some room's sensor to turn on room's heater
  - Get power consumption of WallSwitch connected device to store statistics
  - Self-test script to check important sensors settings and send notification to user's 
    email if some important settings was changed since last time 
- [x] [Read raw log](examples/logs)
  - Track HUB events for home automation
  - Backup events to external source
- [x] [Get Hub's SIM card balance](examples/balance)
  - Track and warn user if balance is low
  - Track and automatically add money to balance (pay via some service) 
    if balance is low
- [x] [Get logged in user data](examples/user)

## Installation

```bash
composer require igormukhingmailcom/ajax-systems-api
```

## Usage

```php
<?php

require('vendor/autoload.php');

use Mukhin\AjaxSystemsApi\AjaxSystemsApiClient;
use Mukhin\AjaxSystemsApi\Exception\Exception;

$ajaxSystemsClient = new AjaxSystemsApiClient();

try {
    $ajaxSystemsClient->login('login', 'password');
    $ajaxSystemsClient->getCsaConnection();
    $ajaxSystemsClient->setArm(AjaxSystemsApiClient::ARM_STATE_PARTIAL, 'hub id');
    echo "Partially armed";
} catch (Exception $e) {
    echo $e->getMessage();
}
```

# Contribution

## Testing / running examples

```bash
composer install

# Put your credentials to credentials.php
cp examples/credentials.php.dist examples/credentials.php
nano examples/credentials.php

# Go to any example an run it
cd examples/arm
php index.php
```

# TODO

- [ ] Turn on/off Ajax Relay if this supported by app.ajax.systems (haven't this device to play with)
- [ ] Turn on/off Ajax Socket if this supported by app.ajax.systems (haven't this device to play with)
- [ ] Convert RAW data to objects
- [ ] Save objects
- [ ] SSE (server-sent events)
- [ ] Symfony bundle


[ico-version]: https://img.shields.io/packagist/v/igormukhingmailcom/ajax-systems-api.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/igormukhingmailcom/ajax-systems-api.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/igormukhingmailcom/ajax-systems-api
[link-code-quality]: https://scrutinizer-ci.com/g/igormukhingmailcom/ajax-systems-apiAJAX_DEFAULT_HUB_ID
