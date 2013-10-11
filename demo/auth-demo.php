<?php
require __DIR__.'/../vendor/autoload.php';

use Ant\ChateaClient\Service\Client\ChateaOAuth2Client;
use Guzzle\Http\Exception\BadResponseException;

/*
 curl --data "client_id=1_1rqwnvdprfq8c0socws0ogco4c08goko0o80cocko0kkos84co&client_secret=4cvsrxs9s12ccs804wgk8k84ocoog4g4ooswwwkk8c4go0g4go&grant_type=password&username=alex&password=alex" http://api.chateagratis.local/app_dev.php/oauth/v2/token


 */
$client = ChateaOAuth2Client::factory(array('environment'=>'dev'));


$client_id ='1_1rqwnvdprfq8c0socws0ogco4c08goko0o80cocko0kkos84co';
$secret = '4cvsrxs9s12ccs804wgk8k84ocoog4g4ooswwwkk8c4go0g4go';
$user = 'alex';
$password = 'alex';

try{
    // withUserCredentials
    /* @var $command Guzzle\Service\Command\AbstractCommand */
    $command = $client->getCommand('withUserCredentials',
            array('client_id'=>$client_id,'client_secret'=>$secret,'username'=>$user,'password'=>$password)
    );
    $model = $command->execute();
    echo $model;
    echo "<br><br><br>";

    // withUserCredentials
    /* @var $command Guzzle\Service\Command\AbstractCommand */
    $command = $client->getCommand('withAuthorizationCode',
        array('client_id'=>$client_id,'client_secret'=>$secret,'redirect_uri'=>'http://www.chateagratis.net','code'=>'auth-token-test')
    );
    $model = $command->execute();
    echo $model;
    echo "<br><br><br>";


    //withClientCredentials
    /* @var $command Guzzle\Service\Command\AbstractCommand */
    $command = $client->getCommand('withClientCredentials',
        array('client_id'=>$client_id,'client_secret'=>$secret)
    );
    $model = $command->execute();
    echo $model;
    echo "<br><br><br>";

    //withRefreshToken
    /* @var $command Guzzle\Service\Command\AbstractCommand */
    $command = $client->getCommand('withRefreshToken',
        array('client_id'=>$client_id,'client_secret'=>$secret,'refresh_token'=>$model['refresh_token'])
    );
    $model = $command->execute();
    echo $model;
    echo "<br><br><br>";
}catch (BadResponseException $ex){
    echo "<br>ERROR<br>";
    echo $ex->getMessage();
    ldd($ex);
}
