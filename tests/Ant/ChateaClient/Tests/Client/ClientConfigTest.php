<?php
namespace Ant\ChateaClient\Client;

class ClientConfigTest extends \PHPUnit_Framework_TestCase 
{

	public function testGetAcceptHeader(){
		$this->assertEquals(ClientConfig::ACCEPT_JSON, 
				ClientConfig::getAcceptHeader("json")
		);
	}
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\ConfigException
	 */
	public function testConfigExceptionInConstruct(){
		$config = new ClientConfig(array());
		$config = new ClientConfig(array('client_id'=>'notvalue'));
		$config = new ClientConfig(array('client_secret'=>'notvalue'));
				
	}
	public function testFromJSONFile(){
		$client_id 						= "2_63gig21vk9gc0kowgwwwgkcoo0g84kww00c4400gsc0k8oo4ks";
		$client_secret 					= "202mykfu3ilckggkwosgkoo8g40w4wws0k0kooo488wo048k0w";
		$redirect_uri 					= "http://www.chateagratis.net";
		$credentials_in_request_body 	=  false;
		$data_format 					= "json";
						
		try {
			$clientConfig = ClientConfig::fromJSONFile();
			
			$this->assertEquals($client_id, $clientConfig->getClientId());
			$this->assertEquals($client_secret, $clientConfig->getClientSecret());
			$this->assertEquals($redirect_uri, $clientConfig->getRedirectUri());
			$this->assertEquals($credentials_in_request_body, $clientConfig->getCredentialsInRequestBody());
			$this->assertEquals(ClientConfig::getAcceptHeader($data_format), $clientConfig->getAccept());
			
		}catch (ConfigException $ex){
			$this->fail('I don\'t expected exception,  ConfigException has not been raised.');
		}
	}
}
