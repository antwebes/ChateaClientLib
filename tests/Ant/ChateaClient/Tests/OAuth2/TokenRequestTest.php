<?php

namespace Ant\ChateaClient\OAuth2;

use Ant\ChateaClient\Client\ClientConfig;
use Ant\ChateaClient\Client\ChateaConfig;
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


		$plugin = new \Guzzle\Plugin\Mock\MockPlugin();
		$dataResponseMok = '{"access_token":"ZjgxZGQwMTA3YWNkZWY2YmM5MTcwY2U3Mzg5ZTlhZDZmMDAzNDRiNzg1NjdmOWZlNDAxZGNhM2I1YmNkODE0Ng","expires_in":3600,"token_type":"bearer","scope":null,"refresh_token":"NjMzOTI2MmQ1ODEzZmU2MTU0MTUxOGI2OTNmYmM2Yjg1YWQyMDg1NGU1Yjc3ZmIwYjMzZTY0MDI4N2QyMzA5Yg"}';
		$plugin->addResponse(
				new \Guzzle\Http\Message\Response(
				200,
				array(),
				$dataResponseMok				
				)
		);
		$client = new \Guzzle\Http\Client();
		$client->addSubscriber($plugin);
		

		
		$tokenRequest = new TokenRequest(
				$client, 
				ClientConfig::fromJSONFile(),
				ChateaConfig::fromJSONFile()
		);
		$time = time();
		$tokenResponseExpected = new TokenResponse(
				new AccessToken(
						"ZjgxZGQwMTA3YWNkZWY2YmM5MTcwY2U3Mzg5ZTlhZDZmMDAzNDRiNzg1NjdmOWZlNDAxZGNhM2I1YmNkODE0Ng",
						new TokenType("bearer"),
						3600,
						$time,
						null
					),
				new RefreshToken(
						"NjMzOTI2MmQ1ODEzZmU2MTU0MTUxOGI2OTNmYmM2Yjg1YWQyMDg1NGU1Yjc3ZmIwYjMzZTY0MDI4N2QyMzA5Yg",		
						3600,				
						$time,
						null						
					),
					3600,
					null
		);
		
		$tokenResponseActual = $tokenRequest->withPasswordCredentials('username', 'password');
		
		$this->assertEquals($tokenResponseExpected, $tokenResponseActual);
	}
}