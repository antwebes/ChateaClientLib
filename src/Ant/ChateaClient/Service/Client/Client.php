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

use Guzzle\Service\Client as BaseClient;
use Guzzle\Service\Description\ServiceDescription;
use InvalidArgumentException;

/**
 * Client object for executing commands on a web service.
 *
 * @package Ant\ChateaClient\Service\Client
 *
 * @see InvalidArgumentException
 * @see Client
 * @see ServiceDescription
 */
class Client extends BaseClient
{
    const NAME_SERVICE_API  = 'api-services';
    const NAME_SERVICE_AUTH = 'api-auth-services';

    /**
     * API scheme (aka. http/https), defaults: "https"
     *
     * @var string The server schema
     */
    protected $scheme;
    /**
     * API subdomain endpoint, defaults: "api"
     *
     * @var string the subdominian
     */
    protected $subdomain;
    /**
     * The version server api.
     *
     * @var string The version server api.
     */
    protected $version;

    /**
     * Client constructor
     *
     * @param string $baseUrl Base URL of the web service
     *
     * @param string $scheme API scheme (aka. http/https), defaults: "https"
     *
     * @param string $subdomain API subdomain endpoint, defaults: "api"
     *
     * @param array|Collection $config  Configuration settings
     *
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

    /**
     * This method prepare uri with format api/{id}/user/{user_id} and replace for
     *      api/1/user/6
     *
     * @param string $uri The base string url
     *
     * @param array $params A collection of params, the order is important
     *
     * @return string the uri well formatted
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     */
    private function parseUrl($uri, array $params = null)
    {
        if (empty($params)) {
            return $uri;
        }

        $pattern = '/{\w+}/';

        if (is_array($params)) {
            $leng = count($params);

            if (preg_match_all('/({\w+})/', $uri) !== $leng) {
                throw new \InvalidArgumentException('Client::parseRouting() ERROR: The params number does not match with the path');
            }
            $pattern = array_fill(0, $leng, "/{\w+}/");
        }

        return preg_replace($pattern, $params, $uri, 1);
    }
}