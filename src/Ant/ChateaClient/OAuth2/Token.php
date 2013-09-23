<?php

namespace Ant\ChateaClient\OAuth2;

use Symfony\Component\Validator\Constraints\Collection;
/**
 * 
 * A Token is a piece of data in base64, that is used in network 
 * communications to identify a session, a series of related message exchanges. 
 * 
 * @author ant@antweb.es
 */
class Token
{

    /** value VARCHAR(255) NOT NULL */
    private $value;

    /** scope VARCHAR(255) NOT NULL */
    private $scopes;

    /** issue_time INTEGER NOT NULL */
    private $issueTime;

    
    /**
     * Create a new object of type Token 
     * 
     * @param string $tokenValue the string what represent data in base64
     * @param string $issueTime
     * @param array $scope A Scope is a permission setting that specifies access to a users, to non-public data.
     */
    public function __construct($tokenValue, $issueTime = null, array $scopes = array())
    {    	
    	$this->setValue($tokenValue);        
    	
    	$this->setIssueTime($issueTime);
    	    	
        $this->setScope($scopes);
        		      
    }
    
    /**
     * Update de token value.
     * 
     * @param string $token_value 
     * 	the string what represent data in base64
     * 
     * @throws TokenException 
     * 	This exception is running, if $token value is not a string or it's a empty string
     */
    public function setValue($token_value)
    {
        if (!is_string($token_value) || 0 >= strlen($token_value)) {
            throw new TokenException("Token: token_value needs to be a non-empty string", $this);
        }
        $this->value = $token_value;
    }
	/**
	 * Retrive de token value
	 *  
	 * @return string
	 */
    public function getValue()
    {
        return $this->value;
    }
    
	/**
	 * Update de scope 
	 * 
	 * @param Scope $scope 
	 * 	
	 */
    public function setScope(array $scopes = array())
    {
        if ($scopes) {
            foreach ($scopes as $scope){
        	   Scope::validate($scope);   
            }     	 
        }        
        
        $this->scopes = $scopes;
    }

    /**
     * 
     * @return Collection of Scope
     */
    public function getScope($asString = false)
    {
        if($asString && is_array($this->scopes))
        {
        	return json_encode($this->scopes,JSON_PRETTY_PRINT);
        }
        return $this->scopes;
    }
	/**
	 * 
	 * @param Scope $scope
	 * @throws TokenException
	 * @return boolean
	 */
    public function hasScope(Scope $scope)
    {
        if (!$scope) {
            throw new TokenException(sprintf("Token: missing field scope is '%s'", $scope));
        }
        
        Scope::validate($scope);
        
        return array_search($scope, $this->scope) === true;
    }

    /**
     * Unix time
     * @param integer $issueTime
     * @throws TokenException
     */
    public function setIssueTime($issueTime = null)
    {
    	
    	if (null !== $issueTime) {
    		if (!is_numeric($issueTime) || 0 >= $issueTime) {
                throw new TokenException("issueTime should be positive integer or null", $this);
            }
            
            $issueTime = (int) $issueTime;            
        }   
        $this->issueTime = (int) $issueTime;
        
    }
	/**
	 * 
	 * @return integer
	 */
    public function getIssueTime()
    {
        return $this->issueTime;
    }
    
    public function __toString(){
    	return $this->value;
    }
}
