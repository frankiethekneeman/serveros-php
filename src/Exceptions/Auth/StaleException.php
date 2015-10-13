<?
/**
 * StaleException Class.
 */
namespace Serveros\Serveros\Exceptions\Auth;

use Serveros\Serveros\Exceptions\ServerosException;

/**
 * A stale Authentication request.
 *
 * @author Francis J.. Van Wetering IV
 */
class StaleError extends ServerosException {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct("Stale Authentication Request.", 401);
    }

    /**
     * Return the Server Clock for skew resolution.
     *
     * @return Array Serverclock =>
     */
    public function additionalInformation() {
        return [
            "ServerClock" => time() * 1000
        ];
    }
}
