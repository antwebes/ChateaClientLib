<?php
/**
 * Created by Ant-WEB S.L.
 * Developer: Xabier Fernández Rodríguez <jjbier@gmail.com>
 * Date: 14/10/13
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\Service\Client\ClientInterface;

/**
 * This class interface contains all command that run in api server.
 *
 * @package Ant\ChateaClient\Client
 */
interface ApiInterface
{
    /**
     * Get fans (users) the channel
     *
     * @param number $channel_id Channel to retrieve fans
     *
     * @param int $limit         number of items to retrieve at most
     *
     * @param int $offset        The distance (displacement) from the start of a data
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
     *          "href" => "http://api.chateagratis.net/api/channels/2/fans"
     *          )
     *      ),
     *      "resources" => array(
     *           array(  "id" => 1,
     *                   "username" => "alex",
     *                   "email" => "alex@chateagratis.net",
     *                   "_links" => array(
     *                          "self" => array(
     *                              "href" => "http://api.chateagratis.net/api/users/1"
     *                          ),
     *                          "channels" => array(
     *                              "href" => "http://api.chateagratis.net/api/users/1/channels"
     *                          ),
     *                          "channels_fan" => array(
     *                               "href" => "http://api.chateagratis.net/api/users/1/channelsFan"
     *                          ),
     *                          "blocked_users" => array(
     *                              "href" => "http://api.chateagratis.net/api/users/1/blocked"
     *                          )
     *                   )
     *           )
     *      )
     * );
     */
    public function showChannelFans ($channel_id, $limit = 1, $offset = 0);

    /**
     * upload new photo at server api
     *
     * @param number $user_id   The user owner of photo
     *
     * @param string $imageFile The path to upload photo
     *
     * @param string $imageTile The name of photo
     *
     * @return array|string Associative array with new album data | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Add Photo
     *
     *      $your_api_instance->addPhoto(1,'my face in wall', '/home/alex/my_face_wall.png');
     *
     *  array(
     *      "id" => 9,
     *      "participant" => array(
     *          "id" => 1,
     *          "username" => "alex",
     *      ),
     *      "publicated_at" => "2013-10-30T08:11:22+0100",
     *      "path" => "2013/10/30/5270b11b25f5c.png",
     *      "number_votes" => 0,
     *      "_links" => array(
     *          "self" => array(
     *              "href" => "http://api.chateagratis.net/api/photos/9",
     *          ),
     *          "creator" => array(
     *              "href" => "http://api.chateagratis.net/api/users/1",
     *          ),
     *          "path" => array(
     *              "href" => "http://api.chateagratis.net/uploads/2013/10/30/5270b11b25f5c.png",
     *              "type" => "image/*",
     *          )
     *      )
     * );
     *
     */
    public function addPhoto ($user_id, $imageFile, $imageTile = '');

    /**
     * Delete a photo of album
     *
     * @param number $user_id  The user id to retrieve data
     *
     * @param number $photo_id The photo id to retrieve data
     *
     * @return string Message sucessfully if can delete photo of album | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Delete an abum 1 to user 1
     *
     *      $your_api_instance->delAlbum(1,1);
     *
     *      Photo deleted of Album
     */
    public function delPhotoAlbum ($user_id, $photo_id);

    /**
     * UnBlocked one user
     *
     * @param number $user_id         User to retrieve by ID
     *
     * @param number $user_blocked_id User to blocked by ID
     *
     * @return string Message sucessfully if can blocked an user | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Unblocked one user
     *
     *      $your_api_instance->delUserBlocked(1,6);
     *
     *      //output
     *      User unblocked
     */
    public function delUserBlocked ($user_id, $user_blocked_id);

    /**
     * Revokes the access token and logout the user session in server.
     *
     * @return string a message ok and the HTTP status code 200
     *
     * @throws ApiException This exception is thrown if server send one error
     */
    public function logout ();

    /**
     * Get me friends
     *
     * @param number $user_id The user id retrieve friends
     *
     * @param int $limit      number of items to retrieve at most
     *
     * @param int $offset     The distance (displacement) from the start of a data
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
     *              "href" => "http://api.chateagratis.net/api/users/1/friends",
     *          ),
     *      ),
     *      "resources" => array(
     *          array(
     *              "id" => 3,
     *              "username" => "alex3",
     *              "email" => "alex3@chateagratis.net",
     *              "_links" => array(
     *                  "self" => array(
     *                      "href" => "http://api.chateagratis.net/api/users/3",
     *                  ),
     *                  "channels" => array(
     *                      "href" => "http://api.chateagratis.net/api/users/3/channels",
     *                  ),
     *                  "channels_fan" => array(
     *                      "href" => "http://api.chateagratis.net/api/users/3/channelsFan",
     *                  ),
     *                  "blocked_users" => array(
     *                      "href" => "http://api.chateagratis.net/api/users/3/blocked",
     *                  ),
     *              ),
     *          ),
     *      )
     * );
     */
    public function showFriends ($user_id, $limit = 1, $offset = 0);

