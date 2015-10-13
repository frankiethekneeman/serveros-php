<?
namespace Serveros\Serveros\Exceptions\Crypto;

use Serveros\Serveros\Exceptions\UnsupportedException;

/**
 *  A cipher that cannot be supported was encountered.
 *  
 *  @class Error.CryptoError.UnrecognizedCipherError
 *  @extends UnsupportedError
 *  @inheritdoc
 *  @param {String} cipherRequested The name of the requested Cipher.
 *  @param {String[]} supportedCiphers The list of supported Ciphers.
 */
class UnrecognizedCipherException extends UnsupportedException {
    public function __construct($cipherRequested, $supportedCiphers) {
        parent::__construct($cipherRequested, $supportedCiphers, "An unrecognized cipher was encountered");
    }
};
