<?
namespace Serveros\Serveros\Exceptions\Crypto;

use Serveros\Serveros\Exceptions\ServerosException;

/**
 *  Error for an unverified Message.
 *  
 *  @class Error.CryptoError.VerificationError
 *  @extends ServerosError
 *  @inheritdoc
 */
class VerificationException extends ServerosException {
    public function __construct() {
        parent::__construct("Verifier Returned False.");
    }
}
