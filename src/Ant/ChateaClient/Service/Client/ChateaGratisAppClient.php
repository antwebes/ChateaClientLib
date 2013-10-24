<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ant3
 * Date: 23/10/13
 * Time: 15:12
 * To change this template use File | Settings | File Templates.
 */

namespace Ant\ChateaClient\Service\Client;

use Ant\ChateaClient\Client\Authentication;
use Guzzle\Common\Collection;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\FileCookieJar;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Service\Description\ServiceDescription;
use Ant\Guzzle\Plugin\OAuth2Plugin;
use Ant\Guzzle\Plugin\AcceptHeaderPluging;

class ChateaGratisAppClient extends Client
{
    /**
     * @var \Ant\Guzzle\Plugin\OAuth2Plugin
     */
    private $_OAuth2Plugin;

    private $refresh_token;
    private $expires_at;
    private static $authentication;
    private $cookie;

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

        // primeira autenticacion
        if(!isset( $_SESSION['chatea_client_expires_at'])) {

            //auth in server
            $authClient = ChateaOAuth2Client::factory($config->toArray());
            ldd($authClient);
            $auth_data = $authClient->withClientCredentials();

            $config->set('access_token',$auth_data['access_token']);
            $client->_OAuth2Plugin = new OAuth2Plugin($config->toArray());
            $client->addSubscriber($client->_OAuth2Plugin);

            $_SESSION['chatea_client_access_token'] = $auth_data['access_token'];
            $_SESSION['chatea_client_refresh_token'] = $auth_data['refresh_token'];
            $_SESSION['chatea_client_expires_at'] = $auth_data['expires_in']+time();
        }else if($_SESSION['chatea_client_expires_at'] > time()){
            //TODO: the acces_token timeout , new acces_toekn with refresh_token


            $clientAuth = ChateaOAuth2Client::factory($config->toArray());
            $auth_data =  $clientAuth->withRefreshToken($_SESSION['chatea_client_refresh_token']);
            $client->updateAccessToken($auth_data['access_token']);
            $_SESSION['chatea_client_access_token'] = $auth_data['access_token'];
            $_SESSION['chatea_client_refresh_token'] = $auth_data['refresh_token'];
            $_SESSION['chatea_client_expires_at'] = $auth_data['expires_in']+time();

        }else{
            //set in header access_token of session
            ldd("set in header access_token of session");
        }

        return $client;
    }

    /*protected function prepareCommand(CommandInterface $command)
    {

      if($this->expires_at > time()){
          $auth_data = self::$authentication->withRefreshToken($this->refresh_token);
          $this->refresh_token = $auth_data['refresh_token'];
          $this->expires_at = $auth_data['expires_in']+time();
          $this->updateAccessToken($auth_data['acces_token']);
      }

      return parent::prepareCommand($command);
    }*/

    public function updateAccessToken($acces_token)
    {
        $this->_OAuth2Plugin->updateAccessToken($acces_token);
    }


}