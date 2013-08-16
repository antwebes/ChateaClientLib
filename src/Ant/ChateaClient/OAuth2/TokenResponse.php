<?php

namespace Ant\ChateaClient\OAuth2;

class TokenResponse
{
    private $accessToken;
    private $tokenType;
    private $expiresIn;
    private $refreshToken;
    private $scope;

    public function __construct(AccessToken $accessToken, TokenType $tokenType)
    {
    	if (!$accessToken) {
    		throw new TokenResponseException(sprintf("missing field accessToken value is '%s'", $accessToken));
    	}
    	if (!$tokenType) {
    		throw new TokenResponseException(sprintf("missing field tokenType value is '%s'", $tokenType));
    	}    	
    	$this->setAccessToken($accessToken);
        $this->setTokenType($tokenType);
        $this->setExpiresIn(null);
        $this->setRefreshToken(null);
        $this->setScope(null);
    }

    public static function fromArray(array $data)
    {
        foreach (array('access_token', 'token_type') as $key) {
            if (!array_key_exists($key, $data)) {
                throw new TokenResponseException(sprintf("missing field '%s'", $key));
            }
        }
        $tokenResponse = new static(new AccessToken($data['access_token']), new TokenType($data['token_type']));
        
        if (array_key_exists('expires_in', $data)) {
            $tokenResponse->setExpiresIn($data['expires_in']);
        }
        if (array_key_exists('refresh_token', $data)) {
            $tokenResponse->setRefreshToken(new RefreshToken($data['refresh_token']));
        }
        if (array_key_exists('scope', $data)) {
            $tokenResponse->setScope(new Scope($data['scope']));
        }

        return $tokenResponse;
    }

    /**
     * 
     * @param AccessToken $accessToken
     */
    public function setAccessToken(AccessToken $accessToken)
    {
    	if (!$accessToken) {
    		throw new TokenResponseException(sprintf("missing field '%s'", $accessToken));
    	}   
    	 	
        $this->accessToken = $accessToken;
    }

    /**
     * 
     * @return AccessToken
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * 
     * @param TokenType $tokenType
     */
    public function setTokenType(TokenType $tokenType)
    {
    	if (!$tokenType) {
    		throw new TokenResponseException(sprintf("missing field '%s'", $tokenType));
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
     * 
     * @param Integer $expiresIn
     */
    public function setExpiresIn($expiresIn)
    {
        $this->expiresIn = $expiresIn;
    }

    public function getExpiresIn()
    {
        return $this->expiresIn;
    }

    /**
     * 
     * @param RefreshToken $refreshToken
     */
    public function setRefreshToken(RefreshToken $refreshToken)
    {
    	if (!$refreshToken) {
    		throw new TokenResponseException(sprintf("missing field '%s'", $refreshToken));
    	}    	
        $this->refreshToken = $refreshToken;
    }

    /**
     * 
     * @return RefreshToken
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * 
     * @param Scope $scope
     */
    public function setScope(Scope $scope)
    {
        $this->scope = $scope;
    }

    /**
     * @return Scope
     */
    public function getScope()
    {
        return $this->scope;
    }
}
