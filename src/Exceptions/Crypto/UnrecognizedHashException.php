<?
namespace Serveros\Serveros\Exceptions\Crypto;

use Serveros\Serveros\Exceptions\UnsupportedException;

/**
 *  A Hash has been encountered that cannot be supported.
 *  
 *  @class Error.CryptoError.UnrecognizedHashError
 *  @extendsUnsupportedError
 *  @inheritdoc
 *  @param {String} hashRequested The requested Hash
 *  @param {String[]} supportedHashes The Hashes actually supported.
 */
class UnrecognizedHashException extends UnsupportedException {
    public function __construct($hashRequested, $supportedHashes) {
        parent::__construct($hashRequested, $supportedHashes, "An unrecognized hash was encountered");
    }
};
