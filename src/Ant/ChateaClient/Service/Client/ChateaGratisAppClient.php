<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ant3
 * Date: 23/10/13
 * Time: 15:12
 * To change this template use File | Settings | File Templates.
 */

namespace Ant\ChateaClient\Service\Client;

use Guzzle\Common\Collection;
use Guzzle\Plugin\Cookie\Cookie;
use  Guzzle\Common\Event;
use Guzzle\Service\Description\ServiceDescription;
use Guzzle\Service\Command\CommandInterface;
use Ant\Guzzle\Plugin\AcceptHeaderPluging;

class ChateaGratisAppClient extends Client
{

    const  COOKIE_NAME = 'chat_client';
    /**@var StoreInterface store */
    private $store ;
    public static function factory($config = array()){
        // Provide a hash of default client configuration options
        $default = array(
            'base_url'=>'{scheme}://{subdomain}.chateagratis.local',
            'Accept'=>'application/json',
            'environment'=>'prod',
            'scheme' => 'https',
            'version'=>'',
            'subdomain'=>'api',
            'service-description-name' => Client::NAME_SERVICE_API,
            'store' => 'Ant\ChateaClient\Service\Client\FileStore'
        );

        $required = array(
            'base_url',
            'Accept',
            'scheme',
            'environment',
            'subdomain',
            'client_id',
            'secret',
            'store'
        );

        // Merge in default settings and validate the config
        $config = Collection::fromConfig($config, $default, $required);

        if($config['environment'] == 'dev' ){

            $config['base_url'] = $config['base_url'] . '/app_dev.php';
            $config['scheme'] = 'http';
            $config['ssl.certificate_authority'] = 'system';
            $config['curl.options'] = array(CURLOPT_SSL_VERIFYHOST=>false,CURLOPT_SSL_VERIFYPEER=>false);
        }

        // Create a new ChateaGratis client
        $client = new self($config->get('base_url'),
            $config->get('scheme'),
            $config->get('subdomain'),
            $config
        );

        $store = $config->get('store');
        $client->store = new $store();
        $client->addSubscriber(new AcceptHeaderPluging($config->toArray()));

        return $client;
    }
    public function prepareCommand(CommandInterface $command)
    {
        $request = parent::prepareCommand($command);
        $request->setHeader('Authorization ','Bearer '. $this->prepareAccessToken());
        return $request;
    }

    public function updateAccessToken($access_token)
    {
        $this->getEventDispatcher()->addListener('request.before_send', function(Event $event) use($access_token){
            $request = $event['request'];
            $request->setHeader('Authorization ','Bearer '. $access_token);
        });
    }


    private function prepareAccessToken()
    {

        if(!$this->store->getPersistentData('token_expires_at')){

            $authData = ChateaOAuth2Client::factory(array('environment'=>$this->getConfig('environment'),'client_id'=>$this->getConfig('client_id'),'secret'=>$this->getConfig('secret')))->withClientCredentials();
            $this->store->setPersistentData('access_token',$authData['access_token']);
            $this->store->setPersistentData('token_refresh',$authData['refresh_token']);
            $this->store->setPersistentData('token_expires_at',$authData['expires_in'] + time());

            return $authData['access_token'];

        }else if($this->store->getPersistentData('token_expires_at') < time()){

            $authData = ChateaOAuth2Client::factory(array('environment'=>$this->getConfig('environment'),'client_id'=>$this->getConfig('client_id'),'secret'=>$this->getConfig('secret')))->withRefreshToken($_SESSION['chatea_client_refresh']);

            $this->store->setPersistentData('access_token',$authData['access_token']);
            $this->store->setPersistentData('token_refresh',$authData['refresh_token']);
            $this->store->setPersistentData('token_expires_at',$authData['expires_in'] + time());

            return $authData['access_token'];

        }else{
            return $this->store->getPersistentData('access_token');
        }
    }
    public function revokeToken()
    {
        $command = $this->getCommand('RevokeToken');
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