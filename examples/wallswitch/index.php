<?php

require('../credentials.php');
require('../../vendor/autoload.php');

use Mukhin\AjaxSystemsApi\AjaxSystemsApiClient;
use Mukhin\AjaxSystemsApi\Exception\Exception;

$ajaxSystemsClient = new AjaxSystemsApiClient();

try {
    $ajaxSystemsClient->login(AJAX_SYSTEMS_LOGIN, AJAX_SYSTEMS_PASSWORD);

    $ajaxSystemsClient->getCsaConnection();

    if ($ajaxSystemsClient->setSwitchState(AjaxSystemsApiClient::COMMAND_SWITCH_STATE_ON, AJAX_WALL_SWITCH_ID,AJAX_DEFAULT_HUB_ID)) {
        echo "Switch turned on\n";
    }

    if ($ajaxSystemsClient->setSwitchState(AjaxSystemsApiClient::COMMAND_SWITCH_STATE_OFF, AJAX_WALL_SWITCH_ID,AJAX_DEFAULT_HUB_ID)) {
        echo "Switch turned off\n";
    }

} catch (Exception $e) {
    echo $e->getMessage();
}
