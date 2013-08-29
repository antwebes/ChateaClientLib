<?php
namespace Ant\ChateaClient\Http;
use Guzzle\Http\Client;
use Guzzle\Common\Collection;
use Ant\ChateaClient\Http\Exception\HttpClientException;

class HttpClient extends Client implements IHttpClient
{

	private $request;
	private $response;
	private $bearerToken;	
	private $accept_header;
	private $content_type_header;
	 
	public function __construct(
				$baseUrl = '', 
				$bearerToken = '',
				$accept_header = 'application/json',  
				$content_type_header = 'application/json'
	){
		
	    if (!is_string($token_value) || 0 >= strlen($token_value)) {
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
        $this->bearerToken = $bearerToken;
        $this->accept_header = $accept_header;
        $this->content_type_header = $content_type_header;
        
		parent::__construct($baseUrl,$config);	
	}
	
	public function addGetData($data = null, $uri = null){	
		$this->request = $this->createRequest("GET", $uri, null, array('query' =>$data), array() );
	}
	public function addPostData($data, $uri)
	{
		
		$this->request = $this->createRequest("POST",$uri,null,json_encode($data), array());
	}
	public function addDeleteData($postData= null, $uri = null)
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
	public function getAcceptHeader()
	{
		return $this->accept_header;
	}
	public function getAcceptHeader()
	{
		return $this->accept_header;
	}	
	public function getContentHeader()
	{
		return $this->content_type_header;	
	}
	
	public function addBearerToken($bearerToken){
		if (!is_string($bearerToken) || 0 >= strlen($bearerToken)) {
			throw new HttpClientException("BearerToken must be a non-empty string",$this);
		}		
		$this->$bearerToken = $bearerToken;
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
				'Content-type'=>$this->getContentHeader()
		);
				
		if(!empty($this->bearerToken)){
			array_push($headers, array('Authorization'=> sprintf("Bearer %s", $this->bearerToken)));
		}
		
		$this->defaultHeaders = new Collection($headers);
		$this->response = parent::send($this->request);				
		return $this->response->getBody($json_format); 
			
	}	
}