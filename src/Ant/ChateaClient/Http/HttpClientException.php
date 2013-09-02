<?php
namespace Ant\ChateaClient\Http;

use Ant\ChateaClient\Http\IHttpClient;

class HttpClientException extends \Exception 
{
	protected $httpClient;
	protected $responseMessage;
	protected $errorMessage;
	public function __construct (
			$message = null, 
			IHttpClient $httpClient = null,  
			$responseMessage = '',  
			$errorMessage = '',
			$code = 0, 
			$previous = null 
	){

		$message .= '\n\t Response message: '.$responseMessage;	
		if($httpClient != null){
			$message = get_class($httpClient).': '.$message;
		}
		$this->httpClient = $httpClient;
		$this->responseMessage = $responseMessage;
		$this->errorMessage = $errorMessage;
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
	public function getErrorMensage()
	{
		return $this->errorMessage;
	}
}