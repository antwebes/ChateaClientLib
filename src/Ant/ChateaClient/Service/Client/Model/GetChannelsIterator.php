<?php
/**
 * Created by Ant-WEB S.L.
 * User: Xabier Fernández Rodríguez <jjbier@gmail.com>
 * Date: 10/10/13
 * Time: 16:57
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ant\ChateaClient\Service\Client\Model;

use Guzzle\Service\Resource\ResourceIterator;

class GetChannelsIterator extends ResourceIterator
{
    /**
     * Send a request to retrieve the next page of results. Hook for subclasses to implement.
     *
     * @return array Returns the newly loaded resources
     */
    protected function sendRequest()
    {
        // Execute the command and parse the result
        $result = $this->command->execute();
        ld($result['resources']);
    }
}