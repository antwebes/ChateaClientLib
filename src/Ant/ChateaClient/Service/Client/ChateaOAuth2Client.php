<?php
/**
 * Created by Ant-WEB S.L.
 * Developer: Xabier Fernández Rodríguez <jjbier@gmail.com>
 * Date: 14/10/13
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ant\ChateaClient\Service\Client;

use Guzzle\Common\Collection;
use Ant\Guzzle\Plugin\AcceptHeaderPluging;

/**
 * Specifies a client that provides user authentication for ApiChateaClient sites that use OAuth2-based authentication.
 *
 * @author Xabier Fernández Rodríguez in Ant-Web S.L.
 *
 * @package Ant\ChateaClient\Client
 *
 * @see  Client
 * @see  AuthenticationException
 * @see  AcceptHeaderPluging
 * @see  Collection
 *
 */
class ChateaOAuth2Client extends Client
{

    /**
     * Build new class ChateaOAuth2Client, this provides user authentication for ApiChateaClient
     *
     * @param array $config Associative array can configure the client. The parameters are:
     *                      client_id   The public key of client. This parameter is required
     *                      secret      The private key of client. This parameter is required
     *                      base_url    The server endpoind url. This parameter is optional
     *                      Accept      The accept header, default value is json. This parameter is optional
     *                      environment Set mode production [prod] or developing [dev] default value is prod. This parameter is optional
     *                      scheme      Set server schema communication [http|https] for default https. This parameter is optional
     *                      subdomain   Set server subdomain if this exist. For default is api. This parameter is optional
     *
     *
     * @return ChateaOAuth2Client|\Guzzle\Service\Client
     *
     */
    public static function factory($config = array())
    {
        // Provide a hash of default client configuration options
        $default = array(
            'base_url'=>'{scheme}://{subdomain}.chateagratis.local',
            'Accept'=>'application/json',
            'environment'=>'prod',
            'scheme' => 'https',
            'version'=>'',
            'subdomain'=>'api',
            'service-description-name' => Client::NAME_SERVICE_AUTH
        );
        $required = array(
            'base_url',
            'scheme',
            'subdomain',
            'Accept',
            'environment',
            'client_id',
            'secret'
        );

        // Merge in default settings and validate the config
        $config = Collection::fromConfig($config, $default, $required);

        if($config['environment'] == 'dev' ){
            $config['base_url'] = $config['base_url'] . '/app_dev.php';
            $config['scheme'] = 'http';
            $config['ssl.certificate_authority'] = 'system';
            $config['curl.options'] = array(CURLOPT_SSL_VERIFYHOST=>false,CURLOPT_SSL_VERIFYPEER=>false);
        }


        // Create a new ChateaOAuth2 client
        $client = new ChateaOAuth2Client ($config->get('base_url'),
            $config->get('scheme'),
            $config->get('subdomain'),
            $config
        );
        $client->addSubscriber(new AcceptHeaderPluging($config->toArray()));
        return $client;
    }

    /**
     * The public key of client
     *
     * @return string The public key of client
     */
    public function getClientId()
    {
        return $this->getConfig('client_id');
    }

    /**
     * The private key of client
     *
     * @return string The private key of client
     */
    public function getSecret()
    {
        return $this->getConfig('secret');
    }

