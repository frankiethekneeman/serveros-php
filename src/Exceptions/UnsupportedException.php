<?
namespace Serveros\Serveros\Exceptions;

use Serveros\Serveros\Exceptions\ServerosException;

/**
 *  An unsupported entity was encountered.
 *  
 *  @class Error.UnsupportedError
 *  @extends ServerosError
 *  @inheritdoc
 *  @param {String} requested the requested entity
 *  @param {String[]} supported The list of supported entities
 *  @param {String} [message] a simple message describing the Error.
 *  @param {Integer} [statusCode] A status code for use in HTTP responses.
 */
class UnsupportedException extends ServerosException {

    public $requested = "No Requested item was passed";
    public $supported = [];

    public function __construct($requested, $supported, $message, $statusCode) {
        parent::__construct($message, $statusCode);
        if ($requested) $this->requested = $requested;
        if ($supported) $this->supported = $supported;
    }

    public function additionalInformation() {
        return [
            "requested" => $this->requested
            , "supported" => $this->supported
        ];
    }
}
