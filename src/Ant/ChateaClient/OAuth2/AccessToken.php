<?php

namespace Ant\ChateaClient\OAuth2;

/**
 * An access token is an object that describes the security context of a 
 * process or thread. The information in a token includes the identity and 
 * privileges of the user account associated with the process or thread. 
 * When a user logs on, the system verifies the user's password by comparing 
 * it with information stored in a security database. 
 * If the password is authenticated, the system produces an access token. 
 * Every process executed on behalf of this user has a copy of this access token.
 * 
 * @author ant@anteweb.es
 *
 */
class AccessToken extends Token
{
    /** token_type VARCHAR(255) NOT NULL */
    private $tokenType;

    /** expires_in INTEGER DEFAULT NOT NULL */
    private $expiresIn;
    
    public function __construct($tokenValue, TokenType $tokenType = null, $expiresIn = 3600, $issueTime = null, Scope $scope = null)
    {
    	
    	parent::__construct($tokenValue,$issueTime,$scope);
        
    	if($tokenType === null){
    		$tokenType = new TokenType(TokenType::BEARER);
    	}
    	
        $this->setTokenType($tokenType);
        
        //FIXME: is required param expires_in                
        $this->setExpiresIn($expiresIn);        

    }

    /**
     * 
     * @param TokenType $tokenType
     * @throws TokenException
     */
    public function setTokenType(TokenType $tokenType)
    {
        if (!$tokenType) {
            throw new TokenException(sprintf("AccessToken: missing field tokenType with value '%s'", $tokenType));
        }

        $this->tokenType = $tokenType;
    }

    /**
     * 
     * @return TokenType
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }

    /**
     * Unix time
     * @param integer $expiresIn
     * @throws TokenException
     */
    public function setExpiresIn($expiresIn)
    {
        if (null !== $expiresIn) {
            if (!is_numeric($expiresIn) || 0 >= $expiresIn) {
                throw new TokenException("expires_in should be positive integer or null");
            }
            $expiresIn = (int) $expiresIn;
        }
        $this->expiresIn = $expiresIn;
    }

    /**
     * @return  integer unix time
     */
    public function getExpiresIn()
    {
        return $this->expiresIn;
    }
    
    public function getExpiresAt(){
    	
    	return $this->getExpiresIn()+ $this->getIssueTime();
    } 
    /**
     * true if expired token.
     * 
     * @return boolean
     */
    public function hasExpired(){
    	return time() > $this->getExpiresAt();
    }    
}
