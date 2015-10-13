<?
/**
 *  An erroneous response from a server.
 *  
 *  @class Error.AuthError.ProtocolError
 *  @extends ServerosError
 *  @inheritdoc
 *  @param {Integer} returnedCode The code the server returned.
 *  @param {mixed} body The body of the response.
 */
class ProtocolException extends ServerosException {
    public $returnedCode;
    public $body;

    public function __construct($returnedCode, $body) {
        parent::__construct("Remote Returned Erroneous Response", 500);
        $this->returnedCode = $returnedCode;
        $this->body = $body;
    }

    public function additionalInformation() {
        return [
            "returnedCode" => $this->returnedCode
            , "body" => $this->body
        ];
    }
}
