<?php
/**
 * Created by Ant-WEB S.L.
 * User: Xabier Fernández Rodríguez <jjbier@gmail.com>
 * Date: 9/10/13
 * Time: 18:33
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ant\ChateaClient\Service\Client\Model;

use Guzzle\Service\Resource\ResourceIterator;

class GetChannelsIterator  extends  ResourceIterator
{

    /**
     * Send a request to retrieve the next page of results. Hook for subclasses to implement.
     *
     * @return array Returns the newly loaded resources
     */
    protected function sendRequest()
    {
        // TODO: Implement sendRequest() method.
    }
}