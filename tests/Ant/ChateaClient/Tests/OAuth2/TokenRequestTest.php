<?php

namespace Ant\ChateaClient\OAuth2;

class TokenRequestTest extends  \PHPUnit_Framework_TestCase
{

	public function testParametersNullConstruct(){
		
		try {
			$TokenRequest = new TokenRequest(null, null, null);
		}catch (\Exception $expected ){
			return;
		}
		
		$this->fail('An expected exception has not been raised.');		
	}
	
	public function testwithPasswordCredentialsGET(){
		
		$mockClient = $this->getMockBuilder("Guzzle\Http\Client")
			->disableOriginalConstructor()
			->getMock();
		
		$mockClient->expects($this->once())
			->method('get')
			->will($this->returnValue(new Guzzle\Http\Message\Request()));
		

		$mockClientConfig = $this->getMockBuilder("Ant\ChateaClient\Client\ClientConfig")		
			->disableOriginalConstructor()
			->getMock();
		

		$mockChateaConfig = $this->getMockBuilder("Ant\ChateaClient\Client\ChateaConfig")
		->disableOriginalConstructor()
		->getMock();
				
		$tokenRequest = new TokenRequest($mockClient, $mockClientConfig, $mockChateaConfig);
		
		$body = $tokenRequest->withPasswordCredentials('username', 'password');
	}
}