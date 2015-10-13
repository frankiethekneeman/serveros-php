<?
namespace Serveros\Serveros\Exceptions\Auth;

use Serveros\Serveros\Exceptions\ServerosException;

/**
 *  A stale Authentication request.
 *  
 *  @class Error.AuthError.StaleError
 *  @extends ServerosError
 *  @inheritdoc
 */
class StaleError extends ServerosException {
    public function __construct() {
        parent::__construct("Stale Authentication Request.", 401);
    }
    
    public function additionalInformation() {
        return [
            "ServerClock" => time() * 1000
        ];
    }
}
