<?php
include_once( __DIR__ . '/../vendor/autoload.php');

use ComplexMedia\Guzzle\Plugin\Hawk;
use GuzzleHttp\Client as Guzzle;
use Serveros\Serveros\ServerosConsumer;
use GuzzleHttp\Exception\RequestException;

$masterPublicPem = file_get_contents(__DIR__ . '/keys/master.pem8');
$myPrivatePem = file_get_contents(__DIR__ . '/keys/serverA');

$consumer = new ServerosConsumer("Application A"
    , ['md5', 'sha256', 'sha1']
    , ['des', 'aes128']
    , 'http://localhost:3500'
    , $masterPublicPem
    , $myPrivatePem
);

$credentials = $consumer->getCredentials("Application B", "http://localhost:8000/demo/providerTest.php");
echo "\nConsumer now has Credentials:\n\n";
var_dump($credentials);

$client = new Guzzle();

$signer = new Hawk($credentials['id'], $credentials['key'], $credentials['algorithm']);
$client->getEmitter()->attach($signer);
$response = $client->get("http://localhost:8000/demo/providerHawkTest.php");
echo "\nHawk Response:\n\n";
var_dump($response->json());
