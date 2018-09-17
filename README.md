# AjaxSystemsApi

API client for [ajax.systems](https://ajax.systems/) security system

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

## Testing / running example

```bash
composer install

# Put your credentials to credentials.php
cp examples/credentials.php.dist examples/credentials.php
nano examples/credentials.php

# Go to any example an run it
cd examples/arm
php index.php
```

## Cases current library covers

- [x] Arm/disarm/partially arm (night mode)
  - Arm/disarm your Hub with CRON
- [x] Send panic
  - Send panic to Hub on some external event
- [x] Read raw log
  - Track HUB events for home automation
  - Backup events to external source
