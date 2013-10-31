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

use Ant\ChateaClient\Service\Client\ChateaOAuth2Client;
use Ant\ChateaClient\Service\Client\AuthenticationException;

/**
 * Specifies a client that provides user authentication for ApiChateaClient sites that use OAuth2-based authentication.
 *
 * @author Xabier Fernández Rodríguez in Ant-Web S.L.
 *
 * @package Ant\ChateaClient\Client
 *
 * @see  ChateaOAuth2Client
 *
 * @see  AuthenticationException
 */
class Authentication
{

    /**
     * The http client to connect with api server
     * @var \Ant\ChateaClient\Service\Client\ChateaOAuth2Client
     */
    private $client;

    /**
     * Build new class Authentication,  this provides user authentication for ApiChateaClient
     *
     * @param ChateaOAuth2Client $client The http client to know connect with api server
     */
    function __construct(ChateaOAuth2Client $client)
    {
        $this->client = $client;

    }

    /**
     * Get the http client for connect with api server
     *
     * @return \Ant\ChateaClient\Service\Client\ChateaOAuth2Client  The Http Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * The public key of client
     *
     * @return string The public key of client
     */
    public function getClientId()
    {
        return $this->client->getClientId();
    }

    /**
     * The private key of client
     *
     * @return string The private key of client
     */
    public function getSecret()
    {
        return $this->client->getSecret();
    }


    /**
     * Enables the user to get service credentials as well as service credential
     * authentication settings for use on the client side of communication.
     *
     * @param string $username The client name
     *
     * @param string $password The secret credentials of user
     *
     * @return array|string Associative array with client credentials | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws AuthenticationException This exception is thrown if you do not credentials or you cannot use this method
     *
     * @example Get client credentials
     *
     *      $authenticationInstande->withUserCredentials('username','password');
     *
     *  array("access_token"    => access-token-demo,
     *        "expires_in"      => 3600,
     *        "token_type"      => bearer,
     *        "scope"           => password,
     *        "refresh_token"   => refresh-token-demo
     *  );
     */
    public function withUserCredentials($username, $password)
    {
        return $this->getClient()->withUserCredentials($username, $password);

    }
    /**
     * Enables the user to get service credentials as well as service credential
     * authentication settings for use on the client side of communication.
     *
     * @param $auth_code The unique client code
     *
     * @param $redirect_uri The url to redirect after you obtain client credentials
     *
     * @return array|string Associative array with client credentials | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws AuthenticationException This exception is thrown if you do not credentials or you cannot use this method
     *
     * @example Get client credentials
     *
     *      $authenticationInstande->withAuthorizationCode('auth-code-demo','http://www.chateagratis.net');
     *
     *  array("access_token"    => access-token-demo,
     *        "expires_in"      => 3600,
     *        "token_type"      => bearer,
     *        "scope"           => password,
     *        "refresh_token"   => refresh-token-demo
     *  );
     */
    public function withAuthorizationCode($auth_code, $redirect_uri)
    {
        return $this->getClient()->withAuthorizationCode($auth_code, $redirect_uri);
    }

    /**
     * Enables the apps to get service credentials as well as service credential
     * authentication settings for use on the apps side of communication.
     *
     * @return array|string Associative array with client credentials | Message with error in json format
     *
     * @throws AuthenticationException This exception is thrown if you do not credentials or you cannot use this method
     *
     * @example Get client credentials
     *
     *      $authenticationInstande->withClientCredentials();
     *
     *  array("access_token"    => access-token-demo,
     *        "expires_in"      => 3600,
     *        "token_type"      => bearer,
     *        "scope"           => password,
     *        "refresh_token"   => refresh-token-demo
     *  );
     */
    public function withClientCredentials()
    {
        return $this->getClient()->withClientCredentials();
    }

    /**
     *  After the client has been authorized for access, they can use a refresh token to get a new access token.
     *
     * @param string $refresh_token The client refresh token that you obtain in first request of credentials.
     *
     * @return array|string Associative array with client credentials | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws AuthenticationException This exception is thrown if you do not credentials or you cannot use this method
     *
     * @example Get client credentials
     *
     *      $authenticationInstande->withRefreshToken('refresh-token-demo');
     *
     *  array("access_token"    => access-token-demo,
     *        "expires_in"      => 3600,
     *        "token_type"      => bearer,
     *        "scope"           => password,
     *        "refresh_token"   => refresh-token-demo
     *  );
     */
    public function withRefreshToken($refresh_token)
    {
        return $this->getClient()->withRefreshToken($refresh_token);
    }

    /**
     * Disable the service credentials as well as the session.
     *
     * @param string $access_token The toke to revoke
     *
     * @return string  Message sucessfully if can revoke token | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws AuthenticationException This exception is thrown if you do not credentials or you cannot use this method
     *
     * @example Delete client credentials
     *
     *      $authenticationInstande->revokeToken('access-token-demo');
     *
     *      //output
     *          Access token revoked
     */
    public function revokeToken($access_token)
    {
        return $this->getClient()->revokeToken($access_token);
    }
}