    /**
     * Show user by ID
     *
     * @param number $user_id User to retrieve by ID
     *
     * @return array|string Associative array with user data | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Show user with DI 1
     *
     *      $your_api_instance->showUser();
     *
     *  array(
     *      "id" => 1,
     *      "username" => "alex",
     *      "email" => "alex@chateagratis.net",
     *      "_links" => array(
     *          "self" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/users/1",
     *          ),
     *          "channels" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/channels",
     *          ),
     *          "channels_fan" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/channelsFan",
     *          ),
     *          "blocked_users" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/blocked",
     *          )
     *      )
     * );
     */
    public function showUser ($user_id);

    /**
     * Register new user at server
     *
     * @param string $username            The name of user. This is unique for user
     *
     * @param string $email               The email for user. This is unique for user
     *
     * @param string $new_password        The user password
     *
     * @param string $repeat_new_password Repeat the password
     *
     * @param string $affiliate_host      The name of your server, where you make send request.
     *                                    You don't use protocols (http:// or ftp ) or subdomains only use primary name
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
    public function register ($username, $email, $new_password, $repeat_new_password, $affiliate_host);

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
    public function delMe ();

    /**
     * Add new profile one user
     *
     * @param number $user_id           User to retrieve by ID
     *
     * @param string $about             Short description  your profile
     *
     * @param string $sexualOrientation Choice between <heterosexual|bisexual|otro>
     *
     * @return array|string Associative array with profile data | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Create profile one user
     *
     *      $your_api_instance->addUserProfile(1,'about-10','bisexual');
     *
     *  array(
     *       "id" => 11,
     *       "about" => "about-10",
     *       "sexual_orientation" => "bisexual",
     *       "count_visits" => 0,
     *       "publicated_at" => "2013-10-30T16:43:51+0100",
     *  );
     */
    public function addUserProfile ($user_id, $about, $sexualOrientation);

    /**
     * Delete friends between two users
     *
     * @param number $user_id        The user id that you delere friend
     *
     * @param number $user_delete_id The user id, you want deleted.
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
    public function delFriend ($user_id, $user_delete_id);

    /**
     *
     * Add one message on thread
     *
     * @param number $user_id   The user id that create the thread
     *
     * @param number $thread_id The ID of thread
     *
     * @param string $body      the text of message
     *
     * @return array|string Associative array with messages | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Add one message on thread
     *
     *      $your_api_instance->showThreadMessages(1,1,'this is entity body');
     *
     * array(
     *  "id" => 15,
     *   "body" => "this is entity body",
     *  "created_at" => "2013-10-30T14:00:53+0100"
     * );
     */
    public function addThreadMessage ($user_id, $thread_id, $body);

