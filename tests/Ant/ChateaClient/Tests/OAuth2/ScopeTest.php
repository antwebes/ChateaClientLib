<?php

namespace Ant\ChateaClient\Test\OAuth2;

use Ant\ChateaClient\OAuth2\Scope;

class ScopeTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\ScopeException
	 */
	public function testValueIsNotString()
	{
		
		$scope = new Scope(time());

	}
	/**
	 * @expectedException Ant\ChateaClient\OAuth2\ScopeException
	 */
	public function testValueIsNotEmpty()
	{
		$token = new Scope('');
	}

	public function testGetName()
	{
		$scopeName = "read-only";
		$scope = new Scope($scopeName, new Scope('api'));

		$this->assertEquals($scopeName, $scope->getName());

		$this->assertEquals('api', $scope->getParent()->getName());
	}
}

