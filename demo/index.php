<?php
require __DIR__.'/../vendor/autoload.php';

use Ant\ChateaClient\Service\Client\ChateaOAuth2Client;
use Guzzle\Http\Exception\BadResponseException;



$client = ChateaOAuth2Client::factory(
        array(
            'client_id'=>'1_1rqwnvdprfq8c0socws0ogco4c08goko0o80cocko0kkos84co',
            'secret'=>'4cvsrxs9s12ccs804wgk8k84ocoog4g4ooswwwkk8c4go0g4go',
            'environment'=>'dev'
    )
);
