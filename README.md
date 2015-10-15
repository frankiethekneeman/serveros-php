An in progress PHP Implementation of [Serveros](https://github.com/frankiethekneeman/serveros).

##How do I consume Services?

    use Serveros\Serveros\ServerosConsumer;

    $masterPublicPem = file_get_contents($master);
    $myPrivatePem = file_get_contents($private);

    $consumer = new ServerosConsumer("Application A"
        , ['sha256', 'sha1']
        , ['aes128']
        , 'http://localhost:3500'
        , $masterPublicPem
        , $myPrivatePem
    );

    $credentials = $consumer->getCredentials("Application B", "http://localhost:3501/authenticate");

##How do I provide Services?

    use Serveros\Serveros\ServerosServiceProvider;

    $masterPublicPem = file_get_contents($master);
    $serviceProviderPrivatePem = file_get_contents($private);

    $provider = new ServerosServiceProvider("Application B"
        , ['sha256', 'sha1']
        , ['aes128']
        , $masterPublicPem
        , $serviceProviderPrivatePem
    );

    $entityBody = file_get_contents('php://input');
    $greeting = json_decode($entityBody, true);
    $authorized = $provider->validate($greeting);
    $hawkCredentials = [
        "key" => $authorized["secret"]
        , "algorithm" => $authorized["hash"]
        , "expires" => $authorized["expires"]
        , "authData" => $authorized["authData"]
        , "consumer" => $authorized["requester"]
    ];

## Contributing 

Please do.  Keep your commits sensible.  Fix security holes.  I'm definitely an amateur.

## Disclaimer

I do not claim this to be a perfect security system.  It's offered up for free in good faith
to try and make our applications more secure, but I am an amateur.
