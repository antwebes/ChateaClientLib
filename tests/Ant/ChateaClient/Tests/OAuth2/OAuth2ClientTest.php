<?php
namespace Ant\ChateaClient\Test\OAuth2;

use Ant\ChateaClient\OAuth2\OAuth2Client;
use Ant\ChateaClient\OAuth2\OAuth2ClientException;

class OAuth2ClientTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @expectedException Ant\ChateaClient\OAuth2\OAuth2ClientException
	 */
	public function testClientIdNotString()
	{	
		$oauthclient = new OAuth2Client(1231564987897);
	}
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\OAuth2ClientException
	 */
	public function testClientIdNotEmpty()
	{
		$oauthclient = new OAuth2Client('');
	}
	/**
	 * @dataProvider providerNotValidRediretUri
	 * @expectedException Ant\ChateaClient\OAuth2\OAuth2ClientException
	 */
	public function testNotValidRediretUri($client_id, $secret,$redirect_uri)
	{
		$oauthclient = new OAuth2Client($client_id, $secret,$redirect_uri); 		
	}
	/**
	 * @dataProvider providerValidRediretUri
	 */
	public function testValidRediretUri($client_id, $secret, $redirect_uri)
	{
		$oauthclient = new OAuth2Client($client_id, $secret,$redirect_uri);
	}	
	
	public static function providerValidRediretUri()
	{
		return array(
				array('client_id','secret',''),
				array('client_id','secret','chateagratis.net'), 
				array('client_id','secret','www.chateagratis.net'), 
				array('client_id','secret','http://chateagratis.net'),
		);		
	}
	
	public static function providerNotValidRediretUri()
	{
		return array(
				array('client_id','secret','falla'),
				array('client_id','secret','http://...'),
				array('client_id','secret','http://www...'),				
				array('client_id','secret','www.chateagratis'),
				array('client_id','secret','http://www.chateagratis'),
				array('client_id','secret','http://www.chateagratis_es'),
		);
	}	
	
	public function testGeters()
	{
		$oauthclient = new OAuth2Client('public_id','secret-id','http://www.chateagratis.net');
		
		$this->assertEquals('public_id', $oauthclient->getPublicId());
		$this->assertEquals('secret-id', $oauthclient->getSecret());
		$this->assertEquals('http://www.chateagratis.net', $oauthclient->getRedirectUri());
	}
	
}