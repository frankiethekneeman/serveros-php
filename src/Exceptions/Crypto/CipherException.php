<?
/**
 * CipherException Class.
 */
namespace Serveros\Serveros\Exceptions\Crypto;

use Serveros\Serveros\Exceptions\WrappedException;

/**
 * For Errors Ciphering or Deciphering Data.
 *
 * @author Francis J.. Van Wetering IV
 */
class CipherException extends WrappedException {

    /**
     * Constructor
     *
     * @param \Exception $e The error encountered.
     */
    public function __construct($e) {
        parent::__construct($e, "An Error was encountered while enciphering or deciphering data.");
    }
}
