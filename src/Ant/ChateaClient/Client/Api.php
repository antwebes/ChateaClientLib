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
 * this responsibility it is class that implement ClientInterface for example ChateaGratisClient or ChateaGratisAppClient
 *
 * @author Xabier Fernández Rodríguez in Ant-Web S.L.
 *
 * @see Ant\ChateaClient\Service\Client\Client;
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
     * @return array|string return a collection of data or a message. | Message with error in json format
     *
     * @throws ApiException This exception is thrown if server send one error
     */
    private function executeCommand(CommandInterface $command)
    {
        try {
            return $command->execute();
        }
        catch (ClientErrorResponseException $cerEx) {
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
        $command = $this->client->getCommand('RevokeToken');

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
     * @return array|string Associative array with you profile | Message with error in json format
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
     * @return array|string Associative array with channels data | Message with error in json format
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
     *      <h3>Sample Ouput</h3>
     *  array(
     *      "id"=>11,
     *      "name" => "channel 11",
     *      "slug" => "channel-11",
     *      "owner"=>array(
     *          id: 1
     *          username: alex
     *       ),
     *      "channel_type" => array(
     *          "name" => "default"
     *       ),
     *      "_links" => array(
     *          "self" => array(
     *              href => "http://api.chateagratis.local/app_dev.php/api/channels/11"
     *          ),
     *          "fans" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/channels/11/fans"
     *          ),
     *          "owner" => array(
     *              "href => http://api.chateagratis.local/app_dev.php/api/users/1"
     *           )
     *      );
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
        /** @var $command \Guzzle\Service\Command\AbstractCommand */
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
     * @return array|string Associative array with new channel | Message with error in json format
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
     *      <h3>Sample Ouput</h3>
     *
     * array(
     *      "id" =>96
     *      "name" =>"new channel name",
     *      "slug" =>"new-channel-name",
     *      "title" =>"API - Channel Name",
     *      "description" =>"This is channel about loves, friends and others",
     *      "owner" => array(
     *              "id" =>1
     *              "username" =>"alan"
     *      ),
     *      "channel_type" => array(
     *          "name" =>"love"
     *      ),
     *      "_links" => array(
     *          "self" => array(
     *              "href" =>"https://api.chateagratis.local/api/channels/96"
     *          ),
     *          "fans" => array(
     *              "href" =>"https://api.chateagratis.local/api/channels/96/fans"
     *          ),
     *          "owner" => array(
     *              "href" =>"https://api.chateagratis.local/api/users/1"
     *          )
     *      )
     *  );
     *
     */
    public function addChanel($name, $title = '', $description = '', $channel_type = '')
    {
        if (!is_string($name) || 0 >= strlen($name)) {
            throw new InvalidArgumentException("addChanel name field needs to be a non-empty string");
        }

        /** @var $command \Guzzle\Service\Command\AbstractCommand */
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
     * @return array|string Associative array with updated channel | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example
     *
     *          $your_api_instance->updateChannel(96, 'update channel name','API - Channel title',
     *                                        'This is channel about loves, friends and others', 'love');
     *
     *      <h3>Sample Ouput</h3>
     *
     * array(
     *      "id" =>96
     *      "name" =>"update channel name",
     *      "slug" =>"update-channel-name",
     *      "title" =>"API - Channel title",
     *      "description" =>"This is channel about loves, friends and others",
     *      "owner" => array(
     *              "id" =>1
     *              "username" =>"alex"
     *      ),
     *      "channel_type" => array(
     *          "name" =>"love"
     *      ),
     *      "_links" => array(
     *          "self" => array(
     *              "href" =>"https://api.chateagratis.local/api/channels/96"
     *          ),
     *          "fans" => array(
     *              "href" =>"https://api.chateagratis.local/api/channels/96/fans"
     *          ),
     *          "owner" => array(
     *              "href" =>"https://api.chateagratis.local/api/users/1"
     *          )
     *      )
     *  );
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
     * @example
     *
     *          $your_api_instance->delChannel(96);
     *
     *      <h3>Ouput this message: </h3>
     *
     *      Channel deleted
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
     * Get channel. Show data channel by id
     *
     * @param $channel_id  Channel to retrieve by ID
     *
     * @return array|string Associative array with channel data | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example
     *
     *          $your_api_instance->showChannel(96);
     *
     *      <h3>Sample Ouput</h3>
     *
     * array(
     *      "id" =>96
     *      "name" =>"update channel name",
     *      "slug" =>"update-channel-name",
     *      "title" =>"API - Channel title",
     *      "description" =>"This is channel about loves, friends and others",
     *      "owner" => array(
     *              "id" =>1
     *              "username" =>"alex"
     *      ),
     *      "channel_type" => array(
     *          "name" =>"love"
     *      ),
     *      "_links" => array(
     *          "self" => array(
     *              "href" =>"https://api.chateagratis.local/api/channels/96"
     *          ),
     *          "fans" => array(
     *              "href" =>"https://api.chateagratis.local/api/channels/96/fans"
     *          ),
     *          "owner" => array(
     *              "href" =>"https://api.chateagratis.local/api/users/1"
     *          )
     *      )
     *  );
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
     * @param int $limit  number of items to retrieve at most
     *
     * @param int $offset The distance (displacement) from the start of a data
     *
     * @return array|string Associative array with users that are fans a channel | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     *
     * @example Show Channels Fans of channel 2, only the first
     *
     *
     *          $your_api_instance->showChannelFans(3,1,0);
     *
     *      <h3>Sample Ouput</h3>
     *
     * array(
     *      "total" => 2,
     *      "limit" => 2,
     *      "offset" => 0,
     *      "_links" => array(
     *          "self" => array(
     *          "href" => "http://api.chateagratis.local/app_dev.php/api/channels/2/fans"
     *          )
     *      ),
     *      "resources" => array(
     *           array(  "id" => 1,
     *                   "username" => "alex",
     *                   "email" => "alex@chateagratis.net",
     *                   "_links" => array(
     *                          "self" => array(
     *                              "href" => "http://api.chateagratis.local/app_dev.php/api/users/1"
     *                          ),
     *                          "channels" => array(
     *                              "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/channels"
     *                          ),
     *                          "channels_fan" => array(
     *                               "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/channelsFan"
     *                          ),
     *                          "blocked_users" => array(
     *                              "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/blocked"
     *                          )
     *                   )
     *           )
     *      )
     * );
     */
    public function showChannelFans($channel_id, $limit = 1, $offset = 0)
    {
        if (!is_numeric($channel_id) || 0 >= $channel_id) {
            throw new InvalidArgumentException(
                "ApiException::showChannelFans channel_id field should be positive integer");
        }

        if ($limit < 1) {
            throw new InvalidArgumentException(
                "Api::showChannelFans() limit must be a min 1 ");
        }
        if ($offset < 0) {
            throw new InvalidArgumentException(
                "Api::showChannelFans() $offset must be a positive number,  min 0 ");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetChannelFans', array("id" => $channel_id,'limit'=>$limit,'offset'=>$offset));

        return $this->executeCommand($command);
    }

    /**
     * Get Channles types
     *
     * @param int $limit  number of items to retrieve at most
     *
     * @param int $offset The distance (displacement) from the start of a data
     *
     * @return array|string  Associative array with channels types use one channel | Message with error in json format
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @example Show Channels types, only the first
     *
     *
     *          $your_api_instance->showChannelsTypes(1,0);
     *
     *      <h3>Sample Ouput</h3>
     * array(
     *      "total" => 6,
     *      "limit" => 1,
     *      "offset" => 0,
     *      "_links" => array(
     *              "self" => array(
     *                  "href" => "http://api.chateagratis.local/app_dev.php/api/channelstype"
     *              ),
     *      ),
     *      "resources" => array(
     *              array(
     *                  "name" => "adult",
     *                   "_links" => array(
     *                          "channelsType" => array(
     *                                  "href" => "http://api.chateagratis.local/app_dev.php/api/channels?filter%3DchannelType=adult",
     *                          )
     *                   )
     *              )
     *
     *      )
     * );
     *
     */
    public function showChannelsTypes($limit = 1, $offset = 0)
    {

        if ($limit < 1) {
            throw new InvalidArgumentException(
                "Api::showChannelsTypes() limit must be a min 1 ");
        }
        if ($offset < 0) {
            throw new InvalidArgumentException(
                "Api::showChannelsTypes() $offset must be a positive number,  min 0 ");
        }

        /** @var $command \Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand('GetChannelsType',array('limit'=>$limit,'offset'=>$offset));

        return $this->executeCommand($command);
    }

    /**
     *
     * Show channels created for the user
     *
     * @param $user_id User id  to retrieve channel
     *
     * @param int $limit  number of items to retrieve at most
     *
     * @param int $offset The distance (displacement) from the start of a data
     *
     * @return array|string Associative array with channels created one user | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Show Channels created by user id = 1, only the first channel
     *
     *
     *          $your_api_instance->showUserChannels(1,1,0);
     *
     *  array(
     *      "total" => 32,
     *      "limit" => 32,
     *      "offset" => 0,
     *      "_links" => array(
     *          "self" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/channels",
     *          ),
     *      ),
     *      "resources" => array(
     *          array(
     *              "id" => 1,
     *              "name" => "channel 1",
     *              "slug" => "channel-1",
     *              "owner" => array(
     *                  "id" => 1,
     *                  "username" => "alex",
     *              ),
     *              "channel_type" => array(
     *                  "name" => "adult",
     *              ),
     *              "_links" => array(
     *                  "self" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/channels/1",
     *                  ),
     *                  "fans" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/channels/1/fans",
     *                  ),
     *                  "owner" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/1",
     *                  ),
     *              )
     *          )
     *      )
     *  );
     */
    public function showUserChannels($user_id, $limit= 1, $offset = 0)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "ApiException::showUserChannels user_id field should be positive integer");
        }

        if ($limit < 1) {
            throw new InvalidArgumentException(
                "Api::showUserChannels() limit must be a min 1 ");
        }
        if ($offset < 0) {
            throw new InvalidArgumentException(
                "Api::showUserChannels() $offset must be a positive number,  min 0 ");
        }

        /** @var $command \Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand('GetChannelsCreatedByUser', array("id" => $user_id,'limit'=>$limit,'offset'=>$offset));

        return $this->executeCommand($command);
    }

    /**
     * Show channels favorites one user
     *
     * @param $user_id  User id  to retrieve fans channels
     *
     * @param int $limit  number of items to retrieve at most
     *
     * @param int $offset The distance (displacement) from the start of a data
     *
     * @return array|string Associative array with channel's is fan one user | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Show Channels favorites for user id = 1, only the first channel
     *
     *
     *          $your_api_instance->showUserChannelsFan(1,1,0);
     *
     *  array(
     *      "total" => 2,
     *      "limit" => 1,
     *      "offset" => 0,
     *      "_links" => array(
     *          "self" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/channelsFan"
     *          ),
     *      ),
     *      "resources" => array(
     *          array(
     *              "id" => 2,
     *              "name" => "channel 2",
     *              "slug" => "channel-2",
     *              "_links" => array(
     *                  "self" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/channels/2"
     *                  ),
     *                  "fans" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/channels/2/fans",
     *                  ),
     *                  "owner" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/2"
     *                  )
     *              )
     *          )
     *      )
     *  );
     */
    public function showUserChannelsFan($user_id, $limit= 1, $offset = 0)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "ApiException::showUserChannelsFan user_id field should be positive integer");
        }

        if ($limit < 1) {
            throw new InvalidArgumentException(
                "Api::showUserChannelsFan() limit must be a min 1 ");
        }
        if ($offset < 0) {
            throw new InvalidArgumentException(
                "Api::showUserChannelsFan() $offset must be a positive number,  min 0 ");
        }

        /** @var $command \Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand('GetFavoritesChannelsByUser', array('id' => $user_id,'limit'=>$limit,'offset'=>$offset));

        return $this->executeCommand($command);
    }

    /**
     * Make channel's  fan one user
     *
     * @param $channel_id Channel id to receive a fan
     *
     * @param $user_id The user id will be fan
     *
     * @return string Massage ok if user is new fan
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example add the user 1 fan of channel 1
     *
     *          $your_api_instance->addUserChannelFan(1,1);
     *
     *      <h3>The ouput message:</h3>
     *          The user is a fan of the channel successfully
     */
    public function addUserChannelFan($channel_id, $user_id)
    {
        if (!is_numeric($channel_id) || 0 >= $channel_id) {
            throw new InvalidArgumentException(
                "ApiException::addUserChannelFan channel_id field should be positive integer");
        }
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "ApiException::addUserChannelFan user_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'SetChannelFan',
            array('channel_id' => $channel_id, 'user_id' => $user_id)
        );

        return $this->executeCommand($command);

    }
    /**
     * Remove channel's  fan one user
     *
     * @param $channel_id Channel id to receive a fan
     *
     * @param $user_id The user id will be fan
     *
     * @return string Massage ok if user is not fan
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example remove the user 1 fan of channel 1
     *
     *          $your_api_instance->delUserChannelFan(1,1);
     *
     *      <h3>The ouput message:</h3>
     *          The user has been removed as fan of the channel successfully
     */
    public function delUserChannelFan($channel_id, $user_id)
    {
        if (!is_numeric($channel_id) || 0 >= $channel_id) {
            throw new InvalidArgumentException(
                "ApiException::delUserChannelFan channel_id field should be positive integer");
        }
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "ApiException::delUserChannelFan user_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'DeleteChannelFan',
            array('channel_id' => $channel_id, 'user_id' => $user_id)
        );

        return $this->executeCommand($command);
    }



    /******************************************************************************/
    /*				  				  CHANNEL FRIENSSHIP  	   					  */
    /******************************************************************************/

    /**
     * Get me friends
     *
     * @param $user_id The user id retrieve friends
     *
     * @param int $limit  number of items to retrieve at most
     *
     * @param int $offset The distance (displacement) from the start of a data
     *
     * @return array|string Associative array with users's are friends one user | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Show User friends for user id = 1, only the first friend
     *
     *
     *          $your_api_instance->showFriends(1,1,0);
     *
     *  array(
     *      "total" => 4,
     *      "limit" => 1,
     *      "offset" => 0,
     *      "_links" => array(
     *          "self" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/friends",
     *          ),
     *      ),
     *      "resources" => array(
     *          array(
     *              "id" => 3,
     *              "username" => "alex3",
     *              "email" => "alex3@chateagratis.net",
     *              "_links" => array(
     *                  "self" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/3",
     *                  ),
     *                  "channels" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/3/channels",
     *                  ),
     *                  "channels_fan" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/3/channelsFan",
     *                  ),
     *                  "blocked_users" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/3/blocked",
     *                  ),
     *              ),
     *          ),
     *      )
     * );
     */
    public function showFriends($user_id, $limit = 1, $offset = 0)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "Api::showFriends() user_id field should be positive integer");
        }

        if ($limit < 1) {
            throw new InvalidArgumentException(
                "Api::showFriends() limit must be a min 1 ");
        }
        if ($offset < 0) {
            throw new InvalidArgumentException(
                "Api::showFriends() $offset must be a positive number,  min 0 ");
        }

        /** @var $command \Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand('ShowFriends', array('id' => $user_id,'limit'=>$limit,'offset'=>$offset));

        return $this->executeCommand($command);
    }

    /**
     * Sends a friendship request between two users
     *
     * @param $user_id Your user id
     *
     * @param $friend_id The user id that retrieve the request
     *
     * @return string Massage endorsement , if the request have been sent
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Sends a friendship request between user 1 to user 3
     *
     *
     *          $your_api_instance->addFriends(1,3);
     *
     *
     */

    public function addFriends($user_id, $friend_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "Api::addFriends user_id field should be positive integer");
        }

        if (!is_numeric($friend_id) || 0 >= $friend_id) {
            throw new InvalidArgumentException(
                "Api::addFriends friend_id field should be positive integer");
        }

        /**  @var $command \Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand('AddFriends', array('id' => $user_id, 'user_id' => $friend_id));

        return $this->executeCommand($command);
    }

    /**
     * Show list user, that one user will pending have your friendship
     *
     * @param $user_id The user id that you retrieve data
     *
     * @param int $limit  number of items to retrieve at most
     *
     * @param int $offset The distance (displacement) from the start of a data
     *
     * @return array|string Associative array with users's are friends one user | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Show a friendship pending by user 1, only the first friendship
     *
     *          $your_api_instance->showFriendshipsPending(1);
     *
     *  array(
     *      "total" => 4,
     *      "limit" => 1,
     *      "offset" => 0,
     *      "_links" => array(
     *          "self" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/friends/pending",
     *          ),
     *      ),
     *      "resources" => array(
     *          array(
     *              "id" => 3,
     *              "username" => "alex3",
     *              "username_canonical" => "alex3",
     *              "email" => "alex3@chateagratis.net",
     *              "_links" => array(
     *                  "self" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/3",
     *                  ),
     *                  "channels" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/3/channels",
     *                  ),
     *                  "channels_fan" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/3/channelsFan",
     *                  ),
     *                  "blocked_users" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/3/blocked",
     *                  ),
     *              )
     *          )
     *      )
     *  );
     */
    public function showFriendshipsPending($user_id, $limit = 1, $offset = 0)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "Api::showFriendshipsPending user_id field should be positive integer");
        }

        if ($limit < 1) {
            throw new InvalidArgumentException(
                "Api::showFriendshipsPending() limit must be a min 1 ");
        }
        if ($offset < 0) {
            throw new InvalidArgumentException(
                "Api::showFriendshipsPending() $offset must be a positive number,  min 0 ");
        }

        /** @var $command \Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand('ShowFriendshipsPending', array('id' => $user_id,'limit'=>$limit,'offset'=>$offset));

        return $this->executeCommand($command);
    }

    /**
     * Show the friendship requests sent by user id, and it pending to be accepted
     *
     * @param $user_id The user id that you retrieve data
     *
     * @param int $limit  number of items to retrieve at most
     *
     * @param int $offset The distance (displacement) from the start of a data
     *
     * @return array|string Associative array with users's are friends one user | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Show the friendship requests sent by user 1, only the first request friendship
     *
     *          $your_api_instance->showFriendshipsRequest(1,1,0);
     *
     *  array(
     *      "total" => 4,
     *      "limit" => 1,
     *      "offset" => 0,
     *      "_links" => array(
     *          "self" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/friends/pending",
     *          ),
     *      ),
     *      "resources" => array(
     *          array(
     *              "id" => 3,
     *              "username" => "alex3",
     *              "username_canonical" => "alex3",
     *              "email" => "alex3@chateagratis.net",
     *              "_links" => array(
     *                  "self" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/3",
     *                  ),
     *                  "channels" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/3/channels",
     *                  ),
     *                  "channels_fan" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/3/channelsFan",
     *                  ),
     *                  "blocked_users" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/3/blocked",
     *                  ),
     *              )
     *          )
     *      )
     *  );
     */
    public function showFriendshipsRequest($user_id, $limit = 1, $offset = 0)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "Api::showFriendshipsRequest user_id field should be positive integer");
        }

        if ($limit < 1) {
            throw new InvalidArgumentException(
                "Api::showFriendshipsRequest() limit must be a min 1 ");
        }
        if ($offset < 0) {
            throw new InvalidArgumentException(
                "Api::showFriendshipsRequest() $offset must be a positive number,  min 0 ");
        }

        /** @var $command \Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand('ShowFriendshipsRequest', array('id' => $user_id,'limit'=>$limit,'offset'=>$offset));

        return $this->executeCommand($command);
    }


    /**
     * Accept request new Friend
     *
     * @param $user_id The user id that you retrieve request new Friend
     *
     * @param $user_accept_id The user id pending friendship
     *
     * @return string Message accepted | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example User 1 accept like new Friend to user 3
     *
     *          $your_api_instance->showFriendshipsRequest(1,3);
     *
     *       Friendship accepted
     *
     */
    public function addFriendshipRequest($user_id, $user_accept_id)
    {

        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "Api::addFriendshipRequest user_id field should be positive integer");
        }

        if (!is_numeric($user_accept_id) || 0 >= $user_accept_id) {
            throw new InvalidArgumentException(
                "Api::addFriendshipRequest user_accept_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'AddFriendshipRequest',
            array('id' => $user_id, 'user_accept_id' => $user_accept_id)
        );

        return $this->executeCommand($command);
    }

    /**
     * @param $user_id The user id that you decliene new Friend
     *
     * @param $user_decline_id The user id pending friendship
     *
     * @return string Message declined | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example User 1 declined like new Friend to user 3
     *
     *          $your_api_instance->delFriendshipRequest(1,3);
     *
     *      Friendship declined
     *
     */
    public function delFriendshipRequest($user_id, $user_decline_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "Api::addFriendshipRequest user_id field should be positive integer");
        }

        if (!is_numeric($user_decline_id) || 0 >= $user_decline_id) {
            throw new InvalidArgumentException(
                "Api::delFriendshipRequest user_decline_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'DeleteFriendshipRequest',
            array('id' => $user_id, 'user_decline_id' => $user_decline_id)
        );

        return $this->executeCommand($command);
    }

    //
    /**
     * Delete friends between two users
     *
     * @param $user_id The user id that you delere friend
     *
     * @param $user_delete_id The user id, you want deleted.
     *
     * @return string Message delete | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example User 1 delete friendship to user 3
     *
     *          $your_api_instance->delFriend(1,3);
     *
     *      Friendship deleted
     *
     */
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

    /******************************************************************************/
    /*				  				  CHANNEL ME            					  */
    /******************************************************************************/

    /**
     * Get my user
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example
     *
     *      $your_api_instance->me();
     *
     *      array(
     *          'id' => '1',
     *          'username' => 'xabier',
     *          'email' => 'xabier@antweb.es',
     *          "_links" => array(
     *              "self" => array(
     *                "href" => "http://api.chateagratis.local/app_dev.php/api/users/1",
     *              ),
     *              "channels" => array(
     *                  "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/channels",
     *              ),
     *              "channels_fan" => array(
     *                  "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/channelsFan",
     *              ),
     *              "blocked_users" => array(
     *                  "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/blocked",
     *              )
     *          )
     *      );
     */
    public function me()
    {
        $command = $this->client->getCommand('Whoami');

        return $this->executeCommand($command);
    }

    /**
     * Update a profile of me user
     *
     * @param $username your user name | the new username
     *
     * @param $email your email | the new user email
     *
     * @param $current_password your password. This method can not change your password for this use @link #changePassword
     *
     * @return array|string Associative array with you profile updated | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example
     *
     *      $your_api_instance->updateMe('xabier','xabier@antweb.es','mySecretPassword');
     *
     *      array(
     *          'id' => '1',
     *          'username' => 'xabier',
     *          'email' => 'xabier@antweb.es',
     *          );
     */
    public function updateMe($username, $email, $current_password)
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
     * Delete my user
     *
     * @return string Message deleted user | Message with error in json format
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Delete me user
     *
     *      $your_api_instance->delMe();
     *      //ouput message
     *      User deleted
     */
    public function delMe()
    {
        $command = $this->client->getCommand('DelMe');

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
     * @return string message password changed sucessfully if your password have been changed | Message with error in json format
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


    /******************************************************************************/
    /*				  				  CHANNEL PHOTO            					  */
    /******************************************************************************/

    /**
     * List all photos of an album
     *
     * @param $album_id The photo album ID that you to retrieve data
     *
     * @param int $limit  number of items to retrieve at most
     *
     * @param int $offset The distance (displacement) from the start of a data
     *
     * @return array|string Associative array with photos an album | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Show first photo of photo album
     *
     *      $your_api_instance->showPhotoAlbum(1);
     *
     *  array(
     *      "total" => 6,
     *      "limit" => 1,
     *      "offset" => 0,
     *      "_links" => array(
     *          "self" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/albums/1/photos",
     *          )
     *      ),
     *      "resources" => array(
     *          array(
     *              "id" => 1,
     *              "participant" => array(
     *                  "id" => 1,
     *                  "username" => "alex",
     *              ),
     *              "publicated_at" => "2013-10-29T12:27:34+0100",
     *              "path" => "photo1-path.jpg",
     *              "title" => "la foto 1",
     *              "number_votes" => 15,
     *              "score" => 15,
     *              "_links" => array(
     *                  "self" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/photos/1",
     *                  ),
     *                  "creator" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/1",
     *                  ),
     *                  "path" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/uploads/photo1-path.jpg",
     *                      "type" => "image/*",
     *                  ),
     *                  "album" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/albums/1",
     *                  ),
     *              ),
     *          ),
     *      )
     * );
     *
     */
    public function showPhotoAlbum($album_id, $limit = 1, $offset = 0)
    {
        if (!is_numeric($album_id) || 0 >= $album_id) {
            throw new InvalidArgumentException(
                "Api::showPhotoAlbum album_id field should be positive integer");
        }

        if ($limit < 1) {
            throw new InvalidArgumentException(
                "Api::showFriendshipsRequest() limit must be a min 1 ");
        }
        if ($offset < 0) {
            throw new InvalidArgumentException(
                "Api::showFriendshipsRequest() offset must be a positive number,  min 0 ");
        }

        /** @var $command \Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand('ShowPhotoAlbum',array('album_id' => $album_id, 'limit'=>$limit,'offset'=>$offset));
        return $this->executeCommand($command);
    }

    /**
     * Show Photo
     *
     * @param $photo_id The photo ID that you to retrieve data
     *
     * @return array|string Associative array with photo data | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Show a photo
     *
     *      $your_api_instance->showPhoto(1);
     *
     *  array(
     *      "id" => 1,
     *      "participant" => array(
     *          "id" => 1,
     *          "username" => "alex",
     *      ),
     *      "publicated_at" => "2013-10-29T12:27:34+0100",
     *      "path" => "photo1-path.jpg",
     *      "title" => "la foto 1",
     *      "number_votes" => 15,
     *      "score" => 15,
     *      "album" => array(
     *          "id" => 1,
     *          "title" => "la foto 1",
     *          "description" => "default album",
     *      ),
     *      "_links" => array(
     *          "self" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/photos/1",
     *          ),
     *          "creator" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/users/1",
     *          ),
     *          "path" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/users/uploads/photo1-path.jpg",
     *              "type" => "image/*",
     *          ),
     *          "album" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/albums/1",
     *           ),
     *      )
     *  );
     */
    public function showPhoto($photo_id)
    {
        if (!is_numeric($photo_id) || 0 >= $photo_id) {
            throw new InvalidArgumentException(
                "Api::showPhoto $photo_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('ShowPhoto',array('id' => $photo_id,));

        return $this->executeCommand($command);
    }
    /**
     * @param $photo_id The Photo ID to be reported
     *
     * @param $reason The reason this report
     *
     * @return array|string Associative array with report | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Add Photo report
     *
     *      $your_api_instance->addReportPhoto(1,'This photo is not adequate');
     *
     * array(
     *  "reason" => "this is not me photo",
     *  "created_at" => "2013-10-29T12:13:07+0100",
     * "id" => 9,
     * );
     *
     */
    public function addReportPhoto($photo_id, $reason)
    {
        if (!is_numeric($photo_id) || 0 >= $photo_id) {
            throw new InvalidArgumentException(
                "Api::addReportPhoto photo_id field should be positive integer");
        }

        if (!is_string($reason) || 0 >= strlen($reason)) {
            throw new InvalidArgumentException("addReportPhoto reason field needs to be a non-empty string");
        }

        /** @var $command \Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand('AddReportPhoto',array('id' => $photo_id, 'report'=>array('reason' => $reason)));
        return $this->executeCommand($command);
    }

    /**
     * Delete a Photo
     *
     * @param $photo_id The photo id you like deleting
     *
     * @return string Message sucessfully if can delete the photo | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Delete Photo
     *
     *      $your_api_instance->delPhoto(1);
     *       Photo deleted
     */
    public function delPhoto($photo_id)
    {
        if (!is_numeric($photo_id) || 0 >= $photo_id) {
            throw new InvalidArgumentException(
                "Api::delPhoto photo_id field should be positive integer");
        }

        /** @var $command \Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand('DeletePhoto',array('photo_id' => $photo_id));

        return $this->executeCommand($command);
    }

    /**
     * Add new Photo Album in user accont
     *
     * @param $user_id The user ID
     *
     * @param $title One name for the Album
     *
     * @param string $description A short description of type photos
     *
     * @return array|string  | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Add Photo Album
     *
     * array(
     *  "id" => 2,
     *  "participant" => array(
     *      "id" => 1,
     *      "username" => "alex",
     *      "email" => "alex@chateagratis.net"
     *  ),
     *  "title" => "new Album",
     *  "description" => "this is my secret album"
     * );
     *
     */
    public function addAlbum($user_id, $title, $description='')
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "Api::addAlbum user_id field should be positive integer");
        }

        if (!is_string($title) || 0 >= strlen($title)) {
            throw new InvalidArgumentException("Api::addAlbum reason title field needs to be a non-empty string");
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
                "Api::addPhoto user_id field should be positive integer");
        }

        if (!is_string($imageTile) || 0 >= strlen($imageTile)) {
            throw new InvalidArgumentException("Api::addPhoto imageTile title field needs to be a non-empty string");
        }

        if (!is_string($imageFile) || 0 >= strlen($imageFile)) {
            throw new InvalidArgumentException("Api::addPhoto imageFile title field needs to be a non-empty string");
        }
        if(!file_exists($imageFile)){
            throw new InvalidArgumentException("Api::addPhoto '.$imageFile.' not exist or It do not read");
        }

        /* @var $command \Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand('AddPhoto',array('id' => $user_id, 'title'=>'titulo command','image'=>$imageFile));

        return $this->executeCommand($command);

    }

    /**
     * @param $user_id The user id to retrieve data
     *
     * @param int $limit  number of items to retrieve at most
     *
     * @param int $offset The distance (displacement) from the start of a data
     *
     * @return array|string Associative array with photos an album | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Show photos of user id
     *
     * array (
        "total" => 40,
        "limit" => 1,
        "offset" => 0,
        "_links" => array(
            "self" => array(
                "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/photos",
            ),
     *   ),
     *   "resources" => array(
     *       array(
     *           "id" => 1,
     *           "participant" => array(
     *               "id" => 1,
     *               "username" => "alex",
     *           ),
     *           "publicated_at" => "2013-10-29T12:27:34+0100",
     *           "path" => "photo1-path.jpg",
     *           "title" => "la foto 1",
     *           "number_votes" => 15,
     *           "score" => 15,
     *           "album" => array(
     *           "id" => 1,
     *           "title" => "la foto 1",
     *           "description" => "default album",
     *       ),
     *       "_links" => array(
     *           "self" => array(
     *             "href" => "http://api.chateagratis.local/app_dev.php/api/photos/1",
     *           ),
     *           "creator" => array(
     *               "href" => "http://api.chateagratis.local/app_dev.php/api/users/1",
     *           ),
     *           "path" => array(
     *               "href" => "http://api.chateagratis.local/app_dev.php/api/users/uploads/photo1-path.jpg",
     *               "type" => "image/*",
     *           ),
     *           "album" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/albums/1",
     *          )
     *      )
     *  )
     *  );
     */
    public function showUserPhotos($user_id, $limit = 1, $offset = 0)
    {

        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "Api::ShowUserPhotos user_id field should be positive integer");
        }

        if ($limit < 1) {
            throw new InvalidArgumentException(
                "Api::ShowUserPhotos() limit must be a min 1 ");
        }
        if ($offset < 0) {
            throw new InvalidArgumentException(
                "Api::ShowUserPhotos() offset must be a positive number,  min 0 ");
        }
        /* @var $command \Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand('ShowUserPhotos',array('id' => $user_id, 'limit'=>$limit, 'offset'=>$offset));

        return $this->executeCommand($command);
    }

    public function showPhotoVotes($user_id, $photo_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "Api::showPhotoVotes user_id field should be positive integer");
        }

        if (!is_numeric($photo_id) || 0 >= $photo_id) {
            throw new InvalidArgumentException(
                "Api::showPhotoVotes photo_id field should be positive integer");
        }

        /* @var $command \Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand('ShowUserPhotoVote',array('id' => $user_id, 'photo_id'=>$photo_id));

        return $this->executeCommand($command);
    }

    public function showPhotos($user_id)
    {
        // TODO: Implement showPhotos() method.
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


}