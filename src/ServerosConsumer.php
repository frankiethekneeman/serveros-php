<?php
/**
 * ServerosConsumer Class.
 */

namespace Serveros\Serveros;

use Serveros\Serveros\Encrypter;
use Serveros\Serveros\Exceptions\Auth\HTTPException;
use Serveros\Serveros\Exceptions\Auth\JSONException;
use Serveros\Serveros\Exceptions\Auth\NonceException;
use Serveros\Serveros\Exceptions\Auth\ProtocolException;
use Serveros\Serveros\Exceptions\Auth\StaleException;
use Serveros\Serveros\Exceptions\Crypto\RSAException;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;

/**
 * A Serveros Service Consumer Object.  Used to retrieve tickets from the Authentication Master
 * to use a Service Provider on the Network.
 *
 * @author Francis J.. Van Wetering IV
 */
class ServerosConsumer extends Encrypter {

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
     *     Authentication Master to uniquely identify the consumer
     * @param string[] $supportedHashes A list of acceptable hashes, in order of descending preference
     * @param string[] $supportedCiphers A list of acceptable Ciphers, in order of descending preference
     * @param string $masterLocation the protocol/host/port on which the Authentication Master is listening
     * @param string $masterPublicKey the public key distributed by the Authentication Master - as a
     *     PEM8 encoded string.
     * @param String $myPrivateKey The Private Key for the Consumer, as a PEM encoded string. The matching
     *     Public key should be registered with the Authentication Master separately
     *  
     * @param throws RSAException If either of the keys passed is invalid.
     */
    public function __construct($id, $supportedHashes, $supportedCiphers, $masterLocation, $masterPublicKey, $myPrivateKey) {
        parent::__construct($supportedCiphers, $supportedHashes);
        $this->id = $id;
        $this->chosenHash = $this->hashPrefs[0];
        $this->chosenCipher = $this->cipherPrefs[0];
        $this->privateKey = openssl_get_privatekey($myPrivateKey);
        if (!$this->privateKey){
            throw new RSAException(new Exception(openssl_error_string()));
        }
        $this->master = [
            "host" => $masterLocation
            , "publicKey" => openssl_get_publickey($masterPublicKey)
        ];
        if (!$this->master["publicKey"]){
            throw new RSAException(new Exception(openssl_error_string()));
        }
    }

    /**
     * Simple method to build a ticket request.
     *
     * @param mixed $requested the ID of the service the ticket is requesting access to.
     *
     * @returns Array A properly formatted ticket.
     */
    public function buildRequestTicket($requested) {
        return [
            "requester" => $this->id
            , "requested" => $requested
            , "nonce" => $this->nonce()
            , "ts" => time() * 1000
            , "hash" => $this->chosenHash
            , "suppportedHashes" => $this->hashPrefs
            , "supportedCiphers" => $this->cipherPrefs
        ];
    }

