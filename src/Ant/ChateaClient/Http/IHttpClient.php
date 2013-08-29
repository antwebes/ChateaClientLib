<?php
namespace Ant\ChateaClient\Http; 

interface IHttpClient
{
	const SERVER_ENDPOINT 			= "http://api.chateagratis.local/app_dev.php/";
	const TOKEN_ENDPOINT 			= "http://api.chateagratis.local/app_dev.php/oauth/v2/token";
	const AUTHORIZE_ENDPOINT		= "http://api.chateagratis.local/app_dev.php/oauth/v2/auth";
	const REVOKE_ENDPOINT			= "http://api.chateagratis.local/app_dev.php/oauth/v2/revoke";
				
	public function addGetData($data = null, $uri = null);
	public function addPostData($data = null, $uri = null);
	public function addDeleteData($data= null, $uri = null);
	public function addPutData($data = null, $uri = null);
	public function addPatchData($data =  null, $uri = null);
	public function getHeaderAccept();
	public function getRequest();
	public function getResponse();
	public function send($json_format = true);
	public function setBaseUrl($url);
	public function getUrl();
}
