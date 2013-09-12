<?php

namespace Ant\ChateaClient\Http;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\BadResponseException;
use Ant\ChateaClient\Http\HttpClientException;
use Ant\ChateaClient\OAuth2\AccessToken;

class HttpClient extends Client implements IHttpClient {
	private $request;
	private $response;
	private $accesToken;
	private $accept_header;
	private $server_error; 	
	public function __construct(
			$baseUrl = '', 					
			$accept_header = 'application/json',
			AccessToken $accesToken = null
	) {
		
		if (! is_string ( $baseUrl ) || 0 >= strlen ( $baseUrl )) {
			$baseUrl = self::SERVER_ENDPOINT;
		}
		
		parent::__construct ( $baseUrl, null );
		
		if (! is_string ( $accept_header ) || 0 >= strlen ( $accept_header )) {
			$accept_header = 'application/json';
		}
		$this->accept_header = $accept_header;
		$this->defaultHeaders->add('Accept',$this->accept_header);
		
		$this->request = null;
		$this->response = null;
		$this->server_error = null;
		
		if($accesToken !== null){
			$this->addAccesToken($accesToken);
		}		
	}
	public static function parseRouting($uri, $params = null)
	{		
		if(empty($params)){
			return $uri;
		}
		
		$pattern = '/{\w+}/';
		
		if(is_array($params)){
			$leng = count($params);

			if(preg_match_all('/({\w+})/',$uri) !== $leng)
			{
				throw new HttpClientException('HttpClientException::parseRouting() ERROR: The params number does not match with the path');
			}			
			$pattern = array_fill(0, $leng, "/{\w+}/");
		}
		
		return preg_replace($pattern, $params, $uri,1);
	}	
	private function addRequest($method = 'GET', $uri = null, $data = null, $contentType = null)
	{
		$headers = null;
		$options = array();
		
		if($contentType){
			
			$headers = array('Content-Type'=>$contentType);
			
			if($contentType === 'application/json' && !self::isJson($data)){
				$data = json_encode($data);
			} 				
		}
		$this->request = $this->createRequest($method,$uri,$headers,$data,$options);
	}	
	/**
	 * Add a POST file to the upload
	 *
	 * @param string $filename    Full path to the file. Do not include the @ symbol.
	 * @param string $field       POST field to use (e.g. file). Used to reference content from the server.	 
	 * @param string $contentType Optional Content-Type to add to the Content-Disposition.
	 *                            Default behavior is to guess. Set to false to not specify.
	 */
	public function addPostFile($filename = null, $field = 'file', $contentType = null)
	{
		if(!file_exists($filename)){
			throw new HttpClientException("The file not existm put valid filename or path",$this);
		}		
		if($this->request === NULL){
			$this->request = $this->createRequest("POST");
		}
		$this->request->addPostFile($field, $filename, $contentType);
	}
	/**
	 * Add POST files to use in the upload
	 *
	 * @param array $files An array of POST fields => filenames where filename can be a string
	 *
	 */
	public function addPostFiles(array $files)
	{
		foreach ($files as $key => $filename) {
			 if (is_string($filename)) {
				// TODO Convert non-associative array keys into 'file'
				if (is_numeric($key)) {
					$key = 'file';
				}
				$this->addPostFile($filename,$key, null);
			} else {
				$this->addPostFile($filename, null, null);
			}
		}		
	}
	
	public function addGet($uri = null, $data = null)
	{
		$this->addRequest ( "GET", $uri, array ('query' => $data ));
	}
	public function addPost($uri = null, $data = null, $contentType = null)
	{
		$this->addRequest ("POST", $uri,$data, $contentType);
	}	
	public function addDelete($uri = null, $data = null, $contentType = null)
	{
		$this->addRequest ("DELETE",$uri,$data, $contentType);
	}
	public function addPut($uri = null, $data = null, $contentType = null)
	{
		$this->addRequest ("PUT",$uri,$data, $contentType);		
	}
	public function addPatch($uri = null, $data = null, $contentType = null)
	{
		$this->addRequest ("PATCH",$uri,$data, $contentType);
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
	public function gettHeaders() 
	{
		return $this->request?$this->request->getHeaderLines():$this->defaultHeaders;
	}
	public function setBaseUrl($url)
	{
		parent::setBaseUrl ( $url );
	}
	
	public function getUrl()
	{
		if($this->request !== null){
			return $this->request->getUrl();
		}
		return $this->getBaseUrl();
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
		//FIXME you this has to be:   type =  $this->accesToken->getTokenType ()->getName ()				
		$this->defaultHeaders->add('Authorization', sprintf ( "Bearer %s", $accesToken->getValue () ));		
	}

	public function send($response_type = 'json') 
	{
		$method = null;
		$arguments = null;

		switch ($response_type)
		{		
			case 'xml':
				$this->accept_header = $this->accept_header.',application/xml';
				$method = 'xml';
				$arguments = null;
				break;
			case 'json':
				$this->accept_header = $this->accept_header.',application/json';
				$method = 'getBody';
				$arguments = true;				
				break;
			case 'array':
					$this->accept_header = $this->accept_header.',application/json';
					$method = 'json';										
				break;					
			default:
				$method = 'getBody';
				$arguments = true;
				break;						
		}		
		$this->request->addHeader('Accept',$this->accept_header);
		try {
			$this->response = parent::send ( $this->request );
		} catch (BadResponseException $ex ) {			
			$this->server_error = $ex->getResponse ()->getBody(true);
			throw new HttpClientException ( 
					"Error to send request in HttpClient: " . $ex->getMessage (), 
					$this, 
					$ex->getRequest(), 
					$ex->getResponse (), 
					$ex->getCode (), 
					$ex 
			);
		} catch (ClientErrorResponseException $ex ){	
			$this->server_error = $ex->getResponse ()->getBody(true);
			throw new HttpClientException ( 
					"Error to send request in HttpClient: " . $ex->getMessage (), 
					$this, 
					$ex->getRequest(), 
					$ex->getResponse (), 
					$ex->getCode (), 
					$ex 
			);
    	}    	
    	return $this->response->$method($arguments);
	}
	
	private static function isJson($string) 
	{
		if (! is_string ( $string ) || 0 >= strlen ( $string )) 
		{
			return false;
		}
		json_decode ( $string );
		return (json_last_error () == JSON_ERROR_NONE);
	}	
}