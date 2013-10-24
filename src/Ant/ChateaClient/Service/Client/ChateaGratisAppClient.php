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
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\FileCookieJar;
use  Guzzle\Common\Event;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Service\Description\ServiceDescription;
use Guzzle\Service\Command\CommandInterface;
use Ant\Guzzle\Plugin\OAuth2Plugin;
use Ant\Guzzle\Plugin\AcceptHeaderPluging;

class ChateaGratisAppClient extends Client
{

    private $access_token;
    const  COOKIE_NAME = 'chat_client';

    public static function factory($config = array()){
        // Provide a hash of default client configuration options
        $default = array(
            'base_url'=>'{scheme}://{subdomain}.chateagratis.local',
            'Accept'=>'application/json',
            'environment'=>'prod',
            'scheme' => 'https',
            'version'=>'',
            'subdomain'=>'api',
            'service-description-name' => Client::NAME_SERVICE_API
        );

        $required = array(
            'base_url',
            'Accept',
            'scheme',
            'environment',
            'subdomain',
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

        // Create a new ChateaGratis client
        $client = new self($config->get('base_url'),
            $config->get('scheme'),
            $config->get('subdomain'),
            $config
        );

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
        $access_token = null;

        if(!isset($_SESSION['chatea_client_token'])){

            $authData = ChateaOAuth2Client::factory(array('environment'=>$this->getConfig('environment'),'client_id'=>$this->getConfig('client_id'),'secret'=>$this->getConfig('secret')))->withClientCredentials();

            $_SESSION['chatea_client_token'] = $authData['access_token'];
            $_SESSION['chatea_client_time'] = $authData['expires_in'] + time();
            $_SESSION['chatea_client_refresh'] = $authData['refresh_token'];

            $access_token = $_SESSION['chatea_client_token'];

        }else if($_SESSION['chatea_client_time'] < time()){

            $authData = ChateaOAuth2Client::factory(array('environment'=>$this->getConfig('environment'),'client_id'=>$this->getConfig('client_id'),'secret'=>$this->getConfig('secret')))->withRefreshToken($_SESSION['chatea_client_refresh']);

            $_SESSION['chatea_client_token'] = $authData['access_token'];
            $_SESSION['chatea_client_time'] = $authData['expires_in'] + time();
            $_SESSION['chatea_client_refresh'] = $authData['refresh_token'];

            $access_token = $_SESSION['chatea_client_token'];

        }else{
            $access_token = $_SESSION['chatea_client_token'];
        }

        return $access_token;
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