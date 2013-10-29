<?php
require __DIR__.'/../vendor/autoload.php';

use Ant\ChateaClient\Service\Client\ChateaGratisClient;
use Ant\ChateaClient\Client\Api;
use Ant\ChateaClient\Client\ApiException;

$client = ChateaGratisClient::factory(
    array('access_token' => 'access-token-demo',
        'environment'=>'dev'
    )
);
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

$api = new Api($client);

try{

    /**
     * ShowFriends by user 1
     */
    echo "<h2>ShowFriends by user 1</h2>";
    print_array($api->showFriends(1,1,0));


    /**
     * Sends a friendship request between user 1 to user 3
     */
    echo "<h2>Sends a friendship request between user 1 to user 3</h2>";
    print_array($api->addFriends(1,3));


    /**
     * Show a friendship pending by user 1, only the first friendship
     */
     echo "<h2>Show a friendship pending by user 1, only the first friendship</h2>";
    print_array($api->showFriendshipsPending(1));


    /**
     * Show the friendship requests sent by user id, and it pending to be accepted
     */
     echo "<h2> ShowFriendshipsRequest by user 1 </h2>";
    print_array($api->showFriendshipsRequest(1));


    /**
     * Accept request new Friend
     */
    echo "<h2>User 1 accept request new Friend for user 7</h2>";
    print_r($api->addFriendshipRequest(1,7));

    /**
     * User 1 decline request new Friend for user 7
     */
    echo "<h2>User 1 decline request new Friend for user 6</h2>";
    print_r($api->delFriendshipRequest(1,6));

    /**
     * Delete a friends
     */
     echo "<h2>Delete Friendship between user 1 and user 3</h2>";
     print_r($api->delFriend(1,3));


    echo "<br/><br/><br/>";
}catch (ApiException $ex){
    echo "<br>ERROR:<br>";
    echo ($ex->getMessage());
}