<?php

namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\Service\Client\ClientInterface;

interface ApiInterface
{
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
    public function logout();
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
     */
    public function register($username, $email, $new_password, $repeat_new_password, $affiliate_host);

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
     */
    public function forgotPassword($username_or_email);


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
     */
    public function showChannels($limit = 25, $offset = 0, array $filter = null);

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
     */
    public function addChanel($name, $title = '', $description = '', $channel_type = '');

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
     */
    public function updateChannel($channel_id, $name, $title = '', $description = '', $channel_type = '');

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
    public function delChannel($channel_id);

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
     */
    public function showChannel($channel_id);

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
     */
    public function showChannelFans($channel_id, $limit = 1, $offset = 0);

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
     */
    public function showChannelsTypes($limit = 1, $offset = 0);
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
     */
    public function showUserChannels($user_id, $limit= 1, $offset = 0);

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
     */
    public function showUserChannelsFan($user_id, $limit= 1, $offset = 0);

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
     */
    public function addUserChannelFan($channel_id, $user_id);
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
     */
    public function delUserChannelFan($channel_id, $user_id);



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
     */
    public function showFriends($user_id, $limit = 1, $offset = 0);

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
     */

    public function addFriends($user_id, $friend_id);

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
     */
    public function showFriendshipsPending($user_id, $limit = 1, $offset = 0);

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
     */
    public function showFriendshipsRequest($user_id, $limit = 1, $offset = 0);


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
     */
    public function addFriendshipRequest($user_id, $user_accept_id);

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
     */
    public function delFriendshipRequest($user_id, $user_decline_id);

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
     */
    public function delFriend($user_id, $user_delete_id);

    /******************************************************************************/
    /*				  				  CHANNEL ME            					  */
    /******************************************************************************/

    /**
     * Get my user
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     */
    public function me();
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
     */
    public function updateMe($username, $email, $current_password);
    /**
     * Delete my user
     *
     * @return string Message deleted user | Message with error in json format
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     */
    public function delMe();

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
     */
    public function changePassword($current_password,$new_password,$repeat_new_password);


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
     */
    public function showPhotoAlbum($album_id, $limit = 1, $offset = 0);

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
     */
    public function showPhoto($photo_id);
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
     */
    public function addReportPhoto($photo_id, $reason);

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
     */
    public function delPhoto($photo_id);

    /**
     * Add new Photo Album in user accont
     *
     * @param $user_id The user ID
     *
     * @param $title One name for the Album
     *
     * @param string $description A short description of type photos
     *
     * @return array|string Associative array with new album data | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     */
    public function addAlbum($user_id, $title, $description='');


    /**
     * upload new photo at server api
     *
     * @param number $user_id The user owner of photo
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
     */
    public function addPhoto($user_id, $imageFile, $imageTile = '');

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
     */
    public function showUserPhotos($user_id, $limit = 1, $offset = 0);

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
     */
    public function showPhotoVotes($user_id, $photo_id);

    /**
     * Add one vote at photo
     *
     * @param number $user_id  The user id to retrieve data
     *
     * @param number $photo_id The photo id to retrieve data
     *
     * @param int $score The score photo,  The score ha to be between one and ten
     *
     * @return array|string Associative array with you vote | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     */
    public function addPhotoVote($user_id, $photo_id, $score = 1);

    /**
     * Show all votes of one user
     *
     * @param $user_id The user id to retrieve data
     *
     * @param int $limit  number of items to retrieve at most
     *
     * @param int $offset The distance (displacement) from the start of a data
     *
     * @return array|string Associative array with all votes one user | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     */

    public function showUserPhotoVotes($user_id, $limit = 1, $offset = 0);

    /**
     * Delete one vote of one photo
     *
     * @param $user_id The user id to delete data
     *
     * @param $photo_id The phot id to delete vote
     *
     * @return string Message sucessfully if can delete the vote | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     */
    public function delPhotoVote($user_id, $photo_id);


    /**
     * Show all abums one user
     *
     * @param $user_id The user id to retrieve data
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
     */
    public function showAlbums($user_id, $limit = 1, $offset = 0);

    /**
     * Show one album of user
     *
     * @param number $user_id The user id to retrieve data
     *
     * @param number $album_id The album id to retrieve data
     *
     * @return array|string Associative array with the album one user | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     */
    public function showAlbum($user_id, $album_id);

    /**
     * Delete one album
     *
     * @param number $user_id The user id to retrieve data
     *
     * @param number $album_id The album id to retrieve data
     *
     * @return string Message sucessfully if can delete the album | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     */
    public function delAlbum($user_id, $album_id);

