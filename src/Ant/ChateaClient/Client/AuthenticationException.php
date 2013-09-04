<?php
namespace Ant\ChateaClient\Client;

class AuthenticationException extends \Exception
{

	private $auth;
	
	public function __construct (
			$message = "",
			IAuthentication $auth = null,
			$code = 0,
			$previous = NULL
	){
	
		if($auth !== NULL){
			$message = get_class($auth).': '.$message;
		}
		$this->auth = $auth;
	
		parent::__construct($message,$code,$previous);
	}
		
	
}