<?php
namespace Ant\ChateaClient\Client;
use InvalidArgumentException;
use Exception;
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
    public function showProfile()
    {
        throw new Exception("This method do not implemented yet");
    }

// 	 PUT|PATCH /api/profile/
    /**
     * Update a profile of an user
     *
     * @param string $username the new username
     *
     * @param string $email the new email
     *
     * @param String $current_password the password of user in session.
     *    if you will change password use: @link #changePassword
     *
     * @throws InvalidArgumentException
     *        The exception that is thrown when
     *        $username, $email, $current_password is not valid string

     */
    public function updateProfile($username, $email, $current_password)
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

        throw new InvalidArgumentException("This method do not implemented yet");
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

    public function showChannels($page = 1, $filter = '')
    {

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetChannels');

        return $command->execute();
    }

    public function showChannel($channel_id)
    {
        if (!is_numeric($channel_id) || 0 >= $channel_id) {
            throw new InvalidArgumentException(
                "ShowChannel channel_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetChannel', array('id' => $channel_id));

        return $command->execute();
    }

    public function addChanel($name, $title = '', $description = '')
    {
        if (!is_string($name) || 0 >= strlen($name)) {
            throw new InvalidArgumentException("addChanel name field needs to be a non-empty string");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('SetChannel', array('channel' => array("name" => $name, "title" => $title, "description" => $description)));
        return $command->execute();
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

        return $command->execute();
    }

    public function delChannel($channel_id)
    {
        if (!is_numeric($channel_id) || 0 >= $channel_id) {
            throw new InvalidArgumentException(
                "ApiException::updateChannel channel_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('DeleteChannel', array("id"=>$channel_id));

        return $command->execute();
    }

    public function showChannelFans($channel_id)
    {
        if (!is_numeric($channel_id) || 0 >= $channel_id) {
            throw new InvalidArgumentException(
                "ApiException::showChannelFans channel_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetChannelFans', array("id"=>$channel_id));

        return $command->execute();
    }

    public function showChannelsTypes()
    {
        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetChannelsType');
        return $command->execute();
    }

    public function showUserChannels($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "ApiException::showChannelsByUser user_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetChannelsCreatedByUser', array("id"=>$user_id));

        return $command->execute();
    }

    public function showUserChannelsFan($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "ApiException::showChannelsFan user_id field should be positive integer");
        }
        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetFavoritesChannelsByUser',array('id'=>$user_id));

        return $command->execute();
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

        return $command->execute();

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

        return $command->execute();
    }

    /**
     * returns the friends of user
     */
    public function showFriends($user_id)
    {
        // TODO: Implement showFriends() method.
    }

    /**
     * sends a friendship request to a given user
     *
     * @param number $user_id
     * @param number $friend_id
     */
    public function addFriends($user_id, $friend_id)
    {
        // TODO: Implement addFriends() method.
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
     * Deletes a friendship
     *
     * @param number $user_id
     * @param number $user_delete_id
     */
    public function delFriend($user_id, $user_delete_id)
    {
        // TODO: Implement delFriend() method.
    }

    /**
     * Get my user of session
     */
    public function whoami()
    {
        // TODO: Implement whoami() method.
    }

    /**
     * Delete my user
     */
    public function delMe()
    {
        // TODO: Implement delMe() method.
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
    public function who()
    {
        // TODO: Implement who() method.
    }

    /**
     * Get the user
     * @param number $user_id
     */
    public function showUser($user_id)
    {
        // TODO: Implement showUser() method.
    }

    /**
     * Get blocked users of the session user
     * @param number $user_id
     */
    public function showUsersBlocked($user_id)
    {
        // TODO: Implement showUsersBlocked() method.
    }

    /**
     * Blocks the given user for the session user
     *
     * @param number $user_id
     * @param number $user_blocked_id
     */
    public function addUserBlocked($user_id, $user_blocked_id)
    {
        // TODO: Implement addUserBlocked() method.
    }

    /**
     * Unblocks the given user for the session user
     *
     * @param number $user_id
     * @param number $user_blocked_id
     */
    public function delUserBlocked($user_id, $user_blocked_id)
    {
        // TODO: Implement delUserBlocked() method.
    }

    /**
     * Show a profile
     *
     * @param $user_id
     * @return mixed
     */
    public function showUserProfile($user_id)
    {
        // TODO: Implement showUserProfile() method.
    }
}
