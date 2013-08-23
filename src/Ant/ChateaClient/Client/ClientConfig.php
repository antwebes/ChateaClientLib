<?php
namespace Ant\ChateaClient\Client;
use Ant\ChateaClient\OAuth2\ClientConfigInterface;
use Ant\ChateaClient\OAuth2\ConfigException;

class ClientConfig implements ClientConfigInterface {

	// VSCHAR     = %x20-7E
	const REGEXP_VSCHAR = '/^(?:[\x20-\x7E])*$/';

	/* The client identifier issued to the client during the registration process */
	private $clientId;
	/*The client secret. The client MAY omit the parameter if the client secret is an empty string.*/
	private $clientSecret;
	/* the url rediret */
	private $redirectUri;
	/* true: provide credentials client_id and secret in the POST body */
	private $credentialsInRequestBody;
	/*header Accept: only xmlor json */
	private $accept;

	/**
	 * Create object ChateaConfig.   
	 * 
	 * @param array $data
	 * @throws ConfigException
	 */
	public function __construct(array $data) {
		foreach (array('client_id', 'client_secret') as $key) {
			if (!array_key_exists($key, $data)) {
				throw new ConfigException(sprintf("missing field '%s'", $key));
			}
		}

		$this->setClientId($data['client_id']);
		$this->setClientSecret($data['client_secret']);

		$redirectUri = array_key_exists('redirect_uri', $data) ? $data['redirect_uri']
				: null;
		$this->setRedirectUri($redirectUri);

		$credentialsInRequestBody = array_key_exists(
				'credentials_in_request_body', $data) ? $data['credentials_in_request_body']
				: false;

		$this->setCredentialsInRequestBody($credentialsInRequestBody);

		if (array_key_exists('data_format', $data)) {			
			$accept = $this
					->setAccept(
							self::getAcceptHeader(
									$data['data_format']));
		} else {
			$this->setAccept(self::ACCEPT_JSON);
		}

	}

	public static function fromJSONFile($filename = "client_secrets.json") {

		if (!file_exists($filename)) {
			//TODO check file in default directory		
			$filename = getcwd() . DIRECTORY_SEPARATOR . 'app'
					. DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR
					. $filename;
			if (!file_exists($filename)) {
				throw new ConfigException(
						sprintf("file '%s' not exist ", $filename));
			}
		}

		return new static(json_decode(file_get_contents($filename), true));
		;
	}
	public static function fromArray(array $data) {

		return new static($data);
	}
	public function setClientId($clientId) {
		if (!is_string($clientId) || 0 >= strlen($clientId)) {
			throw new ConfigException("client_id must be a non-empty string");
		}
		$this->validateUserData($clientId);
		$this->clientId = $clientId;
	}
	public function getClientId() {
		return $this->clientId;
	}

	public function setClientSecret($clientSecret) {
		if (null !== $clientSecret) {
			if (!is_string($clientSecret) || 0 >= strlen($clientSecret)) {
				throw new ConfigException(
						"client_secret must be a non-empty string or null");
			}
			$this->validateUserData($clientSecret);
		}
		$this->clientSecret = $clientSecret;
	}

	public function getClientSecret() {
		return $this->clientSecret;
	}

	public function setRedirectUri($redirectUri) {
		if (null !== $redirectUri) {
			$this->validateUris($redirectUri);
		}
		$this->redirectUri = $redirectUri;
	}

	public function getRedirectUri() {
		return $this->redirectUri;
	}

	public function setCredentialsInRequestBody($credentialsInRequestBody) {
		$this->credentialsInRequestBody = (bool) $credentialsInRequestBody;
	}

	public function getCredentialsInRequestBody() {
		return $this->credentialsInRequestBody;
	}

	public function getAccept() {
		return $this->accept;
	}
	public function setAccept($header_accept) {
		if (!is_string($header_accept) || 0 >= strlen($header_accept)) {
			throw new ConfigException(
					"ClientConfig: header must be a non-empty string or null");
		}

		if (!strcmp($header_accept, self::ACCEPT_JSON)
				&& !strcmp($header_accept, self::ACCEPT_XML)) {
			throw new ConfigException(
					"ClientConfig: In this library version only is valid the header type json or xml");
		}

		$this->accept = $header_accept;
	}

	private function validateUserData($userPass) {
		if (1 !== preg_match(self::REGEXP_VSCHAR, $userPass)) {
			throw new ConfigException(
					"invalid characters in client_id or client_secret");
		}
	}

	private function validateUris($endpointUri) {
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

	public static function getAcceptHeader($name = "json"){
		
		if("JSON" !== strtoupper($name)  && "XML" !== strtoupper($name)){
			throw new ConfigException(sprintf("field data format with value '%s' is not supported", $name));
		}		
		switch (strtoupper($name)){
			case "JSON":
				return self::ACCEPT_JSON;
			case "XML":
				return self::ACCEPT_XML;
			default:
				return self::ACCEPT_JSON;												
		}
	}

}
