<?php
namespace Ant\ChateaClient\Client;

class ChateaConfigTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @expectedException Ant\ChateaClient\OAuth2\ConfigException
	 */
	public function testConfigExceptionInConstruct(){
		 
		$config = new ChateaConfig(array());
		$config = new ChateaConfig(array('server_endpoint'=>'notvalue'));
		$config = new ChateaConfig(array('authorize_endpoint'=>'notvalue'));
		$config = new ChateaConfig(array('token_endpoint'=>'notvalue'));
		$config = new ChateaConfig(array('revoke_endpoint'=>'notvalue'));
		$config = new ChateaConfig(array('server_endpoint'=>'notvalue', 'authorize_endpoint'=>'notvalue'));
		$config = new ChateaConfig(array('token_endpoint'=>'notvalue', 'revoke_endpoint'=>'notvalue'));
		$config = new ChateaConfig(array('server_endpoint'=>'notvalue', 'authorize_endpoint'=>'notvalue','token_endpoint'=>'notvalue'));
		$config = new ChateaConfig(array('server_endpoint'=>'notvalue', 'authorize_endpoint'=>'notvalue','revoke_endpoint'=>'notvalue'));
		
	}
	
	public function testFromJSONFile(){
   		$server_endpoint 		= "http://api.chateagratis.local/app_dev.php/";
    	$authorize_endpoint 	= "http://api.chateagratis.local/app_dev.php/oauth/v2/auth";
    	$token_endpoint 		= "http://api.chateagratis.local/app_dev.php/oauth/v2/token";
    	$revoke_endpoint 		= "http://api.chateagratis.local/app_dev.php/oauth/v2/revoke";
	
		try {
			$config = ChateaConfig::fromJSONFile();
				
			$this->assertEquals($server_endpoint, $config->getServerEndpoint());
			$this->assertEquals($authorize_endpoint, $config->getAuthorizeEndpoint());
			$this->assertEquals($token_endpoint, $config->getTokenEndpoint());
			$this->assertEquals($revoke_endpoint, $config->getRevokeEndpoint());
				
		}catch (ConfigException $ex){
			$this->fail('I don\'t expected exception,  ConfigException has not been raised.');
		}
	}	
}