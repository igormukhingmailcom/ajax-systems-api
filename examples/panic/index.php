<?php

require('../credentials.php');
require('../../vendor/autoload.php');

use Mukhin\AjaxSystemsApi\AjaxSystemsApiClient;
use Mukhin\AjaxSystemsApi\Exception\Exception;

$ajaxSystemsClient = new AjaxSystemsApiClient();

try {
    $ajaxSystemsClient->login(AJAX_SYSTEMS_LOGIN, AJAX_SYSTEMS_PASSWORD);

    $ajaxSystemsClient->getCsaConnection();

    if ($ajaxSystemsClient->sendPanic(AJAX_DEFAULT_HUB_ID)) {
        echo "Panic was sent\n";
    }

} catch (Exception $e) {
    echo $e->getMessage();
}
