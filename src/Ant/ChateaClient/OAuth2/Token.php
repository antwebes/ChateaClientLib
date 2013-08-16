<?php

namespace Ant\ChateaClient\OAuth2;

class Token
{

    /** value VARCHAR(255) NOT NULL */
    private $value;

    /** scope VARCHAR(255) NOT NULL */
    private $scope;

    /** issue_time INTEGER NOT NULL */
    private $issueTime;

    public function __construct(array $data)
    {
    	if (!is_string($data['token_value']) || 0 >= strlen($data['token_value'])) {
                throw new TokenException(sprintf("missing field token_value is '%s'", $data['token_value']));            
        }
        $this->setValue($data['token_value']);        
        $this->setScope($data['scope']?$data['scope']:null);
        $this->setIssueTime($data['issue_time']?$data['issue_time']:time());
    }
    
    /**
     * 
     * @param string $token_value
     * @throws TokenException
     */
    public function setValue($token_value)
    {
        if (!is_string($token_value) || 0 >= strlen($token_value)) {
            throw new TokenException("token_value needs to be a non-empty string");
        }
        $this->value = $token_value;
    }
	/**
	 * 
	 * @return string
	 */
    public function getValue()
    {
        return $this->value;
    }
    
	/**
	 * 
	 * @param Scope $scope
	 */
    public function setScope(Scope $scope)
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
            throw new TokenException(sprintf("missing field scope is '%s'", $scope));
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
            throw new TokenException("issue_time should be positive integer");
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
}
