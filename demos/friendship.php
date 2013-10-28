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


    echo "<h2>ShowFriends by user 1</h2>";
    print_array($api->showFriends(1,1,0));



    echo " addFriends by user 1 friend 3 <br>";
    print_r($api->addFriends(1,3));
    echo "<br/><br/><br/>";
//    echo " delFriend by user 1 friend 3 <br>";
//    print_r($api->delFriend(1,3));
//    echo "<br/><br/><br/>";
//    echo " showFriendshipsPending by user 1 <br>";
//    print_r($api->showFriendshipsPending(1));
//    echo "<br/><br/><br/>";

//    echo " showFriendshipsRequest by user 1 <br>";
//    print_r($api->showFriendshipsRequest(1));
//    echo "<br/><br/><br/>";

}catch (ApiException $ex){
    echo "<br>ERROR:<br>";
    echo ($ex->getMessage());
}