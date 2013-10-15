<?php
namespace Ant\ChateaClient\Client;
use Guzzle\Http\Exception\CurlException;
use InvalidArgumentException;
use Exception;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Ant\ChateaClient\Client\IApi;
use Ant\ChateaClient\Service\Client\ClientInterface;
use Ant\ChateaClient\Service\Client\ChateaGratisClient;

/**
 * This class represent the one chat API, this is single abstraction
 * for all API methods.
 *
 * This class cannot connect with server,
 * this responsibility it is class that implement IHttpClient for example HttpClient
 *
 * @author Xabier Fernández Rodríguez in Ant-Web S.L.
 *
 * @see Ant\ChateaClient\Http\IHttpClient;
 * @see Ant\ChateaClient\Http\HttpClient;
 * @see Ant\ChateaClient\Client\IApi;
 * @see Ant\ChateaClient\Client\ApiException;
 */
class Api implements IApi
{

    private $client;

    /**
     * Create a ne API objet
     *
     * @param ChateaGratisClient $client the Httclient send request to api and response in json format
     *
     */
    public function __construct(ChateaGratisClient $client)
    {
        $this->client = $client;
    }


    public static function register(ClientInterface $client, $username, $email, $new_password, $repeat_new_password)
    {

        if ($client == null) {
            throw new InvalidArgumentException("httpClient is not null");
        }
        if (!is_string($username) || 0 >= strlen($username)) {
            throw new InvalidArgumentException("username must be a non-empty string");
        }
        if (!is_string($email) || 0 >= strlen($email)) {
            throw new InvalidArgumentException("email must be a non-empty string");
        }
        if (!is_string($new_password) || 0 >= strlen($new_password)) {
            throw new InvalidArgumentException("new_password must be a non-empty string");
        }

        if (!is_string($repeat_new_password) || 0 >= strlen($repeat_new_password)) {
            throw new InvalidArgumentException(
                "repeat_new_password must be a non-empty string");
        }

        if (strcmp($new_password, $repeat_new_password)) {
            throw new InvalidArgumentException(
                "the new_password and repeat_new_password isn't equals");
        }

        throw new Exception("This method do not implemented yet");
    }


    public static function requestResetpassword(ClientInterface $client, $username)
    {
        if ($client == null) {
            throw new InvalidArgumentException("httpclient is not null");
        }
        if (!is_string($username) || 0 >= strlen($username)) {
            throw new InvalidArgumentException("username must be a non-empty string");
        }

        throw new Exception("This method do not implemented yet");
    }

    /******************************************************************************/
    /*				  				  PROFILE METHODS    	   					  */
    /******************************************************************************/

    /**
     * Show a profile of an user
     */
    public function showAccount()
    {
        $command = $this->client->getCommand('ShowAccount');

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }


    public function updateAccount($username, $email, $current_password)
    {
        if (!is_string($username) || 0 >= strlen($username)) {
            throw new InvalidArgumentException(
                "ApiException::updateProfile username field needs to be a non-empty string");
        }
        if (!is_string($email) || 0 >= strlen($email)) {
            throw new InvalidArgumentException(
                "ApiException::updateProfile email field needs to be a non-empty string");
        }
        if (!is_string($current_password) || 0 >= strlen($current_password)) {
            throw new InvalidArgumentException(
                "ApiException::updateProfile current_password field needs to be a non-empty string");
        }

        $command = $this->client->getCommand('UpdateAccount',array('profile'=>array('username'=>$username,'email'=>$email,'current_password'=>$current_password)));

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }

