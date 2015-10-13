<?
namespace Serveros\Serveros\Exceptions\Crypto;

use Serveros\Serveros\Exceptions\WrappedException;

/**
 *  An Error during RSA Encryption/Decryption.
 *  
 *  @class Error.CryptoError.RSAError
 *  @extends WrappedError
 *  @inheritdoc
 *  @param {Error} err The encountered error.
 */
class RSAException extends WrappedException {
    public function __construct($e) {
        parent::__construct($e, "An Error was encountered while performing RSA Encryption");
    }
};
