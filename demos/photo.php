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
    $album = $api->addAlbum(1,'new Album','this is my secret album');
    print_array($album);

    /**
     * Add Photo
     */
    echo "<h2>Add Photo </h2>";
    print_array($api->addPhoto(1,'/home/ant3/photo_up.png', 'my_guzzle_photo'));

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

    /**
     * Show my vote of a photo of the user
     */
    echo "<h2>Show my votes for photos only 1rst vote </h2>";
    print_array($api->showUserPhotoVotes(1,1,0));

    /**
     * Add one voto to photo 6
     */
    echo "<h2>Add 10 points to photo 6 </h2>";
    print_array($api->addPhotoVote(1,6,10));

    /**
     * Delete one voto to photo 6
     */
    echo "<h2>Delete my vote to photo 6 </h2>";
    print_r($api->delPhotoVote(1,6));


    /**
     * Show user albums
     */
    echo "<h2>Show user albums</h2>";
    print_array($api->showAlbums(1));

    /**
     * Show album 1 user 1
     */
    echo "<h2>Show album 1 to user 1</h2>";
    print_array($api->showAlbum(1,1));


    /**
     * Delete album ? user 1
     */
    echo "<h2>Delete album ".$album['id']." to user 1</h2>";
    print_r($api->delAlbum(1,$album['id']));


    /**
     * Add photo 1 in album 1
     */
    echo "<h2>Insert a photo entity into album id</h2>";
    print_r($api->addAlbumPhoto(1,6,2));


    /**
     * Delete a photo of album
     */
    echo "<h2>Delete a photo of album</h2>";
    print_r($api->delPhotoAlbum(1,6));


    echo "<br><br>";
}catch (ApiException $ex){
    echo "<br>ERROR:<br>";
    echo ($ex->getMessage());
}