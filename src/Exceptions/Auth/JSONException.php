<?
namespace Serveros\Serveros\Exceptions\Auth;

use Serveros\Serveros\Exceptions\WrappedException;

/**
 *  An error while JSON encoding/decoding.
 *  
 *  @class Error.AuthError.JSONError
 *  @extends WrappedError
 *  @inheritdoc
 *  @param {Error} err The error.
 */
class JSONException extends WrappedException {

    public function __construct($e) {
        parent::__construct($e, "JSON Exception", 400);
    }
}
