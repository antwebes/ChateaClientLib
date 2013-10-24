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
    private $access_token;
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
        $client->cookie = new Cookie(array(
            'name'        => 'ChateaGratis',
            'secure'      => true,
            'data'          => array()
        ));

        $cookieJar = new ArrayCookieJar();
        $cookieJar->add($client->cookie);
        $client->addSubscriber(new AcceptHeaderPluging($config->toArray()));
        $client->addSubscriber(new CookiePlugin($cookieJar));


        $authClient = ChateaOAuth2Client::factory(
            array('environment'=>$config->get('environment'),
                'client_id'=>$config->get('client_id'),
                'secret'=>$config-> get('secret')
            )
        );

        // primeira autenticacion
        if(!$client->cookie->getAttribute('chatea_client_expires_at')) {
            $auth_data = $authClient->withClientCredentials();
            $config->set('access_token',$auth_data['access_token']);
            $client->_OAuth2Plugin = new OAuth2Plugin($config->toArray());
            $client->addSubscriber($client->_OAuth2Plugin);


            $client->cookie->setAttribute('chatea_client_access_token', $auth_data['access_token']);
            $client->cookie->setAttribute('chatea_client_refresh_token', $auth_data['refresh_token']);
            $client->cookie->setAttribute('chatea_client_expires_at', $auth_data['expires_in']);
            $client->cookie->setAttribute('chatea_client_access_token', $auth_data['access_token']);
            $client->access_token = $auth_data['access_token'];

        }else if($client->cookie->getAttribute('chatea_client_expires_at') > time()){
            //TODO: the acces_token timeout , new acces_toekn with refresh_token
            $auth_data =  $authClient->withRefreshToken($_SESSION['chatea_client_refresh_token']);
            $client->access_token = $auth_data['access_token'];
            $client->updateAccessToken($auth_data['access_token']);

            $client->cookie->setAttribute('chatea_client_access_token', $auth_data['access_token']);
            $client->cookie->setAttribute('chatea_client_refresh_token', $auth_data['refresh_token']);
            $client->cookie->setAttribute('chatea_client_expires_at', $auth_data['expires_in']);
            $client->cookie->setAttribute('chatea_client_access_token', $auth_data['access_token']);



        }else{
            //set in header access_token of session
            ldd("set in header access_token of session");
        }

        return $client;
    }

    public function updateAccessToken($acces_token)
    {
        $this->_OAuth2Plugin->updateAccessToken($acces_token);
    }

}