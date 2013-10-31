<?php
/**
 * Created by Ant-WEB S.L.
 * Developer: Xabier Fernández Rodríguez <jjbier@gmail.com>
 * Date: 14/10/13
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ant\Guzzle\Plugin\Token;

use Guzzle\Common\Collection;

/**
 * Create a token of type  Bearer
 *
 * @package Ant\Guzzle\Plugin\Token
 */
class BearerToken implements AccessToken{

    private   $format;
    protected $tokenString;

    /**
     * Create a  token type Bearer
     * @param array $config Associative array with only one key token_format, The default value is Bearer
     */
    public function __construct(array $config = array())
    {
        $config = Collection::fromConfig($config, array(
                'token_format' => 'Bearer',
            ), array(
                'token_format',
            ));
        $this->tokenString = $config['access_token'];
        $this->format = $config['token_format'];
    }
    /**
     * The access token as string
     *
     * @return string The value of token
     */
    public function __toString()
    {
        return sprintf('%s %s', $this->getFormat(), $this->tokenString);
    }
    /**
     * Get the token type
     *
     * @return string Value of token type
     */
    public function getFormat()
    {
        return $this->format;
    }
    /**
     * Set the token type
     *
     * @param string $format set the token type
     *
     * @return void
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }
}