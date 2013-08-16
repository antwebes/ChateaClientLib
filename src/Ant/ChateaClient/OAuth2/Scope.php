<?php

namespace Ant\ChateaClient\OAuth2;

class Scope
{
    private $name;
    private $parentName;

    /**
     * 
     */
    public function __construct($name, Scope $parentName = '')
    {
    	if (!is_string($name)) {
    		throw new TokenException("scope needs to be string");
    	}   	
        $this->name = self::normalize($name);
        $this->parentName = $parentName;
    }

    /**
     * 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Scope
     */
    public function getParentName()
    {
        return $this->parentName;
    }
    
    public static function validate(Scope $scope)
    {
    	$scopeTokenRegExp = '(?:\x21|[\x23-\x5B]|[\x5D-\x7E])+';
    	$scopeRegExp = sprintf('/^%s(?: %s)*$/', $scopeTokenRegExp, $scopeTokenRegExp);
    	$result = preg_match($scopeRegExp, $scope->getName());
    	if (1 !== $result) {
    		throw new TokenException(sprintf("invalid scope '%s'", $scope->getName()));
    	}
    }
    
    private static function normalize(Scope $scope)
    {
    	$explodedScope = explode(" ", $scope->getName());
    	sort($explodedScope, SORT_STRING);
    
    	return implode(" ", array_values(array_unique($explodedScope, SORT_STRING)));
    }    
}
