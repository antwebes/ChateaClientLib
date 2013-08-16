<?php
require __DIR__.'/../vendor/autoload.php';

use Ant\ChateaClient\Client\ClientConfig;
use Ant\ChateaClient\Client\ChateaOAuth2;

$clientConfig = ClientConfig::fromJSONFile(__DIR__.'/../app/config/client_secrets.json');

$auth = new ChateaOAuth2($clientConfig);


$auth->authenticate();
