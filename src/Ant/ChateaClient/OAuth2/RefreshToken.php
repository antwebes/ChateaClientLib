<?php

namespace Ant\ChateaClient\OAuth2;

class RefreshToken extends Token
{
    /** refresh_token VARCHAR(255) NOT NULL */
    private $refreshToken;
    
    /** expires_in INTEGER DEFAULT NULL */
    private $expiresIn;

    
    public function __construct($tokenValue, $expiresIn = null, $issueTime = null, Scope $scope = null)
    {
    	//FIXME: is required param expires_in
    	$this->setExpiresIn($expiresIn);
    	
    	parent::__construct($tokenValue,$issueTime,$scope);                
    }
    
    /**
     * 
     * @return integer unix time
     */
    public function getExpiresIn()
    {
    	return $this->expiresIn;
    }

    /**
     * unix time
     * @param integer $expiresIin
     * @throws TokenException
     */
    public function setExpiresIn($expiresIn){
    	if (null !== $expiresIn) {
    		if (!is_numeric($expiresIn) || 0 >= $expiresIn) {
    			throw new TokenException("expires_in should be positive integer or null");
    		}
    		$expiresIn = (int) $expiresIn;
    	}    	
    	$this->expiresIn = $expiresIn;    	
    }
    
    /**
     *
     * @return boolean
     */
    public function hasExpired(){
    	return time() > $this->getExpiresAt();
    }    

    public function getExpiresAt(){
    	return $this->getExpiresIn()+ $this->getIssueTime();
    }    
}