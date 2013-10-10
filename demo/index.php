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

    $iterator = $client->getIterator('GetChannels',array('page'=>1),array('limit'=>0,'page_size'=>30));
    ld($iterator);

    foreach ($iterator as $item) {
        ld("in foreach->".$item);
        echo $item['name'] . ' title ' . $item['title'] . PHP_EOL;
    }

    $ChannelModel = $client->getChannels();

    //ld($ChannelModel->get('resources'));
    //ld($iterator);
    /*
    -OK-
    $model = $client->addChannel(array('channel'=>array("name"=>"new".time(),"title"=>"new title","description"=>"description")));
    echo ($model->get('name'));
    */

}catch (BadResponseException $ex){
    ld ($ex->getRequest()->getParams());
    echo "<br>ERROR:<br>";
    echo ($ex->getResponse()->getBody(true));
}