    /**
     * Change user password
     *
     * @param string $current_password
     * @param string $new_password
     * @param String $repeat_new_password
     * @throws InvalidArgumentException
     *        The exception that is thrown when
     *        $current_password, $new_password, $new_password, $repeat_new_password is not valid string
     *        or $new_password not equals to $repeat_new_password
     *        or API error send for server
     */
    public function changePassword($current_password, $new_password,
                                   $repeat_new_password)
    {
        if (!is_string($current_password) || 0 >= strlen($current_password)) {
            throw new InvalidArgumentException(
                "ApiException::changePassword() current_password must be a non-empty string");
        }

        if (!is_string($new_password) || 0 >= strlen($new_password)) {
            throw new InvalidArgumentException(
                "ApiException::changePassword() new_password must be a non-empty string");
        }

        if (!is_string($repeat_new_password)
            || 0 >= strlen($repeat_new_password)
        ) {
            throw new InvalidArgumentException(
                "ApiException::changePassword() repeat_new_password must be a non-empty string");
        }

        if (strcmp($new_password, $repeat_new_password)) {
            throw new InvalidArgumentException(
                "ApiException::changePassword() the new_password and repeat_new_password isn't equals");
        }
        throw new InvalidArgumentException("This method do not implemented yet");
    }

    /******************************************************************************/
    /*				  				  CHANNEL METHODS    	   					  */
    /******************************************************************************/

    public function showChannels($page = 1, array $filter = null)
    {

        $filterHash = '';
        foreach ($filter as $key=>$value) {

            $filterHash .= $key .'='. $value;

            if($value != end($filter))
            {
                $filterHash .= ',';
            }
        }
        $filter = 'filter='.$filterHash;

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetChannels',array('page'=>$page,'filter'=>$filter));

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }

