<?
/**
 * HTTPException Class.
 */

namespace Serveros\Serveros\Exceptions\Auth;

use Serveros\Serveros\Exceptions\WrappedException;

/**
 * Signals an Error occured while making an HTTP request.
 *
 * @author Francis J.. Van Wetering IV
 */
class HTTPException extends WrappedException {

    /**
     * Constructor
     *
     * @param \Exception $e The HTTP error.
     */
    public function __construct($e) {
        parent::__construct($e, "HTTP Exception");
    }
}
