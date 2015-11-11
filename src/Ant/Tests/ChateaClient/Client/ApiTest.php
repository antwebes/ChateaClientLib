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
        $this->configureCommandMock('UpdateUserCity', $data);

        $this->api->updateUserCity(1, 2);
    }

    public function testGetPhotos()
    {
        $data = array('filters' => 'number_votes_greater_equal=3', 'order' => 'score=asc', 'limit' => null, 'offset' => null);
        $this->configureCommandMock('getPhotos', $data);

        $this->api->getPhotos('number_votes_greater_equal=3', 'score=asc');
    }

    public function testGetPhotosWithLimitAndOffset()
    {
        $data = array('filters' => 'number_votes_greater_equal=3', 'order' => 'score=asc', 'limit' => 30, 'offset' => 5);
        $this->configureCommandMock('getPhotos', $data);

        $this->api->getPhotos('number_votes_greater_equal=3', 'score=asc', 30, 5);
    }

    private function configureCommandMock($comandName, $expectedData)
    {
        $command = $this->getMock('Guzzle\Service\Command\CommandInterface');

        $this->client->expects($this->once())
            ->method('getCommand')
            ->with($comandName, $expectedData)
            ->will($this->returnValue($command));
    }
}