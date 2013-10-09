<?php
/**
 * Created by Ant-WEB S.L.
 * User: Xabier Fernández Rodríguez <jjbier@gmail.com>
 * Date: 9/10/13
 * Time: 10:25
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ant\Guzzle\Plugin\Token;

use Guzzle\Common\Collection;

class BearerToken implements AccessToken{

    private   $format;
    protected $tokenString;

    public function __construct($config)
    {
        $config = Collection::fromConfig($config, array(
                'token_format' => 'Bearer',
            ), array(
                'token_format',
            ));
        $this->tokenString = $config['access_token'];
        $this->format = $config['token_format'];
    }

    public function __toString()
    {
        return sprintf('%s %s', $this->getFormat(), $this->tokenString);
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function setFormat($format)
    {
        $this->format = $format;
    }
}