<?
/**
 * RandomBytesException Class.
 */
namespace Serveros\Serveros\Exceptions\Crypto;

use Serveros\Serveros\Exceptions\WrappedException;

/**
 * An error generating Random Bytes.
 *
 * @author Francis J.. Van Wetering IV
 */
class RandomBytesException extends WrappedException {

    /**
     * Constructor
     *
     * @param \Exception $e The error encountered.
     */
    public function __construct($e) {
        parent::__construct($e, "An Error was encountered while Gathering Entropy");
    }
};
