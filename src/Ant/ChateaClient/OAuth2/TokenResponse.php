<?php

namespace Ant\ChateaClient\OAuth2;

class TokenResponse
{
    private $accessToken;
    private $refreshToken;
    private $tokenType;
    private $expiresIn;    
    private $scope;

    public function __construct(AccessToken $accessToken, RefreshToken $refreshToken, $expiresIn, Scope $scope = null )
    {
    	if (!$accessToken) {
    		throw new TokenResponseException(sprintf("missing field accessToken value is '%s'", $accessToken));
    	}
   	
    	$this->setAccessToken($accessToken);
        $this->setTokenType($accessToken->getTokenType());
        $this->setExpiresIn($expiresIn);
        $this->setRefreshToken($refreshToken);
        $this->setScope($scope);
    }

    public static function fromArray(array $data)
    {
        foreach (array('access_token', 'token_type','refresh_token','expires_in') as $key) {
            if (!array_key_exists($key, $data)) {
                throw new TokenResponseException(sprintf("missing field '%s'", $key));
            }
        }
        

        $scope = null;
        if (array_key_exists('scope', $data) && $data['scope'] !== null) {
        	$scope = new Scope($data['scope']);
        }
        $tokenResponse = null;        
        if (array_key_exists('refresh_token', $data)) {
        	$tokenResponse = new RefreshToken($data['refresh_token'],time(),$scope);
        }
                
        $tokenResponse = new static(
        		new AccessToken(
        				$data['access_token'],
        				new TokenType($data['token_type']),
        				$data['expires_in'],
        				time(),
        				$scope        				
        		),
        		$tokenResponse,
        		$data['expires_in'],
        		$scope
        	);
        
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
    public function setRefreshToken(RefreshToken $refreshToken = null)
    {
    	if (!$refreshToken) {
    		throw new TokenResponseException(sprintf("TokenResponse: missing field refreshToken with value '%s'", $refreshToken));
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
    public function setScope(Scope $scope = null)
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
