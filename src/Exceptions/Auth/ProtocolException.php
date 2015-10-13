<?
/**
 * ProtocolException Class.
 */
namespace Serveros\Serveros\Exceptions\Auth;

use Serveros\Serveros\Exceptions\ServerosException;

/**
 * An erroneous response from a server.
 *
 * @author Francis J.. Van Wetering IV
 */
class ProtocolException extends ServerosException {

    /**
     * The returned code of the Exception.
     */

    public $returnedCode;

    /**
     * The body of the exception.
     */
    public $body;

    /**
     * Constructor
     *
     * @param int $returnedCode The code the server returned.
     * @param mixed $body The body of the response.
     */
    public function __construct($returnedCode, $body) {
        parent::__construct("Remote Returned Erroneous Response", 500);
        $this->returnedCode = $returnedCode;
        $this->body = $body;
    }

    /**
     * Return
     */
    public function additionalInformation() {
        return [
            "returnedCode" => $this->returnedCode
            , "body" => $this->body
        ];
    }
}
