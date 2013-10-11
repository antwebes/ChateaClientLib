<?php
/**
 * Created by Ant-WEB S.L.
 * User: Xabier Fernández Rodríguez <jjbier@gmail.com>
 * Date: 11/10/13
 * Time: 12:03
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\Service\Client\ChateaOAuth2Client;

class Authentication
{

    private $client;
    private $client_id;
    private $secret;
    function __construct(ChateaOAuth2Client $client, $client_id, $secret)
    {

        if (!$client || null == $client ) {
            throw new InvalidArgumentException("client must is not null");
        }

        if (!is_string($client_id) || 0 >= strlen($client_id)) {
            throw new InvalidArgumentException("client_id must be a non-empty string");
        }
        if (!is_string($secret) || 0 >= strlen($secret)) {
            throw new InvalidArgumentException("secret must be a non-empty string");
        }

        $this->client = $client;
        $this->client_id = $client_id;
        $this->secret = $secret;

    }

    /**
     * @return \Ant\ChateaClient\Service\Client\ChateaOAuth2Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param $username
     * @param $password
     * @throws InvalidArgumentException
     * @return \Guzzle\Service\Resource\Model
     */
    public function withUserCredentials($username, $password)
    {
        if (!is_string($username) || 0 >= strlen($username)) {
            throw new InvalidArgumentException("username must be a non-empty string");
        }
        if (!is_string($password) || 0 >= strlen($password)) {
            throw new InvalidArgumentException("password must be a non-empty string");
        }

        /* @var $command Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand('withUserCredentials',
            array('client_id'=>$this->client_id,'client_secret'=>$this->secret,'username'=>$username,'password'=>$password)
        );

        return $command->execute();

    }

    public function withAuthorizationCode($auth_code, $redirect_uri)
    {
        if (!is_string($auth_code) || 0 >= strlen($auth_code)) {
            throw new InvalidArgumentException("auth_code must be a non-empty string");
        }

        if (!is_string($redirect_uri) || 0 >= strlen($redirect_uri)) {
            throw new InvalidArgumentException("redirect_uri must be a non-empty string");
        }

        /* @var $command Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand('withAuthorizationCode',
            array('client_id'=>$this->client_id,'client_secret'=>$this->secret,'redirect_uri'=>$redirect_uri,'code'=>$auth_code)
        );

        return  $command->execute();
    }

    public function withClientCredentials()
    {
        /* @var $command Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand('withClientCredentials',
            array('client_id'=>$this->client_id,'client_secret'=>$this->secret)
        );

        return $command->execute();
    }

    public function withRefreshToken($refresh_token)
    {
        if (!is_string($refresh_token) || 0 >= strlen($refresh_token)) {
            throw new InvalidArgumentException("refresh_token must be a non-empty string");
        }

        /* @var $command Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand('withRefreshToken',
            array('client_id'=>$this->client_id,'client_secret'=>$this->secret,'refresh_token'=>$refresh_token)
        );

        return $command->execute();
    }
}