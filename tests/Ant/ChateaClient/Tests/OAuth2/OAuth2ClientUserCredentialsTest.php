<?php
namespace Ant\ChateaClient\Test\OAuth2;

use Ant\ChateaClient\OAuth2\OAuth2ClientUserCredentials;
use Ant\ChateaClient\OAuth2\OAuth2ClientException;

class OAuth2ClientUserCredentialsTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\OAuth2ClientException
	 */
	public function testUsernameNotString()
	{
		$oauthclient = new OAuth2ClientUserCredentials('client_id','sectret',1231564987897,'password');
	}
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\OAuth2ClientException
	 */
	public function testUsernameNotEmpty()
	{
		$oauthclient = new OAuth2ClientUserCredentials('client_id','sectret','','password');
	}
	
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\OAuth2ClientException
	 */
	public function testPasswordNotString()
	{
		$oauthclient = new OAuth2ClientUserCredentials('client_id','sectret','username',12345);
	}
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\OAuth2ClientException
	 */
	public function testPasswordNotEmpty()
	{
		$oauthclient = new OAuth2ClientUserCredentials('client_id','sectret','username','');
	}	
}