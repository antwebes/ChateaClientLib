<?php
namespace Ant\ChateaClient\Http;

use Guzzle\Http\Client;
use Ant\ChateaClient\Http\HttpClientException;
use Ant\ChateaClient\OAuth2\AccessToken;

class HttpClient extends Client implements IHttpClient
{

	private $request;
	private $response;
	private $accesToken;	
	private $accept_header;
	private $content_type_header;
	 
	public function __construct(
				$baseUrl = '', 
				AccessToken $accesToken = null,
				$config = null,
				$accept_header = 'application/json',  
				$content_type_header = 'application/json'
	){
		
	    if (!is_string($baseUrl) || 0 >= strlen($baseUrl)) {
            $baseUrl = self::SERVER_ENDPOINT;
        }
        
        if (!is_string($accept_header) || 0 >= strlen($accept_header)) {
        	$accept_header = 'application/json';
        }
        if (!is_string($content_type_header) || 0 >= strlen($content_type_header)) {
        	$content_type_header = 'application/json';
        }
                        
        $this->request 	= null;
        $this->response = null;
        $this->accesToken = $accesToken;
        $this->accept_header = $accept_header;
        $this->content_type_header = $content_type_header;
        
		parent::__construct($baseUrl,$config);	
	}
	
	public function addGetData($data = null, $uri = null){	
		$this->request = $this->createRequest("GET", $uri, null, array('query' =>$data), array() );
	}
	
	public function addPostData($data = null, $uri = null)
	{
		
		$this->request = $this->createRequest("POST",$uri,null,json_encode($data), array());
	}
	
	public function addDeleteData($data= null, $uri = null)
	{
		$this->request = $this->createRequest("DELETE",$uri,null,json_encode($data), array());		
	}
	
	public function addPutData($putData = null, $uri = null)
	{
		$this->request = $this->createRequest("PUT",$uri,null,json_encode($data), array());		
	}		
	public function addPatchData($patchData =  null, $uri = null)
	{
		$this->request = $this->createRequest("PATCH",$uri,null,json_encode($data), array());
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
	public function getContentHeader()
	{
		return $this->content_type_header;	
	}
	
	public function addAccesToken(AccessToken $accesToken)
	{
		if(null == $accesToken || !$accesToken){
			throw new HttpClientException("AccessToken is not null",$this);
		}
		if (!is_string($accesToken->getValue()) || 0 >= strlen($accesToken->getValue())) {
			throw new HttpClientException("AccessToken value must be a non-empty string",$this);
		}
	
		$this->accesToken = $accesToken;
	}	
	public function setBaseUrl($url)
	{
		parent::setBaseUrl($url);
	}
	public function getUrl(){
		return $this->getBaseUrl();
	}
	public function send($json_format = true)
	{
		$headers = array(
				'Accept'=>$this->getHeaderAccept(),
				'Content-type'=>$this->getContentHeader(),
		);
				
		if($this->accesToken !== null){
			
			$headers['Authorization']= sprintf("%s %s", 
						$this->accesToken->getTokenType()->getName(),
						$this->accesToken->getValue()
						);
			ld($headers);
		}
		
		if(null == $this->request){
			$this->request = $this->get("/");
		}
		$this->request->addHeaders($headers);		
		
		try {
		$this->response = parent::send($this->request);
		}catch (\Guzzle\Http\Exception\BadResponseException $ex){						
			throw new HttpClientException(
					"Error to send request in HttpClient: ".$ex->getMessage(),
					$this,
					$ex->getResponse()->getMessage()
			);
		}catch (\Exception $ex){
			throw $ex;
		}	
		
		return $this->response->getBody($json_format); 
			
	}	
}