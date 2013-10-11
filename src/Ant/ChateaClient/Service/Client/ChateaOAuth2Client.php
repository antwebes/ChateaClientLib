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
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;
use Ant\Guzzle\Plugin\QueryPluging;
use Ant\Guzzle\Plugin\AcceptHeaderPluging;

class ChateaOAuth2Client extends Client
{
    public static function factory($config = array())
    {
        // Provide a hash of default client configuration options
        $default = array('Accept'=>'application/json', 'environment'=>'prod');
        $required = array(
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
        $client->setDescription(ServiceDescription::factory(__DIR__.'/descriptions/api-auth-services.json'));

        $client->addSubscriber(new AcceptHeaderPluging($config->toArray()));
        return $client;
    }
}