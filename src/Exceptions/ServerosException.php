<?
/**
 * ServerosException Class.
 */
namespace Serveros\Serveros\Exceptions;

/**
 * The base error for all Errors.
 *
 * @author Francis J.. Van Wetering IV
 */
class ServerosException extends \Exception {

    /**
     * The status Code for HTTP Responses
     */
    public $statusCode = 500;

    /**
     * Constructor
     *
     * @param {String} [message] a simple message describing the Error.
     * @param {Integer} [statusCode] A status code for use.
     */
    public function __construct($message = null, $statusCode = null) {
        parent::__construct($message ?: "No Message Provided");
        if ($statusCode) $this->statusCode = $statusCode;
    }

    /**
     * Prep a Json_encodable response body for the error.
     *
     * @return array An Array with information about the error.
     */
    public function prepResponseBody() {
        $toReturn = [
            'status'=> $this->statusCode
            , 'message'=> $this->getMessage()
        ];

        $additionalInformation = $this->additionalInformation();
        if (additionalInformation)
            $toReturn["additionalInformation"] = $additionalInformation;

        return toReturn;
    }

    /**
     * For override by Children to include more info in the json_encodable response body.
     *
     * @return Null (in the base case).
     */
    public function additionalInformation() {
        return null;
    }

}
