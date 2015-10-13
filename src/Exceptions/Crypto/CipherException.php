<?
namespace Serveros\Serveros\Exceptions\Crypto;

use Serveros\Serveros\Exceptions\WrappedException;

/**
 *  For Errors Ciphering or Deciphering Data.
 *  
 *  @class Error.CryptoError.CipherError
 *  @extends WrappedError
 *  @inheritdoc
 *  
 *  @param {Error} err The error encountered.
 */
class CipherException extends WrappedException {
    public function __construct($e) {
        parent::__construct($e, "An Error was encountered while enciphering or deciphering data.");
    }
}
