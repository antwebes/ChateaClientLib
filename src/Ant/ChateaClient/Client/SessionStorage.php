<?php

namespace Ant\ChateaClient\Client;
use Ant\ChateaClient\OAuth2\AccessToken;
use Ant\ChateaClient\OAuth2\RefreshToken;
use Ant\ChateaClient\Client\StorageInterface;

class SessionStorage implements IStorage {

	private static $session;

	private function __construct() {
		if ("" === session_id()) {
			// no session currently exists, start a new one
			session_start();
		}
		$_SESSION['ant-chatea-client'] = array();
		unset($_SESSION['ant-chatea-client']);
	}

	public static function getInstance() {
		if (!self::$session instanceof self) {
			self::$session = new self;
		}
		return self::$session;
	}
	public function findAccessTokenByClientId($client_id) {
		if (!isset($_SESSION['ant-chatea-client'][$client_id]['access_token'])) {
			return false;
		}
		return unserialize(
				$_SESSION['ant-chatea-client'][$client_id]['access_token']);

	}

	public function addAccessToken($client_id, AccessToken $accessToken) {
		if (!isset($_SESSION['ant-chatea-client'][$client_id]['access_token'])) {
			$_SESSION['ant-chatea-client'][$client_id]['access_token'] = array();
		}

		$_SESSION['ant-chatea-client'][$client_id]['access_token'] = serialize(
				$accessToken);
		return true;
	}
	public function updateAccessToken($client_id, $accessToken) {
		return $this->addAccessToken($client_id, $accessToken);
	}
	public function deleteAccessToken($client_id) {
		if (!isset($_SESSION['ant-chatea-client'][$client_id]['access_token'])) {
			return false;
		}

		unset($_SESSION['ant-chatea-client'][$client_id]['access_token']);

		return true;

	}

	public function findRefreshTokenByClientId($client_id) {
		if (!isset($_SESSION['ant-chatea-client'][$client_id]['refresh_token'])) {
			return false;
		}

		return unserialize(
				$_SESSION['ant-chatea-client'][$client_id]['refresh_token']);
	}

	public function addRefreshToken($client_id, RefreshToken $refreshToken) {
		if (!isset($_SESSION['ant-chatea-client'][$client_id]['refresh_token'])) {
			$_SESSION['ant-chatea-client'][$client_id]['refresh_token'] = array();
		}

		$_SESSION['ant-chatea-client'][$client_id]['refresh_token'] = serialize(
				$refreshToken);

		return true;
	}
	public function updateRefreshToken($client_id, $refreshToken) {
		return $this->addRefreshToken($client_id, $refreshToken);
	}
	public function deleteRefreshToken($client_id) {
		if (!isset($_SESSION['ant-chatea-client'][$client_id]['refresh_token'])) {
			return false;
		}

		unset($_SESSION['ant-chatea-client'][$client_id]['refresh_token']);

		return true;
	}

}
