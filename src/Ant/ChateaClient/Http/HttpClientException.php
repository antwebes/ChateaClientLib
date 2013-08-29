<?php
namespace Ant\ChateaClient\Http\Exception;

use Ant\ChateaClient\Http\IHttpClient;

class HttpClientException extends \Exception 
{
	protected $httpClient;
	protected $responseMessage;
	
	public function __construct (
			string $message = "", 
			IHttpClient $httpClient = null,  
			string $responseMessage = '', 
			int $code = 0, 
			Exception $previous = NULL 
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