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

/**
 * Class AcceptHeaderPluging put Accept header at Guzzle client
 *
 * @package Ant\Guzzle\Plugin
 */
class AcceptHeaderPluging implements EventSubscriberInterface
{


    /**
     * Creare a instace of AcceptHeaderPluging
     *
     * @param array $config The config values, this plugin only accept key accept-header
     */
    public function __construct($config)
    {
        $this->config = Collection::fromConfig($config, array(
                'accept-header' => 'application/json'
            ), array(
                'accept-header'
            ));
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
     * @return string Accept Header value
     */
    public function onRequestBeforeSend(Event $event)
    {
        $request = $event['request'];

        $request->setHeader('Accept',(string) $this->config['accept-header']);

        return $this->config['accept-header'];
    }
}