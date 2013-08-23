<?php
namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\OAuth2\ChateaConfigInterface;
use Ant\ChateaClient\OAuth2\ConfigException;
use Ant\ChateaClient\Client\StorageInterface;

class ChateaConfig implements ChateaConfigInterface {

	private $authorize_endpoint;
	private $token_endpoint;
	private $revoke_endpoint;
	private $server_endpoint;
	private $storage;
	
 	public function __construct(array $data)
 	{
		foreach (array('server_endpoint', 'authorize_endpoint', 'token_endpoint','revoke_endpoint') as $key) {
			if (!array_key_exists($key, $data)) {
				throw new ConfigException(sprintf("missing field '%s'", $key));
			}
		}		
		$this->setServerEndPoind($data['server_endpoint']);
		$this->setAuthorizeEndpoint($data['authorize_endpoint']);
		$this->setTokenEndpoint($data['token_endpoint']);
		$this->setRevokeEndpoint($data['revoke_endpoint']);
						
	}
	public static function createDefaultChateaConfig(){
		return ChateaConfig::fromJSONFile();
	}
	public static function fromJSONFile($filename = "chatea_config.json")
	{

		if(!file_exists($filename) ){
			$filename = __DIR__.'/'.$filename;
			if(!file_exists($filename)){
				throw new ConfigException(sprintf("file '%s' not exist ", $filename));
			}
		}
		
		$chateaConfig = new static(
					json_decode(file_get_contents($filename), true)
		);
		return $chateaConfig;
	}
	public static function fromArray(array $data)
	{
		foreach (array('server_endpoint','authorize_endpoint', 'token_endpoint','revoke_endpoint') as $key) {
			if (!array_key_exists($key, $data)) {
				throw new ConfigException(sprintf("missing field '%s'", $key));
			}
		}		
	
		return new static($data);
	}
	
	public function getServerEndpoint(){
		return $this->server_endpoint;
	}
	
	public function setServerEndPoind($serverEndPoind){
		$this->validateUris($serverEndPoind);
		$this->server_endpoint = $serverEndPoind;		
	}
			
	public function getAuthorizeEndpoint() {
		return $this->authorize_endpoint;
	}
	public function setAuthorizeEndpoint($authorizeEndpoint) {
        $this->validateUris($authorizeEndpoint);
        $this->authorize_endpoint = $authorizeEndpoint;

	}
	public function getTokenEndpoint() {
		return $this->token_endpoint;

	}
	public function setTokenEndpoint($tokenEndpoint) {
        $this->validateUris($tokenEndpoint);
        $this->token_endpoint = $tokenEndpoint;

	}
	public function getRevokeEndpoint() {
		return $this->revoke_endpoint;

	}
	public function setRevokeEndpoint($revokeEndpoint) {
        $this->validateUris($revokeEndpoint);
        $this->revoke_endpoint = $revokeEndpoint;

	}
	public function getStorage(){
		return $this->storage;
	}
	public function setStorage(StorageInterface $clientStorage){
		if (!$clientStorage) {
			throw new ConfigException(sprintf("missing field clientStorage with '%s'", $clientStorage));
		}
		$this->storage =  $clientStorage;
	}
	private function validateUris($endpointUri)
	{
		if (!is_string($endpointUri) || 0 >= strlen($endpointUri)) {
			throw new ConfigException("uri must be a non-empty string");
		}
		if (false === filter_var($endpointUri, FILTER_VALIDATE_URL)) {
			throw new ConfigException("uri must be valid URL");
		}
		// not allowed to have a fragment (#) in it
		if (null !== parse_url($endpointUri, PHP_URL_FRAGMENT)) {
			throw new ConfigException("uri must not contain a fragment");
		}
	}
}
