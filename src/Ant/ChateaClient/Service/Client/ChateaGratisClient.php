<?php
/**
 * Created by Javier Fernández Rodríguez
 * User: jjbier@gmail.com
 * Date: 9/10/13
 * Time: 9:49
 * To change this template use File | Settings | File Templates.
 */
namespace Ant\ChateaClient\Service\Client;

use Guzzle\Common\Collection;
use Ant\Guzzle\Plugin\OAuth2Plugin;
use Ant\Guzzle\Plugin\AcceptHeaderPluging;

/**
 * A ChateaGratis API client
 */
class ChateaGratisClient extends Client
{
    /**
     * @var \Ant\Guzzle\Plugin\OAuth2Plugin
     */
    private $_OAuth2Plugin;

    public static function factory($config = array())
    {
        // Provide a hash of default client configuration options
        $default = array(
                'base_url'=>'{scheme}://{subdomain}.chateagratis.local',
                'token_format'=>'Bearer',
                'Accept'=>'application/json',
                'environment'=>'prod',
                'scheme' => 'https',
                'version'=>'',
                'subdomain'=>'api',
                'service-description-name' => Client::NAME_SERVICE_API
                );

        $required = array(
            'base_url',
            'scheme',
            'subdomain',
            'token_format',
            'access_token',
            'Accept',
            'environment',
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


        $client->_OAuth2Plugin = new OAuth2Plugin($config->toArray());

        // Ensure that the Oauth2Plugin is attached to the client
        $client->addSubscriber($client->_OAuth2Plugin);

        $client->addSubscriber(new AcceptHeaderPluging($config->toArray()));

        return $client;
    }

    public function updateAccessToken($acces_token)
    {
        $this->_OAuth2Plugin->updateAccessToken($acces_token);
    }
}