    /**
     * Delete a Photo
     *
     * @param number $photo_id The photo id you like deleting
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
    public function delPhoto ($photo_id);

    /**
     * Change user password
     *
     * @param string $current_password    your actual password
     *
     * @param string $new_password        your new password
     *
     * @param string $repeat_new_password repeat your new password
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
    public function changePassword ($current_password, $new_password, $repeat_new_password);

    /**
     * Create un new Channel
     *
     * @param string $name         The name of channel this is unique
     *
     * @param string $title        The title of channel
     *
     * @param string $description  The long description of channel
     *
     * @param string $channel_type The type pof channel, This value have a subset available in  server,
     *                             you can view the channels type with command @link #showChannelsTypes()
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
    public function addChanel ($name, $title = '', $description = '', $channel_type = '');

    /**
     * Add new Photo Album in user accont
     *
     * @param number $user_id     The user ID
     *
     * @param string $title       One name for the Album
     *
     * @param string $description A short description of type photos
     *
     * @return array|string Associative array with new album data | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Add Photo Album
     *
     *      $your_api_instance->addAlbum(1,'new Album', 'this is me personal photobook');
     *
     * array(
     *  "id" => 2,
     *  "participant" => array(
     *      "id" => 1,
     *      "username" => "alex",
     *      "email" => "alex@chateagratis.net"
     *  ),
     *  "title" => "new Album",
     *  "description" => "this is me personal photobook"
     * );
     *
     */
    public function addAlbum ($user_id, $title, $description = '');

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
     *                "href" => "http://api.chateagratis.net/api/users/1",
     *              ),
     *              "channels" => array(
     *                  "href" => "http://api.chateagratis.net/api/users/1/channels",
     *              ),
     *              "channels_fan" => array(
     *                  "href" => "http://api.chateagratis.net/api/users/1/channelsFan",
     *              ),
     *              "blocked_users" => array(
     *                  "href" => "http://api.chateagratis.net/api/users/1/blocked",
     *              )
     *          )
     *      );
     */
    public function me ();

    /**
     * Show score I had put to one photo
     *
     * @param number $user_id  The user id to retrieve data
     *
     * @param number $photo_id The photo id to retrieve data
     *
     * @return array|string Associative array with photos an album | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Show score User id 1 had put to 5 photo
     *
     *      $your_api_instance->showPhotoVotes(1,5);
     *
     *  array(
     *      "score" => 100,
     *      "photo" => array(
     *          "id" => 5,
     *          "number_votes" => 15,
     *          "score" => 55,
     *      ),
     *      "_links" => array(
     *          "self" => array(
     *              "href" => "http://api.chateagratis.net/api/users/1/photos/5/votes",
     *          ),
     *          "photo" => array(
     *              "href" => "http://api.chateagratis.net/api/photos/5",
     *          ),
     *          "participant" => array(
     *              "href" => "http://api.chateagratis.net/api/users/1",
     *          )
     *      )
     *  );
     *
     */
    public function showPhotoVotes ($user_id, $photo_id);

    /**
     *
     * Update a channel
     *
     * @param number $channel_id   Channel to update by ID
     *
     * @param string $name         The new name of channel, if you would like  update this field.
     *
     * @param string $title        The new title of channel, if you would like  update this field.
     *
     * @param string $description  The new description, of channel if you would like  update this field.
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
    public function updateChannel ($channel_id, $name, $title = '', $description = '', $channel_type = '');

    /**
     * Create new thread
     *
     * @param number $user_id        The user id that create the thread
     *
     * @param string $recipient_name The ID username that retrieve the message
     *
     * @param string $subject        The short description of thread
     *
     * @param string $body           The  long text of thread
     *
     * @return array|string Associative array with data thread | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Add new thread
     *
     *      $your_api_instance->addThread(1,'alex2','Subject sample',"<b>this is my body in 1rst message </b>")
     *
     *  array(
     *      "id" => 5,
     *      "subject" => "Subject sample",
     * );
     */
    public function addThread ($user_id, $recipient_name, $subject, $body);

    /**
     * Decline a friendship request
     *
     * @param number $user_id         The user id that you decliene new Friend
     *
     * @param number $user_decline_id The user id pending friendship
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
    public function delFriendshipRequest ($user_id, $user_decline_id);

    /**
     * Delete one vote of one photo
     *
     * @param number $user_id  The user id to delete data
     *
     * @param number $photo_id The phot id to delete vote
     *
     * @return string Message sucessfully if can delete the vote | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example  Delete one vote of  photo 6
     *
     *      $your_api_instance->delPhotoVote(1,6);
     *
     *          //output
     *          Vote deleted
     */
    public function delPhotoVote ($user_id, $photo_id);

    /**
     * Remove channel's  fan one user
     *
     * @param number $channel_id Channel id to receive a fan
     *
     * @param number $user_id    The user id will be fan
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
    public function delUserChannelFan ($channel_id, $user_id);

    /**
     * Get channel. Show data channel by id
     *
     * @param number $channel_id Channel to retrieve by ID
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
    public function showChannel ($channel_id);

    /**
     * Report a user
     *
     * @param number $user_reported_id The user id that is report
     *
     * @param string $reason           Description for report the user
     *
     * @return array|string Associative array with report data | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * array(
     *   "reason" => "this user is heterosexual",
     *   "created_at" => "2013-10-30T17:03:29+0100",
     *   "id" => 44,
     * );
     */
    public function addUserReports ($user_reported_id, $reason);

    /**
     * Show channels favorites one user
     *
     * @param number $user_id User id  to retrieve fans channels
     *
     * @param int $limit      number of items to retrieve at most
     *
     * @param int $offset     The distance (displacement) from the start of a data
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
     *              "href" => "http://api.chateagratis.net/api/users/1/channelsFan"
     *          ),
     *      ),
     *      "resources" => array(
     *          array(
     *              "id" => 2,
     *              "name" => "channel 2",
     *              "slug" => "channel-2",
     *              "_links" => array(
     *                  "self" => array(
     *                      "href" => "http://api.chateagratis.net/api/channels/2"
     *                  ),
     *                  "fans" => array(
     *                      "href" => "http://api.chateagratis.net/api/channels/2/fans",
     *                  ),
     *                  "owner" => array(
     *                      "href" => "http://api.chateagratis.net/api/users/2"
     *                  )
     *              )
     *          )
     *      )
     *  );
     */
    public function showUserChannelsFan ($user_id, $limit = 1, $offset = 0);

    /**
     * Report a photo
     *
     * @param number $photo_id The Photo ID to be reported
     *
     * @param string $reason   The reason this report
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
    public function addReportPhoto ($photo_id, $reason);

    /**
     * Blocked one user
     *
     * @param number $user_id         User to retrieve by ID
     *
     * @param number $user_blocked_id User to blocked by ID
     *
     * @return string Message sucessfully if can blocked an user | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Blocked one user
     *
     *      $your_api_instance->addUserBlocked(1,6);
     *
     *      //output
     *      User blocked
     */
    public function addUserBlocked ($user_id, $user_blocked_id);

    /**
     * Get all the users
     *
     * @param int $limit  number of items to retrieve at most
     *
     * @param int $offset The distance (displacement) from the start of a data
     *
     * @return array|string Associative array with all users | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Show all users, only first
     *
     *      $your_api_instance->showUsers();
     *
     *  array(
     *      "total" => 12,
     *      "limit" => 1,
     *      "offset" => 0,
     *      "_links" => array(
     *          "self" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/users",
     *              )
     *      ),
     *      "resources" => array(
     *          array(
     *              "id" => 1,
     *              "username" => "alex",
     *              "email" => "alex@chateagratis.net",
     *              "_links" => array(
     *                  "self" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/1",
     *                  ),
     *                  "channels" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/channels",
     *                  ),
     *                  "channels_fan" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/channelsFan",
     *                  ),
     *                  "blocked_users" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/blocked",
     *                  )
     *              )
     *          )
     *      )
     * );
     */
    public function showUsers ($limit = 1, $offset = 0);

    /**
     * List all channels register in Server
     *
     * @param int $limit    number of items to retrieve at most
     *
     * @param int $offset   The distance (displacement) from the start of a data
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
     *              href => "http://api.chateagratis.net/api/channels/11"
     *          ),
     *          "fans" => array(
     *              "href" => "http://api.chateagratis.net/api/channels/11/fans"
     *          ),
     *          "owner" => array(
     *              "href => http://api.chateagratis.net/api/users/1"
     *           )
     *      );
     *
     */
    public function showChannels ($limit = 25, $offset = 0, array $filter = null);

    /**
     * Show count visits on one profile and who visits of one profile
     *
     * @param number $user_id User to retrieve by ID
     *
     * @param int $limit      number of items to retrieve at most
     *
     * @param int $offset     The distance (displacement) from the start of a data
     *
     * @return array|string
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * array(
     *      array(
     *          "participant" => array(
     *          "id" => 1,
     *          "username" => "alex",
     *          "email" => "alex@chateagratis.net",
     *      ),
     *      "participant_voyeur" => array(
     *          "id" => 2,
     *          "username" => "alex2",
     *          "email" => "alex2@chateagratis.net",
     *      ),
     *      "frequency" => 1,
     *   )
     * );
     */
    public function showUserVisitors ($user_id, $limit = 1, $offset = 0);

    /**
     * Show user messages have in inbox
     *
     * @param number $user_id The user id see her/his inbox
     *
     * @return array|string Associative array with the subject | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Show messages inbox
     *
     *      $your_api_instance->addThread(1);
     *
     * array(
     *      array(
     *          "id" => 1,
     *          "subject" => "hola thread 1",
     *      ),
     *      array(
     *      "id" => 4,
     *      "subject" => "hola thread 4",
     *      )
     *  );
     */
    public function showThreadsInbox ($user_id);

    /**
     * Show all reports
     *
     * @param int $limit  number of items to retrieve at most
     *
     * @param int $offset The distance (displacement) from the start of a data
     *
     * @return array|string Associative array with all albums one user | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Show all reports, only 1rst
     *
     *      $your_api_instance->showReports();
     *
     *  array(
     *      "total" => 42,
     *      "limit" => 1,
     *      "offset" => 0,
     *      "_links" => array(
     *          "self" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/reports",
     *          )
     *      ),
     *      "resources" => array(
     *          array(
     *              "reason" => "I dislike a lot this guy",
     *              "created_at" => "2013-08-27T15:01:01+0200",
     *              "reviewed_at" => "2013-08-27T16:01:01+0200",
     *              "id" => 1,
     *              "_links" => array(
     *                  "self" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/reports/1",
     *                  ),
     *                  "reporter" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/1",
     *                  ),
     *                  "resource" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/2",
     *                  )
     *              )
     *          )
     *      )
     * );
     */
    public function showReports ($limit = 1, $offset = 0);

    /**
     * Show Photo
     *
     * @param string $photo_id The photo ID that you to retrieve data
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
     *              "href" => "http://api.chateagratis.net/api/photos/1",
     *          ),
     *          "creator" => array(
     *              "href" => "http://api.chateagratis.net/api/users/1",
     *          ),
     *          "path" => array(
     *              "href" => "http://api.chateagratis.net/api/users/uploads/photo1-path.jpg",
     *              "type" => "image/*",
     *          ),
     *          "album" => array(
     *              "href" => "http://api.chateagratis.net/api/users/1/albums/1",
     *           ),
     *      )
     *  );
     */
    public function showPhoto ($photo_id);

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
     *                  "href" => "http://api.chateagratis.net/api/channelstype"
     *              ),
     *      ),
     *      "resources" => array(
     *              array(
     *                  "name" => "adult",
     *                   "_links" => array(
     *                          "channelsType" => array(
     *                                  "href" => "http://api.chateagratis.net/api/channels?filter%3DchannelType=adult",
     *                          )
     *                   )
     *              )
     *
     *      )
     * );
     *
     */
    public function showChannelsTypes ($limit = 1, $offset = 0);

    /**
     * Show list user, that one user will pending have your friendship
     *
     * @param number $user_id The user id that you retrieve data
     *
     * @param int $limit      number of items to retrieve at most
     *
     * @param int $offset     The distance (displacement) from the start of a data
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
     *              "href" => "http://api.chateagratis.net/api/users/1/friends/pending",
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
     *                      "href" => "http://api.chateagratis.net/api/users/3",
     *                  ),
     *                  "channels" => array(
     *                      "href" => "http://api.chateagratis.net/api/users/3/channels",
     *                  ),
     *                  "channels_fan" => array(
     *                      "href" => "http://api.chateagratis.net/api/users/3/channelsFan",
     *                  ),
     *                  "blocked_users" => array(
     *                      "href" => "http://api.chateagratis.net/api/users/3/blocked",
     *                  ),
     *              )
     *          )
     *      )
     *  );
     */
    public function showFriendshipsPending ($user_id, $limit = 1, $offset = 0);

    /**
     * Shows the details of a report
     *
     * @param number $report_id The report id to retrieve data
     *
     * @return array|string Associative array with all albums one user | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Show report ID 1
     *
     *      $your_api_instance->showReport(1);
     *
     *  array(
     *      "reason" => "I dislike a lot this guy",
     *      "created_at" => "2013-08-27T15:01:01+0200",
     *      "reviewed_at" => "2013-08-27T16:01:01+0200",
     *      "id" => 1,
     *      "_links" => array(
     *          "self" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/reports/1",
     *          ),
     *          "reporter" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/users/1",
     *          ),
     *          "resource" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/users/2",
     *          )
     *      )
     *  );
     */
    public function showReport ($report_id);

    /**
     * Show all photos one user
     *
     * @param number $user_id The user id to retrieve data
     *
     * @param int $limit      number of items to retrieve at most
     *
     * @param int $offset     The distance (displacement) from the start of a data
     *
     * @return array|string Associative array with photos an album | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Show photos of user id, only the first
     *
     *      $your_api_instance->showUserPhotos(1,1,0);
     *
     * array (
     *   "total" => 40,
     *   "limit" => 1,
     *   "offset" => 0,
     *   "_links" => array(
     *       "self" => array(
     *           "href" => "http://api.chateagratis.net/api/users/1/photos",
     *       ),
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
     *             "href" => "http://api.chateagratis.net/api/photos/1",
     *           ),
     *           "creator" => array(
     *               "href" => "http://api.chateagratis.net/api/users/1",
     *           ),
     *           "path" => array(
     *               "href" => "http://api.chateagratis.net/api/users/uploads/photo1-path.jpg",
     *               "type" => "image/*",
     *           ),
     *           "album" => array(
     *              "href" => "http://api.chateagratis.net/api/users/1/albums/1",
     *          )
     *      )
     *  )
     *  );
     */
    public function showUserPhotos ($user_id, $limit = 1, $offset = 0);

    /**
     *
     * Show channels created for the user
     *
     * @param number $user_id User id  to retrieve channel
     *
     * @param int $limit      number of items to retrieve at most
     *
     * @param int $offset     The distance (displacement) from the start of a data
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
     *              "href" => "http://api.chateagratis.net/api/users/1/channels",
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
     *                      "href" => "http://api.chateagratis.net/api/channels/1",
     *                  ),
     *                  "fans" => array(
     *                      "href" => "http://api.chateagratis.net/api/channels/1/fans",
     *                  ),
     *                  "owner" => array(
     *                      "href" => "http://api.chateagratis.net/api/users/1",
     *                  ),
     *              )
     *          )
     *      )
     *  );
     */
    public function showUserChannels ($user_id, $limit = 1, $offset = 0);

    /**
     * Accept request new Friend
     *
     * @param number $user_id        The user id that you retrieve request new Friend
     *
     * @param number $user_accept_id The user id pending friendship
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
    public function addFriendshipRequest ($user_id, $user_accept_id);

    /**
     * Delete one thread ant all messages
     *
     * @param number $user_id   The user id that create the thread
     *
     * @param number $thread_id The ID of thread
     *
     * @return string Message sucessfully if can delete thread | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Delete thread 6
     *
     *      $your_api_instance->delThread(6);
     *
     *      //ouput message
     *  Thread deleted
     */
    public function delThread ($user_id, $thread_id);

    /**
     * Delete channel
     *
     * @param number $channel_id Channel to update by ID
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
    public function delChannel ($channel_id);

    /**
     * Show all abums one user
     *
     * @param number $user_id The user id to retrieve data
     *
     * @param int $limit      number of items to retrieve at most
     *
     * @param int $offset     The distance (displacement) from the start of a data
     *
     * @return array|string Associative array with all albums one user | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Show all abums to user 1, only 1rst
     *
     *      $your_api_instance->showUserAlbums(1);
     *
     * array(
     *      "total" => 1,
     *      "limit" => 1,
     *      "offset" => 0,
     *      "_links" => array(
     *          "self" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/albums",
     *          )
     *      ),
     *      "resources" => array(
     *          array(
     *              "id" => 1,
     *              "title" => "la foto 1",
     *              "description" => "default album",
     *              "_links" => array(
     *                  "self" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/albums/1",
     *              ),
     *                  "participant" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/1",
     *                ),
     *                  "photos" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/albums/1/photos",
     *                  )
     *              )
     *          )
     *      )
     * );
     */
    public function showAlbums ($user_id, $limit = 1, $offset = 0);

    /**
     *
     * Request reset the user password. The server send email with new password,
     * this this request is only once for day.
     *
     * @param string $username_or_email The user email or username
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
    public function forgotPassword ($username_or_email);

    /**
     * Update a profile of me user
     *
     * @param string $username         your user name | the new username
     *
     * @param string $email            your email | the new user email
     *
     * @param string $current_password your password. This method can not change your password for this use @link #changePassword
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
    public function updateMe ($username, $email, $current_password);

    /**
     * Show one album of user
     *
     * @param number $user_id  The user id to retrieve data
     *
     * @param number $album_id The album id to retrieve data
     *
     * @return array|string Associative array with the album one user | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Show an abum 1 to user 1
     *
     *      $your_api_instance->showUserAlbum(1,1);
     *
     * array(
     *  "id" => 1,
     *  "title" => "la foto 1",
     *  "description" => "default album",
     *  "_links" => array(
     *      "self" => array(
     *          "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/albums/1",
     *      ),
     *      "participant" => array(
     *          "href" => "http://api.chateagratis.local/app_dev.php/api/users/1",
     *      ),
     *      "photos" => array(
     *          "href" => "http://api.chateagratis.local/app_dev.php/api/albums/1/photos",
     *      )
     *   )
     * );
     *
     */
    public function showAlbum ($user_id, $album_id);

    /**
     * Show the friendship requests sent by user id, and it pending to be accepted
     *
     * @param number $user_id The user id that you retrieve data
     *
     * @param int $limit      number of items to retrieve at most
     *
     * @param int $offset     The distance (displacement) from the start of a data
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
     *              "href" => "http://api.chateagratis.net/api/users/1/friends/pending",
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
     *                      "href" => "http://api.chateagratis.net/api/users/3",
     *                  ),
     *                  "channels" => array(
     *                      "href" => "http://api.chateagratis.net/api/users/3/channels",
     *                  ),
     *                  "channels_fan" => array(
     *                      "href" => "http://api.chateagratis.net/api/users/3/channelsFan",
     *                  ),
     *                  "blocked_users" => array(
     *                      "href" => "http://api.chateagratis.net/api/users/3/blocked",
     *                  ),
     *              )
     *          )
     *      )
     *  );
     */
    public function showFriendshipsRequest ($user_id, $limit = 1, $offset = 0);

    /**
     * List all message one thread
     *
     * @param number $user_id   The user id that create the thread
     *
     * @param number $thread_id The ID of thread
     *
     * @return array|string Associative array with messages | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Show messages on thread
     *
     *      $your_api_instance->showThreadMessages(1,1);
     *
     * array(
     *  array(
     *      "id" => 1,
     *      "body" => "este es el contenido del msg 1",
     *      "created_at" => "2013-08-25T15:01:01+0200",
     *  ),array(
     *      "id" => 2,
     *      "body" => "este es el contenido del msg 2",
     *      "created_at" => "2013-08-25T15:31:01+0200",
     *  )
     *);
     */
    public function showThreadMessages ($user_id, $thread_id);

    /**
     * Add one vote at photo
     *
     * @param number $user_id  The user id to retrieve data
     *
     * @param number $photo_id The photo id to retrieve data
     *
     * @param int $score       The score photo,  The score ha to be between one and ten
     *
     * @return array|string Associative array with you vote | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example
     *
     * array (
     *  "score" => 10,
     *      "photo" => array(
     *          "id" => 6,
     *          "number_votes" => 21,
     *          "score" => 47.571428571428,
     *      ),
     *      "_links" => array(
     *          "self" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/photos/6/votes",
     *          ),
     *          "photo" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/photos/6",
     *          ),
     *          "participant" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/users/1",
     *          )
     *      )
     *  );
     */
    public function addPhotoVote ($user_id, $photo_id, $score = 1);

    /**
     * Show profile by user ID
     *
     * @param number $user_id User to retrieve by ID
     *
     * @return array|string Associative array with profile data | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Create profile one user
     *
     *      $your_api_instance->showUserProfile(1);
     *
     *  array(
     *       "id" => 11,
     *       "about" => "about-10",
     *       "sexual_orientation" => "bisexual",
     *       "count_visits" => 0,
     *       "publicated_at" => "2013-10-30T16:43:51+0100",
     *  );
     */
    public function showUserProfile ($user_id);

    /**
     * Sends a friendship request between two users
     *
     * @param number $user_id   Your user id
     *
     * @param number $friend_id The user id that retrieve the request
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
    public function addFriends ($user_id, $friend_id);

    /**
     * Insert a photo entity into album id
     *
     * @param number $user_id  The user id to retrieve data
     *
     * @param number $photo_id The photo id to insert
     *
     * @param number $album_id The album id to retrieve data
     *
     * @return array|string Associative array with the album one user | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Add photo 1 on album 1
     *
     *      $your_api_instance->addAlbumPhoto(1,1,1);
     *
     *      // ouput message
     *      Photo inserted
     */
    public function addAlbumPhoto ($user_id, $photo_id, $album_id);

    /**
     * List all photos of an album
     *
     * @param string $album_id The photo album ID that you to retrieve data
     *
     * @param int $limit       number of items to retrieve at most
     *
     * @param int $offset      The distance (displacement) from the start of a data
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
     *              "href" => "http://api.chateagratis.net/api/albums/1/photos",
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
     *                      "href" => "http://api.chateagratis.net/api/photos/1",
     *                  ),
     *                  "creator" => array(
     *                      "href" => "http://api.chateagratis.net/api/users/1",
     *                  ),
     *                  "path" => array(
     *                      "href" => "http://api.chateagratis.net/api/users/uploads/photo1-path.jpg",
     *                      "type" => "image/*",
     *                  ),
     *                  "album" => array(
     *                      "href" => "http://api.chateagratis.net/api/users/1/albums/1",
     *                  ),
     *              ),
     *          ),
     *      )
     * );
     *
     */
    public function showPhotoAlbum ($album_id, $limit = 1, $offset = 0);

    /**
     * Show user blocked by ID user
     *
     * @param number $user_id User to retrieve by ID
     *
     * @param int $limit      number of items to retrieve at most
     *
     * @param int $offset     The distance (displacement) from the start of a data
     *
     * @return array|string Associative array with all users blocked | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Show all users blocked, only first
     *
     *      $your_api_instance->showUsersBlocked(1);
     *
     *  array(
     *       "total" => 2,
     *       "limit" => 1,
     *       "offset" => 0,
     *       "_links" => array(
     *          "self" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/users",
     *          )
     *       ),
     *       "resources" => array(
     *          array(
     *              "id" => 2,
     *              "username" => "alex2",
     *              "email" => "alex2@chateagratis.net",
     *              "_links" => array(
     *                  "self" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/2",
     *                  ),
     *                  "channels" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/2/channels",
     *                  ),
     *                  "channels_fan" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/2/channelsFan",
     *                  ),
     *                  "blocked_users" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/2/blocked",
     *                  )
     *              )
     *          )
     *      )
     * );
     */
    public function showUsersBlocked ($user_id, $limit = 1, $offset = 0);

    /**
     * Update user  profile
     *
     * @param number $user_id           User to retrieve by ID
     *
     * @param string $about             Short description  your profile
     *
     * @param string $sexualOrientation Choice between <heterosexual|bisexual|otro>
     *
     * @return array|string Associative array with profile data | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Update profile an user
     *
     *      $your_api_instance->updateUserProfile(1,'about-103156','homosexual'));
     *
     *  array(
     *       "id" => 11,
     *       "about" => "about-10",
     *       "sexual_orientation" => "homosexual",
     *       "count_visits" => 0,
     *       "publicated_at" => "2013-10-30T16:43:51+0100",
     *  );
     *
     *
     */
    public function updateUserProfile ($user_id, $about = '', $sexualOrientation = '');

    /**
     * Delete one album
     *
     * @param number $user_id  The user id to retrieve data
     *
     * @param number $album_id The album id to retrieve data
     *
     * @return string Message sucessfully if can delete the album | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Delete an abum 1 to user 1
     *
     *      $your_api_instance->delAlbum(1,1);
     *
     *      // ouput message
     *      Album deleted
     */
    public function delAlbum ($user_id, $album_id);

    /**
     * Show all thread that you sent
     *
     * @param number $user_id The user id see her/his sent messages
     *
     * @return array|string Associative array with the subject | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Show messages sent
     *
     *      $your_api_instance->showThreadsSent(1);
     *
     * array(
     *      array(
     *          "id" => 1,
     *          "subject" => "thread sent 1",
     *      ),
     *      array(
     *      "id" => 4,
     *      "subject" => "thread sent 2",
     *      )
     *  );
     */
    public function showThreadsSent ($user_id);

    /**
     * Make channel's  fan one user
     *
     * @param number $channel_id Channel id to receive a fan
     *
     * @param number $user_id    The user id will be fan
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
    public function addUserChannelFan ($channel_id, $user_id);

    /**
     * Show all votes of one user
     *
     * @param number $user_id The user id to retrieve data
     *
     * @param int $limit      number of items to retrieve at most
     *
     * @param int $offset     The distance (displacement) from the start of a data
     *
     * @return array|string Associative array with all votes one user | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example Show votes User id 1 had put to all photos, only first voto
     *
     *      $your_api_instance->showUserPhotoVotes(1,1,0);
     *
     *  array(
     *      "total" => 3,
     *      "limit" => 1,
     *      "offset" => 0,
     *      "_links" => array(
     *          "self" => array(
     *              "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/votes",
     *          )
     *      ),
     *      "resources" => array(
     *          array(
     *              "score" => 3,
     *              "photo" => array(
     *                  "id" => 2,
     *                  "number_votes" => 25,
     *                  "score" => 25,
     *              ),
     *              "_links" => array(
     *                  "self" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/1/photos/2/votes",
     *                  ),
     *                  "photo" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/photos/2",
     *                  ),
     *                  "participant" => array(
     *                      "href" => "http://api.chateagratis.local/app_dev.php/api/users/1",
     *                  )
     *              )
     *          )
     *      )
     * );
     */
    public function showUserPhotoVotes ($user_id, $limit = 1, $offset = 0);
}