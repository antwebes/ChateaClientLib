<?php

namespace Ant\ChateaClient\Http;

use Guzzle\Http\Client;
use Ant\ChateaClient\Http\HttpClientException;
use Ant\ChateaClient\OAuth2\AccessToken;

class HttpClient extends Client implements IHttpClient {
	private $request;
	private $response;
	private $accesToken;
	private $accept_header;
	private $content_type_header;
	public function __construct(
			$baseUrl = '', 
			AccessToken $accesToken = null, 
			$config = null, 
			$accept_header = 'application/json', 
			$content_type_header = 'application/json'
	) {
		
		if (! is_string ( $baseUrl ) || 0 >= strlen ( $baseUrl )) {
			$baseUrl = self::SERVER_ENDPOINT;
		}
		
		if (! is_string ( $accept_header ) || 0 >= strlen ( $accept_header )) {
			$accept_header = 'application/json';
		}
		if (! is_string ( $content_type_header ) || 0 >= strlen ( $content_type_header )) {
			$content_type_header = 'application/json';
		}
		
		$this->request = null;
		$this->response = null;
		$this->accesToken = $accesToken;
		$this->accept_header = $accept_header;
		$this->content_type_header = $content_type_header;
		
		parent::__construct ( $baseUrl, $config );
	}
	public function addGet($uri = null, $data = null) 
	{
		$this->request = $this->createRequest ( "GET", $uri, null, array (
				'query' => self::prepareData ( $data, "GET" ) 
		), array () );
	}
	public function addPost($uri = null, $data = null) 
	{
		$this->request = $this->createRequest ( "POST", $uri, null, self::prepareData ( $data ), array () );
	}
	public function addDelete($uri = null, $data = null) 
	{
		$data = self::isJson ( $data ) ? $data : json_encode ( $data );
		$this->request = $this->createRequest ( "DELETE", $uri, null, self::prepareData ( $data ), array () );
	}
	public function addPut($uri = null, $data = null) 
	{
		$this->request = $this->createRequest ( "PUT", $uri, null, self::prepareData ( $data ), array () );
	}
	public function addPatch($uri = null, $data = null) 
	{
		$this->request = $this->createRequest ( "PATCH", $uri, null, self::prepareData ( $data ), array () );
	}
	public function getRequest() 
	{
		return $this->request;
	}
	public function getResponse() 
	{
		return $this->response;
	}
	public function getHeaderAccept() 
	{
		return $this->accept_header;
	}
	public function getContentHeader() 
	{
		return $this->content_type_header;
	}
	public function addAccesToken(AccessToken $accesToken) 
	{
		if (null == $accesToken || ! $accesToken) {
			throw new HttpClientException ( "AccessToken is not null", $this );
		}
		if (! is_string ( $accesToken->getValue () ) || 0 >= strlen ( $accesToken->getValue () )) {
			throw new HttpClientException ( "AccessToken value must be a non-empty string", $this );
		}
		
		$this->accesToken = $accesToken;
	}
	public function setBaseUrl($url) {
		parent::setBaseUrl ( $url );
	}
	public function getUrl() {
		return $this->getBaseUrl ();
	}
	public function send($json_format = true) 
	{
		$headers = array (
				'Accept' => $this->getHeaderAccept (),
				'Content-type' => $this->getContentHeader () 
		);
		
		if ($this->accesToken !== null) {
			
			$headers ['Authorization'] = sprintf ( "%s %s", $this->accesToken->getTokenType ()->getName (), $this->accesToken->getValue () );
		}
		
		if (null == $this->request) {
			$this->request = $this->get ( "/" );
		}
		$this->request->addHeaders ( $headers );
		
		try {
			$this->response = parent::send ( $this->request );
		} catch ( \Guzzle\Http\Exception\BadResponseException $ex ) {
			
			throw new HttpClientException ( "Error to send request in HttpClient: " . $ex->getMessage (), $this, $ex->getResponse ()->getMessage (), $ex->getResponse ()->getBody ( true ), $ex->getCode (), $ex );
		} catch ( \Exception $ex ) {
			throw $ex;
		}
		
		return $this->response->getBody ( $json_format );
	}
	private static function isJson($string) {
		if (! is_string ( $string ) || 0 >= strlen ( $string )) 
		{
			return false;
		}
		json_decode ( $string );
		return (json_last_error () == JSON_ERROR_NONE);
	}
	private static function prepareData($data, $method = '') {
		if ("GET" === $method) {
			return ($data !== null && ! self::isJson ( $data )) ? $data : json_encode($data);;
		}else{
			return self::isJson($data)?$data:json_encode($data);
		}
	}
}