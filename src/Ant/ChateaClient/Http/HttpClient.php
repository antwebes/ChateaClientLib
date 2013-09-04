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
		
	public function __construct(
			$baseUrl = '', 
			AccessToken $accesToken = null, 			
			$accept_header = 'application/json'
	) {
		
		if (! is_string ( $baseUrl ) || 0 >= strlen ( $baseUrl )) {
			$baseUrl = self::SERVER_ENDPOINT;
		}
		
		if (! is_string ( $accept_header ) || 0 >= strlen ( $accept_header )) {
			$accept_header = 'application/json';
		}
		$this->request = null;
		$this->response = null;
		$this->accesToken = $accesToken;
		$this->accept_header = $accept_header;
		parent::__construct ( $baseUrl, null );
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
		return $this->request?$this->request->getHeaderLines():array();
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
	public function setBaseUrl($url) 
	{
		parent::setBaseUrl ( $url );
	}
	
	public function getUrl() 
	{
		if($this->request !== null){
			return $this->request->getUrl();
		}
		return $this->getBaseUrl ();
	}
	public function send($response_type = 'json') 
	{
		$method = null;
		$arguments = null;
		if (null == $this->request) {
			$this->addRequest();
		}

		switch ($response_type)
		{		
			case 'xml':
				$this->accept_header = 'application/xml';
				$method = 'xml';
				$arguments = null;
				break;
			case 'json':
				$this->accept_header = 'application/json';
				$method = 'getBody';
				$arguments = true;				
				break;
			case 'array':
					$this->accept_header = 'application/json';
					$method = 'json';										
				break;					
			default:
				$this->accept_header = 'application/json';
				$method = 'getBody';
				$arguments = true;
				ld("default");
				break;						
		}		
				
		$this->request->addHeader('Accept', $this->getHeaderAccept());		
		if ($this->accesToken !== null) {
			
			$this->request->addHeader('Authorization', sprintf ( "%s %s", $this->accesToken->getTokenType ()->getName (), $this->accesToken->getValue ()));			
		}		
		try {
			$this->response = parent::send ( $this->request );
		} catch (BadResponseException $ex ) {			
			throw new HttpClientException ( 
					"Error to send request in HttpClient: " . $ex->getMessage (), 
					$this, 
					$ex->getRequest(), 
					$ex->getResponse (), 
					$ex->getCode (), 
					$ex 
			);
		} catch (ClientErrorResponseException $ex ){			
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

	public function getDefaultUserAgent()
	{
        return 'Chatea Client/1.0';
            //. parent::getDefaultUserAgent();
	}
}