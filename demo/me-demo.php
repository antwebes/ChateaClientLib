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
    echo " Get whoami <br>";

    print_r($api->whoami());
    echo "<br/><br/><br/>";

     echo " del me <br>";
    print_r($api->delMe());
    echo "<br/><br/><br/>";


}catch (ApiException $ex){
    echo "<br>ERROR:<br>";
    echo ($ex->getMessage());
}