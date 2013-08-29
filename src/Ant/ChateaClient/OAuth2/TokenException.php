<?php
namespace Ant\ChateaClient\OAuth2;

class TokenException extends \Exception
{
	private $token;
	
	public function __construct (
			string $message = "",
			Token $token = null,
			int $code = 0,
			Exception $previous = NULL
	){
	
		if($token != null){
			$message = get_class($token).': '.$message;
		}
		$this->token = $token;
		
		parent::__construct($message,$code,$previous);
	}
	
	public function getToken()
	{
		return $this->token;	
	}
}
