<?php
namespace Ant\ChateaClient\Test\OAuth2;

use Ant\ChateaClient\OAuth2\TokenResponse;
use Ant\ChateaClient\OAuth2\AccessToken;
use Ant\ChateaClient\OAuth2\RefreshToken;
use Ant\ChateaClient\OAuth2\TokenResponseException;

class TokenResponseTest extends \PHPUnit_Framework_TestCase
{


	public function testSetNotAccesToken()
	{
		
		try {
			$token = new TokenResponse(null, null);
		}catch (\Exception $expected ){
			return;
		}

		$this->fail('An expected exception has not been raised.');

	}
	/**
	 * @dataProvider providerFromArray
	 * @expectedException Ant\ChateaClient\OAuth2\TokenResponseException
	 */
	public function testSetMissingField(array $data){

		$token = TokenResponse::fromArray($data);		
		
	}
	public static function providerFromArray()
	{
		return array(
				array(array('access_token'=>'')),	
				array(array('access_token'=>'notvalue')),
				array(array('refresh_token'=>'notvalue')),
				array(array('expires_in'=>'notvalue')),
				array(array('access_token'=>'notvalue', 'token_type'=>'notvalue')),
				array(array('access_token'=>'notvalue', 'refresh_token'=>'notvalue')),
				array(array('access_token'=>'notvalue', 'expires_in'=>'notvalue')),
				array(array('access_token'=>'notvalue', 'expires_in'=>'no', 'refresh_token'=> 'notvalue')),
				array(array('access_token'=>'notvalue', 'expires_in'=>'no', 'expires_in'=> 'notvalue'))
			
			);
	}
	public function testSetScopeAsNull()
	{
		try {
			$token = new TokenResponse(
					new AccessToken('MGQ3OGEzNTQzNTdhOTE5YjJjNWU4MTljYWI1ZDNkN2U5YzI5NDdlNDk5NmUzOTZmMzkyMTgyNGIyYzc0ODlmYQ'), 
					new RefreshToken('MGQ3OGEzNTQzNTdhOTE5YjJjNWU4MTljYWI1ZDNkN2U5YzI5NDdlNDk5NmUzOTZmMzkyMTgyNGIyYzc0ODlmYQ'),
					3600,
					null
		);			
		$token->setScope(null); 
		}catch (\Ant\ChateaClient\OAuth2\TokenResponseException $notexpected ){
			$this->fail('Not expected exception has been raised.');	
		}

		$this->assertNull($token->getScope());
	} 
}

