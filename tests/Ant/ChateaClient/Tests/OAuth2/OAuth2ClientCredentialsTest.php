<?php
namespace Ant\ChateaClient\Test\OAuth2;

use Ant\ChateaClient\OAuth2\OAuth2ClientCredentials;
use Ant\ChateaClient\OAuth2\OAuth2ClientException;

class OAuth2ClientCredentialsTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\OAuth2ClientException
	 */
	public function testClientIdNotString()
	{
		$oauthclient = new OAuth2ClientCredentials(13213564,'sectret');
	}
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\OAuth2ClientException
	 */
	public function testClientIdNotEmpty()
	{
		$oauthclient = new OAuth2ClientCredentials('','sectret');
	}
	
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\OAuth2ClientException
	 */
	public function testSecretString()
	{
		$oauthclient = new OAuth2ClientCredentials('client_id',12346546);
	}
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\OAuth2ClientException
	 */
	public function testSecretNotEmpty()
	{
		$oauthclient = new OAuth2ClientCredentials('client_id','');
	}	
}