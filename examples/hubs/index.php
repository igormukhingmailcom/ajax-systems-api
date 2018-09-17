<?php

require('../credentials.php');
require('../../vendor/autoload.php');

use Mukhin\AjaxSystemsApi\AjaxSystemsApiClient;
use Mukhin\AjaxSystemsApi\Exception\Exception;

$ajaxSystemsClient = new AjaxSystemsApiClient();

try {
    $ajaxSystemsClient->login(AJAX_SYSTEMS_LOGIN, AJAX_SYSTEMS_PASSWORD);

    $ajaxSystemsClient->getCsaConnection();

    $hubsData = $ajaxSystemsClient->getRawHubsData();

    foreach ($hubsData as $hubId=>$hubData) {
        echo sprintf(
            "Hub #%s have %s unread events and %s objects connected:\n",
            $hubId,
            $hubData['unreadEvents'],
            count($hubData['objects'])
        );

        foreach ($hubData['objects'] as $object) {
            echo sprintf(
                " - Object %s have next properties: %s\n",
                $object['hexObjectId'],
                implode(', ', array_keys($object))
            );
        }
    }

} catch (Exception $e) {
    echo $e->getMessage();
}
