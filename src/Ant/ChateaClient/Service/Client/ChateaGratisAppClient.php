<?php
/**
 * Created by Ant-WEB S.L.
 * Developer: Xabier Fernández Rodríguez <jjbier@gmail.com>
 * Date: 14/10/13
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ant\ChateaClient\Service\Client;

use Guzzle\Common\Collection;
use Guzzle\Common\Event;
use Guzzle\Http\Exception\ServerErrorResponseException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\CurlException;
use Guzzle\Service\Command\CommandInterface;
use Ant\Guzzle\Plugin\AcceptHeaderPluging;
use Ant\ChateaClient\Service\Client\FileStore;
use Ant\ChateaClient\Service\Client\AuthenticationException;
use Ant\ChateaClient\Client\Exception\UnauthorizedException;

/**
 * Specifies a client that provides service to run command at api server
 * This client can get self credentials, and save in store. This version do not encrypted credentials.
 *
 * @package Ant\ChateaClient\Service\Client
 *
 * @see Client
 * @see Collection
 * @see Event
 * @see CommandInterface
 * @see AcceptHeaderPluging
 */
class ChateaGratisAppClient extends Client
{
    /**
     * @var StoreInterface this save data on store
     */
    private $store ;

    /**
     * @var ChateaOAuth2Client client to authenticate
     */
    private $chateaOAuth2Client;

    /**
     * @var should the client authenticate as guest or with client credentials
     */
    private $authenticateAsGuest;

    /**
     * Build new class ChateaOAuth2Client, this provides commands to run at ApiChateaServer
     * This client can get self credentials, and save in store. This version do not encrypted credentials.
     *
     * @param array $config Associative array can configure the client. The parameters are:
     *                      client_id   The public key of client. This parameter is required
     *                      secret      The private key of client. This parameter is required
     *                      base_url    The server endpoind url. This parameter is optional
     *                      Accept      The accept header, default value is json. This parameter is optional
     *                      environment Set mode production [prod] or developing [dev] default value is prod. This parameter is optional
     *                      scheme      Set server schema communication [http|https] for default https. This parameter is optional
     *                      subdomain   Set server subdomain if this exist. For default is api. This parameter is optional
     *                      store       Set where save server credentials
     *
     * @return ChateaGratisAppClient|\Guzzle\Service\Client
     */
    public static function factory($config = array()){
        // Provide a hash of default client configuration options
        $default = array(
            'Accept'=>'application/json',
            'environment'=>'prod',
            'service-description-name' => Client::NAME_SERVICE_API,
            'store' => new FileStore(),
            'ssl'=>false
        );

        $required = array(
            'base_url',
            'client_id',
            'secret',
        );

        // Merge in default settings and validate the config
        $config = Collection::fromConfig($config, $default, $required);

        if($config['environment'] == 'dev' && $config['ssl'] ==  false ){
            $config['ssl.certificate_authority'] = 'system';
            $config['curl.options'] = array(CURLOPT_SSL_VERIFYHOST=>false,CURLOPT_SSL_VERIFYPEER=>false);
        }

        // Create a new ChateaGratis client
        $client = new self($config->get('base_url'),$config);

        $client->authenticateAsGuest = isset($config['as_guest']) ? $config['as_guest'] : false;
        $client->chateaOAuth2Client = $config->get('OAuth2Client');
        $client->store = $config->get('store');
        $client->addSubscriber(new AcceptHeaderPluging($config->toArray()));
        return $client;
    }
    /**
     * Prepare a command for sending and get the RequestInterface object created by the command
     *
     * @param CommandInterface $command Command to prepare
     *
     * @return RequestInterface
     */
    public function prepareCommand(CommandInterface $command)
    {
        $request = parent::prepareCommand($command);
        $request->setHeader('Authorization ','Bearer '. $this->prepareAccessToken());
        return $request;
    }

    /**
     * Update the access token on header.
     *
     * @param string $access_token The token you put in header
     */
    public function updateAccessToken($access_token)
    {
        $this->getEventDispatcher()->addListener('request.before_send', function(Event $event) use($access_token){
            $request = $event['request'];
            $request->setHeader('Authorization ','Bearer '. $access_token);
        });
    }

    /**
     * This retrieve the access token in store or in server
     *
     * @return string the access token
     */
    private function prepareAccessToken()
    {
        // if not exits data in the store
        if(!$this->store->getPersistentData('token_expires_at')){
            return $this->getAccessTokenWithClientCredentials();
         // if access token expires.
        }else if($this->store->getPersistentData('token_expires_at') < time()){
            try{
                return $this->getAccessTokenWithRefreshToken();    
            }catch(AuthenticationException $e){
                return $this->getAccessTokenWithClientCredentials();
            }
        }else{
            return $this->store->getPersistentData('access_token');
        }
    }

    /**
     * Disable the service credentials as well as the session.
     *
     * @return string  Message sucessfully if can revoke token | Message with error in json format
     *
     * @throws AuthenticationException This exception is thrown if you do not credentials or you cannot use this method
     */
    public function revokeToken()
    {
        $command = $this->getCommand('RevokeToken');
        $this->store->clearAllPersistentData();
        try{
            return $command->execute();
        }catch (ServerErrorResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch (BadResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch(ClientErrorResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch(CurlException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }
    }

    private function getAccessTokenWithClientCredentials()
    {
        if($this->authenticateAsGuest){
            $authData = $this->chateaOAuth2Client->withGuestCredentials();
        }else{
            $authData = $this->chateaOAuth2Client->withClientCredentials();
        }

        $this->persistAuthData($authData);

        return $authData['access_token'];
    }

    private function getAccessTokenWithRefreshToken()
    {
        $authData = $this->chateaOAuth2Client->withRefreshToken($this->store->getPersistentData('token_refresh'));

        $this->persistAuthData($authData);

        return $authData['access_token'];
    }

    private function persistAuthData($authData)
    {
        $this->store->setPersistentData('access_token',$authData['access_token']);
        $this->store->setPersistentData('token_refresh',$authData['refresh_token']);
        $this->store->setPersistentData('token_expires_at',$authData['expires_in'] + time());
    }

//    public function execute($command)
//    {
//        try{
//            return parent::execute($command);
//
//        }catch (ClientErrorResponseException $ex){
//            if($ex->getResponse()->getStatusCode() == '401'){
//                try{
//                    //pido una vez mas el token y si no exception
//                    $this->store->clearAllPersistentData();
//                    $this->getAccessTokenWithClientCredentials();
//                }catch (\Exception $e){
//
//                    throw $e;
//                }
//            }else{
//                throw $ex;
//            }
//        }catch (\Exception $e){
//            throw $e;
//        }
//
//    }
}
