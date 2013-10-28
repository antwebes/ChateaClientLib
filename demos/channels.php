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
function print_array(array $array )
{
    foreach($array as $key=>$value){
        $parte = $array[$key];
        if(is_array($parte)){
            if(!is_numeric($key)){
                echo '<li style="list-style-type:none"><strong>"'.$key.'"</strong> => ';
            }
            echo 'array(';
            echo '<ul style="list-style-type:none" >';
            print_array($parte);
            echo '</ul>';
            echo '),';
        }else{
            $value = is_numeric($value)? $value: '"'.$value.'"';
            echo '<li style="list-style-type:none" ><strong>"'.$key.'"</strong> => '.$value .', </li>';
        }
    }
}
try{
    /**
     * Show all channels start in 10 an request 3 elemts
     */
    echo '<h2>Show all channels start in 10 an request 10 elemts</h2>';
    $channels =  $api->showChannels(3,10) ;
    echo "Total channels: ". $channels['total'] ."<br/>";
    echo "Limit: ". $channels['limit'] ."<br/>";
    echo "Offset: ". $channels['offset'] ."<br/>";
    echo "Link_self: ". $channels['_links']['self']['href'] ."<br/>";
    echo "Resources".
    print_array($channels['resources']);


/**********************************************************************************************************************/

    /**
     * Show all channel with filter
     */
    echo '<h2>Show all channels with filters </h2>';
    $channels = $api->showChannels(1,0,array('name'=>'channel 2','channelType'=>'love'));
    print_array ($channels);


/**********************************************************************************************************************/

    /**
     * Show channel by id = 1
     */
    echo "<h2>Show channel by id = 1 </h2>";
    print_array ( $api->showChannel(1));

/**********************************************************************************************************************/

    /**
     * New Channel
     */
    echo "<h2>New Channel </h2>";
    $key = time();
    $model  = $api->addChanel('channel_name_'.$key,'channel_title_'.$key,'channel_description_'.$key,'love');
    print_array ( $model );


/**********************************************************************************************************************/
    /**
     * Update channel
     */
    echo "<h2>Update Channel </h2>";
    print_array ( $api->updateChannel($model['id'],'channel_name_update_'.$key,'channel_title_update_'.$key,'channel_description_update_'.$key,'adult') );


/**********************************************************************************************************************/

    /**
     * Show Channels Fans of channel 1. only the first three
     */
    echo "<h2>Show Channels Fans of channel 2. only the first three </h2>";
    print_array ( $api->showChannelFans(2,3,0) );


/**********************************************************************************************************************/
    /**
     *  List all the channels type
     */
    echo "<h2>List all the channels type</h2>";

    $channelsTypes = $api->showChannelsTypes(1,0);
    print_array($channelsTypes);

/**********************************************************************************************************************/

    /**
     * Show channels created by user 1, only the first three
     */
    echo "<h2>Show channels created by user 1, only the first three</h2>";

    $meChannels = $api->showUserChannels(1, 3, 0);
    print_array($meChannels);


/**********************************************************************************************************************/

    /**
     * Show my favorite channels, only the first three
     */
    echo "<h2>Show my favorite channels, only the first three</h2>";
    $favoriteChannels = $api->showUserChannelsFan(1, 1, 0);
    print_array($favoriteChannels);


/**********************************************************************************************************************/

    /**
     *  Make user 1 a channel fan
     */
    echo "<h2>Make Fan</h2>";
    echo ( $api->addUserChannelFan($model['id'],1));


/**********************************************************************************************************************/

    /**
     *  Remove user as a channel fan
     */
    echo "<h2>Remove user as a channel fan</h2>";
    print_r ( $api->delUserChannelFan($model['id'],1));

/**********************************************************************************************************************/

    /**
     * Delete last channel
     */
    echo "<h2>Delete last channel created (".$model['name'].")</h2>";
    print_r ( $api->delChannel($model['id']));

    echo "<br><br><br>";

}catch (BadResponseException $ex){
    echo "<br>REQUEST:<br>";
    ld ($ex->getRequest());
    echo "<br>ERROR:<br>";
    echo ($ex->getResponse()->getBody(true));
}