    public function showChannel($channel_id)
    {
        if (!is_numeric($channel_id) || 0 >= $channel_id) {
            throw new InvalidArgumentException(
                "ShowChannel channel_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetChannel', array('id' => $channel_id));

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }

    public function addChanel($name, $title = '', $description = '')
    {
        if (!is_string($name) || 0 >= strlen($name)) {
            throw new InvalidArgumentException("addChanel name field needs to be a non-empty string");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('AddChannel', array('channel' => array("name" => $name, "title" => $title, "description" => $description)));

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }

    public function updateChannel($channel_id, $name, $title = '', $description = '')
    {
        if (!is_numeric($channel_id) || 0 >= $channel_id) {
            throw new InvalidArgumentException(
                "ApiException::updateChannel channel_id field should be positive integer");
        }
        if (!is_string($name) || 0 >= strlen($name)) {
            throw new InvalidArgumentException(
                "ApiException::updateChannel name field needs to be a non-empty string");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('UpdateChannel', array("id"=>$channel_id,'channel'=>array("name" => $name, "title" => $title, "description" => $description)));

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }

    }

    public function delChannel($channel_id)
    {
        if (!is_numeric($channel_id) || 0 >= $channel_id) {
            throw new InvalidArgumentException(
                "ApiException::updateChannel channel_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('DeleteChannel', array("id"=>$channel_id));

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }

    public function showChannelFans($channel_id)
    {
        if (!is_numeric($channel_id) || 0 >= $channel_id) {
            throw new InvalidArgumentException(
                "ApiException::showChannelFans channel_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetChannelFans', array("id"=>$channel_id));

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }

    public function showChannelsTypes()
    {
        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetChannelsType');

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }

    public function showUserChannels($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "ApiException::showChannelsByUser user_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetChannelsCreatedByUser', array("id"=>$user_id));

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }

    public function showUserChannelsFan($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "ApiException::showChannelsFan user_id field should be positive integer");
        }
        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetFavoritesChannelsByUser',array('id'=>$user_id));

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }

    public function addUserChannelFan($channel_id, $user_id)
    {
        if (!is_numeric($channel_id) || 0 >= $channel_id) {
            throw new InvalidArgumentException(
                "ApiException::addChannelFan channel_id field should be positive integer");
        }
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "ApiException::addChannelFan user_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('SetChannelFan',array('channel_id'=>$channel_id,'user_id'=>$user_id));

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }

    }

    public function delUserChannelFan($channel_id, $user_id)
    {
        if (!is_numeric($channel_id) || 0 >= $channel_id) {
            throw new InvalidArgumentException(
                "ApiException::addChannelFan channel_id field should be positive integer");
        }
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "ApiException::addChannelFan user_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('DeleteChannelFan',array('channel_id'=>$channel_id,'user_id'=>$user_id));

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }

    /**
     * returns the friends of user
     */
    public function showFriends($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "ShowFriends user_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('ShowFriends',array('id'=>$user_id));

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }

    public function addFriends($user_id, $friend_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "addFriends user_id field should be positive integer");
        }

        if (!is_numeric($friend_id) || 0 >= $friend_id) {
            throw new InvalidArgumentException(
                "addFriends friend_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('AddFriends',array('id'=>$user_id,'user_id'=>$friend_id));

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }


    public function delFriend($user_id, $user_delete_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "delFriend user_id field should be positive integer");
        }

        if (!is_numeric($user_delete_id) || 0 >= $user_delete_id) {
            throw new InvalidArgumentException(
                "delFriend user_delete_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('DeleteFriends',array('id'=>$user_id,'user_delete_id'=>$user_delete_id));

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }
    /**
     * returns the friends that they are pending accept by an user
     */
    public function showFriendshipsPending($user_id)
    {
        // TODO: Implement showFriendshipsPending() method.
    }

    /**
     * returns the requests friendships that one user doesn't have accepted
     *
     * @param number $user_id
     */
    public function showFriendshipsRequest($user_id)
    {
        // TODO: Implement showFriendshipsRequest() method.
    }

    /**
     * accepts a friendship request
     *
     * @param number $user_id
     * @param number $user_accept_id
     */
    public function addFriendshipRequest($user_id, $user_accept_id)
    {
        // TODO: Implement addFriendshipRequest() method.
    }

    /**
     * Decline a friendship request
     *
     * @param number $user_id
     * @param number $user_decline_id
     */
    public function delFriendshipRequest($user_id, $user_decline_id)
    {
        // TODO: Implement delFriendshipRequest() method.
    }


    /**
     * Get my user of session
     */
    public function whoami()
    {
        $command = $this->client->getCommand('Whoami');

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }

    /**
     * Delete my user
     */
    public function delMe()
    {
        $command = $this->client->getCommand('DelMe');

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }

    /**
     * Show a photo
     *
     * @param number $photo_id
     */
    public function showPhoto($photo_id)
    {
        // TODO: Implement showPhoto() method.
    }

    /**
     * Show a vote of a photo
     *
     * @param number $photo_id
     */
    public function showPhotoVotes($photo_id)
    {
        // TODO: Implement showPhotoVotes() method.
    }

    /**
     * List all photos of an user
     *
     * @param number $user_id
     */
    public function showPhotos($user_id)
    {
        // TODO: Implement showPhotos() method.
    }

    /**
     * Create a photo
     *
     * @param number $user_id
     * @param string $imageTile
     * @param string $imageFile
     */
    public function addPhoto($user_id, $imageTile, $imageFile)
    {
        // TODO: Implement addPhoto() method.
    }

    /**
     * Show all votes of an user
     *
     * @param number $user_id
     */
    public function showUserVotes($user_id)
    {
        // TODO: Implement showUserVotes() method.
    }

    /**
     * Create a vote
     *
     * @param number $user_id
     * @param number $photo_id
     * @param number $core
     */
    public function addPhotoVote($user_id, $photo_id, $core)
    {
        // TODO: Implement addPhotoVote() method.
    }

    /**
     * Delete a vote
     * @param number $user_id
     * @param number $photo_id
     */
    public function delPhotoVote($user_id, $photo_id)
    {
        // TODO: Implement delPhotoVote() method.
    }

    /**
     * Delete a photo
     * @param number $user_id
     * @param number $photo_id
     */
    public function delPhoto($user_id, $photo_id)
    {
        // TODO: Implement delPhoto() method.
    }

    /**
     *
     * Creates a thread
     *
     * @param number $user_id
     * @param string $recipient
     * @param string $subject
     * @param string $body
     */
    public function addThread($user_id, $recipient, $subject, $body)
    {
        // TODO: Implement addThread() method.
    }

    /**
     * Lists threads with messages had been sent by one user
     *
     * @param number $user_id
     */
    public function showThreadsInbox($user_id)
    {
        // TODO: Implement showThreadsInbox() method.
    }

    /**
     * Messages list in inbox one user.
     *
     * @param number $user_id
     */
    public function showThreadsSent($user_id)
    {
        // TODO: Implement showThreadsSent() method.
    }

    /**
     * The messages list a given thread
     *
     * @param number $thread_id
     */
    public function showThread($thread_id)
    {
        // TODO: Implement showThread() method.
    }

    /**
     * Replies a message to a given thread
     *
     * @param number $user_id
     * @param number $thread_id
     * @param string $body
     */
    public function addThreadMessage($user_id, $thread_id, $body)
    {
        // TODO: Implement addThreadMessage() method.
    }

    /**
     * Deletes a thread
     *
     * @param number thread_id
     */
    public function delThread($thread_id)
    {
        // TODO: Implement delThread() method.
    }

    /**
     * Get all the users
     */
    public function who($page = 1)
    {
        $command = $this->client->getCommand('Who',array('page'=>$page));

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }

    public function showUser($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "showUser user_id field should be positive integer",404);
        }

        $command = $this->client->getCommand('ShowUser',array('id'=>$user_id));

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }


    public function showUsersBlocked($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "showUsersBlocked user_id field should be positive integer",404);
        }

        $command = $this->client->getCommand('showUsersBlocked',array('id'=>$user_id));

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }

