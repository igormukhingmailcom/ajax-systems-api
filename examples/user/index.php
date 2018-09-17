<?php

require('../credentials.php');
require('../../vendor/autoload.php');

use Mukhin\AjaxSystemsApi\AjaxSystemsApiClient;
use Mukhin\AjaxSystemsApi\Exception\Exception;

$ajaxSystemsClient = new AjaxSystemsApiClient();

try {
    $ajaxSystemsClient->login(AJAX_SYSTEMS_LOGIN, AJAX_SYSTEMS_PASSWORD);

    $ajaxSystemsClient->getCsaConnection();

    $userData = $ajaxSystemsClient->getRawUserData();
    echo sprintf(
        "User %s have next settings:\n E-mail: %s\n Mobile: %s\n Locale: %s\n",
        $userData['userName'],
        $userData['userMail'],
        $userData['userMobile'],
        $userData['locale']
    );

} catch (Exception $e) {
    echo $e->getMessage();
}
