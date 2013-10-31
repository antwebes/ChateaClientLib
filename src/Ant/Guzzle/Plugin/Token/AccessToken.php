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

/**
 *
 * Interface AccessToken
 *
 * @package Ant\Guzzle\Plugin\Token
 */
interface AccessToken {

    /**
     * The access token as string
     *
     * @return string The value of token
     */
    public function __toString();

    /**
     * Get the token type
     *
     * @return string Value of token type
     */
    public function getFormat();

    /**
     * Set the token type
     *
     * @param string $format set the token type
     *
     * @return void
     */
    public function setFormat($format);
}