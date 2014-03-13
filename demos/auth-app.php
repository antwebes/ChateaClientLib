<?php
require __DIR__.'/../vendor/autoload.php';

use Ant\ChateaClient\Service\Client\ChateaGratisAppClient;
use Guzzle\Http\Exception\BadResponseException;


//config app data
$client_id ='2_random-demo';
$secret = 'secret-demo';

try{
    $cliente = ChateaGratisAppClient::factory(array('environment'=>'dev','client_id'=>$client_id,'secret'=>$secret));
}catch (Exception $ex){
    echo "<br>ERROR<br>";
    echo $ex->getMessage();
}
