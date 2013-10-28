<?php
namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\Client\IApi;
use Ant\ChateaClient\Service\Client\Client;
use Exception;
use InvalidArgumentException;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\CurlException;
use Guzzle\Service\Command\CommandInterface;



/**
 * This class represent the chateagratis API's, this is single abstraction
 * for all API methods.
 *
 * This class cannot connect with server,
 * this responsibility it is class that implement ClientInterface for example ChateaGratisClient
 *
 * @author Xabier Fernández Rodríguez in Ant-Web S.L.
 *
 * @see Ant\ChateaClient\Service\Client\ChateaGratisClient;
 * @see Ant\ChateaClient\Client\IApi;
 * @see Ant\ChateaClient\Client\ApiException;
 */
class Api
{

    private $client;

    /**
     * Create a new API object
     *
     * @param Client $client The http client liable to request server commands
     *
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Execute the command in server
     *
     * @param CommandInterface $command
     *
     * @return array|string return a collection of data or a message.
     *
     * @throws ApiException This exception is thrown if server send one error
     */
    private function executeCommand(CommandInterface $command)
    {
        try {
            return $command->execute();
        }
        catch (ClientErrorResponseException $cerEx) {
            ldd($cerEx->getRequest()->getHeaderLines());
            throw new ApiException($cerEx->getResponse()->getBody(), $cerEx->getResponse()->getStatusCode(), $cerEx);
        }catch (BadResponseException $brEx) {
            throw new ApiException($brEx->getResponse()->getBody(), $brEx->getResponse()->getStatusCode(), $brEx);
        }catch (CurlException $curlEx) {
            throw new ApiException($curlEx->getMessage(), $curlEx->getCode(), $curlEx);
        }catch (Exception $ex){
            throw new ApiException($ex->getMessage(), $ex->getCode(), $ex);
        }
    }

    /******************************************************************************/
    /*				  				  API METHODS    	   					      */
    /******************************************************************************/

    /**
     * Revokes the access token and logout the user session in server.
     *
     * @return string a message ok and the HTTP status code 200
     *
     * @throws ApiException This exception is thrown if server send one error
     */
    public function logout()
    {

        $command = $this->client->getCommand('revoke');

        return $this->executeCommand($command);
    }

