<?php
namespace Ant\ChateaClient\Http; 

use Ant\ChateaClient\OAuth2\AccessToken;

interface IHttpClient
{
	const SEND_RESPONSE_TYPE_JSON	= 'json';
	const SEND_RESPONSE_TYPE_XML	= 'xml';
	const SEND_RESPONSE_TYPE_ARRAY	= 'array';
	const SERVER_ENDPOINT 			= "http://api.chateagratis.local/app_dev.php/";
	const TOKEN_ENDPOINT 			= "http://api.chateagratis.local/app_dev.php/oauth/v2/token";
	const AUTHORIZE_ENDPOINT		= "http://api.chateagratis.local/app_dev.php/oauth/v2/auth";
	const REVOKE_ENDPOINT			= "http://api.chateagratis.local/app_dev.php/api/oauth/v2/revoke";
					
	public function addGet($uri = null, $data = null);
	public function addPost($uri = null, $data = null, $contentType = null);
	public function addPostFile($filename = null, $field = 'file', $contentType = null);
	public function addPostFiles(array $files);
	public function addDelete($uri = null, $data= null,$contentType = null);
	public function addPut($uri = null,$data = null, $contentType = null);
	public function addPatch($uri = null, $data = null, $contentType = null);
	public function addAccesToken(AccessToken $accesToken);
	public function getHeaderAccept();
	public function getRequest();
	public function getResponse();
	public function send($response_type = 'json');
	public function setBaseUrl($url);
	public function getUrl();
	public static function parseRouting($uri, $params = null);
}
