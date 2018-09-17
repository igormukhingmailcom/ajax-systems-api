<?php

require('../credentials.php');
require('../../vendor/autoload.php');

use Mukhin\AjaxSystemsApi\AjaxSystemsApiClient;
use Mukhin\AjaxSystemsApi\Exception\Exception;

$ajaxSystemsClient = new AjaxSystemsApiClient();

try {
    $ajaxSystemsClient->login(AJAX_SYSTEMS_LOGIN, AJAX_SYSTEMS_PASSWORD);

    $ajaxSystemsClient->getCsaConnection();

    $logs = $ajaxSystemsClient->getRawLogs(AJAX_DEFAULT_HUB_ID, 4, 0);
    foreach ($logs as $log) {
        // Division by 1000 move dot between unix time and microseconds
        $dateTime = DateTime::createFromFormat('U.u', $log['time'] / 1000);
        echo sprintf(
            "%s. Event with code #%s raised at object %s (#%s at HUB %s) at %s\n",
            $log['id'],
            $log['eventCode'],
            $log['objName'],
            $log['hexObjectId'],
            $log['hexHubId'],
            $dateTime->format('Y-m-d H:i:s.u')
        );
    }

} catch (Exception $e) {
    echo $e->getMessage();
}
