<?php
require __DIR__.'/../vendor/autoload.php';

use Ant\ChateaClient\Service\Client\ChateaGratisClient;
use Ant\ChateaClient\Client\Api;
use Ant\ChateaClient\Client\ApiException;

$client = ChateaGratisClient::factory(
    array('access_token' => 'access-token-demo',
        'environment'=>'dev'
    )
);
$api = new Api($client);

try{

    echo " show Account<br>";
    print_r($api->showAccount());
    echo "<br/><br/><br/>";

    echo " update Account<br>";
    print_r($api->updateAccount('alex_xabier','alex_xabier@gmail.com','alex'));
    print_r($api->updateAccount('alex','alex@demo.com','alex'));
    echo "<br/><br/><br/>";

    echo " Change password<br>";
    print_r($api->changePassword('alex','alex_new','alex_new'));
            $api->changePassword('alex_new','alex','alex');
}catch (ApiException $ex){
    echo "<br>ERROR:<br>";
    echo ($ex->getMessage());
}