    /**
     * Enables the user to get service credentials as well as service credential
     * authentication settings for use on the client side of communication.
     *
     * @param string $username The client name
     *
     * @param string $password The secret credentials of user
     *
     * @return array|string Associative array with client credentials | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws AuthenticationException This exception is thrown if you do not credentials or you cannot use this method
     *
     * @example Get client credentials
     *
     *      $clientInstande->withUserCredentials('username','password');
     *
     *  array("access_token"    => access-token-demo,
     *        "expires_in"      => 3600,
     *        "token_type"      => bearer,
     *        "scope"           => password,
     *        "refresh_token"   => refresh-token-demo
     *  );
     */
    public function withUserCredentials($username, $password)
    {
        if (!is_string($username) || 0 >= strlen($username)) {
            throw new InvalidArgumentException("username must be a non-empty string");
        }
        if (!is_string($password) || 0 >= strlen($password)) {
            throw new InvalidArgumentException("password must be a non-empty string");
        }

        $command = $this->getCommand('withUserCredentials',
            array('client_id'=>$this->getClientId(),'client_secret'=>$this->getSecret(),'username'=>$username,'password'=>$password)
        );

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch(ClientErrorResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch(CurlException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }

    }
    /**
     * Enables the user to get service credentials as well as service credential
     * authentication settings for use on the client side of communication.
     *
     * @param $auth_code The unique client code
     *
     * @param $redirect_uri The url to redirect after you obtain client credentials
     *
     * @return array|string Associative array with client credentials | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws AuthenticationException This exception is thrown if you do not credentials or you cannot use this method
     *
     * @example Get client credentials
     *
     *      $clientInstande->withAuthorizationCode('auth-code-demo','http://www.chateagratis.net');
     *
     *  array("access_token"    => access-token-demo,
     *        "expires_in"      => 3600,
     *        "token_type"      => bearer,
     *        "scope"           => password,
     *        "refresh_token"   => refresh-token-demo
     *  );
     */
    public function withAuthorizationCode($auth_code, $redirect_uri)
    {
        if (!is_string($auth_code) || 0 >= strlen($auth_code)) {
            throw new InvalidArgumentException("auth_code must be a non-empty string");
        }

        if (!is_string($redirect_uri) || 0 >= strlen($redirect_uri)) {
            throw new InvalidArgumentException("redirect_uri must be a non-empty string");
        }

        $command = $this->getCommand('withAuthorizationCode',
            array('client_id'=>$this->getClientId(),'client_secret'=>$this->getSecret(),'redirect_uri'=>$redirect_uri,'code'=>$auth_code)
        );

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch(ClientErrorResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch(CurlException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }
    }
    /**
     * Enables the apps to get service credentials as well as service credential
     * authentication settings for use on the apps side of communication.
     *
     * @return array|string Associative array with client credentials | Message with error in json format
     *
     * @throws AuthenticationException This exception is thrown if you do not credentials or you cannot use this method
     *
     * @example Get client credentials
     *
     *      $clientInstande->withClientCredentials();
     *
     *  array("access_token"    => access-token-demo,
     *        "expires_in"      => 3600,
     *        "token_type"      => bearer,
     *        "scope"           => password,
     *        "refresh_token"   => refresh-token-demo
     *  );
     */
    public function withClientCredentials()
    {
        $command = $this->getCommand('withClientCredentials',
            array('client_id'=>$this->getClientId(),'client_secret'=>$this->getSecret())
        );

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch(ClientErrorResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch(CurlException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }
    }
    /**
     *  After the client has been authorized for access, they can use a refresh token to get a new access token.
     *
     * @param string $refresh_token The client refresh token that you obtain in first request of credentials.
     *
     * @return array|string Associative array with client credentials | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws AuthenticationException This exception is thrown if you do not credentials or you cannot use this method
     *
     * @example Get client credentials
     *
     *      $clientInstande->withRefreshToken('refresh-token-demo');
     *
     *  array("access_token"    => access-token-demo,
     *        "expires_in"      => 3600,
     *        "token_type"      => bearer,
     *        "scope"           => password,
     *        "refresh_token"   => refresh-token-demo
     *  );
     */
    public function withRefreshToken($refresh_token)
    {
        if (!is_string($refresh_token) || 0 >= strlen($refresh_token)) {
            throw new InvalidArgumentException("refresh_token must be a non-empty string");
        }

        $command = $this->getCommand('withRefreshToken',
            array('client_id'=>$this->getClientId(),'client_secret'=>$this->getSecret(),'refresh_token'=>$refresh_token)
        );

        try{
            return $command->execute();
        }catch (BadResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch(ClientErrorResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch(CurlException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }
    }
    /**
     * Disable the service credentials as well as the session.
     *
     * @param string $access_token The toke to revoke
     *
     * @return string  Message sucessfully if can revoke token | Message with error in json format
     *
     * @throws InvalidArgumentException This exception is thrown if any parameter has errors
     *
     * @throws AuthenticationException This exception is thrown if you do not credentials or you cannot use this method
     *
     * @example Delete client credentials
     *
     *      $clientInstande->revokeToken('access-token-demo');
     *
     *      //output
     *          Access token revoked
     */
    public function revokeToken($access_token)
    {

        if (!is_string($access_token) || 0 >= strlen($access_token)) {
            throw new InvalidArgumentException("access_token must be a non-empty string");
        }

        $command = $this->getCommand('RevokeToken',array('access_token'=>$access_token));
        $request = $command->prepare();
        $request->setHeader('Authorization','Bearer '.$access_token);
        try{

            return $request->send()->getBody(true);

        }catch (BadResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch(ClientErrorResponseException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }catch(CurlException $ex){
            throw new AuthenticationException($ex->getMessage(), 400, $ex);
        }
    }
}