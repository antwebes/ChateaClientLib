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
     * Show me user
     */
    echo "<h2>Get me user </h2>";
    print_array($api->me());

    /**
     * Update prfile of me user
     */
    echo "<h2>Update prfile of me user</h2>";
    print_array($api->updateMe('newName','neweamil@email.com','alex'));
    $api->updateMe('alex','alex@antweb.com','alex');

    /**
     * Change password
     */
    echo "<h2>Change password</h2>";
    print_array($api->changePassword('alex','new_password','new_password'));
    $api->changePassword('new_password','alex','alex');
    /**
     *  Del me user.
     */
    echo "<h2>Del me </h2>";
//    print_r($api->delMe());

    echo "<br/><br/><br/>";

}catch (ApiException $ex){
    echo "<br>ERROR:<br>";
    echo ($ex->getMessage());
}