<?php
namespace Ant\ChateaClient\Test\OAuth2;

use Ant\ChateaClient\OAuth2\OAuth2ClientAuthCode;
use Ant\ChateaClient\OAuth2\OAuth2ClientException;

class OAuth2ClientAuthCodeTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @expectedException Ant\ChateaClient\OAuth2\OAuth2ClientException
	 */
	public function testAuthCodeNotString()
	{	
		$oauthclient = new OAuth2ClientAuthCode('client_id','sectret',1231564987897,'http://www.chategaratis.net');
	}
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\OAuth2ClientException
	 */
	public function testAuthCodeNotEmpty()
	{
		$oauthclient = new OAuth2ClientAuthCode('client_id','sectret','','http://www.chategaratis.net');
	}
	
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\OAuth2ClientException
	 */
	public function testRedirectUriNotString()
	{
		$oauthclient = new OAuth2ClientAuthCode('client_id','sectret','aut-code',12345);
	}
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\OAuth2ClientException
	 */
	public function testRedirectUriNotEmpty()
	{
		$oauthclient = new OAuth2ClientAuthCode('client_id','sectret','aut-code','');
	}	
}