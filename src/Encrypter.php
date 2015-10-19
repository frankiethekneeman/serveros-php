<?php
/**
 * Encrypter Class.
 */

namespace Serveros\Serveros;

use Serveros\Serveros\Constants;
use Serveros\Serveros\Exceptions\ServerosException;
use Serveros\Serveros\Exceptions\Crypto\UnrecognizedCipherException;
use Serveros\Serveros\Exceptions\Crypto\UnsupportedCipherException;
use Serveros\Serveros\Exceptions\Crypto\UnsupportedHashException;
use Serveros\Serveros\Exceptions\Crypto\CipherException;
use Serveros\Serveros\Exceptions\Crypto\RSAException;
use Serveros\Serveros\Exceptions\Crypto\VerificationException;


/**
 * A Base class for all encryption classes.
 *
 * @author Francis J.. Van Wetering IV
 */
class Encrypter {

    /**
     * Wether or not to be secure.
     */
    public $secure = true;
    /**
     * The delimiter used in Cryptexts.
     */
    public $DELIMITER = ':';
    /**
     * Clock Drift.
     */
    public $STALE_REQUEST_TOLERANCE = 60000; // One minute clock drift allowed.
    /**
     * A Regular Expression to strip Padding Characters.
     */
    public $PADDING_CHARACTERS = '/(?:([\x00-\x1F])\1*|\x80\x00*|\x00*[\x01-\x1F])$/';
    /**
     * The Preferred Ciphers, in order.
     */
    public $cipherPrefs;
    /**
     * The preferred Hashes, in Order.
     */
    public $hashPrefs;

    /**
     * Constructor
     *
     * @param String[] $cipherPrefs The Ciphers this Encrypter should use, in order of preference.
     * @param String[] $hashPrefs The Hashes this Encrypter should use, in order of preference.
     */
    public function __construct($cipherPrefs, $hashPrefs) {
        $this->cipherPrefs = $cipherPrefs?
            array_values(array_unique(array_intersect($cipherPrefs, Constants::$CIPHERS)))
            :
            Constants::$CIPHERS;
        $this->hashPrefs = $hashPrefs?
            array_values(array_unique(array_intersect($hashPrefs, Constants::$HASHES)))
            :
            Constants::$HASHES;
    }
    /**
     *  Import a key, or set of keys, from a string to a PHP OPENSSL format.
     *  
     *  @param mixed $key A Key, or an array of keys, in PKCS8 PEM strings.
     *  @param boolean $private True if private keys, False if public keys.
     *  
     *  @return mixed The imported key, or keys.
     *  
     *  @throws RSAException if there's an import error.
     */
    public function import($key, $private) {
        $toReturn = [];
        $toParse = is_array($key)? $key : [$key];
        foreach ($toParse AS $k) {
            $parsed = $private ?
                openssl_get_privatekey($k)
                :
                openssl_get_publickey($k)
                ;
            
            if (!$parsed) {
                throw new RSAException(new \Exception(openssl_error_string()));
            }
            $toReturn[] = $parsed;
        }
        return is_array($key) ? $toReturn : $toReturn[0];
    }

    /**
     * Get some random bytes - abstracting this away so it can be updated due to PHP's crazy number
     * of ways to get random bytes.  This method should be as cryptographically secure as possible.
     *
     * @param Integer $bytes The number of bytes of randomness needed.
     * @return String Random Bytes.
     */
    protected function getRandomBytes($bytes) {
        $bytes = mcrypt_create_iv($bytes, MCRYPT_DEV_URANDOM);
        return $bytes;
    }

    /**
     * Create a nonce.  Currently just a random number.
     *
     * @return Integer A json encodeable Nonce.
     */
    public function nonce() {
        return rand();
    }

