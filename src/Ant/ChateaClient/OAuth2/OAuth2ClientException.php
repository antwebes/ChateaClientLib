<?php
namespace Ant\ChateaClient\OAuth2\Exception;

class OAuth2ClientException extends \Exception 
{

	private $client;
	public function public __construct (
			string $message = "",
			IOAuth2Client $client = null,
			int $code = 0,
			Exception $previous = NULL
	){
	
		if($client != null){
			$message = get_class($client).': '.$message
		}
		$this->client = $client;
		parent::__construct($message,$code,$previous);
	}

	public function getOAuth2Client(){
		return $this->client;
	}
}