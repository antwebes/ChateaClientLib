<?php
namespace Ant\ChateaClient\Test\OAuth2;

use Ant\ChateaClient\OAuth2\TokenRequest;
use Guzzle\Http\Message\Response;
use Ant\ChateaClient\OAuth2\TokenResponse;
use Ant\ChateaClient\OAuth2\RefreshToken;
 
class TokenRequestTest extends \PHPUnit_Framework_TestCase
{
	private $httpClient;
	private $tokenResponse;
	private $clientMock;

	public function setUp()
	{
		$this->httpClient = new \Ant\ChateaClient\Http\HttpClient(
				\Ant\ChateaClient\Http\IHttpClient::TOKEN_ENDPOINT
		);
	
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$myTime = time();
		$data_json =
		'{
						"access_token":"YjY0ZjdkMTYwNjk5OWUwNjIxM2JlNmNkN2FlNmQ3Zjg2ZTJiNDQ0MWVkNzE4NzdjMTNkYmNmNjBiZmI0NmJlNg",
						"expires_in":3600,
						"token_type":"bearer",
						"scope":"read-only",
						"refresh_token":"Yjg4MjAyZmYzMTBkNTI4ZWVlYzkwYTczMTFhMjZjNTc3YTVjMjYxZjcyMWZhNDE2NWE2OGM4ZWUyZjQzNjAxYQ"
		}';
	
		$mock->addResponse(new \Guzzle\Http\Message\Response(
				200,
				array('cache-control'=>"no-store, private",
						'content-type'=>"application/json",
				),
				$data_json
		)
		);
	
		$this->clientMock =
		$this->getMockBuilder("Ant\ChateaClient\OAuth2\IOAuth2Client")
		->disableOriginalConstructor()
		->getMock();
	
		$this->tokenResponse = TokenResponse::formJson($data_json);
	
		$this->httpClient->addSubscriber($mock);
	}
		
	public function tearDown()
	{
		$this->httpClient = null;
		$this->tokenResponse = null;
		$this->clientMock = null;
	}
		
	public function testConstructing()
	{
		try {
			$token = new  TokenRequest(null, null);
		}catch (\Exception $expected ){
			return;
		}

		$this->fail('An expected exception has not been raised.');
	}
	
	public function testWithAuthorizationCode()
	{
		
		$this->clientMock->expects($this->once())
		->method('getPublicId')
		->will($this->returnValue('public-id')
		);				
		$this->clientMock->expects($this->exactly(3))		
			->method('getSecret')
				->will($this->returnValue('secret-id')
		);
		$this->clientMock->expects($this->exactly(3))
			->method('getRedirectUri')
			->will($this->returnValue('public-uri')					
		);

		$tokenRequest = new TokenRequest($this->clientMock, $this->httpClient ); 
			
		$actual = $tokenRequest->withAuthorizationCode('auth-code');
		
		$this->assertEquals($this->tokenResponse, $actual);
	}

	public function testWithUserCredentials()
	{
		$this->clientMock->expects($this->once())
		->method('getPublicId')
		->will($this->returnValue('public-id')
		);
		$this->clientMock->expects($this->exactly(3))
		->method('getSecret')
		->will($this->returnValue('secret-id')
		);
		
		$tokenRequest = new TokenRequest($this->clientMock, $this->httpClient );
		
		$actual = $tokenRequest->withUserCredentials('username', 'password');
		
		
		$this->assertEquals($this->tokenResponse, $actual);		
	}
	
	public function testwithClientCredentials()
	{
		$this->clientMock->expects($this->once())
		->method('getPublicId')
		->will($this->returnValue('public-id')
		);
		
		$this->clientMock->expects($this->exactly(3))
		->method('getSecret')
		->will($this->returnValue('secret-id')
		);
		
		$tokenRequest = new TokenRequest($this->clientMock, $this->httpClient );
		
		$actual = $tokenRequest->withClientCredentials();
		
		$this->assertEquals($this->tokenResponse, $actual);		
	}
	
	public function testWithRefreshToken()
	{
		$this->clientMock->expects($this->once())
		->method('getPublicId')
		->will($this->returnValue('public-id')
		);
		
		$this->clientMock->expects($this->exactly(3))
		->method('getSecret')
		->will($this->returnValue('secret-id')
		);
		
		$tokenRequest = new TokenRequest($this->clientMock, $this->httpClient );
		
		$actual = $tokenRequest->withRefreshToken(new RefreshToken('myRefresToken'));
		
		$this->assertEquals($this->tokenResponse, $actual);		
	}	
}