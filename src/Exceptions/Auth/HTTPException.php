<?
namespace Serveros\Serveros\Exceptions\Auth;

use Serveros\Serveros\Exceptions\WrappedException;

/**
 *  Signals an Error occured while making an HTTP request.
 *  
 *  @class Error.AuthError.HTTPError
 *  @extends WrappedError
 *  @inheritdoc
 *  @param {Error} err The HTTP error.
 */
class HTTPException extends WrappedException {

    public function __construct($e) {
        parent::__construct($e, "HTTP Exception");
    }
}
