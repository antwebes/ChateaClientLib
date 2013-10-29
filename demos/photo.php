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
     * List all Photo of an album
     */
    echo "<h2>List all Photo of an album</h2>";
    print_array($api->showPhotoAlbum(1));

    /**
     * Show Photo
     */
    echo "<h2>Show photo id 1</h2>";
    print_array($api->showPhoto(1));

    /**
     * Report one photo
     */
    echo "<h2>Report one photo</h2>";
    print_array($api->addReportPhoto(1,'this is not me photo'));

    /**
     * Add new photo album
     */
    echo "<h2>Add new photo album</h2>";
    //print_array($api->addAlbum(1,'new Album','this is my secret album'));

    /**
     * Add Photo
     */
    echo "<h2>Add Photo </h2>";
    print_array($api->addPhoto(1,'my_guzzle_photo','/home/ant3/photo_up.png'));

    /**
     * Delete one photo
     */
    echo "<h2>Delete one photo</h2>";
    //print_r($api->delPhoto(2));


    /**
     * Show user photos
     */
    echo "<h2>Show user photos</h2>";
    print_array($api->showUserPhotos(1));


    /**
     * Show my vote of a photo of the user
     */
    echo "<h2>Show my vote of a photo of the user</h2>";
    print_array($api->showPhotoVotes(1,5));

}catch (ApiException $ex){
    echo "<br>ERROR:<br>";
    echo ($ex->getMessage());
}