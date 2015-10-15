<?php
include_once( __DIR__ . '/../vendor/autoload.php');

use ComplexMedia\Guzzle\Plugin\Hawk;
use GuzzleHttp\Client as Guzzle;
use Serveros\Serveros\ServerosConsumer;
use Serveros\Serveros\ServerosServiceProvider;

$masterPublicPem = file_get_contents(__DIR__ . '/../../serveros/demo/keys/master.pem8');
$serviceProviderPrivatePem = file_get_contents(__DIR__ . '/../../serveros/demo/keys/serverB');

$provider = new ServerosServiceProvider("Application B"
    , ['md5', 'sha256', 'sha1']
    , ['des', 'aes128']
    , $masterPublicPem
    , $serviceProviderPrivatePem
);

$entityBody = file_get_contents('php://input');
$greeting = json_decode($entityBody, true);
if (!$greeting)
    throw new Exception(json_last_error_msg());

$authorized = $provider->validate($greeting);

$hawkCredentials = [
    "key" => $authorized["secret"]
    , "algorithm" => $authorized["hash"]
    , "expires" => $authorized["expires"]
    , "authData" => $authorized["authData"]
    , "consumer" => $authorized["requester"]
];
$fname = __DIR__ . "/creds/". preg_replace('#/#', '-', $authorized["id"]);
$ret = file_put_contents($fname, json_encode($hawkCredentials));
$acknowledgement = json_encode($provider->prepAcknowledgement($authorized));
echo($acknowledgement);
