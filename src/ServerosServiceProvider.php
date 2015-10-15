<?php
/**
 * ServerosServiceProvider Class.
 */

namespace Serveros\Serveros;

use Serveros\Serveros\Encrypter;
use Serveros\Serveros\Exceptions\Auth\JSONException;
use Serveros\Serveros\Exceptions\Auth\NonceException;
use Serveros\Serveros\Exceptions\Auth\StaleException;
use Serveros\Serveros\Exceptions\Crypto\RSAException;

/**
 * A Serveros Service Provider Object.  Used validate credentials from a Consumer.
 *
 * @author Francis J.. Van Wetering IV
 */
class ServerosServiceProvider extends Encrypter {

    /**
     * The ID of this application.
     */
    public $id;

    /**
     * The chosen Hash for interaction with the Authentication Master
     */
    public $chosenHash;

    /**
     * The chosen Cipher for interaction with the Authentication Master
     */
    public $chosenCipher;

    /**
     * Master information.
     */
    public $master;

    /**
     * My Private Key.
     */
    public $privateKey;

    /**
     * Constructor.
     *
     * @param mixed $id Anything that can be (a) JSON encoded and (b) used by the
     *     Authentication Master to uniquely identify the service provider
     * @param string[] $supportedHashes A list of acceptable hashes, in order of descending preference
     * @param string[] $supportedCiphers A list of acceptable Ciphers, in order of descending preference
     * @param string $masterPublicKey the public key distributed by the Authentication Master - as a
     *     PEM8 encoded string.
     * @param String $myPrivateKey The Private Key for the Consumer, as a PEM encoded string. The matching
     *     Public key should be registered with the Authentication Master separately
     *  
     * @param throws RSAException If either of the keys passed is invalid.
     */
    public function __construct($id, $supportedHashes, $supportedCiphers, $masterPublicKey, $myPrivateKey) {
        parent::__construct($supportedCiphers, $supportedHashes);
        $this->id = $id;
        $this->chosenHash = $this->hashPrefs[0];
        $this->chosenCipher = $this->cipherPrefs[0];
        $this->privateKey = openssl_get_privatekey($myPrivateKey);
        if (!$this->privateKey){
            throw new RSAException(new Exception(openssl_error_string()));
        }
        $this->master = [
            "publicKey" => openssl_get_publickey($masterPublicKey)
        ];
        if (!$this->master["publicKey"]){
            throw new RSAException(new Exception(openssl_error_string()));
        }
    }

    /**
     * Validate an incoming Greeting.
     *
     * @param array $greeting The over the wire Greeting from a Service Consumer.
     * 
     * @return array The validated authorization info.
     *  
     * @throws CipherException If there's any error  Deciphering.
     * @throws UnsupportedCipherException If this Consumer has not been configured to use the algorithm
     *     in the ticket.
     * @throws RSAException IF there's any error Verifying, Decrypting, Encrypting, or signing.
     * @throws UnsupportedHashException If an unconfigured Hash is used by the Authentication Master
     * @throws NonceException If the Server does not return the correct Nonce.
     * @throws StaleException If the Server's Response is stale.
     */
    public function validate($greeting) {
        $ticket = $this->decryptAndVerify($greeting["ticket"]);
        $id = json_decode($this->decipher($greeting["id"]
            , $ticket["oneTimeCredentials"]["key"]
            , $ticket["oneTimeCredentials"]["iv"]
            , $ticket["oneTimeCredentials"]["cipher"]
        ), true);
        if (!$id) {
            throw new JSONException(new Exception(json_last_error_msg()));
        }
        if($id["serverNonce"] != $ticket["serverNonce"])
            throw new NonceError();
        if ($id["requesterNonce"] != $ticket["requesterNonce"])
            throw new NonceError();
        if ($this->isStale($id["ts"]))
            throw new StaleError();
        return [
            "id" => $ticket["id"]
            , "secret" => $ticket["secret"]
            , "authData" => $ticket["authData"]
            , "requester" => $ticket["requester"]
            , "hash" => $ticket["hash"]
            , "cipher" => $ticket["cipher"]
            , "expires" => $ticket["expires"]
            , "oneTimeCredentials" => $ticket["oneTimeCredentials"]
            , "nonces" => [
                "server" => $id["serverNonce"]
                , "requester" => $id["requesterNonce"]
                , "final" => $id["finalNonce"]
                , "iv" => $id["iv"]
            ]
        ];
    }

    /**
     * Prep an acknowledgement to the Consumer.
     *  
     * @param array $authorized Filled out authorization data.
     *  
     * @return array A ready to encode acknowledgement for the consumer
     * 
     * @throws CipherException If there's any error Enciphering.
     * @throws UnrecognizedCipherException If the cipher requested is not one
     *     that can we have data on.
     * @throws UnsupportedCipherException If this Encrypter has not been configured to use the algorithm
     *     used to requested
     */
    public function prepAcknowledgement($authorized) {
        $ack = json_encode([
            "serverNonce" => $authorized["nonces"]["server"]
            , "requesterNonce" => $authorized["nonces"]["requester"]
            , "finalNonce" => $authorized["nonces"]["final"]
            , "ts" => time() * 1000
        ]);
        if (!$ack) {
            throw new JSONException(new Exception(json_last_error_msg()));
        }
        $ciphertext = $this->encipher( 
            $ack
            , $authorized["oneTimeCredentials"]["key"]
            , $authorized["nonces"]["iv"]
            , $authorized["oneTimeCredentials"]["cipher"]
        );
        return [ "message" => $ciphertext ];
    }

    /**
     * A thin wrapper which provides the correct information.
     *
     * @param array $message The encrypted, Singed message.
     *
     * @return array the decrypted message.
     *
     * @throws RSAException IF there's any error Verifying or Decrypting
     * @throws UnsupportedHashException If this Encrypter has not been configured to use the algorithm
     *     used to sign the message.
     * @throws CipherException If there's any error Enciphering.
     * @throws UnrecognizedCipherException If the cipher used to encrypt the message is not one
     *     that can we have data on.
     * @throws UnsupportedCipherException If this Encrypter has not been configured to use the algorithm
     *     used to encipher the message.
     */
    public function decryptAndVerify($message) {
        return parent::decryptAndVerify($this->privateKey, $this->master["publicKey"], $message);
    }
}