    /**
     * Get Credentials for one time encipherment.
     *
     * @param cipherName a named Cipher.
     * @return Array The Credentials - a Key and IV, with identifying algorithm.
     *     Random Bytes are base64 encoded.
     *
     * @throws UnrecognizedCipherException If the cipher in question is not one that can we have data on.
     */
    public function getOneTimeCredentials($cipherName) {
        if (!isset(Constants::$CIPHERDATA[$cipherName])) {
            throw new UnrecognizedCipherException($cipherName, array_keys(Constants::$CIPHERDATA));
        }
        $facts = Constants::$CIPHERDATA[$cipherName];
        return [
            "key" => base64_encode($this->getRandomBytes(ceil($facts['key']/8)))
            , "iv" => base64_encode($this->getRandomBytes(ceil($facts['block']/8)))
            , "algorithm" => $cipherName
        ];
    }


    /**
     * Decipher a symmetrically encrypted ciphertext.
     *
     * @param String $ciphertext a base64 encoded string.
     * @param String $key Either a base64 encoded string.
     * @param String $iv Either a base64 encoded string.
     * @param String $algorithm The cipher algorithm to use while deciphering.
     *
     * @return String the Plaintext.
     *
     * @throws CipherException If there's any error deciphering.
     * @throws UnsupportedCipherException If this Encrypter has not been configured to use the algorithm.
     */
    public function decipher($ciphertext, $key, $iv, $algorithm) {
        if (!in_array($algorithm, $this->cipherPrefs)) {
            throw new UnsupportedCipherException($algorithm, $this->cipherPrefs);
        }
        $deciphered = openssl_decrypt(
            base64_decode($ciphertext)
            , $algorithm
            , base64_decode($key)
            , OPENSSL_RAW_DATA
            , base64_decode($iv)
        );
        if (!$deciphered) {
            throw new CipherException(new \Exception(openssl_error_string()));
        }
        $deciphered = preg_replace($this->PADDING_CHARACTERS, '', $deciphered);
        return $deciphered;
    }

    /**
     * Encipher a symmetrically encrypted ciphertext.
     *
     * @param String $message a utf8 encoded string.
     * @param String $key Either a base64 encoded string.
     * @param String $initialVector a base64 encoded string.
     * @param String $algorithm The cipher algorithm to use while enciphering.
     *
     * @return String the Ciphertext
     *
     * @throws CipherException If there's any error Enciphering.
     * @throws UnsupportedCipherException If this Encrypter has not been configured to use the algorithm.
     */
    public function encipher($message, $key, $initialVector, $algorithm) {
        if (!in_array($algorithm, $this->cipherPrefs)) {
            throw new UnsupportedCipherException($algorithm, $this->cipherPrefs);
        }
        $enciphered = openssl_encrypt(
            $message
            , $algorithm
            , base64_decode($key)
            , OPENSSL_RAW_DATA
            , base64_decode($initialVector)
        );
        if (!$enciphered) {
            throw new CipherException(new \Exception(openssl_error_string()));
        }
        return base64_encode($enciphered);
    }

    /**
     * Encipher the data in question (via JSON Encoded String) with a one-time key/IV, then
     * encrypt the key/IV with the provided RSA key.  The two ciphertexts are then base64 encoded
     * and joined with a delimiter to provide the Encrypted Text.
     *
     * @param String $rsaKey An RSA Key (Public)
     * @param String $message Either a utf8 encoded string.
     * @param String $algorithm The cipher algorithm to use while enciphering.
     *
     * @return String the Cryptext
     *
     * @throws CipherException If there's any error Enciphering.
     * @throws RSAException If there's any error with RSA Encryption.
     * @throws UnrecognizedCipherException If the cipher in question is not one that can we have data on.
     * @throws UnsupportedCipherException If this Encrypter has not been configured to use the algorithm.
     */
    public function encrypt($rsaKey, $data, $algorithm) {
        if( is_array($rsaKey) && !isset($rsaKey['key']))
            $rsaKey = $rsaKey[0];
        $credentials = Encrypter::getOneTimeCredentials($algorithm);
        $enciphered = Encrypter::encipher($data, $credentials["key"], $credentials["iv"], $algorithm);
        $lock = [
            "algorithm"=> $algorithm
            , "key"=> $credentials["key"]
            , "iv"=> $credentials["iv"]
        ];
        openssl_public_encrypt(json_encode($lock), $encrypted, $rsaKey, OPENSSL_PKCS1_OAEP_PADDING);
        if (!$encrypted) {
            throw new RSAException(new \Exception(openssl_error_string()));
        }
        $cryptext = "$enciphered{$this->DELIMITER}" . base64_encode($encrypted);
        return $cryptext;
    }

