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


        $clientAuth = ChateaOAuth2Client::factory(array('environment'=>$config['environment']));
        self::$authentication = new Authentication($clientAuth,$config['client_id'],$config['secret']);
        $auth_data = self::$authentication->withClientCredentials();

        // Create a new ChateaGratis client
        $client = new self($config->get('base_url'),
            $config->get('scheme'),
            $config->get('subdomain'),
            $config
        );


        $client->_OAuth2Plugin = new OAuth2Plugin($config->toArray());
        $client->refresh_token = $auth_data['refresh_token'];
        $client->expires_at = $auth_data['expires_in']+time();

        // Ensure that the Oauth2Plugin is attached to the client
        $client->addSubscriber($client->_OAuth2Plugin);

        $client->addSubscriber(new AcceptHeaderPluging($config->toArray()));

        return $client;
    }

    public function execute($command)
    {

      if($this->expires_at > time()){
          $auth_data = self::$authentication->withRefreshToken($this->refresh_token);
          $this->refresh_token = $auth_data['refresh_token'];
          $this->expires_at = $auth_data['expires_in']+time();
          $this->updateAccessToken($auth_data['acces_token']);
      }

      parent::execute($command);
    }

    protected  function updateAccessToken($acces_token)
    {
        $this->_OAuth2Plugin->updateAccessToken($acces_token);
    }


}