<?php

namespace Ant\ChateaClient\OAuth2;

interface ChateaConfigInterface
{

	public function getServerEndpoint();
	public function setServerEndPoind($serverEndPoind);
	public function getAuthorizeEndpoint();
    public function setAuthorizeEndpoint($authorizeEndpoint);
    public function getTokenEndpoint();
    public function setTokenEndpoint($tokenEndpoint);
    public function getRevokeEndpoint();
    public function setRevokeEndpoint($revokeEndpoint);
        
}