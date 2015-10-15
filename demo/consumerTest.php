<?php
include_once( __DIR__ . '/../vendor/autoload.php');

use ComplexMedia\Guzzle\Plugin\Hawk;
use GuzzleHttp\Client as Guzzle;
use Serveros\Serveros\ServerosConsumer;

$masterPublicPem = file_get_contents(__DIR__ . '/../../serveros/demo/keys/master.pem8');
$myPrivatePem = file_get_contents(__DIR__ . '/../../serveros/demo/keys/serverA');

$consumer = new ServerosConsumer("Application A"
    , ['md5', 'sha256', 'sha1']
    , ['des', 'aes128']
    , 'http://localhost:3500'
    , $masterPublicPem
    , $myPrivatePem
);

$credentials = $consumer->getCredentials("Application B", "http://localhost:3501/authenticate");
var_dump($credentials);

$client = new Guzzle();

$signer = new Hawk($credentials['id'], $credentials['key'], $credentials['algorithm']);
$client->getEmitter()->attach($signer);
$response = $client->get("http://localhost:3501/test");
var_dump($response->json());
