<?
/**
 * RSAException Class.
 */
namespace Serveros\Serveros\Exceptions\Crypto;

use Serveros\Serveros\Exceptions\WrappedException;

/**
 * An Error during RSA Encryption/Decryption.
 *
 * @author Francis J.. Van Wetering IV
 */
class RSAException extends WrappedException {

    /**
     * Constructor
     *
     * @param \Exception $e The encountered error.
     */
    public function __construct($e) {
        parent::__construct($e, "An Error was encountered while performing RSA Encryption");
    }
};
