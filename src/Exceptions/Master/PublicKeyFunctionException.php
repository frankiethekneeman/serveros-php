<?
/**
 * PublicKeyFunctionException Class.
 */
namespace Serveros\Serveros\Exceptions\Master;

use Serveros\Serveros\Exceptions\WrappedException;

/**
 * The PublicKeyFunction threw an error.
 *
 * @author Francis J.. Van Wetering IV
 */
class PublicKeyFunctionError extends WrappedException {

    /**
     * Constructor
     *
     * @param \Exception $e The error thrown.
     */
    public function __construct($e) {
        parent::__construct($e, "An Error was encountered while performing PublicKeyFunction");
    }
}
