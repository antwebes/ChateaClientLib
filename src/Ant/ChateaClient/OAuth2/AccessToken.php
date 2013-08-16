<?php

namespace Ant\ChateaClient\OAuth2;

class AccessToken extends Token
{
    /** token_type VARCHAR(255) NOT NULL */
    private $tokenType;

    /** expires_in INTEGER DEFAULT NOT NULL */
    private $expiresIn;

    public function __construct(array $data)
    {
        parent::__construct($data);

        foreach (array('token_type', 'access_token','expires_in') as $key) {
            if (!array_key_exists($key, $data)) {
                throw new TokenException(sprintf("missing field '%s'", $key));
            }
        }
        $this->setValue($data['access_token']);
        $this->setTokenType($data['token_type']);
        $expiresIn = array_key_exists('expires_in', $data) ? $data['expires_in'] : null;        
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
            throw new TokenException(sprintf("missing field tokenType with value '%s'", $tokenType));
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
    
    /**
     * 
     * @return boolean
     */
    public function hasExpired(){
    	return time() > ($this->getIssueTime() + $this->getExpiresIn());
    }
}
