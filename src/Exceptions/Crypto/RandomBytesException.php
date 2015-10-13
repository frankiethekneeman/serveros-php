<?
namespace Serveros\Serveros\Exceptions\Crypto;

use Serveros\Serveros\Exceptions\WrappedException;

/**
 *  An error generating Random Bytes.
 *  
 *  @class Error.CryptoError.RandomBytesError
 *  @extends WrappedError
 *  @inheritdoc
 *  @param {Error} err The error encountered.
 */
class RandomBytesException extends WrappedException {
    public function __construct($e) {
        parent::__construct($e, "An Error was encountered while Gathering Entropy");
    }
};
