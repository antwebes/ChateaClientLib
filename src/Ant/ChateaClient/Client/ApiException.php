<?php

namespace Ant\ChateaClient\Client;
use Ant\ChateaClient\Http\IHttpClient;

class ApiException extends \Exception
{
	private $httpClient;
	
	public function __construct ($message = '', IHttpClient $httpClient = null, $code = 0, $previous = null)
	{
		if($httpClient != null){
			$this->httpClient = $httpClient;
		}
		parent::__construct($message,$code,$previous);
	}
	
	public function getHttpClient()
	{
		return $this->httpClient;	
	}
	public function status code
}