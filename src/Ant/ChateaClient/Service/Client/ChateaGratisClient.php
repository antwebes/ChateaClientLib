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
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;
use Ant\Guzzle\Plugin\OAuth2Plugin;
use Ant\Guzzle\Plugin\AcceptHeaderPluging;
/**
 * A ChateaGratis API client
 */
class ChateaGratisClient extends Client
{

    public static function factory($config = array())
    {
        // Provide a hash of default client configuration options
        $default = array('base_url' => 'https://api.chateagratis.local/app_dev.php/','token_format'=>'Bearer','Accept'=>'application/json', 'environment'=>'prod');

        $required = array(
            'base_url',
            'token_format',
            'access_token',
            'Accept',
            'environment'
        );
        // use certificate of system and disable checks SSL
        if($config['environment'] == 'dev' ){
            $config['ssl.certificate_authority'] = 'system';
            $config['curl.options'] = array(CURLOPT_SSL_VERIFYHOST=>false,CURLOPT_SSL_VERIFYPEER=>false);
        }
        // Merge in default settings and validate the config
        $config = Collection::fromConfig($config, $default, $required);

        // Create a new ChateaGratis client
        $client = new self($config->get('base_url'), $config);


        // Set the service description
        $client->setDescription(ServiceDescription::factory(__DIR__.'/api-services.json'));

        // Ensure that the Oauth2Plugin is attached to the client
        $client->addSubscriber(new OAuth2Plugin($config->toArray()));
        $client->addSubscriber(new AcceptHeaderPluging($config->toArray()));
        return $client;
    }
}