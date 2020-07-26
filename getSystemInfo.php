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

$api = new SysDashApi($config['server']['baseUrl'], $token);

if (isset($collectedData['software']))
    $api->sendSoftware($collectedData['software']);

if (isset($collectedData['os']))
    $api->sendOS($collectedData['os']);
