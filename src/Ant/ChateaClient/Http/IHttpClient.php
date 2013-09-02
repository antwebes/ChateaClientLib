<?php
namespace Ant\ChateaClient\Http; 

use Ant\ChateaClient\OAuth2\AccessToken;

interface IHttpClient
{
	const SERVER_ENDPOINT 			= "http://api.chateagratis.local/app_dev.php/";
	const TOKEN_ENDPOINT 			= "http://api.chateagratis.local/app_dev.php/oauth/v2/token";
	const AUTHORIZE_ENDPOINT		= "http://api.chateagratis.local/app_dev.php/oauth/v2/auth";
	const REVOKE_ENDPOINT			= "http://api.chateagratis.local/app_dev.php/oauth/v2/revoke";
					
	public function addGet($uri = null, $data = null);
	public function addPost($uri = null,$data = null);
	public function addDelete($uri = null, $data= null);
	public function addPut($uri = null,$data = null);
	public function addPatch($uri = null, $data =  null);
	public function addAccesToken(AccessToken $accesToken);
	public function getHeaderAccept();
	public function getRequest();
	public function getResponse();
	public function send($json_format = true);
	public function setBaseUrl($url);
	public function getUrl();
}
