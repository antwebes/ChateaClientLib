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

class ChateaGratisAppClient extends Client
{
    public static function factory($config = array()){
        // Provide a hash of default client configuration options
        $default = array(
            'base_url'=>'{scheme}://{subdomain}.chateagratis.local',
            'Accept'=>'application/json',
            'environment'=>'prod',
            'scheme' => 'https',
            'version'=>'',
            'subdomain'=>'api',
        );

        $required = array(
            'base_url',
            'scheme',
            'subdomain',
            'client_id',
            'secret',
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


        $clientAuth = ChateaOAuth2Client::factory(array('environment'=>$config['environment']));
        $authApp = new Authentication($clientAuth,$config['client_id'],$config['secret']);

        $json_data = $authApp->withClientCredentials();
        ldd($json_data);

        return $clientAuth;
    }
}