<?php

namespace Ant\ChateaClient\OAuth2;

class TokenType
{
	/**
	 * Name of Bearer token 
	 * 
	 * @var String
	 */
	const BEARER = "Bearer";
	
	private $tokenName;
	
	public function __construct($tokenName){
		if (!is_string($tokenName) || 0 >= strlen($tokenName)) {
			throw new TokenException("TokenType: tokenName needs to be a non-empty string");
		}
		
		$this->tokenName = $tokenName;
	}

	public function getName() {
		return $this->tokenName;
	}
	
	public function setName($tokenName) {
		if (!is_string($tokenName) || 0 >= strlen($tokenName)) {
			throw new TokenException("TokenType: tokenName needs to be a non-empty string");
		}
		$this->tokenName = $tokenName;		
	}
	public function __toString()
	{
		return json_encode(
				array('TokenType'=>array('Name' => $this->getName())),JSON_PRETTY_PRINT);
	}	
}