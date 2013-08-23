<?php
namespace Ant\ChateaClient\Client;

class ChateaOAuth2Test extends \PHPUnit_Framework_TestCase
{
	
	/**
	 * @expectedException Ant\ChateaClient\Client\ChateaApiException
	 */
	public function testConstruct()
	{
		$chateaOAuth2 = new 
			ChateaOAuth2(ClientConfig::fromJSONFile(),'','');
		
	}
	
	public function testAuthenticate()
	{
		// TODO: I don't know, how I make this test ?
	}
	public function testUpdateToken()
	{
		// TODO: I don't know, how I make this test ?
	}
	
	public function testRevokeToken()
	{
		// TODO: I don't know, how I make this test ?
	}
}