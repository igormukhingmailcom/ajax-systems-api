<?php

require('../credentials.php');
require('../../vendor/autoload.php');

use Mukhin\AjaxSystemsApi\AjaxSystemsApiClient;
use Mukhin\AjaxSystemsApi\Exception\Exception;

$ajaxSystemsClient = new AjaxSystemsApiClient();

try {
    $ajaxSystemsClient->login(AJAX_SYSTEMS_LOGIN, AJAX_SYSTEMS_PASSWORD);

    $ajaxSystemsClient->getCsaConnection();

    $ajaxSystemsClient->setArm(AjaxSystemsApiClient::ARM_STATE_PARTIAL, AJAX_DEFAULT_HUB_ID);
    echo "Partial armed\n";

    $ajaxSystemsClient->setArm(AjaxSystemsApiClient::ARM_STATE_DISARMED, AJAX_DEFAULT_HUB_ID);
    echo "Disarmed\n";

} catch (Exception $e) {
    echo $e->getMessage();
}
