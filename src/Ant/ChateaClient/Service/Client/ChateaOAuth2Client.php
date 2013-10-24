<?php
/**
 * Created by JetBrains PhpStorm.
 * User: xabier
 * Date: 10/10/13
 * Time: 23:31
 * To change this template use File | Settings | File Templates.
 */

namespace Ant\ChateaClient\Service\Client;

use Guzzle\Common\Collection;
use Guzzle\Service\Description\ServiceDescription;
use Ant\Guzzle\Plugin\AcceptHeaderPluging;

class ChateaOAuth2Client extends Client
{
    private $client;
    private $client_id;
    private $secret;

    public static function factory($config = array())
    {
        // Provide a hash of default client configuration options
        $default = array(
            'base_url'=>'{scheme}://{subdomain}.chateagratis.local',
            'Accept'=>'application/json',
            'environment'=>'prod',
            'scheme' => 'https',
            'version'=>'',
            'subdomain'=>'api',
            'service-description-name' => Client::NAME_SERVICE_AUTH
        );
        $required = array(
            'base_url',
            'scheme',
            'subdomain',
            'Accept',
            'environment',
            'client_id',
            'secret'
        );

        // Merge in default settings and validate the config
        $config = Collection::fromConfig($config, $default, $required);


        if($config['environment'] == 'dev' ){

            $config['base_url'] = $config['base_url'] . '/app_dev.php';
            $config['scheme'] = 'http';
            $config['ssl.certificate_authority'] = 'system';
            $config['curl.options'] = array(CURLOPT_SSL_VERIFYHOST=>false,CURLOPT_SSL_VERIFYPEER=>false);
        }


        // Create a new ChateaOAuth2 client
        $client = new self($config->get('base_url'),
            $config->get('scheme'),
            $config->get('subdomain'),
            $config
        );
        $client->addSubscriber(new AcceptHeaderPluging($config->toArray()));

        return $client;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->getConfig('client_id');
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->getConfig('secret');
    }


    public function withUserCredentials($username, $password)
    {
        if (!is_string($username) || 0 >= strlen($username)) {
            throw new InvalidArgumentException("username must be a non-empty string");
        }
        if (!is_string($password) || 0 >= strlen($password)) {
            throw new InvalidArgumentException("password must be a non-empty string");
        }

        $command = $this->getCommand('withUserCredentials',
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

        $command = $this->getCommand('withAuthorizationCode',
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

        $command = $this->getCommand('withClientCredentials',
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

        $command = $this->getCommand('withRefreshToken',
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
}