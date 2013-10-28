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

    echo "<h3>Show a profile of an user</h3>";
    ld($api->showAccount());
    echo "<h3>Update a profile of one user</h3>";



    ld($api->updateAccount('alex_xabier','alex_xabier@gmail.com','alex'));
    //me rollback
    $api->updateAccount('alex','alex@demo.com','alex');



    echo "<h3>Update a Password one user <h3>";
    ld($api->changePassword('alex','alex_new','alex_new'));
    //me rollback
    $api->changePassword('alex_new','alex','alex');



}catch (ApiException $ex){
    echo "<br>ERROR:<br>";
    echo ($ex->getMessage());
}