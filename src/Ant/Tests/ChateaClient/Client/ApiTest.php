<?php
/**
 * User: José Ramón Fernandez Leis
 * Email: jdeveloper.inxenio@gmail.com
 * Date: 17/09/15
 * Time: 11:02
 */

namespace Ant\Tests\ChateaClient\Client;


use Ant\ChateaClient\Client\Api;

class ApiTest extends \PHPUnit_Framework_TestCase
{
    private $client;
    private $api;

    protected function setUp()
    {
        $this->client = $this->getMockBuilder('Ant\ChateaClient\Service\Client\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $this->api = new Api($this->client);
    }

    public function testUpdateUserCity()
    {
        $data = array('id' => 1, 'city_id' => 2);
        $command = $this->getMock('Guzzle\Service\Command\CommandInterface');

        $this->client->expects($this->once())
            ->method('getCommand')
            ->with('UpdateUserCity', $data)
            ->will($this->returnValue($command));

        $this->api->updateUserCity(1, 2);
    }
}