    /**
     * Register new user at server
     *
     * @param $username The name of user. This is unique for user
     *
     * @param $email The email for user. This is unique for user
     *
     * @param $new_password The user password
     *
     * @param $repeat_new_password Repeat the password
     *
     * @param $affiliate_host The name of your server, where you make send request.
     *          You don't use protocols (http:// or ftp ) or subdomains only use primary name
     *
     * @return array
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example
     *
     *      $your_api_instance->register('new user name','new_password','repeat_new_password','your hosts name');
     *
     *      <h3>Ouput<h3>
     *
     *      array{
     *          "id"=> 1234,
     *          "username"=> "new-username",
     *          "email"=> "newUserName@ant.com"
     *      }
     */
    public function register($username, $email, $new_password, $repeat_new_password, $affiliate_host)
    {

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
                "the new_password and repeat_new_password is not equals");
        }

        $command = $this->client->getCommand(
            "Register",
            array(
                'user_registration' =>
                array(
                    'email' => $email,
                    'username' => $username,
                    'plainPassword' => array(
                        'first' => $new_password,
                        'second' => $repeat_new_password
                    ),
                    'affiliate'=>$affiliate_host
                )
            )
        );

        return $this->executeCommand($command);
    }

    /**
     * Update a profile of an user
     *
     * @param $username your user name | the new username
     *
     * @param $email your email | the new user email
     *
     * @param $current_password  your password. This method can not change your password for this use @link #changePassword
     *
     * @return array with you data updated
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example
     *
     *      $your_api_instance->updateAccount('xabier','xabier@antweb.es','mySecretPassword');
     *      //ouput
     *      array(
     *          'id' => '1',
     *          'username' => 'xabier',
     *          'email' => 'xabier@antweb.es',
     *          );
     */
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

        $command = $this->client->getCommand(
            'UpdateAccount',
            array(
                'profile' => array(
                    'username' => $username,
                    'email' => $email,
                    'current_password' => $current_password
                )
            )
        );

        return $this->executeCommand($command);
    }

    /**
     * Show a profile of an user
     *
     * @return array with you profile data
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example
     *
     *      $your_api_instance->showAccount();
     *      //ouput
     *      array(
     *          'id' => '2',
     *          'username' => 'me user',
     *          'email' => 'me@antweb.es',
     *          );
     */
    public function showAccount()
    {
        $command = $this->client->getCommand('ShowAccount');
        return $this->executeCommand($command);
    }

    /**
     *
     * Change user password
     *
     * @param $current_password your actual password
     *
     * @param $new_password your new password
     *
     * @param $repeat_new_password repeat your new password
     *
     * @return string message ok message if your password have been changed
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example
     *
     *      $your_api_instance->changePassword('current_password','new_password','repeat_new_password');
     *      //ouput message
     *      Password changed sucessfully
     *
     */
    public function changePassword($current_password,$new_password,$repeat_new_password) {
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

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'ChangePassword',
            array(
                'change_password' => array(
                    'current_password' => $current_password,
                    'plainPassword' => array('first' => $new_password, 'second' => $repeat_new_password)
                )
            )
        );
        return $this->executeCommand($command);
    }


    /**
     *
     * Request reset the user password. The server send email with new password,
     * this this request is only once for day.
     *
     * @param $username_or_email The user email or username
     *
     * @return string message ok message if your new password have been sent
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example
     *
     *      $your_api_instance->forgotPassword('you_user_name');
     *      $your_api_instance->forgotPassword('you_email@antwebs.es');
     *
     *      <h3>Ouput<h3>
     *
     */
    public function forgotPassword($username_or_email)
    {

        if (!is_string($username_or_email) || 0 >= strlen($username_or_email)) {
            throw new InvalidArgumentException("username_or_email must be a non-empty string");
        }

        $command = $this->client->getCommand("RequestResetPassword", array('username' => $username_or_email));

        return $this->executeCommand($command);
    }


    /******************************************************************************/
    /*				  				  CHANNEL METHODS    	   					  */
    /******************************************************************************/

    /**
     * List all channels register in Server
     *
     * @param int $limit  number of items to retrieve at most
     *
     * @param int $offset The distance (displacement) from the start of a data
     *
     * @param array $filter Associative array with format filter_name =>value_name
     *
     * @return array|Collection Associative array with channels data
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example
     *
     *      Get first twenty channels what type is love
     *      $your_api_instance->showChannels(25,0, array('channelType'=>'love'));
     *
     *      <h3>Ouput</h3>
     *
     */
    public function showChannels($limit = 25, $offset = 0, array $filter = null)
    {

        if ($limit < 1) {
            throw new InvalidArgumentException(
                "Api::showChannels() limit must be a min 1 ");
        }
        if ($offset < 0) {
            throw new InvalidArgumentException(
                "Api::showChannels() $offset must be a positive number,  min 0 ");
        }
        $filterHash = '';
        if($filter !== null){

            foreach ($filter as $key => $value) {

                $filterHash .= $key . '=' . $value;

                if ($value != end($filter)) {
                    $filterHash .= ',';
                }
            }
            $filterHash;
        }
        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'GetChannels',
            array('limit' => $limit, 'offset' => $offset, 'filter' => $filterHash)
        );

        return $this->executeCommand($command);
    }

    /**
     * Create un new Channel
     *
     * @param string $name The name of channel this is unique
     *
     * @param string $title The title of channel
     *
     * @param string $description The long description of channel
     *
     * @param string $channel_type The type pof channel, This value have a subset available in  server,
     *  you can view the channels type with command @link #showChannelsTypes()
     *
     * @return array|Collection Associative array with new channel
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example
     *
     *          $your_api_instance->addChanel('new channel name','API - Channel Name',
     *                                        'This is channel about loves, friends and others', 'love');
     *
     *          <h3>Ouput</h3>
     *
     */
    public function addChanel($name, $title = '', $description = '', $channel_type = '')
    {
        if (!is_string($name) || 0 >= strlen($name)) {
            throw new InvalidArgumentException("addChanel name field needs to be a non-empty string");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'AddChannel',
            array('channel' =>
                array("name" => $name,
                      "title" => $title, "description" => $description,'channel_type'=>$channel_type))
        );

        return $this->executeCommand($command);
    }

    /**
     *
     * Update a channel
     *
     * @param $channel_id Channel to update by ID
     *
     * @param $name The new name of channel, if you would like  update this field.
     *
     * @param string $title The new title of channel, if you would like  update this field.
     *
     * @param string $description The new description, of channel if you would like  update this field.
     *
     * @param string $channel_type The type, of channel if you would like  update this field.
     *
     * @return array|Collection Associative array with updated channel
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     */
    public function updateChannel($channel_id, $name, $title = '', $description = '', $channel_type = '')
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
        $command = $this->client->getCommand(
            'UpdateChannel',
            array(
                "id" => $channel_id,
                'channel' => array("name" => $name, "title" => $title, "description" => $description, 'channel_type'=>$channel_type)
            )
        );

        return $this->executeCommand($command);

    }

    /**
     * Delete channel
     *
     * @param $channel_id Channel to update by ID
     *
     * @return string The message ok, if channel has been deleted
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     */
    public function delChannel($channel_id)
    {
        if (!is_numeric($channel_id) || 0 >= $channel_id) {
            throw new InvalidArgumentException(
                "ApiException::updateChannel channel_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('DeleteChannel', array("id" => $channel_id));

        return $this->executeCommand($command);
    }

    /**
     * Get channel
     *
     * @param $channel_id  Channel to retrieve by ID
     *
     * @return array|Collection Associative array with channel data
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     */
    public function showChannel($channel_id)
    {
        if (!is_numeric($channel_id) || 0 >= $channel_id) {
            throw new InvalidArgumentException(
                "ShowChannel channel_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetChannel', array('id' => $channel_id));

        return $this->executeCommand($command);
    }

    /**
     * Get fans (users) the channel
     *
     * @param $channel_id Channel to retrieve fans
     *
     * @return array|Collection Associative array with users that are fans a channel
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     */
    public function showChannelFans($channel_id)
    {
        if (!is_numeric($channel_id) || 0 >= $channel_id) {
            throw new InvalidArgumentException(
                "ApiException::showChannelFans channel_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetChannelFans', array("id" => $channel_id));

        return $this->executeCommand($command);
    }

    /**
     * Get Channles types
     *
     * @return array|Collection
     *
     * @throws ApiException This exception is thrown if server send one error
     */
    public function showChannelsTypes()
    {
        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetChannelsType');

        return $this->executeCommand($command);
    }

    /**
     *
     * Show channels created for the user
     *
     * @param $user_id User id  to retrieve channel
     *
     * @return array|Collection
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     */
    public function showUserChannels($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "ApiException::showChannelsByUser user_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetChannelsCreatedByUser', array("id" => $user_id));

        return $this->executeCommand($command);
    }

    /**
     * Show channels favorites one user
     *
     * @param $user_id  User id  to retrieve fans channels
     *
     * @return array|Collection
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     */
    public function showUserChannelsFan($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "ApiException::showChannelsFan user_id field should be positive integer");
        }
        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetFavoritesChannelsByUser', array('id' => $user_id));

        return $this->executeCommand($command);
    }

    /**
     *
     * @param $channel_id
     * @param $user_id
     * @return array|string
     * @throws \InvalidArgumentException
     */
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
        $command = $this->client->getCommand(
            'SetChannelFan',
            array('channel_id' => $channel_id, 'user_id' => $user_id)
        );

        return $this->executeCommand($command);

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
        $command = $this->client->getCommand(
            'DeleteChannelFan',
            array('channel_id' => $channel_id, 'user_id' => $user_id)
        );

        return $this->executeCommand($command);
    }

    /**********************************************************************************************************************/

    public function showFriends($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "ShowFriends user_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('ShowFriends', array('id' => $user_id));

        return $this->executeCommand($command);
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
        $command = $this->client->getCommand('AddFriends', array('id' => $user_id, 'user_id' => $friend_id));

        return $this->executeCommand($command);
    }

    public function showFriendshipsPending($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "showFriendshipsPending user_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('ShowFriendshipsPending', array('id' => $user_id));

        return $this->executeCommand($command);
    }

    public function showFriendshipsRequest($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "showFriendshipsRequest user_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('ShowFriendshipsRequest', array('id' => $user_id));

        return $this->executeCommand($command);
    }

    public function addFriendshipRequest($user_id, $user_accept_id)
    {

        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "addFriendshipRequest user_id field should be positive integer");
        }

        if (!is_numeric($user_accept_id) || 0 >= $user_accept_id) {
            throw new InvalidArgumentException(
                "addFriendshipRequest user_accept_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'AddFriendshipRequest',
            array('id' => $user_id, 'user_accept_id' => $user_accept_id)
        );

        return $this->executeCommand($command);
    }

    public function delFriendshipRequest($user_id, $user_decline_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "addFriendshipRequest user_id field should be positive integer");
        }

        if (!is_numeric($user_decline_id) || 0 >= $user_decline_id) {
            throw new InvalidArgumentException(
                "delFriendshipRequest user_decline_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'DeleteFriendshipRequest',
            array('id' => $user_id, 'user_accept_id' => $user_decline_id)
        );

        return $this->executeCommand($command);
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
        $command = $this->client->getCommand(
            'DeleteFriends',
            array('id' => $user_id, 'user_delete_id' => $user_delete_id)
        );

        return $this->executeCommand($command);
    }

    /**********************************************************************************************************************/

    /**
     * Get my user of session
     */
    public function whoami()
    {
        $command = $this->client->getCommand('Whoami');

        return $this->executeCommand($command);
    }

    /**
     * Delete my user
     */
    public function delMe()
    {
        $command = $this->client->getCommand('DelMe');

        return $this->executeCommand($command);
    }

    /**********************************************************************************************************************/

    public function addReportPhoto($photo_id, $reason)
    {
        if (!is_numeric($photo_id) || 0 >= $photo_id) {
            throw new InvalidArgumentException(
                "addReportPhoto photo_id field should be positive integer");
        }

        if (!is_string($reason) || 0 >= strlen($reason)) {
            throw new InvalidArgumentException("addReportPhoto reason field needs to be a non-empty string");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'AddReportPhoto',
            array('id' => $photo_id, 'reason' => $reason)
        );

        return $this->executeCommand($command);
    }


    public function delPhoto($photo_id)
    {
        if (!is_numeric($photo_id) || 0 >= $photo_id) {
            throw new InvalidArgumentException(
                "delPhoto photo_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'delPhoto',
            array('id' => $photo_id)
        );

        return $this->executeCommand($command);
    }

    public function addAlbum($user_id, $title, $description='')
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "addAlbum user_id field should be positive integer");
        }

        if (!is_string($title) || 0 >= strlen($title)) {
            throw new InvalidArgumentException("addAlbum reason title field needs to be a non-empty string");
        }


        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'AddAlbum',
            array('id' => $user_id,'ant_photo_album'=>array('title'=>$title,'description'=>$description)));

        return $this->executeCommand($command);
    }
    public function addPhoto($user_id, $imageTile, $imageFile)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "addPhoto user_id field should be positive integer");
        }

        if (!is_string($imageTile) || 0 >= strlen($imageTile)) {
            throw new InvalidArgumentException("addPhoto imageTile title field needs to be a non-empty string");
        }

        if (!is_string($imageFile) || 0 >= strlen($imageFile)) {
            throw new InvalidArgumentException("addPhoto imageFile title field needs to be a non-empty string");
        }
        if(!file_exists($imageFile)){
            throw new InvalidArgumentException("addPhoto '.$imageFile.' not exist or It do not read");
        }

        /* @var $command \Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand(
            'AddPhoto',
            array('id' => $user_id,'ant_photo'=>array('title'=>$imageTile,'files'=>array($imageFile))));

        return $this->executeCommand($command);
    }
    public function showPhoto($photo_id)
    {
        // TODO: Implement showPhoto() method.
    }

    public function showPhotoVotes($photo_id)
    {
        // TODO: Implement showPhotoVotes() method.$user_id
    }

    public function showPhotos($user_id)
    {
        // TODO: Implement showPhotos() method.
    }



    public function showUserVotes($user_id)
    {
        // TODO: Implement showUserVotes() method.
    }

    public function addPhotoVote($user_id, $photo_id, $core)
    {
        // TODO: Implement addPhotoVote() method.
    }

    public function delPhotoVote($photo_id)
    {
        // TODO: Implement delPhotoVote() method.
    }



    /**********************************************************************************************************************/

    public function showReports()
    {
        // TODO: Implement delPhoto() method.
    }

    public function showReport($report_id)
    {

    }

    public function addAsReviewedReport($report_id)
    {

    }

    /******************************************************************************/
    /*				  				  THREAD METHODS    	   					  */
    /******************************************************************************/

    public function addThread($recipient, $subject, $body)
    {

        if (!is_string($recipient) || 0 >= strlen($recipient)) {
            throw new InvalidArgumentException("addThread recipient field needs to be a non-empty string");
        }

        if (!is_string($subject) || 0 >= strlen($subject)) {
            throw new InvalidArgumentException("addThread subject field needs to be a non-empty string");
        }

        if (!is_string($body) || 0 >= strlen($body)) {
            throw new InvalidArgumentException("addThread body field needs to be a non-empty string");
        }

        /* @var $command \Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand(
            'AddThread',
            array('message'=>array('recipient'=>$recipient,'subject'=>$subject,'body'=>$body)));

        return $this->executeCommand($command);
    }

    public function showThreadsInbox($user_id)
    {

        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "showThreadsInbox user_id field should be positive integer");
        }

        /* @var $command \Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand(
            'GetThreadInbox',
            array('id'=>$user_id));

        return $this->executeCommand($command);
    }

    public function showThreadsSent($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "showThreadsSent user_id field should be positive integer");
        }

        /* @var $command \Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand(
            'GetThreadSent',
            array('id'=>$user_id));

        return $this->executeCommand($command);
    }

    public function addThreadMessage($user_id, $thread_id, $body)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "addThreadMessage user_id field should be positive integer");
        }
        /* @var $command \Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand(
            'GetThreadMessages',
            array('id'=>$user_id));

        return $this->executeCommand($command);
    }





    public function showThread($thread_id)
    {
        // TODO: Implement showThread() method.
    }


    public function delThread($thread_id)
    {
        // TODO: Implement delThread() method.
    }

    /******************************************************************************/
    /*				  				  USERS METHODS       	   					  */
    /******************************************************************************/

    /**
     * Get all the users
     */
    public function who($limit = 1, $offset = 0)
    {

        if ($limit < 1) {
            throw new InvalidArgumentException(
                "Api::who() limit must be a min 1 ");
        }
        if ($offset < 0) {
            throw new InvalidArgumentException(
                "Api::who offset must be a positive number,  min 0 ");
        }

        $command = $this->client->getCommand('Who', array('limit' => $limit,'offset'=>$offset));

        return $this->executeCommand($command);
    }

    public function showUser($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "showUser user_id field should be positive integer", 404);
        }

        $command = $this->client->getCommand('ShowUser', array('id' => $user_id));

        return $this->executeCommand($command);
    }

    public function showUsersBlocked($user_id, $limit = 1, $offset = 0)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "showUsersBlocked user_id field should be positive integer", 404);
        }

        if ($limit < 1) {
            throw new InvalidArgumentException(
                "Api::showChannels() limit must be a min 1 ");
        }
        if ($offset < 0) {
            throw new InvalidArgumentException(
                "Api::showChannels() $offset must be a positive number,  min 0 ");
        }

        $command = $this->client->getCommand('showUsersBlocked', array('id' => $user_id,'limit' => $limit,'offset'=>$offset));

        return $this->executeCommand($command);
    }

    public function addUserBlocked($user_id, $user_blocked_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "addUserBlocked user_id field should be positive integer", 404);
        }

        if (!is_numeric($user_blocked_id) || 0 >= $user_blocked_id) {
            throw new InvalidArgumentException(
                "addUserBlocked user_blocked_id field should be positive integer", 404);
        }

        $command = $this->client->getCommand('AddUserBlocked', array('id' => $user_id, 'user_id' => $user_blocked_id));

        return $this->executeCommand($command);
    }

    public function updateUserProfile($user_id, $about, $sexualOrientation)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "addUserProfile user_id field should be positive integer", 404);
        }
        if (!is_string($about) || 0 >= strlen($about)) {
            throw new InvalidArgumentException(
                "about must be a non-empty string", 404);
        }
        if (!is_string($sexualOrientation) || 0 >= strlen($sexualOrientation)) {
            throw new InvalidArgumentException(
                "sexualOrientation must be a non-empty string", 404);
        }

        $command = $this->client->getCommand(
            'UpdateUserProfile',
            array(
                'id' => $user_id,
                'social_profile' => array('about' => $about, 'sexualOrientation' => $sexualOrientation)
            )
        );

        return $this->executeCommand($command);
    }

    public function showUserProfile($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "ShowUserProfile user_id field should be positive integer", 404);
        }

        $command = $this->client->getCommand('ShowUserProfile', array('id' => $user_id));

        return $this->executeCommand($command);
    }

    public function addUserProfile($user_id, $about, $sexualOrientation)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "addUserProfile user_id field should be positive integer", 404);
        }
        if (!is_string($about) || 0 >= strlen($about)) {
            throw new InvalidArgumentException(
                "about must be a non-empty string", 404);
        }
        if (!is_string($sexualOrientation) || 0 >= strlen($sexualOrientation)) {
            throw new InvalidArgumentException(
                "sexualOrientation must be a non-empty string", 404);
        }

        $command = $this->client->getCommand(
            'AddUserProfile',
            array(
                'id' => $user_id,
                'social_profile' => array('about' => $about, 'sexualOrientation' => $sexualOrientation)
            )
        );

        return $this->executeCommand($command);
    }

    public function addUserReports($user_id, $reason)
    {
        throw new \Exception("TThis method is not supported yet");
    }

    public function delUserBlocked($user_id, $user_blocked_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "DeleteUserBlocked user_id field should be positive integer", 404);
        }

        if (!is_numeric($user_blocked_id) || 0 >= $user_blocked_id) {
            throw new InvalidArgumentException(
                "DeleteUserBlocked user_blocked_id field should be positive integer", 404);
        }

        $command = $this->client->getCommand(
            'DeleteUserBlocked',
            array('user_id' => $user_id, 'blocked_user_id' => $user_blocked_id)
        );

        return $this->executeCommand($command);
    }

    public function showUserVisitors($user_id, $maxResult)
    {
        throw new \Exception("TThis method is not supported yet");
    }

}