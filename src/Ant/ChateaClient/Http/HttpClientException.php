<?php
namespace Ant\ChateaClient\Http;

use Ant\ChateaClient\Http\IHttpClient;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Message\RequestInterface;

class HttpClientException extends \Guzzle\Http\Exception\BadResponseException 
{
	protected $httpClient;
	protected $response;
	protected $request; 
	
	public function __construct (
			$message = null, 
			IHttpClient $httpClient = null,  
			RequestInterface $request = null,  
			Response $response = null,
			$code = 0, 
			$previous = null 
	){
		if($httpClient != null){
			$message = ' ['.get_class($httpClient).'] '.$message;
		}	
		if($response){
			$message .= " [Response message] ".$response->__toString();
		}
		$this->httpClient = $httpClient;
		$this->request = $request;
		$this->response = $response;
		parent::__construct($message,$code,$previous);
	}

	public function getHttpClient()
	{
		return $this->httpClient;
	}
	public function getResponse()
	{
		return $this->response;
	}
	public function getRequest()
	{
		return $this->request;
	}
	public function getStatusCode()
	{
		return $this->response?$this->response->getStatusCode():null;
	}
	public function getServerError()
	{
		return $this->response?$this->response->getBody(true):null;
	}
	public function getHeaders()
	{
		return $this->response?$this->response->getHeaders()->toArray():null;
	}
}