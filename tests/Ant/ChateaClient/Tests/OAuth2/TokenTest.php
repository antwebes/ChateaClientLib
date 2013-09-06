<?php

namespace Ant\ChateaClient\Test\OAuth2;

use Ant\ChateaClient\OAuth2\Token;
use Ant\ChateaClient\OAuth2\Scope;

class TokenTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\TokenException
	 */
	public function testValueIsNotString() 
	{
		$token = new Token ( time () );
	}
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\TokenException
	 */
	public function testValueIsNotEmpty() 
	{
		$token = new Token ( '' );
	}
	public function testGetValue() 
	{		
		
		$tokenValue = "MGQ3OGEzNTQzNTdhOTE5YjJjNWU4MTljYWI1ZDNkN2U5YzI5NDdlNDk5NmUzOTZmMzkyMTgyNGIyYzc0ODlmYQ";
		$token = new Token ( $tokenValue );
		
		$this->assertEquals ( $tokenValue, $token->getValue () );
	}
	public function testSetNullIssueTime() {
		$token = new Token ( "MGQ3OGEzNTQzNTdhOTE5YjJjNWU4MTljYWI1ZDNkN2U5YzI5NDdlNDk5NmUzOTZmMzkyMTgyNGIyYzc0ODlmYQ" );
		$token->setIssueTime ( null );
	}
	
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\TokenException
	 */
	public function testSetNotNumericIssueTime() 
	{
		$token = new Token ( "MGQ3OGEzNTQzNTdhOTE5YjJjNWU4MTljYWI1ZDNkN2U5YzI5NDdlNDk5NmUzOTZmMzkyMTgyNGIyYzc0ODlmYQ" );
		$token->setIssueTime ( "not numeric" );
	}
	
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\TokenException
	 */
	public function testSetNotNegativeNumericIssueTime() 
	{
		$token = new Token ( "MGQ3OGEzNTQzNTdhOTE5YjJjNWU4MTljYWI1ZDNkN2U5YzI5NDdlNDk5NmUzOTZmMzkyMTgyNGIyYzc0ODlmYQ" );
		$token->setIssueTime ( - 112 );
	}
	public function testSetNullScope() {
		$token = new Token ( "MGQ3OGEzNTQzNTdhOTE5YjJjNWU4MTljYWI1ZDNkN2U5YzI5NDdlNDk5NmUzOTZmMzkyMTgyNGIyYzc0ODlmYQ" );
		$token->setScope ( null );
	}
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\ScopeException
	 */
	public function testSetInvalidScope() 
	{
		$token = new Token ( "MGQ3OGEzNTQzNTdhOTE5YjJjNWU4MTljYWI1ZDNkN2U5YzI5NDdlNDk5NmUzOTZmMzkyMTgyNGIyYzc0ODlmYQ" );
		$token->setScope ( new Scope ( "_?@#~½¬" ) );
	}
}