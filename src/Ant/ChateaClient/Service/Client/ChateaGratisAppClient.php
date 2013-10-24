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
        $client->addSubscriber(new CookiePlugin(new ArrayCookieJar()));
        return $client;
    }
    public function prepareCommand(CommandInterface $command)
    {
        $request = parent::prepareCommand($command);
        if($request->getCookie(self::COOKIE_NAME)){

        }
        ldd($request->getCookie(self::COOKIE_NAME));
        return $request;
    }

    public function updateAccessToken($access_token)
    {
        $this->access_token = $access_token;
    }

}