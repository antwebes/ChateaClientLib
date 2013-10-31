<?php
/**
 * Created by Ant-WEB S.L.
 * Developer: Xabier Fernández Rodríguez <jjbier@gmail.com>
 * Date: 14/10/13
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ant\Guzzle\Plugin;

use Guzzle\Common\Event;
use Guzzle\Common\Collection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Ant\Guzzle\Plugin\Token\BearerToken;
use Ant\Guzzle\Plugin\Token\MacToken;

/**
 * Class OAuth2Plugin put Authorization header at Guzzle client
 *
 * @package Ant\Guzzle\Plugin
 */
class OAuth2Plugin implements EventSubscriberInterface
{
    /**
     * The collection that configure this Plugin
     *
     * @var \Guzzle\Common\Collection
     */
    private $config;

    /**
     * Creare a instace of OAuth2Plugin
     *
     * @param array $config The config values, this plugin accept key token_type and access_token value
     */
    public function __construct($config)
    {
        $this->config = Collection::fromConfig($config, array(
                'version' => '2.0',
                'token_type' => 'Bearer',
            ), array(
                'version', 'token_type',
                'access_token'
            ));
    }

    /**
     * Update de access token value
     *
     * @param string $acces_token The token value
     */
    public function updateAccessToken($acces_token)
    {
        $this->config['access_token'] = $acces_token;
    }
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array('request.before_send' => 'onRequestBeforeSend');
    }

    /**
     * Request before-send event handler
     *
     * @param Event $event Event received
     * @return string AccessToken value
     */
    public function onRequestBeforeSend(Event $event)
    {
        $request = $event['request'];

        if (is_string($this->config['access_token'])) {
            $params = array('access_token' => $this->config['access_token']);
            if (isset($this->config['token_format'])) {
                $params['token_format'] = $this->config['token_format'];
            }
            switch ($this->config['token_type']) {
                case 'Mac':
                    $this->config['access_token'] = new MacToken($params);
                    break;
                case 'Bearer':
                default:
                    $this->config['access_token'] = new BearerToken($params);
                    break;
            }
        }
        $request->setHeader('Authorization',(string) $this->config['access_token']);
        return $this->config['access_token'];
    }
}