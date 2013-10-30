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
     * Show all users
     */
    echo "<h2>Show all users </h2>";
    print_array($api->showUsers());
    echo "<br/><br/><br/>";

    /**
     * Show user id 1
     */
    echo "<h2>Show user whit ID 1 </h2>";
    print_array($api->showUser(1));

    /**
     * Show user blocked by user 1
     */
    echo "<h2>Show user blocked by user 1 </h2>";
    print_array($api->showUsersBlocked(1));
    echo "<br/><br/><br/>";


    echo "<h2>addUsersBlocked by user 1 to 6</h2>";
    print_r($api->addUserBlocked(1,6));

    echo "<h2>delUserBlocked by user 1 to 6</h2>";
    print_r($api->delUserBlocked(1,6));


    echo "<h2>addUserProfile by user 1</h2>";
    //print_array($api->addUserProfile(1,'about-10','bisexual'));


    echo "<h2>showUserProfile by user 1</h2>";
    print_array($api->showUserProfile(1));


    echo "<h2>updateUserProfile by user 1</h2>";
    print_array($api->updateUserProfile(1,'about-103156','homosexual'));


    echo "<h2>addUserReports user 1</h2>";
    print_array($api->addUserReports(3,'this user is heterosexual'));

    echo "<h2>showUserVisitors user 1</h2>";
    print_array($api->showUserVisitors(1));

    echo "<br/><br/><br/>";

}catch (ApiException $ex){
    echo "<br>ERROR:<br>";
    echo ($ex->getMessage());
}