    /**
     * Request an Authorization ticket from the Authentication Master
     *
     * @param mixed requested the ID of the service the ticket is requesting access to.
     *
     * @return Array The successfully Retreived ticket.
     *
     * @throws RSAException IF there's any error Verifying, Decrypting, Encrypting, or signing.
     * @throws UnsupportedHashException If an unconfigured Hash is used by the Authentication Master
     * @throws CipherException If there's any error Enciphering or Deciphering
     * @throws UnsupportedCipherException If an unconfigured Cipher is still
     * @throws ProtocolException If the Master returns an unrecognized response.
     * @throws HTTPException If some other error happens during the HTTP Request.
     * @throws NonceException If the Server does not return the correct Nonce.
     * @throws StaleException If the Server's Response is stale.
     */
    public function requestTicket($requested) {
        $requestObj = $this->buildRequestTicket($requested);
        $message = $this->encryptAndSign($requestObj);
        try{
            $json = json_encode($message);
            if (!$json) {
                throw new JSONException(new Exception(json_last_error_msg()));
            }
            $client = new Guzzle();
            $response = $client->get("{$this->master["host"]}/authenticate", [
                'query' => [
                    'authRequest' => $json
                ]
            ]);
            $ticket = $this->decryptAndVerify($response->json());
            if($ticket["requesterNonce"] != $requestObj["nonce"])
                throw new NonceException();
            if($this->isStale($ticket["ts"]))
                throw new StaleException();
            return $ticket;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $code = $response->getStatusCode();
                $err = $response->json();
                if ($code == "409" || $code == "490") {
                    switch ($code) {
                        case "409":
                            $this->chosenCipher = array_shift(
                                array_intersect(
                                    $this->cipherPrefs
                                    , $err["additionalInformation"]["supported"]
                                )
                            );
                            break;
                        case "490":
                            $this->chosenHash = array_shift(
                                array_intersect(
                                    $this->hashPrefs
                                    , $err["additionalInformation"]["supported"]
                                )
                            );
                            break;
                    }
                    return $this->requestTicket($requested);
                }
                throw new ProtocolException($code, $err);
            }
            throw new HTTPException($e);
        } catch (Exception $e) {
            throw new HTTPException($e);
        }
    }

    /**
     * Authorize a ticket to its intended Service.
     *
     * @param String $serviceLocation A URL for authorizing to the service.
     * @param Object $ticket A ticket retrieved from requestTicket
     *
     * @return Array Some Credentials, properly exchanged with the service.
     *
     * @throws CipherException If there's any error Enciphering or Deciphering.
     * @throws UnsupportedCipherException If this Consumer has not been configured to use the algorithm
     *     in the ticket.
     * @throws ProtocolException If the Service Provider returns an unrecognized response.
     * @throws HTTPException If some other error happens during the HTTP Request.
     * @throws NonceException If the Server does not return the correct Nonce.
     * @throws StaleException If the Server's Response is stale.
     */
    public function authorize($serviceLocation, $ticket) {
        $idObject = [
            "id" => $this->id
            , "serverNonce" => $ticket["serverNonce"]
            , "requesterNonce" => $ticket["requesterNonce"]
            , "finalNonce" => $this->nonce()
            , "iv" => base64_encode(
                $this->getRandomBytes(
                    strlen(base64_decode($ticket["oneTimeCredentials"]["iv"]))
                )
            )
            , "ts" => time() * 1000
        ];
        $json = json_encode($idObject);
        if (!$json) {
            throw new JSONException(new Exception(json_last_error_msg()));
        }
        $greeting = [
            "id" => $this->encipher(
                $json
                , $ticket["oneTimeCredentials"]["key"]
                , $ticket["oneTimeCredentials"]["iv"]
                , $ticket["oneTimeCredentials"]["cipher"]
            )
            , "ticket" => $ticket["ticket"]
        ];
        $json = json_encode($greeting);
        if (!$json) {
            throw new JSONException(new Exception(json_last_error_msg()));
        }
        try {
            $client = new Guzzle();
            $response = $client->post("$serviceLocation", [
                'json' => $greeting
            ]);
            $providerSays = $response->json();
            $response = json_decode($this->decipher($providerSays["message"]
                , $ticket["oneTimeCredentials"]["key"]
                , $idObject["iv"]
                , $ticket["oneTimeCredentials"]["cipher"]
            ), true);
            if($response["serverNonce"] != $idObject["serverNonce"])
                throw new NonceError();
            if ($response["requesterNonce"] != $idObject["requesterNonce"])
                throw new NonceError();
            if ($response["finalNonce"] != $idObject["finalNonce"])
                throw new NonceError();
            if ($this->isStale($idObject["ts"]))
                throw new StaleError();
            return [
                "application" => $ticket["requested"]
                , "id" => $ticket["id"]
                , "key" => $ticket["secret"]
                , "algorithm" => $ticket["hash"]
            ];
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $code = $response->getStatusCode();
                $err = $response->json();
                throw new ProtocolException($code, $err);
            }
            throw new HTTPException($e);
        } catch (Exception $e) {
            throw new HTTPException($e);
        }
    }

    /**
     * A concatentation of Authorize and Request Ticket.
     *
     * @param string $serviceLocation A URL for authorizing to the service.
     * @param string $requested the ID of the service the ticket is requesting access to.
     *
     * @return Array Some Credentials, properly exchanged with the service.
     *
     * @throws CipherException If there's any error Enciphering or Deciphering.
     * @throws UnsupportedCipherException If this Consumer has not been configured to use the algorithm
     *     in the ticket.
     * @throws RSAException IF there's any error Verifying, Decrypting, Encrypting, or signing.
     * @throws UnsupportedHashException If an unconfigured Hash is used by the Authentication Master
     * @throws ProtocolException If any service returns an unrecognized response.
     * @throws HTTPException If some other error happens during the HTTP Request.
     * @throws NonceException If the Server does not return the correct Nonce.
     * @throws StaleException If the Server's Response is stale.
     */
    public function getCredentials($serviceId, $serviceLocation) {
        return $this->authorize($serviceLocation, $this->requestTicket($serviceId));
    }

    /**
     * A thin wrapper which provides the correct information.
     *
     * @param Array $message The message to be encrypted and signed.
     *
     * @return Array the encrypted message and a signature for it.
     *
     * @throws RSAException If there's any error with RSA Encryption/Signature
     * @throws UnsupportedHashException If this Encrypter has not been configured to use the algorithm.
     * @throws CipherException If there's any error Enciphering.
     * @throws UnrecognizedCipherException If the cipher in question is not one that can we have data on.
     * @throws UnsupportedCipherException If this Encrypter has not been configured to use the algorithm.
     */
    public function encryptAndSign($message) {
        return parent::encryptAndSign( $this->master["publicKey"]
            , $this->privateKey
            , json_encode($message)
            , $this->chosenCipher
            , $this->chosenHash
        );
    }

    /**
     * A thin wrapper which provides the correct information.
     *
     * @param Array $message The encrypted, Singed message.
     *
     * @return Array the decrypted message.
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
