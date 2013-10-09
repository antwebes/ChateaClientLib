<?php
/**
 * Created by Ant-WEB S.L.
 * User: Xabier Fernández Rodríguez <jjbier@gmail.com>
 * Date: 9/10/13
 * Time: 10:37
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ant\Guzzle\Plugin\Token;

interface AccessToken {

    public function __toString();
    public function getFormat();
    public function setFormat($format);
}