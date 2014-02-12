<?php
/**
 * Created by Ant-WEB S.L.
 * Developer: Xabier Fernández Rodríguez <jjbier@gmail.com>
 * Date: 14/10/13
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ant\ChateaClient\Service\Client;

use Guzzle\Common\Collection;
use Ant\Guzzle\Plugin\OAuth2Plugin;
use Ant\Guzzle\Plugin\AcceptHeaderPluging;

/**
 * Specifies a basic client that provides service to run command at api server .
 *
 * This client self can not get  credentials. You have put in config parameter access_token.
 *
 * @package Ant\ChateaClient\Service\Client
 *
 * @see Client
 * @see OAuth2Plugin
 * @see AcceptHeaderPluging
 */
class ChateaGratisClient extends Client
{

    /**
     * Build new class ChateaGratisClient, this provides commands to run at ApiChateaServer
     *
     * @param array $config Associative array can configure the client. The parameters are:
     *                      access_token    The credentilas of client. This parameter is required
     *                      base_url        The server endpoind url. This parameter is optional
     *                      Accept          The accept header, default value is json. This parameter is optional
     *                      environment     Set mode production [prod] or developing [dev] default value is prod. This parameter is optional
     *                      scheme          Set server schema communication [http|https] for default https. This parameter is optional
     *                      subdomain       Set server subdomain if this exist. For default is api. This parameter is optional
     *
     * @return ChateaGratisAppClient|\Guzzle\Service\Client
     */
    public static function factory($config = array())
    {
        // Provide a hash of default client configuration options
        $default = array(
                'token_format'=>'Bearer',
                'Accept'=>'application/json',
                'environment'=>'prod',
                'service-description-name' => Client::NAME_SERVICE_API,
                'ssl'=>false
                );

        $required = array(
            'base_url',
            'token_format',
            'access_token',
            'Accept',
            'environment',
            'ssl'
        );

        // Merge in default settings and validate the config
        $config = Collection::fromConfig($config, $default, $required);

        if($config['environment'] == 'dev' && $config['ssl'] ==  false ){
            $config['ssl.certificate_authority'] = 'system';
            $config['curl.options'] = array(CURLOPT_SSL_VERIFYHOST=>false,CURLOPT_SSL_VERIFYPEER=>false);
        }

        // Create a new ChateaGratis client
        $client = new self($config->get('base_url'),$config);

        // Ensure that the Oauth2Plugin is attached to the client
        $client->addSubscriber(new OAuth2Plugin($config->toArray()));
        $client->addSubscriber(new AcceptHeaderPluging($config->toArray()));
        return $client;
    }

    /**
     * Update the access token on header.
     *
     * @param string $access_token The token you put in header
     */
    public function updateAccessToken($access_token)
    {
        $this->getEventDispatcher()->addListener('request.before_send', function(Event $event) use($access_token){
            $request = $event['request'];
            $request->setHeader('Authorization ','Bearer '. $access_token);
        });
    }
}