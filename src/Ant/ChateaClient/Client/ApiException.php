<?php

namespace Ant\ChateaClient\Client;
use Ant\ChateaClient\Http\HttpClientException;

class ApiException extends \Exception
{
	private $httpClientException;
	
	public function __construct ($message = '', HttpClientException $httpClientException = null, $code = 0, $previous = null)
	{
		$this->httpClientException = $httpClientException;		
		parent::__construct($message,$code,$previous);
	}
	
	public function getHttpException()
	{
		return $this->httpClientException;	
	}
	public function getStatusCode()
	{
		return $this->httpClientException? $this->httpClientException->getStatusCode():'null';
	}
	public function getServerError()
	{
		return $this->httpClientException? $this->httpClientException->getServerError():'null';
	}	
	
	public function __toString()
	{
		return $this->getMessage();
	}
}