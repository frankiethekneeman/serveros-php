<?
/**
 * VerificationException Class.
 */
namespace Serveros\Serveros\Exceptions\Crypto;

use Serveros\Serveros\Exceptions\ServerosException;

/**
 * Error for an unverified Message.
 *
 * @author Francis J.. Van Wetering IV
 */
class VerificationException extends ServerosException {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct("Verifier Returned False.");
    }
}