    /**
     * Decrypt the output of the encrypt function with a set of possible RSK Keys.
     *
     * @param String[] $rsaKeyArray An array of RSA Keys (Private)
     * @param String $data The output of a previous call to Encrypt
     *
     * @return Array the JSON_Decoded plaintext.
     *
     * @throws CipherException If there's any error Enciphering.
     * @throws RSAException If there's any error with RSA Encryption.
     * @throws UnrecognizedCipherException If the cipher in question is not one that can we have data on.
     * @throws UnsupportedCipherException If this Encrypter has not been configured to use the algorithm.
     */
    public function decryptArray($rsaKeyArray, $data) {
        if( !is_array($rsaKeyArray) || isset($rsaKeyArray['key']))
            return $this->decrypt($rsaKeyArray, $data);
        $err = null;
        foreach ($rsaKeyArray AS $i => $rsaKey) {
            try {
                $toReturn = $this->decrypt($rsaKey, $data);
                $toReturn["chosen"] = $i;
                return $toReturn;
            } catch (ServerosException $e) {
                if (floor($e->statusCode/100) == 4)
                    throw $e;
                $err = $e;
            } catch (\Exception $e) {
                $err = $e;
            }
        }
        throw $err;
    }

    /**
     * Decrypt the output of the encrypt function.
     *
     * @param String $rsaKey An RSA Key (Private)
     * @param String $data The output of a previous call to Encrypt
     *
     * @return Array the JSON_Decoded plaintext.
     *
     * @throws CipherException If there's any error Enciphering.
     * @throws RSAException If there's any error with RSA Encryption.
     * @throws UnrecognizedCipherException If the cipher in question is not one that can we have data on.
     * @throws UnsupportedCipherException If this Encrypter has not been configured to use the algorithm.
     */
    public function decrypt($rsaKey, $data) {
        if( is_array($rsaKey) && !isset($rsaKey['key']))
            return $this->decryptArray($rsaKey, $data);
        $pieces = explode(':', $data);
        openssl_private_decrypt(base64_decode($pieces[1]), $decrypted, $rsaKey, OPENSSL_PKCS1_OAEP_PADDING);
        if (!$decrypted) {
            throw new RSAException(new \Exception(openssl_error_string()));
        }
        $credentials = json_decode($decrypted, true);
        $deciphered = Encrypter::decipher($pieces[0], $credentials["key"], $credentials["iv"], $credentials["algorithm"]);
        return json_decode($deciphered, true);
    }

    /**
     * Sign some Data.
     *
     * @param String $rsaKey An RSA Key (Private Key)
     * @param String $data The data to be signed.
     * @param String $algorithm The Hash algorithm to use whilst calculating the HMAC
     *
     * @return A base64 encoded signature.
     *
     * @throws RSAException IF there's any error Signing.
     * @throws UnsupportedHashException If this Encrypter has not been configured to use the algorithm.
     */
    public function sign($rsaKey, $data, $algorithm) {
        if( is_array($rsaKey) && !isset($rsaKey['key']))
            $rsaKey = $rsaKey[0];
        if (!in_array($algorithm, $this->hashPrefs)) {
            throw new UnsupportedHashException($algorithm, $this->hashPrefs);
        }
        $ret = openssl_sign($data, $signed, $rsaKey, $algorithm);
        if (!$ret) {
            throw new RSAException(new \Exception(openssl_error_string()));
        }
        return base64_encode($signed);
    }

