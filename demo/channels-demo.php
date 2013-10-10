<?php
require __DIR__.'/../vendor/autoload.php';

use Ant\ChateaClient\Service\Client\ChateaGratisClient;
use Guzzle\Http\Exception\BadResponseException;
use Ant\ChateaClient\Client\Api;


$client = ChateaGratisClient::factory(
    array('access_token' => 'OTk5MmI0MDk3NGFiMTM4NTBlMzk5OWZlYWM4N2Q5NTlhYTZlOTgwNzUzYmVjYzcxOTI5OGZmZTM2MTQ1MDI4OQ',
          'environment'=>'dev'
    )
);
  $api = new Api($client);

try{
    //show channels
    echo $api->showChannels();
    echo "<br/><br/><br/>";

    //show channel by id
    echo $api->showChannel(1);
    echo "<br/><br/><br/>";

    //set new channel
    $key = time();
    $model  = $api->addChanel('channel_name_'.$key,'channel_title_'.$key,'channel_description_'.$key);
    echo $model;
    echo "<br/><br/><br/>";

    //update last channel
    echo $api->updateChannel($model['id'],'channel_name_update_'.$key,'channel_title_update_'.$key,'channel_description_update_'.$key);
    echo "<br/><br/><br/>";

    //get how all fans a channel
    echo $api->showChannelFans($model['id']);
    echo "<br/><br/><br/>";

    //List all the channels type
    echo $api->showChannelsTypes();
    echo "<br/><br/><br/>";

    //List all the channels type
    echo $api->showUserChannels(1);
    echo "<br/><br/><br/>";

    //Show all channels fan of an user
    echo $api->showUserChannelsFan(1);
    echo "<br/><br/><br/>";

    //Make user a channel fan
    echo $api->addUserChannelFan($model['id'],1);
    echo "<br/><br/><br/>";

    //Remove user as a channel fan
    echo $api->delUserChannelFan($model['id'],1);
    echo "<br/><br/><br/>";


    //delete last channel
    echo $api->delChannel($model['id']);


}catch (BadResponseException $ex){
    echo "<br>REQUEST:<br>";
    ld ($ex->getRequest());
    echo "<br>ERROR:<br>";
    echo ($ex->getResponse()->getBody(true));
}