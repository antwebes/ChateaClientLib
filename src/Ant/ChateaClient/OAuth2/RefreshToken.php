<?php

namespace Ant\ChateaClient\OAuth2;

class RefreshToken extends Token
{
    /** refresh_token VARCHAR(255) NOT NULL */
    private $refreshToken;
    
    /** expires_in INTEGER DEFAULT NULL */
    private $expiresIn;
    
    public function __construct(array $data)
    {
        parent::__construct($data);

        foreach (array('refresh_token') as $key) {
            if (!array_key_exists($key, $data)) {
                throw new TokenException(sprintf("missing field '%s'", $key));
            }
        }

        $this->setValue($data['refresh_token']);
        
        $expiresIn = array_key_exists('expires_in', $data) ? $data['expires_in'] : null;
        $this->setExpiresIn($expiresIn);        
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
    public function setExpiresIn($expiresIin){
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
    	return time() > ($this->getIssueTime() + $this->getExpiresIn());
    }    
}
