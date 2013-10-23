<?php
require __DIR__.'/../vendor/autoload.php';

use Ant\ChateaClient\Service\Client\ChateaGratisAppClient;
use Guzzle\Http\Exception\BadResponseException;


//config app data
$client_id ='1_client_demo';
$secret = 'secret-demo';

try{

 $cliente = ChateaGratisAppClient::factory(array('environment'=>'dev','client_id'=>$client_id,'secret'=>$secret));
 ldd($cliente);

}catch (Exception $ex){
    echo "<br>ERROR<br>";
    echo $ex->getMessage();
    ldd($ex);
}