    public function addUserBlocked($user_id, $user_blocked_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "addUserBlocked user_id field should be positive integer",404);
        }

        if (!is_numeric($user_blocked_id) || 0 >= $user_blocked_id) {
            throw new InvalidArgumentException(
                "addUserBlocked user_blocked_id field should be positive integer",404);
        }

        $command = $this->client->getCommand('AddUserBlocked',array('id'=>$user_id,'user_id'=>$user_blocked_id));

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }


    public function delUserBlocked($user_id, $user_blocked_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "DeleteUserBlocked user_id field should be positive integer",404);
        }

        if (!is_numeric($user_blocked_id) || 0 >= $user_blocked_id) {
            throw new InvalidArgumentException(
                "DeleteUserBlocked user_blocked_id field should be positive integer",404);
        }

        $command = $this->client->getCommand('DeleteUserBlocked',array('user_id'=>$user_id,'blocked_user_id'=>$user_blocked_id));

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }

    public function showUserProfile($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "ShowUserProfile user_id field should be positive integer",404);
        }

        $command = $this->client->getCommand('ShowUserProfile',array('id'=>$user_id));

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }

    /**
     * @param $user_id
     * @param $about
     * @param $sexualOrientation {"heterosexual", "homosexual", "bisexual"}
     * @return mixed
     * @throws ApiException
     * @throws \InvalidArgumentException
     */
    public function addUserProfile($user_id, $about, $sexualOrientation)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "addUserProfile user_id field should be positive integer",404);
        }
        if (!is_string($about) || 0 >= strlen($about)) {
            throw new InvalidArgumentException(
                "about must be a non-empty string", 404);
        }
        if (!is_string($sexualOrientation) || 0 >= strlen($sexualOrientation)) {
            throw new InvalidArgumentException(
                "sexualOrientation must be a non-empty string", 404);
        }

        $command = $this->client->getCommand('AddUserProfile',array('id'=>$user_id,'social_profile'=>array('about'=>$about,'sexualOrientation'=>$sexualOrientation)));

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }

    /**
     * @param $user_id
     * @param $about
     * @param $sexualOrientation {"heterosexual", "homosexual", "bisexual"}
     * @return mixed
     * @throws ApiException
     * @throws \InvalidArgumentException
     */
    public function updateUserProfile($user_id, $about, $sexualOrientation)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "addUserProfile user_id field should be positive integer",404);
        }
        if (!is_string($about) || 0 >= strlen($about)) {
            throw new InvalidArgumentException(
                "about must be a non-empty string", 404);
        }
        if (!is_string($sexualOrientation) || 0 >= strlen($sexualOrientation)) {
            throw new InvalidArgumentException(
                "sexualOrientation must be a non-empty string", 404);
        }

        $command = $this->client->getCommand('UpdateUserProfile',array('id'=>$user_id,'social_profile'=>array('about'=>$about,'sexualOrientation'=>$sexualOrientation)));

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(ClientErrorResponseException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }catch(CurlException $ex){
            $code = $ex->getResponse()->getStatusCode();
            throw new ApiException($ex->getResponse()->getBody(), $code, $ex);
        }
    }
}
