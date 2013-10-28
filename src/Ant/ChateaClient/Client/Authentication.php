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
use Ant\ChateaClient\Service\Client\AuthenticationException;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\CurlException;

class Authentication
{

    private $client;

    function __construct(ChateaOAuth2Client $client)
    {
        $this->client = $client;

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
        return $this->client->getClientId();
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->client->getSecret();
    }


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
            array('client_id'=>$this->getClientId(),'client_secret'=>$this->getSecret(),'username'=>$username,'password'=>$password)
        );

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch(ClientErrorResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch(CurlException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }

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
            array('client_id'=>$this->getClientId(),'client_secret'=>$this->getSecret(),'redirect_uri'=>$redirect_uri,'code'=>$auth_code)
        );

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch(ClientErrorResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch(CurlException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }
    }

    public function withClientCredentials()
    {
        /* @var $command Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand('withClientCredentials',
            array('client_id'=>$this->getClientId(),'client_secret'=>$this->getSecret())
        );

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch(ClientErrorResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch(CurlException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }
    }

    public function withRefreshToken($refresh_token)
    {
        if (!is_string($refresh_token) || 0 >= strlen($refresh_token)) {
            throw new InvalidArgumentException("refresh_token must be a non-empty string");
        }

        /* @var $command Guzzle\Service\Command\AbstractCommand */
        $command = $this->client->getCommand('withRefreshToken',
            array('client_id'=>$this->getClientId(),'client_secret'=>$this->getSecret(),'refresh_token'=>$refresh_token)
        );

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch(ClientErrorResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch(CurlException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }
    }

    public function revokeToken()
    {
        $command = $this->client->getCommand('RevokeToken');
        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch(ClientErrorResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch(CurlException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }
    }
}