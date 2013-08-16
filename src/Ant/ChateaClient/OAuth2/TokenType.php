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
	
	private $name;
	
	public function __construct($tokenName){
		if (!is_string($tokenName) || 0 >= strlen($tokenName)) {
			throw new ContextException("tokenName needs to be a non-empty string");
		}
		$this->tokenName = $tokenName;		
	}

	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		if (!is_string($tokenName) || 0 >= strlen($tokenName)) {
			throw new ContextException("tokenName needs to be a non-empty string");
		}
		$this->tokenName = $tokenName;		
	}
	
}