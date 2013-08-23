<?php
namespace Ant\ChateaClient\OAuth2;

class TokenResponseTest   extends \PHPUnit_Framework_TestCase
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
	 * @expectedException Ant\ChateaClient\OAuth2\TokenResponseException
	 */	
	public function testSetMissingField(){
		// empty array
		$token = TokenResponse::fromArray(array());
		//only access_token
		$token = TokenResponse::fromArray(array('access_token'=>'notvalue'));
		//only refresh_token
		$token = TokenResponse::fromArray(array('refresh_token'=>'notvalue'));		
		//only expires_in		
		$token = TokenResponse::fromArray(array('expires_in'=>'notvalue'));
		//only 'access_token','token_type'
		$token = TokenResponse::fromArray(array('access_token'=>'notvalue', 'token_type'=>'notvalue'));				
		//only 'access_token','refresh_token'
		$token = TokenResponse::fromArray(array('access_token'=>'notvalue', 'refresh_token'=>'notvalue'));				
		//only 'access_token','expires_in'
		$token = TokenResponse::fromArray(array('access_token'=>'notvalue', 'expires_in'=>'notvalue'));
		//only 'access_token','token_type','refresh_token'
		$token = TokenResponse::fromArray(array('access_token'=>'notvalue', 'expires_in'=>'no', 'refresh_token'=> 'notvalue'));		
		//only 'access_token','token_type','expires_in'
		$token = TokenResponse::fromArray(array('access_token'=>'notvalue', 'expires_in'=>'no', 'expires_in'=> 'notvalue'));		
	}
	
	
}
