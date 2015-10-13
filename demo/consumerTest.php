<?php
include_once('../vendor/autoload.php');

use Serveros\Serveros\ServerosConsumer;

$masterPublicPem = file_get_contents('../serveros/demo/keys/master.pem8');
$myPrivatePem = file_get_contents('../serveros/demo/keys/serverA');

$consumer = new ServerosConsumer("Application A"
    , ['md5', 'sha256', 'sha1']
    , ['des', 'aes128']
    , 'http://localhost:3500'
    , $masterPublicPem
    , $myPrivatePem
);

$credentials = $consumer->getCredentials("Application B", "http://localhost:3501/authenticate");
var_dump($credentials);
