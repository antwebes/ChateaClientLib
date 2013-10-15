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
    //show channels
    print_r ( $api->showChannels());
    echo "<br/><br/><br/>";

    //show channel by id
    print_r ( $api->showChannel(1) );
    echo "<br/><br/><br/>";

    //set new channel
    $key = time();
    $model  = $api->addChanel('channel_name_'.$key,'channel_title_'.$key,'channel_description_'.$key);
    print_r ( $model );
    echo "<br/><br/><br/>";

    //update last channel
    print_r ( $api->updateChannel($model['id'],'channel_name_update_'.$key,'channel_title_update_'.$key,'channel_description_update_'.$key) );
    echo "<br/><br/><br/>";

    //get how all fans a channel
    print_r ( $api->showChannelFans($model['id']) );
    echo "<br/><br/><br/>";

    //List all the channels type
    print_r ( $api->showChannelsTypes() );
    echo "<br/><br/><br/>";

    //List all the channels type
    print_r ( $api->showUserChannels(1) );
    echo "<br/><br/><br/>";

    //Show all channels fan of an user
    print_r ( $api->showUserChannelsFan(1));
    echo "<br/><br/><br/>";

    //Make user a channel fan
    print_r ( $api->addUserChannelFan($model['id'],1));
    echo "<br/><br/><br/>";

    //Remove user as a channel fan
    print_r ( $api->delUserChannelFan($model['id'],1));
    echo "<br/><br/><br/>";


    //delete last channel
    print_r ( $api->delChannel($model['id']));


}catch (BadResponseException $ex){
    echo "<br>REQUEST:<br>";
    ld ($ex->getRequest());
    echo "<br>ERROR:<br>";
    echo ($ex->getResponse()->getBody(true));
}