    /**
     * Delete a photo of album
     *
     * @param number $user_id The user id to retrieve data
     *
     * @param number $photo_id The photo id to retrieve data
     *
     * @return string Message sucessfully if can delete photo of album | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     */
    public function delPhotoAlbum($user_id, $photo_id);

    /**
     * Insert a photo entity into album id
     *
     * @param number $user_id The user id to retrieve data
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
     */
    public function addAlbumPhoto($user_id, $photo_id, $album_id);

    /**********************************************************************************************************************/

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
     */
    public function showReports($limit = 1, $offset = 0);

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
     */
    public function showReport($report_id);

    /******************************************************************************/
    /*				  				  THREAD METHODS    	   					  */
    /******************************************************************************/

    /**
     * Create new thread
     *
     * @param number $user_id The user id that create the thread
     *
     * @param string $recipient_name The ID username that retrieve the message
     *
     * @param string $subject The short description of thread
     *
     * @param string $body The  long text of thread
     *
     * @return array|string Associative array with data thread | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     */
    public function addThread($user_id, $recipient_name, $subject, $body);

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
     */
    public function showThreadsInbox($user_id);

    /**
     * @param number $user_id The user id see her/his sent messages
     *
     * @return array|string Associative array with the subject | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     */
    public function showThreadsSent($user_id);

    /**
     * List all message one thread
     *
     * @param number $user_id The user id that create the thread
     *
     * @param number $thread_id The ID of thread
     *
     * @return array|string Associative array with messages | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     */
    public function showThreadMessages($user_id, $thread_id);

    /**
     *
     * Add one message on thread
     *
     * @param number $user_id The user id that create the thread
     *
     * @param number $thread_id The ID of thread
     *
     * @param string $body the text of message
     *
     * @return array|string Associative array with messages | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     */
    public function addThreadMessage($user_id, $thread_id, $body);


    /**
     * Delete one thread ant all messages
     *
     * @param number $user_id The user id that create the thread
     *
     * @param number $thread_id The ID of thread
     *
     * @return string Message sucessfully if can delete thread | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     */
    public function delThread($user_id, $thread_id);

    /******************************************************************************/
    /*				  				  USERS METHODS       	   					  */
    /******************************************************************************/

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
     */
    public function showUsers($limit = 1, $offset = 0);

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
     */
    public function showUser($user_id);

    /**
     * Show user blocked by ID user
     *
     * @param number $user_id User to retrieve by ID
     *
     * @param int $limit  number of items to retrieve at most
     *
     * @param int $offset The distance (displacement) from the start of a data
     *
     * @return array|string Associative array with all users blocked | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     */
    public function showUsersBlocked($user_id, $limit = 1, $offset = 0);

    /**
     * Blocked one user
     *
     * @param number $user_id User to retrieve by ID
     *
     * @param number $user_blocked_id User to blocked by ID
     *
     * @return string Message sucessfully if can blocked an user | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     */
    public function addUserBlocked($user_id, $user_blocked_id);

    /**
     * Update user  profile
     *
     * @param number $user_id User to retrieve by ID
     *
     * @param string $about Short description  your profile
     *
     * @param string $sexualOrientation Choice between <heterosexual|bisexual|otro>
     *
     * @return array|string Associative array with profile data | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     */
    public function updateUserProfile($user_id, $about= '', $sexualOrientation = '');

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
     */
    public function showUserProfile($user_id);

    /**
     * Add new profile one user
     *
     * @param number $user_id User to retrieve by ID
     *
     * @param string $about Short description  your profile
     *
     * @param string $sexualOrientation Choice between <heterosexual|bisexual|otro>
     *
     * @return array|string Associative array with profile data | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     */
    public function addUserProfile($user_id, $about, $sexualOrientation);

    /**
     * Report a user
     *
     * @param number $user_reported_id The user id that is report
     *
     * @param string $reason Description for report the user
     *
     * @return array|string Associative array with report data | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     */
    public function addUserReports($user_reported_id, $reason);
    /**
     * UnBlocked one user
     *
     * @param number $user_id User to retrieve by ID
     *
     * @param number $user_blocked_id User to blocked by ID
     *
     * @return string Message sucessfully if can blocked an user | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     */
    public function delUserBlocked($user_id, $user_blocked_id);

    /**
     * Show count visits on one profile and who visits of one profile
     *
     * @param number $user_id User to retrieve by ID
     *
     * @param number $maxResult The number visit you can see
     *
     * @return array|string
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     */
    public function showUserVisitors($user_id, $maxResult = null);

    /**
     * Show a profile of an user
     *
     * @return array with you profile data
     *
     * @throws ApiException This exception is thrown if server send one error
     *
     * @example
     *
     */
    public function showAccount();
}
