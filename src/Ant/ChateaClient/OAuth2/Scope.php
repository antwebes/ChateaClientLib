<?php

namespace Ant\ChateaClient\OAuth2;

/**
 * A Scope is a permission setting that specifies access to a users, 
 * to non-public data.
 * 
 * Examples of scopes for the one APIs are:
 * Read/Write API! Updates
 * Read (Shared) API! Profiles
 * Read API! Contacts
 * 
 * @author ant@antweb.es
 *
 */
class Scope
{
    private $name;
    private $parent;

    /**
     * 
     */
    public function __construct($name, Scope $parent = null)
    {
    	if (!is_string($name) || 0 >= strlen($name)) {
    		throw new ScopeException("Scope: param 'name' needs to be string");
    	}   	
        $this->name = $this->normalize($name);
        $this->parent = $parent;
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
    public function getParent()
    {
        return $this->parent;
    }
    
    public static function validate(Scope $scope)
    {
    	$scopeTokenRegExp = '(?:\x21|[\x23-\x5B]|[\x5D-\x7E])+';
    	$scopeRegExp = sprintf('/^%s(?: %s)*$/', $scopeTokenRegExp, $scopeTokenRegExp);
    	$result = preg_match($scopeRegExp, $scope->getName());
    	if (1 !== $result) {
    		throw new ScopeException(sprintf("Scope: invalid scope '%s'", $scope->getName()));
    	}
    }
    
    private function normalize($scope_name)
    {
    	$explodedScope = explode(" ", $scope_name);
    	sort($explodedScope, SORT_STRING);
    
    	return implode(" ", array_values(array_unique($explodedScope, SORT_STRING)));
    }    
}
