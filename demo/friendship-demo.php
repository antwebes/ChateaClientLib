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

    echo " showFriends by user 1 <br>";
    print_r($api->showFriends(1));
    echo "<br/><br/><br/>";


    echo " addFriends by user 1 friend 3 <br>";
    print_r($api->addFriends(1,3));
    echo "<br/><br/><br/>";
//    echo " delFriend by user 1 friend 3 <br>";
//    print_r($api->delFriend(1,3));
//    echo "<br/><br/><br/>";
//    echo " showFriendshipsPending by user 1 <br>";
//    print_r($api->showFriendshipsPending(1));
//    echo "<br/><br/><br/>";

//    echo " showFriendshipsRequest by user 1 <br>";
//    print_r($api->showFriendshipsRequest(1));
//    echo "<br/><br/><br/>";

}catch (ApiException $ex){
    echo "<br>ERROR:<br>";
    echo ($ex->getMessage());
}