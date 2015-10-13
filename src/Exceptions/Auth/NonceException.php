<?
/**
 * NonceException Class.
 */
namespace Serveros\Serveros\Exceptions\Auth;

use Serveros\Serveros\Exceptions\ServerosException;

/**
 * Unmatched Nonces Preventing Authentication.
 *
 * @author Francis J.. Van Wetering IV
 */
class NonceException extends ServerosException {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(this, "Nonces don't match", 403);
    }
}
