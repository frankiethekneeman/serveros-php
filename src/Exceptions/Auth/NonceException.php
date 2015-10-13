<?
namespace Serveros\Serveros\Exceptions\Auth;

use Serveros\Serveros\Exceptions\ServerosException;

/**
 *  Unmatched Nonces Preventing Authentication.
 *  
 *  @class Error.AuthError.NonceError
 *  @extends ServerosError
 *  @inheritdoc
 */
class NonceException extends ServerosException {
    public function __construct() {
        parent::__construct(this, "Nonces don't match", 403);
    }
}
