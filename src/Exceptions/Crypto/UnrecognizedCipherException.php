<?
/**
 * UnrecognizedCipherException Class.
 */
namespace Serveros\Serveros\Exceptions\Crypto;

use Serveros\Serveros\Exceptions\UnsupportedException;

/**
 * A cipher that cannot be supported was encountered.
 *
 * @author Francis J.. Van Wetering IV
 */
class UnrecognizedCipherException extends UnsupportedException {

    /**
     * Constructor
     *
     * @param string $cipherRequested The name of the requested Cipher.
     * @param string[] $supportedCiphers The list of supported Ciphers.
     */
    public function __construct($cipherRequested, $supportedCiphers) {
        parent::__construct($cipherRequested, $supportedCiphers, "An unrecognized cipher was encountered");
    }
};
