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
     * add new Thread
     */
    echo '<h2>Add new Thread</h2>';
    $thread = $api->addThread(1,'alex2','Subject sample',"<b>this is my body in 1rst message </b>");
    print_array($thread);

    /**
     * Show inbox user 1
     */
    echo '<h2>Show inbox user 1</h2>';
    print_array($api->showThreadsInbox(1));


    /**
     * Show sent user 1
     */
    echo '<h2>Show sent user 1</h2>';
    print_array($api->showThreadsSent(1));

    /**
     * Show messages of user 1 and thread 1
     */
    echo '<h2>Show messages of user 1 and thread 1</h2>';
    print_array($api->showThreadMessages(1,1));


    /**
     * Add new message on thread
     */
    echo '<h2>Add new message on thread</h2>';
    print_array($api->addThreadMessage(1,1,'this is entity body'));


    /**
     * Delete a thread
     */
    echo '<h2>Delete a thread '.$thread['id'].'</h2>';
    print_r($api->delThread(1,$thread['id']));

    echo "<br><br><br>";

}catch (BadResponseException $ex){
    echo "<br>REQUEST:<br>";
    ld ($ex->getRequest());
    echo "<br>ERROR:<br>";
    echo ($ex->getResponse()->getBody(true));
}