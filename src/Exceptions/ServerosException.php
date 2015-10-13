<?
namespace Serveros\Serveros\Exceptions;
/**
 *  The base error for all Errors.
 *  
 *  @class Error.ServerosError
 *  @param {String} [message] a simple message describing the Error.
 *  @param {Integer} [statusCode] A status code for use.
 */
class ServerosException extends \Exception {

    public $statusCode = 500;

    public function __construct($message, $statusCode) {
        parent::__construct($message);
        if ($statusCode) $this->statusCode = $statusCode;
    }

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

    public function additionalInformation() {
        return null;
    }

}
