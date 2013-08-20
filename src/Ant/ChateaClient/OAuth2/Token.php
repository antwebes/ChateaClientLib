<?php

namespace Ant\ChateaClient\OAuth2;

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
    private $scope;

    /** issue_time INTEGER NOT NULL */
    private $issueTime;

    
    /**
     * Create a new object of type Token 
     * 
     * @param string $tokenValue the string what represent data in base64
     * @param Scope $scope A Scope is a permission setting that specifies access to a users, to non-public data.
     * @param string $issueTime
     */
    public function __construct($tokenValue, Scope $scope = null,  $issueTime = null)
    {

    	$this->setValue($tokenValue);        

        $this->setScope($scope);
        
        $issueTime = $issueTime !== null && is_numeric($issueTime)?$issueTime:time();
        $this->setIssueTime($issueTime);        
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
            throw new TokenException("Token: token_value needs to be a non-empty string");
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
    public function setScope(Scope $scope = null)
    {
        if ($scope) {
        	Scope::validate($scope);        	 
        }        
        
        $this->scope = $scope;
    }

    /**
     * 
     * @return Scope
     */
    public function getScope()
    {
        return $this->scope;
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
        
        return $this->scope === $scope;
    }

    /**
     * Unix time
     * @param integer $issueTime
     * @throws TokenException
     */
    public function setIssueTime($issueTime)
    {
        if (!is_numeric($issueTime) || 0 >= $issueTime) {
            throw new TokenException("Token: issue_time should be positive integer");
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
