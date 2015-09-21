<?php
/**
 * User: José Ramón Fernandez Leis
 * Email: jdeveloper.inxenio@gmail.com
 * Date: 27/08/15
 * Time: 15:48
 */

namespace Ant\ChateaClient\Tests\Service\Client;

use Ant\ChateaClient\Service\Client\AuthenticationException;
use Ant\ChateaClient\Service\Client\ChateaGratisAppClient;

class ChateaGratisAppClientTest extends \PHPUnit_Framework_TestCase
{
    protected $oauthclient;
    protected $store;

    protected function setUp()
    {
        parent::setUp();

        $this->oauthclient = $this->getMockBuilder('Ant\ChateaClient\Service\Client\ChateaOAuth2Client')
            ->disableOriginalConstructor()
            ->getMock();
        $this->store = $this->getMock('Ant\ChateaClient\Service\Client\StoreInterface');
    }

    public function testGetAccessTokenWithClientCredentials()
    {
        $this->oauthclient->expects($this->once())
            ->method('withClientCredentials');
        $this->store->expects($this->once())
            ->method('getPersistentData')
            ->with('token_expires_at')
            ->will($this->returnValue(null));

        $client = $this->createClient(false);
        $command = $this->createCommand();

        $client->prepareCommand($command);
    }

    public function testGetAccessTokenWithGuestCredentials()
    {
        $this->oauthclient->expects($this->once())
            ->method('withGuestCredentials');
        $this->store->expects($this->once())
            ->method('getPersistentData')
            ->with('token_expires_at')
            ->will($this->returnValue(null));

        $client = $this->createClient(true);
        $command = $this->createCommand();

        $client->prepareCommand($command);
    }

    public function testGetAccessTokenWhenAviableInStoreAndNotExpired()
    {
        $this->oauthclient->expects($this->never())
            ->method('withClientCredentials');
        $this->oauthclient->expects($this->never())
            ->method('withGuestCredentials');
        $this->store->expects($this->at(0))
            ->method('getPersistentData')
            ->with('token_expires_at')
            ->will($this->returnValue(time() + 600000));
        $this->store->expects($this->at(1))
            ->method('getPersistentData')
            ->with('token_expires_at')
            ->will($this->returnValue(time() + 600000));
        $this->store->expects($this->at(2))
            ->method('getPersistentData')
            ->with('access_token')
            ->will($this->returnValue('the_access_token'));

        $client = $this->createClient(true);
        $command = $this->createCommand();

        $client->prepareCommand($command);
    }

    public function testGetAccessTokenWhenAviableInStoreButExpiredAndWithNonExpriedRefreshToken()
    {
        $this->oauthclient->expects($this->never())
            ->method('withClientCredentials');
        $this->oauthclient->expects($this->never())
            ->method('withGuestCredentials');
        $this->store->expects($this->at(0))
            ->method('getPersistentData')
            ->with('token_expires_at')
            ->will($this->returnValue(time() - 600000));
        $this->store->expects($this->at(1))
            ->method('getPersistentData')
            ->with('token_expires_at')
            ->will($this->returnValue(time() - 600000));
        $this->store->expects($this->at(2))
            ->method('getPersistentData')
            ->with('token_refresh')
            ->will($this->returnValue('the_access_token'));

        $client = $this->createClient(true);
        $command = $this->createCommand();

        $client->prepareCommand($command);
    }

    public function testGetAccessTokenWhenAviableInStoreButExpiredAndWithInvalidRefreshToken()
    {
        $exception = new AuthenticationException();
        $this->oauthclient->expects($this->once())
            ->method('withRefreshToken')
            ->with('the_access_token')
            ->will($this->throwException($exception));
        $this->oauthclient->expects($this->once())
            ->method('withClientCredentials');
        $this->oauthclient->expects($this->never())
            ->method('withGuestCredentials');
        $this->store->expects($this->at(0))
            ->method('getPersistentData')
            ->with('token_expires_at')
            ->will($this->returnValue(time() - 600000));
        $this->store->expects($this->at(1))
            ->method('getPersistentData')
            ->with('token_expires_at')
            ->will($this->returnValue(time() - 600000));
        $this->store->expects($this->at(2))
            ->method('getPersistentData')
            ->with('token_refresh')
            ->will($this->returnValue('the_access_token'));

        $client = $this->createClient(false);
        $command = $this->createCommand();

        $client->prepareCommand($command);
    }

    public function testGetAccessTokenWhenAviableInStoreButExpiredAndWithInvalidRefreshTokenAsGuest()
    {
        $exception = new AuthenticationException();
        $this->oauthclient->expects($this->once())
            ->method('withRefreshToken')
            ->with('the_access_token')
            ->will($this->throwException($exception));
        $this->oauthclient->expects($this->never())
            ->method('withClientCredentials');
        $this->oauthclient->expects($this->once())
            ->method('withGuestCredentials');
        $this->store->expects($this->at(0))
            ->method('getPersistentData')
            ->with('token_expires_at')
            ->will($this->returnValue(time() - 600000));
        $this->store->expects($this->at(1))
            ->method('getPersistentData')
            ->with('token_expires_at')
            ->will($this->returnValue(time() - 600000));
        $this->store->expects($this->at(2))
            ->method('getPersistentData')
            ->with('token_refresh')
            ->will($this->returnValue('the_access_token'));

        $client = $this->createClient(true);
        $command = $this->createCommand();

        $client->prepareCommand($command);
    }

    private function createClient($asGuest = false)
    {
        $options = array(
            'OAuth2Client' => $this->oauthclient,
            'as_guest' => $asGuest,
            'base_url' => 'http://api.social.com/',
            'client_id' => 'a_client_id',
            'secret' => 'a_client_secret',
            'store' => $this->store
            );

        return ChateaGratisAppClient::factory($options);
    }

    protected function createCommand()
    {
        $request = $this->getMock('Guzzle\Http\Message\RequestInterface');
        $command = $this->getMock('Guzzle\Service\Command\CommandInterface');

        $command->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($request));
        $command->expects($this->once())
            ->method('setClient')
            ->will($this->returnValue($command));
        return $command;
    }
}