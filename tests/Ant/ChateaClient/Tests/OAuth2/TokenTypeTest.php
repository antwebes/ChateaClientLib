<?php

namespace Ant\ChateaClient\OAuth2;

class TokenTypeTest extends \PHPUnit_Framework_TestCase 
{
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\TokenException
	 */
	public function testValueIsNotString()
	{
		$tokenType = new TokenType(time());
	
	}
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\TokenException
	 */
	public function testValueIsNotEmpty()
	{
		$token = new TokenType('');
	}
	public function testGetValue()
	{
		$tokenTypeValue =  TokenType::BEARER;
		$tokenType = new TokenType($tokenTypeValue);
			
		$this->assertEquals($tokenTypeValue, $tokenType->getName());
		
		$tokenType->setName($tokenTypeValue);
		$this->assertEquals($tokenTypeValue, $tokenType->getName());
	}		
}