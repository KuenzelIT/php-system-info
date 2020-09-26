<?php

use App\Api\SysDashApi;

require "vendor/autoload.php";
require "functions.php";
$config = require "config.php";

$data = [];

foreach ($config['collectors'] as $collector)
{
    $class = $collector['collector'];
    $collectorConfig = $collector['config'];
    $collector = new $class($collectorConfig);

    $data = $collector->appendData($data);
}

print_r($data);

$token = $config['server']['token'];

$api = new SysDashApi($config['server']['baseUrl'], $token, $config['server']['systemID']);

if (isset($data['software']) && isset($data['hasPMUpgrades']))
{
    echo "Sending software info...";
    $api->sendSoftware($data['software'], $data['hasPMUpgrades']);
}

if (isset($data['os']))
{
    echo "Sending os info...";
    $api->sendOS($data['os']);
}
