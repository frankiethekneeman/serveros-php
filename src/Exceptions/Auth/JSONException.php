<?
/**
 * JSONException Class.
 */
namespace Serveros\Serveros\Exceptions\Auth;

use Serveros\Serveros\Exceptions\WrappedException;

/**
 * An error while JSON encoding/decoding.
 *
 * @author Francis J.. Van Wetering IV
 */
class JSONException extends WrappedException {

    /**
     * Constructor
     *
     * @param \Exception $e The error.
     */
    public function __construct($e) {
        parent::__construct($e, "JSON Exception", 400);
    }
}
