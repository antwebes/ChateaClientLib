<?php

namespace Ant\ChateaClient\Client;
use Ant\ChateaClient\OAuth2\AccessToken;
use Ant\ChateaClient\OAuth2\RefreshToken;
use Ant\ChateaClient\OAuth2\IOAuth2Client;
use Ant\ChateaClient\Client\IStorage;

class SessionStorage implements IStorage {

	private static $session;

	private function __construct() {
		if ("" === session_id() && self::$session === NULL) {
			// no session currently exists, start a new one
			session_start();	
		}
	}
  public static function destruct()
  {
    
  	if ("" !== session_id() && self::$session !== NULl) {
		  	self::$session = NULL;
		  	session_destroy();
		  	session_unset ();
        }

  }
  public static function getInstance() {
        if (self::$session === NULl) {
            self::$session = new SessionStorage();
        }
 		
        return self::$session;
    }
	public function findAccessTokenByClientId(IOAuth2Client $client) {
		if (!isset($_SESSION['ant-chatea-client'][$client->getPublicId()]['access_token'])) {
			return false;
		}
		
		$accessToken = unserialize(
				$_SESSION['ant-chatea-client'][$client->getPublicId()]['access_token']);
		
		return $accessToken;
		
	}

	public function addAccessToken(IOAuth2Client $client, AccessToken $accessToken) {
		if (!isset($_SESSION['ant-chatea-client'][$client->getPublicId()]['access_token'])) {
			$_SESSION['ant-chatea-client'][$client->getPublicId()]['access_token'] = array();
		}
		
		$_SESSION['ant-chatea-client'][$client->getPublicId()]['access_token'] = serialize(
				$accessToken);		
		return true;
	}
	
	public function updateAccessToken(IOAuth2Client $client, AccessToken $accessToken) {
		return $this->addAccessToken($client, $accessToken);
	}
	
	public function deleteAccessToken(IOAuth2Client $client) {
		if (!isset($_SESSION['ant-chatea-client'][$client->getPublicId()]['access_token'])) {
			return false;
		}

		unset($_SESSION['ant-chatea-client'][$client->getPublicId()]['access_token']);

		return true;

	}

	public function findRefreshTokenByClientId(IOAuth2Client $client) {
		if (!isset($_SESSION['ant-chatea-client'][$client->getPublicId()]['refresh_token'])) {
			return false;
		}

		return unserialize(
				$_SESSION['ant-chatea-client'][$client->getPublicId()]['refresh_token']);
	}

	public function addRefreshToken(IOAuth2Client $client, RefreshToken $refreshToken) {
		if (!isset($_SESSION['ant-chatea-client'][$client->getPublicId()]['refresh_token'])) {
			$_SESSION['ant-chatea-client'][$client->getPublicId()]['refresh_token'] = array();
		}

		$_SESSION['ant-chatea-client'][$client->getPublicId()]['refresh_token'] = serialize(
				$refreshToken);

		return true;
	}
	
	public function updateRefreshToken(IOAuth2Client $client, RefreshToken $refreshToken) {
		return $this->addRefreshToken($client, $refreshToken);
	}
	
	public function deleteRefreshToken(IOAuth2Client $client) {
		if (!isset($_SESSION['ant-chatea-client'][$client->getPublicId()]['refresh_token'])) {
			return false;
		}

		unset($_SESSION['ant-chatea-client'][$client->getPublicId()]['refresh_token']);

		return true;
	}

}
