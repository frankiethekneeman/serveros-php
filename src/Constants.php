<?php
/**
 * Constants Class.
 */
namespace Serveros\Serveros;

/**
 * A class full of constants for use elsewhere so I don't have to stare at its ugly face.
 *
 * @author Francis J.. Van Wetering IV
 */
class Constants {

    /**
     * CipherData (copied from Node).
     */
    public static $CIPHERDATA =  [
        'CAST-cbc' => [
            'block' => 64,
            'key' => 128,
        ],
        'aes-128-cbc' => [
            'block' => 128,
            'key' => 128,
        ],
        'aes-128-cfb' => [
            'block' => 128,
            'key' => 128,
        ],
        'aes-128-cfb1' => [
            'block' => 128,
            'key' => 128,
        ],
        'aes-128-cfb8' => [
            'block' => 128,
            'key' => 128,
        ],
        'aes-128-ctr' => [
            'block' => 128,
            'key' => 128,
        ],
        'aes-128-ecb' => [
            'block' => 128,
            'key' => 128,
        ],
        'aes-128-gcm' => [
            'block' => 128,
            'key' => 128,
        ],
        'aes-128-ofb' => [
            'block' => 128,
            'key' => 128,
        ],
        'aes-128-xts' => [
            'block' => 128,
            'key' => 128,
        ],
        'aes-192-cbc' => [
            'block' => 128,
            'key' => 192,
        ],
        'aes-192-cfb' => [
            'block' => 128,
            'key' => 192,
        ],
        'aes-192-cfb1' => [
            'block' => 128,
            'key' => 192,
        ],
        'aes-192-cfb8' => [
            'block' => 128,
            'key' => 192,
        ],
        'aes-192-ctr' => [
            'block' => 128,
            'key' => 192,
        ],
        'aes-192-ecb' => [
            'block' => 128,
            'key' => 192,
        ],
        'aes-192-gcm' => [
            'block' => 128,
            'key' => 192,
        ],
        'aes-192-ofb' => [
            'block' => 128,
            'key' => 192,
        ],
        'aes-256-cbc' => [
            'block' => 128,
            'key' => 256,
        ],
        'aes-256-cfb' => [
            'block' => 128,
            'key' => 256,
        ],
        'aes-256-cfb1' => [
            'block' => 128,
            'key' => 256,
        ],
        'aes-256-cfb8' => [
            'block' => 128,
            'key' => 256,
        ],
        'aes-256-ctr' => [
            'block' => 128,
            'key' => 256,
        ],
        'aes-256-ecb' => [
            'block' => 128,
            'key' => 256,
        ],
        'aes-256-gcm' => [
            'block' => 128,
            'key' => 256,
        ],
        'aes-256-ofb' => [
            'block' => 128,
            'key' => 256,
        ],
        'aes-256-xts' => [
            'block' => 128,
            'key' => 256,
        ],
        'aes128' => [
            'block' => 128,
            'key' => 128,
        ],
        'aes192' => [
            'block' => 128,
            'key' => 192,
        ],
        'aes256' => [
            'block' => 128,
            'key' => 256,
        ],
        'bf' => [
            'block' => 64,
            'key' => 448,
        ],
        'bf-cbc' => [
            'block' => 64,
            'key' => 448,
        ],
        'bf-cfb' => [
            'block' => 64,
            'key' => 448,
        ],
        'bf-ecb' => [
            'block' => 64,
            'key' => 448,
        ],
        'bf-ofb' => [
            'block' => 64,
            'key' => 448,
        ],
        'blowfish' => [
            'block' => 64,
            'key' => 448,
        ],
        'camellia-128-cbc' => [
            'block' => 128,
            'key' => 128,
        ],
        'camellia-128-cfb' => [
            'block' => 128,
            'key' => 128,
        ],
        'camellia-128-cfb1' => [
            'block' => 128,
            'key' => 128,
        ],
        'camellia-128-cfb8' => [
            'block' => 128,
            'key' => 128,
        ],
        'camellia-128-ecb' => [
            'block' => 128,
            'key' => 128,
        ],
        'camellia-128-ofb' => [
            'block' => 128,
            'key' => 128,
        ],
        'camellia-192-cbc' => [
            'block' => 128,
            'key' => 192,
        ],
        'camellia-192-cfb' => [
            'block' => 128,
            'key' => 192,
        ],
        'camellia-192-cfb1' => [
            'block' => 128,
            'key' => 192,
        ],
        'camellia-192-cfb8' => [
            'block' => 128,
            'key' => 192,
        ],
        'camellia-192-ecb' => [
            'block' => 128,
            'key' => 192,
        ],
        'camellia-192-ofb' => [
            'block' => 128,
            'key' => 192,
        ],
        'camellia-256-cbc' => [
            'block' => 128,
            'key' => 256,
        ],
        'camellia-256-cfb' => [
            'block' => 128,
            'key' => 256,
        ],
        'camellia-256-cfb1' => [
            'block' => 128,
            'key' => 256,
        ],
        'camellia-256-cfb8' => [
            'block' => 128,
            'key' => 256,
        ],
        'camellia-256-ecb' => [
            'block' => 128,
            'key' => 256,
        ],
        'camellia-256-ofb' => [
            'block' => 128,
            'key' => 256,
        ],
        'camellia128' => [
            'block' => 128,
            'key' => 128,
        ],
        'camellia192' => [
            'block' => 128,
            'key' => 192,
        ],
        'camellia256' => [
            'block' => 128,
            'key' => 256,
        ],
        'cast' => [
            'block' => 64,
            'key' => 128,
        ],
        'cast-cbc' => [
            'block' => 64,
            'key' => 128,
        ],
        'cast5-cbc' => [
            'block' => 64,
            'key' => 128,
        ],
        'cast5-cfb' => [
            'block' => 64,
            'key' => 128,
        ],
        'cast5-ecb' => [
            'block' => 64,
            'key' => 128,
        ],
        'cast5-ofb' => [
            'block' => 64,
            'key' => 128,
        ],
        'des' => [
            'block' => 64,
            'key' => 56,
        ],
        'des-cbc' => [
            'block' => 64,
            'key' => 56,
        ],
        'des-cfb' => [
            'block' => 64,
            'key' => 56,
        ],
        'des-cfb1' => [
            'block' => 64,
            'key' => 56,
        ],
        'des-cfb8' => [
            'block' => 64,
            'key' => 56,
        ],
        'des-ecb' => [
            'block' => 64,
            'key' => 56,
        ],
        'des-ede' => [
            'block' => 64,
            'key' => 112,
        ],
        'des-ede-cbc' => [
            'block' => 64,
            'key' => 112,
        ],
        'des-ede-cfb' => [
            'block' => 64,
            'key' => 112,
        ],
        'des-ede-ofb' => [
            'block' => 64,
            'key' => 112,
        ],
        'des-ede3' => [
            'block' => 64,
            'key' => 168,
        ],
        'des-ede3-cbc' => [
            'block' => 64,
            'key' => 168,
        ],
        'des-ede3-cfb' => [
            'block' => 64,
            'key' => 168,
        ],
        'des-ede3-cfb1' => [
            'block' => 64,
            'key' => 168,
        ],
        'des-ede3-cfb8' => [
            'block' => 64,
            'key' => 168,
        ],
        'des-ede3-ofb' => [
            'block' => 64,
            'key' => 168,
        ],
        'des-ofb' => [
            'block' => 64,
            'key' => 56,
        ],
        'des3' => [
            'block' => 64,
            'key' => 168,
        ],
        'desx' => [
            'block' => 64,
            'key' => 184,
        ],
        'desx-cbc' => [
            'block' => 64,
            'key' => 184,
        ],
        'id-aes128-GCM' => [
            'block' => 128,
            'key' => 128,
        ],
        'id-aes192-GCM' => [
            'block' => 128,
            'key' => 192,
        ],
        'id-aes256-GCM' => [
            'block' => 128,
            'key' => 256,
        ],
        'idea' => [
            'block' => 64,
            'key' => 128,
        ],
        'idea-cbc' => [
            'block' => 64,
            'key' => 128,
        ],
        'idea-cfb' => [
            'block' => 64,
            'key' => 128,
        ],
        'idea-ecb' => [
            'block' => 64,
            'key' => 128,
        ],
        'idea-ofb' => [
            'block' => 64,
            'key' => 128,
        ],
        'rc2' => [
            'block' => 64,
            'key' => 128,
        ],
        'rc2-cbc' => [
            'block' => 64,
            'key' => 128,
        ],
        'rc2-cfb' => [
            'block' => 64,
            'key' => 128,
        ],
        'rc2-ecb' => [
            'block' => 64,
            'key' => 128,
        ],
        'rc2-ofb' => [
            'block' => 64,
            'key' => 128,
        ],
        'rc2-40-cbc' => [
            'block' => 64,
            'key' => 128,
        ],
        'rc2-64-cbc' => [
            'block' => 64,
            'key' => 128,
        ],
        'seed' => [
            'block' => 128,
            'key' => 128,
        ],
        'seed-cbc' => [
            'block' => 128,
            'key' => 128,
        ],
        'seed-cfb' => [
            'block' => 128,
            'key' => 128,
        ],
        'seed-ecb' => [
            'block' => 128,
            'key' => 128,
        ],
        'seed-ofb' => [
            'block' => 128,
            'key' => 128,
        ],
    ];

    /**
     * A list of available Ciphers.
     */
    public static $CIPHERS;

    /**
     * A list of available Hashes.
     */
    public static $HASHES;
}
Constants::$CIPHERS = array_values(array_unique(array_intersect(
    array_keys(Constants::$CIPHERDATA)
    , array_map('strtolower', openssl_get_cipher_methods(true))
)));
CONSTANTS::$HASHES = array_values(array_unique(array_map('strtolower', openssl_get_md_methods(true))));
