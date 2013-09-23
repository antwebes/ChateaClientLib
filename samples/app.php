<?php
require __DIR__.'/../../../autoload.php';

use Ant\ChateaClient\Client\AuthUserCredentials;
use Ant\ChateaClient\OAuth2\OAuth2ClientUserCredentials;
use Ant\ChateaClient\Http\HttpClient;


$authClient = new OAuth2ClientUserCredentials(
    "2_63gig21vk9gc0kowgwwwgkcoo0g84kww00c4400gsc0k8oo4ks", 
    "202mykfu3ilckggkwosgkoo8g40w4wws0k0kooo488wo048k0w", 
    'xabier', 
    'xabier'
    );
$httpClient = new HttpClient();
$auth = new AuthUserCredentials($authClient,$httpClient);

try{
    $token = $auth->authenticate();
    echo $token->__toString();
}catch (\Exception $ex)
{

    echo $ex->getMessage();
}
//{"error":"invalid_grant"} user password error


