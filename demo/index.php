<?php
require __DIR__.'/../vendor/autoload.php';

use Ant\ChateaClient\Service\Client\ChateaGratisClient;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Service\Description\ServiceDescription;


$client = ChateaGratisClient::factory(
    array('access_token' => 'OTk5MmI0MDk3NGFiMTM4NTBlMzk5OWZlYWM4N2Q5NTlhYTZlOTgwNzUzYmVjYzcxOTI5OGZmZTM2MTQ1MDI4OQ',
          'environment'=>'dev'
    )
);


//$client = ChateaGratisClient::factory(array('access_token' => 'OTk5MmI0MDk3NGFiMTM4NTBlMzk5OWZlYWM4N2Q5NTlhYTZlOTgwNzUzYmVjYzcxOTI5OGZmZTM2MTQ1MDI4OQ'));

//get Command
//$request = $client->get('api/channels');
//using with SSL self-signed certificate
//$request->getCurlOptions()->set(CURLOPT_SSL_VERIFYHOST, false);
//$request->getCurlOptions()->set(CURLOPT_SSL_VERIFYPEER, false);
//send response and return response

//echo $request->getUrl(true);

try{

    //$model = $client->getChannel(array('id'=>1));

    //$newChannel = $client->CreateChannel(array('name'=>'new Channel','title'=>'new channel','description'=>'new channel'));

    $command = $client->getCommand('AddChannel');
    ld($command);
    $command->setName("New Channel");
    $response = $client->execute($command);

    ldd($response);
    /*
    $iterator = $client->getIterator('GetChannels');
    ld($iterator);
    foreach ($iterator as $channel) {
        echo $channel['name'] . ' title  ' . $channel['title'] . PHP_EOL;
    }
    */
}catch (BadResponseException $ex){
    ld ($ex->getRequest()->getParams());
    echo "<br>ERROR:<br>";
    echo ($ex->getResponse()->getBody(true));
}


