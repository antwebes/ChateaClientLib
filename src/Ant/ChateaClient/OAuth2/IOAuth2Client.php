<?php
namespace Ant\ChateaClient\OAuth2; 

interface IOAuth2Client
{

	public function getPublicId();
	public function getSecret();
	public function getRedirectUri();
}