<?php
require __DIR__.'/../vendor/autoload.php';

use Ant\ChateaClient\Service\Client\ChateaOAuth2Client;
use Ant\ChateaClient\Client\Authentication;
use Guzzle\Http\Exception\BadResponseException;

$client = ChateaOAuth2Client::factory(array('environment'=>'dev'));
//config app data
$client_id ='1_1rqwnvdprfq8c0socws0ogco4c08goko0o80cocko0kkos84co';
$secret = '4cvsrxs9s12ccs804wgk8k84ocoog4g4ooswwwkk8c4go0g4go';

$authentication = new Authentication($client,$client_id,$secret);

//users data
$username = 'alex';
$password = 'alex';
$authCode = 'auth-token-test';
$rediret = 'http://www.chateagratis.net';

try{
    // withUserCredentials
    echo $authentication->withUserCredentials($username,$password);
    echo "<br><br><br>";

    // withAuthorizationCode
    echo $authentication->withAuthorizationCode($authCode,$rediret);
    echo "<br><br><br>";


    //withClientCredentials
    echo $authentication->withClientCredentials();
    $model = $authentication->withClientCredentials();;
    echo "<br><br><br>";

    //withRefreshToken
    echo $authentication->withRefreshToken($model['refresh_token']);
    echo "<br><br><br>";
}catch (BadResponseException $ex){
    echo "<br>ERROR<br>";
    echo $ex->getMessage();
    ldd($ex);
}
