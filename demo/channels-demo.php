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
    /**
     * Show all channels start in 10 an request 10 elemts
     */
    echo "<h3>Show all channels start in 10 an request 10 elemts</h3>";
    $channels =  $api->showChannels(10,10) ;
    echo "Total channels: ". $channels['total'] ."<br/>";
    echo "Limit: ". $channels['limit'] ."<br/>";
    echo "Offset: ". $channels['offset'] ."<br/>";
    echo "Link_self: ". $channels['_links']['self']['href'] ."<br/>";

    foreach ($channels['resources'] as $channel){
        echo "- ". $channel['id'] . "<-->". $channel['name'] ."<br/>";
    }
    echo "<br/><br/><br/>";

     // Show all channel with filter
    echo "<h3>Show all channels with filters </h3>";
    $channels = $api->showChannels(1,0,array('name'=>'channel 2','channelType'=>'love'));
    print_r ($channels);
    echo "<br/><br/><br/>";

    // Show all channel with filter
    echo "<h3>Show channel id 1 </h3>";
    print_r ( $api->showChannel(1));
    echo "<br/><br/><br/>";

    echo "<h3>New Channel </h3>";
    //set new channel
    $key = time();
    $model  = $api->addChanel('channel_name_'.$key,'channel_title_'.$key,'channel_description_'.$key,'love');
    print_r ( $model );
    echo "<br/><br/><br/>";

    echo "<h3>Update Channel </h3>";
    print_r ( $api->updateChannel($model['id'],'channel_name_update_'.$key,'channel_title_update_'.$key,'channel_description_update_'.$key,'adult') );
    echo "<br/><br/><br/>";


    echo "<h3>Show Channels Fans </h3>";
    print_r ( $api->showChannelFans($model['id']) );
    echo "<br/><br/><br/>";

    echo "<h3>List all the channels type</h3>";
    //List all the channels type
    $channelsTypes = $api->showChannelsTypes();
    foreach($channelsTypes['resources'] as $channelType){
        echo " - ".$channelType['name'] ."<br>";
    }
    echo "<br/><br/><br/>";

    echo "<h3>Show channles created by user 1</h3>";
    //Show channles created by user 1
    $meChannels = $api->showUserChannels(1);
    foreach ($meChannels['resources'] as $channel){
        echo "- ". $channel['id'] . "<-->". $channel['name'] ."<br/>";
    }
    echo "<br/><br/><br/>";

    echo "<h3>Show my favorite channels</h3>";
    //Show all channels fan of an user
    $favoriteChannels = $api->showUserChannelsFan(1);
    foreach ($favoriteChannels['resources'] as $channel){
        echo "- ". $channel['id'] . "<-->". $channel['name'] ."<br/>";
    }
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