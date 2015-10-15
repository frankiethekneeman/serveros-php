<?
include_once( __DIR__ . '/../vendor/autoload.php');

use Dflydev\Hawk\Server\ServerBuilder;
use Dflydev\Hawk\Credentials\Credentials;

$credentials = null;
$credentialsProvider = function($id) use (&$credentials) {
    $fname = __DIR__ . "/creds/". preg_replace('#/#', '-', $id);
    $credentials =  json_decode(file_get_contents($fname), true);
    if (time() * 1000 < $credentials['expires'])
        return new Credentials($credentials['key'], $credentials['algorithm'], $id);
    return null;
};

$server = ServerBuilder::create($credentialsProvider)->build();

$headers = (getallheaders());

$authentication = $server->authenticate(
    $_SERVER['REQUEST_METHOD'] //HTTP Verb
    , 'localhost'
    , $_SERVER['SERVER_PORT']
    , $_SERVER['REQUEST_URI']
    , null
    , null
    , $headers['Authorization']
);

$response = [
    "authedAs" => $credentials['consumer']
    , "authData" => $credentials['authData']
];

echo json_encode($response);
