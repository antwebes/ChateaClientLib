<?php
namespace Ant\ChateaClient\OAuth2;

class AccessTokenTest  extends \PHPUnit_Framework_TestCase
{

	public function testTokenTypeIsNotNull(){
		$refreshToken = new AccessToken('MGQ3OGEzNTQzNTdhOTE5YjJjNWU4MTljYWI1ZDNkN2U5YzI5NDdlNDk5NmUzOTZmMzkyMTgyNGIyYzc0ODlmYQ');
		
		try {
			$refreshToken->setTokenType(null);
		}catch (\Exception $expected ){
			return;
		}
		
		$this->fail('An expected exception has not been raised.');
				
		
	}
	public function testExpiresInIsNull(){
	
		$refreshToken = new AccessToken('MGQ3OGEzNTQzNTdhOTE5YjJjNWU4MTljYWI1ZDNkN2U5YzI5NDdlNDk5NmUzOTZmMzkyMTgyNGIyYzc0ODlmYQ');
		$refreshToken->setExpiresIn(null);
	}
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\TokenException
	 */
	public function testSetNotNumericExpiresIn()
	{
		$token = new RefreshToken("MGQ3OGEzNTQzNTdhOTE5YjJjNWU4MTljYWI1ZDNkN2U5YzI5NDdlNDk5NmUzOTZmMzkyMTgyNGIyYzc0ODlmYQ");
		$token->setExpiresIn("not numeric");
	}
	
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\TokenException
	 */
	public function testSetNotNegativeNumericExpiresIn()
	{
		$token = new AccessToken("MGQ3OGEzNTQzNTdhOTE5YjJjNWU4MTljYWI1ZDNkN2U5YzI5NDdlNDk5NmUzOTZmMzkyMTgyNGIyYzc0ODlmYQ");
		$token->setExpiresIn(-112);
	}
	
	public function testGetExpiresAt(){
		$expiresIn = 3600;
		$issueTime = time();
		$expiresAt = $expiresIn + $issueTime;
	
		$token = new AccessToken(
				"MGQ3OGEzNTQzNTdhOTE5YjJjNWU4MTljYWI1ZDNkN2U5YzI5NDdlNDk5NmUzOTZmMzkyMTgyNGIyYzc0ODlmYQ",
				new TokenType(TokenType::BEARER),
				$expiresIn,
				$issueTime,
				new Scope('read-only')
		);
	
		$this->assertEquals($expiresAt, $token->getExpiresAt());
	}
	
	public function testHasExpired(){
		$expiresIn = 3600;
		$issueTime = time();
		$token = new AccessToken(
				"MGQ3OGEzNTQzNTdhOTE5YjJjNWU4MTljYWI1ZDNkN2U5YzI5NDdlNDk5NmUzOTZmMzkyMTgyNGIyYzc0ODlmYQ",
				new TokenType(TokenType::BEARER),
				$expiresIn,
				$issueTime,				
				new Scope('read-only')
		);
	
		$this->assertFalse($token->hasExpired());
	
		//last time
		$issueTime = time() - 3601;
		$token->setIssueTime($issueTime);
	
		$this->assertTrue($token->hasExpired());
	
	}
}