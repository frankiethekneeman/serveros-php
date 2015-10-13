<?
/**
 * WrappedException Class.
 */
namespace Serveros\Serveros\Exceptions;

use Serveros\Serveros\Exceptions\ServerosException;

/**
 * A base class to wrap errors generated by other PHP libraries.
 *
 * @author Francis J.. Van Wetering IV
 */
class WrappedException {

    /**
     * The wrapped Exception
     */
    public $e;

    /**
     * Constructor
     *
     * @param \Exception $e The wrapped Error
     * @param string $message A message about where the error occured.
     */
    public function __construct($e, $message) {
        parent::__construct($message);
        if ($e) $this->e = $e;
    }

    /**
     * Return the Root Exception.
     *
     * @return Array "rootError" =>
     */
    public function additionalInformation() {
        return [
            "rootError" => $this->e->getMessage()
        ];
    }
}
