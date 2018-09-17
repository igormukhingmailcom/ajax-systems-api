<?php

require('../credentials.php');
require('../../vendor/autoload.php');

use Mukhin\AjaxSystemsApi\AjaxSystemsApiClient;
use Mukhin\AjaxSystemsApi\Exception\Exception;

$ajaxSystemsClient = new AjaxSystemsApiClient();

try {
    $ajaxSystemsClient->login(AJAX_SYSTEMS_LOGIN, AJAX_SYSTEMS_PASSWORD);

    $ajaxSystemsClient->getCsaConnection();

    echo $ajaxSystemsClient->getHubBalance(AJAX_DEFAULT_HUB_ID);

} catch (Exception $e) {
    echo $e->getMessage();
}