    /**
     * Verify a Signature - using an Array of keys.
     *
     * @param Stringp[] $rsaKey An array of RSA Keys (Public Key)
     * @param String $data The previously signed data.
     * @param String $algorithm The Hash algorithm to use whilst calculating the HMAC
     * @param String $signature The previously generated Signature - as a base64 encoded String.
     *
     * @return True if the verification succeeded, false otherwise.
     *
     * @throws RSAException IF there's any error Verifying.
     * @throws UnsupportedHashException If this Encrypter has not been configured to use the algorithm.
     */
    public function verifyArray($rsaKeyArray, $data, $algorithm, $signature) {
        if( !is_array($rsaKeyArray) || isset($rsaKeyArray['key']))
            return $this->decrypt($rsaKeyArray, $data);
        $err = null;
        foreach ($rsaKeyArray AS $i => $rsaKey) {
            try {
                $toReturn = $this->verify($rsaKey, $data, $algorithm, $signature);
                $toReturn["chosen"] = $i;
                return $toReturn;
            } catch (ServerosException $e) {
                if (floor($e->statusCode/100) == 4)
                    throw $e;
                $err = $e;
            } catch (\Exception $e) {
                $err = $e;
            }
        }
        throw $err;
    }

    /**
     * Verify a Signature.
     *
     * @param String $rsaKey An RSA Key (Public Key)
     * @param String $data The previously signed data.
     * @param String $algorithm The Hash algorithm to use whilst calculating the HMAC
     * @param String $signature The previously generated Signature - as a base64 encoded String.
     *
     * @return True if the verification succeeded, false otherwise.
     *
     * @throws RSAException IF there's any error Verifying.
     * @throws UnsupportedHashException If this Encrypter has not been configured to use the algorithm.
     */
    public function verify($rsaKey, $data, $algorithm, $signature) {
        if( is_array($rsaKey) && !isset($rsaKey['key']))
            return $this->verifyArray($rsaKey, $data, $algorithm, $signature);
        if (!in_array($algorithm, $this->hashPrefs)) {
            throw new UnsupportedHashException($algorithm, $this->hashPrefs);
        }
        $ret = openssl_verify($data, base64_decode($signature), $rsaKey, $algorithm);
        if ($ret == -1) {
            throw new RSAException(new \Exception(openssl_error_string()));
        }
        if (!$ret) {
            throw new VerificationException();
        }
        return [
            "verified" => true
        ];
    }

    /**
     * Check if a timstamp is stale - gathered here for repitition's sake.
     *
     * @param Number $ts A numeric timestamp in milliseconds since the epoch.
     *
     * @returns Boolean True if the timestamp in question is too far out of synch with the local clock.
     */
    public function isStale($ts) {
        return !$ts || abs($ts - (time() * 1000)) > $this->STALE_REQUEST_TOLERANCE;
    }

    /**
     * Encrypt and Sign
     *
     * @param String $encryptKey An RSA Key (Public Key)
     * @param String $signKey An RSA Key (Private Key)
     * @param String $message a utf8 encoded string.
     * @param String $cipher The cipher algorithm to use while enciphering.
     * @param String $hash The Hash algorithm to use whilst calculating the HMAC
     *
     * @return Array The encrypted message, and the signature.
     *
     * @throws RSAException If there's any error with RSA Encryption/Signature
     * @throws UnsupportedHashException If this Encrypter has not been configured to use the algorithm.
     * @throws CipherException If there's any error Enciphering.
     * @throws UnrecognizedCipherException If the cipher in question is not one that can we have data on.
     * @throws UnsupportedCipherException If this Encrypter has not been configured to use the algorithm.
     */
    public function encryptAndSign($encryptKey, $signKey, $message, $cipher, $hash) {
        $encrypted = Encrypter::encrypt($encryptKey, $message, $cipher);
        $signed = Encrypter::sign($signKey, $encrypted, $hash);
        return [
            "message" => $encrypted
            , "signature" => $signed
        ];
    }


    /**
     * Decrypt and Verify
     *
     * @param String $encryptKey A PEM Encoded RSA Key (Public Key)
     * @param String $signKey A PEM Encoded RSA Key (Private Key)
     * @param Array $The over the wire message - shaped like output from encrypt and sign
     *
     * @return the Decrypted, deciphered object.
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
    public function decryptAndVerify($decryptKey, $verifyKey, $message) {
        $decrypted = Encrypter::decrypt($decryptKey, $message["message"]);
        $verified = Encrypter::verify($verifyKey, $message["message"], $decrypted["hash"], $message["signature"]);
        if (!$verified) {
            throw new VerificatonException();
        }
        return $decrypted;
    }
}

