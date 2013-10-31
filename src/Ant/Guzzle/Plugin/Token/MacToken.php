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
 * Create a token of type  MAC
 *
 * @package Ant\Guzzle\Plugin\Token
 */
class MacToken implements  AcessToken
{

    /**
     * The collection that configure this Plugin
     *
     * @var \Guzzle\Common\Collection
     */
    private $config;

    /**
     * Create nre instance of MacToken
     *
     * @param array $config The collection that configure this Plugin
     */
    public function __construct($config)
    {
        $this->config = $config;
    }
    /**
     * The access token as string
     *
     * @return string The value of token
     */
    public function __toString()
    {
        $macString = sprintf('%s ', $this->getFormat());
        foreach ($this->config as $key => $value) {
            $macString .= sprintf('%s="%s",'.PHP_EOL, $key, $value);
        }
        return trim($macString, PHP_EOL.",");
    }
    /**
     * Get the token type
     *
     * @return string Value of token type
     */
    public function getFormat()
    {
        return 'MAC';
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