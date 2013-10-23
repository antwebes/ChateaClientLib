<?php
/**
 * Created by Ant-WEB S.L.
 * User: Xabier Fernández Rodríguez <jjbier@gmail.com>
 * Date: 16/10/13
 * Time: 11:17
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ant\ChateaClient\Service\Client;

use Exception;
use Guzzle\Service\Client as BaseClient;
use Guzzle\Service\Description\ServiceDescription;

class Client extends BaseClient
{
    const NAME_SERVICE_API  = 'api-services';
    const NAME_SERVICE_AUTH = 'api-auth-services';

    protected $scheme,
              $subdomain,
              $version;

    /**
     * Client constructor
     *
     * @param string $baseUrl Base URL of the web service
     * @param string $scheme API scheme (aka. http/https), defaults: "https"
     * @param string $subdomain API subdomain endpoint, defaults: "api"
     * @param array|Collection $config  Configuration settings
     */
    public function __construct($baseUrl, $scheme, $subdomain, $config = null)
    {

        $this->scheme = $scheme;
        $this->subdomain = $subdomain;
        $this->version = array_key_exists('version',$config)?$config['version']:'';

        $baseUrl = $this->parseUrl($baseUrl,array($scheme,$subdomain));
        if (is_string($this->version) && 0 >= strlen($this->version)) {
            $baseUrl.='/'.$this->version.'/';
        }
        parent::__construct($baseUrl, $config);


        if($config['service-description-name']){

            $this->setDescription(ServiceDescription::factory(__DIR__.'/descriptions/'.$config['service-description-name'].'.json'));
        }

    }

    private function parseUrl($uri, array $params = null)
    {
        if (empty($params)) {
            return $uri;
        }

        $pattern = '/{\w+}/';

        if (is_array($params)) {
            $leng = count($params);

            if (preg_match_all('/({\w+})/', $uri) !== $leng) {
                throw new Exception('Client::parseRouting() ERROR: The params number does not match with the path');
            }
            $pattern = array_fill(0, $leng, "/{\w+}/");
        }

        return preg_replace($pattern, $params, $uri, 1);
    }
}