<?
/**
 * UnrecognizedHashException Class.
 */
namespace Serveros\Serveros\Exceptions\Crypto;

use Serveros\Serveros\Exceptions\UnsupportedException;

/**
 * A Hash has been encountered that cannot be supported.
 *
 * @author Francis J.. Van Wetering IV
 */
class UnrecognizedHashException extends UnsupportedException {

    /**
     * Constructor
     *
     * @param string $hashRequested The requested Hash
     * @param string[] $supportedHashes The Hashes actually supported.
     */
    public function __construct($hashRequested, $supportedHashes) {
        parent::__construct($hashRequested, $supportedHashes, "An unrecognized hash was encountered");
    }
};
