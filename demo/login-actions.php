<?php
require __DIR__.'/../vendor/autoload.php';

use Ant\ChateaClient\Service\Client\ChateaGratisClient;
use Guzzle\Http\Exception\BadResponseException;
use Ant\ChateaClient\Client\Api;


$client = ChateaGratisClient::factory(
    array('access_token' => 'access-token-demo',
        'environment'=>'dev'
    )
);
$api = new Api($client);

try{
    $key = time();
    echo "<h3>Login up</h3>";
    print_r($api->register("xabier_".$key,"xabier_".$key."@gmail.com",'12345678','12345678','api.chateagratis.local'));


    echo "<h3>Forgot password | send email</h3>";
    print_r($api->forgotPassword("alex3@chateagratis.net"));

    echo "<h3>Forgot password | send email</h3>";
    print_r($api->logout());

}catch (ApiException $ex){
    echo "<br>ERROR:<br>";
    echo ($ex->getMessage());
}