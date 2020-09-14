<?php

use App\Api\SysDashApi;

require "vendor/autoload.php";
require "functions.php";
$config = require "config.php";

$collectedData = [];

foreach ($config['collectors'] as $collector)
{
    $class = $collector['collector'];
    $data = $collector['config'];
    $collector = new $class($data);

    $collectedData = $collector->appendData($collectedData);
}

print_r($collectedData);

$token = $config['server']['token'];

$api = new SysDashApi($config['server']['baseUrl'], $token, $config['server']['systemID']);

if (isset($collectedData['software']))
{
    echo "Sending software info...";
    $api->sendSoftware($collectedData['software']);
}

if (isset($collectedData['os']))
{
    echo "Sending os info...";
    $api->sendOS($collectedData['os']);
}
