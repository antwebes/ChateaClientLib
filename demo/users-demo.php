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
    echo "  Who <br>";

    print_r($api->who());
    echo "<br/><br/><br/>";

    echo " Show user 1 <br>";
    print_r($api->showUser(1));
    echo "<br/><br/><br/>";

    echo " showUsersBlocked by user 1 <br>";
    print_r($api->showUsersBlocked(1));
    echo "<br/><br/><br/>";

    echo " addUsersBlocked by user 1 to 6<br>";
    print_r($api->addUserBlocked(1,6));
    echo "<br/><br/><br/>";

    echo " delUserBlocked by user 1 to 6<br>";
    print_r($api->delUserBlocked(1,6));
    echo "<br/><br/><br/>";


    echo " showUserProfile by user 1<br>";
    print_r($api->showUserProfile(1));
    echo "<br/><br/><br/>";

    echo " addUserProfile by user 10<br>";
    print_r($api->addUserProfile(1,'about-10','bisexual'));
    echo "<br/><br/><br/>";

}catch (ApiException $ex){
    echo "<br>ERROR:<br>";
    echo ($ex->getMessage());
}