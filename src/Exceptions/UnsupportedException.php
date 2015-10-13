<?
/**
 * UnsupportedException Class.
 */
namespace Serveros\Serveros\Exceptions;

use Serveros\Serveros\Exceptions\ServerosException;

/**
 * An unsupported entity was encountered.
 *
 * @author Francis J.. Van Wetering IV
 */
class UnsupportedException extends ServerosException {

    /**
     * The requested Item.
     */
    public $requested = "No Requested item was passed";

    /**
     * The supported items.
     */
    public $supported = [];

    /**
     * Constructor
     *
     * @param string $requested the requested entity
     * @param string[] $supported The list of supported entities
     * @param string $message a simple message describing the Error.
     * @param int $statusCode A status code for use in HTTP responses.
     */
    public function __construct($requested, $supported, $message, $statusCode) {
        parent::__construct($message, $statusCode);
        if ($requested) $this->requested = $requested;
        if ($supported) $this->supported = $supported;
    }

    /**
     * Return the requested/supported types.
     *
     * @return array
     */
    public function additionalInformation() {
        return [
            "requested" => $this->requested
            , "supported" => $this->supported
        ];
    }
}
