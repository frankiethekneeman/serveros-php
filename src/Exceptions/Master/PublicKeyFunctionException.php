<?
namespace Serveros\Serveros\Exceptions\Master;

use Serveros\Serveros\Exceptions\WrappedException;

/**
 *  The PublicKeyFunction threw an error.
 *  
 *  @class Error.MasterError.PublicKeyFunctionError
 *  @extends WrappedError
 *  @inheritdoc
 *  
 *  @param {Error} err The error thrown.
 */
class PublicKeyFunctionError extends WrappedException {
    public function __construct($e) {
        parent::__construct($e, "An Error was encountered while performing PublicKeyFunction");
    }
}
