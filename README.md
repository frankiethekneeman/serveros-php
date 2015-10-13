An in progress PHP Implementation of [Serveros](https://github.com/frankiethekneeman/serveros).

##How?

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

## Contributing 

Please do.  Keep your commits sensible.  Fix security holes.  I'm definitely an amateur.

## Disclaimer

I do not claim this to be a perfect security system.  It's offered up for free in good faith
to try and make our applications more secure, but I am an amateur.
