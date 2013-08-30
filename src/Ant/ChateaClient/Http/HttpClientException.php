<?php
namespace Ant\ChateaClient\Http;

use Ant\ChateaClient\Http\IHttpClient;

class HttpClientException extends \Exception 
{
	protected $httpClient;
	protected $responseMessage;
	
	public function __construct (
			$message = null, 
			IHttpClient $httpClient = null,  
			$responseMessage = null, 
			$code = null, 
			\Exception $previous = null 
	){

		$message .= '\n\t Response message: '.$responseMessage;	
		if($httpClient != null){
			$message = get_class($httpClient).': '.$message;
		}
		$this->httpClient = $httpClient;
		$this->responseMessage = $responseMessage;
		parent::__construct($message,$code,$previous);
	}

	public function getHttpClient()
	{
		return $this->httpClient;
	}
	
	public function getResponseMessage()
	{
		return $this->responseMessage;
	}
}