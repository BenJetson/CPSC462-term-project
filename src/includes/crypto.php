<?php

/**
 * ATTENTION
 *
 * This file is sourced in its entirety from this answer on Stack Overflow:
 * https://stackoverflow.com/a/30159120
 *
 * Credit goes to Scott Arciszewski of the Paragon Initiative.
 *
 * The rationale for including this file in the project is that cryptography
 * is best left to experts.
 *
 * In accordance with the license requirements, code in this file is available
 * under the terms of the CC-BY-SA 4.0 license.
 * Terms: https://creativecommons.org/licenses/by-sa/4.0/
 */

require_once './includes/autoload.php';

/**
 * Encrypt a message
 *
 * Source: https://stackoverflow.com/a/30159120
 *
 * @param string $message - message to encrypt
 * @param string $key - encryption key
 * @return string
 * @throws RangeException
 */
function safe_encrypt($message,  $key)
{
    if (mb_strlen($key, '8bit') !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
        throw new RangeException(
            'Key is not the correct size (must be 32 bytes).'
        );
    }
    $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

    $cipher = base64_encode(
        $nonce .
            sodium_crypto_secretbox(
                $message,
                $nonce,
                $key
            )
    );

    return $cipher;
}

/**
 * Decrypt a message
 *
 * Source: https://stackoverflow.com/a/30159120
 *
 * @param string $encrypted - message encrypted with safeEncrypt()
 * @param string $key - encryption key
 * @return string
 * @throws Exception
 */
function safe_decrypt($encrypted,  $key)
{
    $decoded = base64_decode($encrypted);
    $nonce = mb_substr(
        $decoded,
        0,
        SODIUM_CRYPTO_SECRETBOX_NONCEBYTES,
        '8bit'
    );
    $ciphertext = mb_substr(
        $decoded,
        SODIUM_CRYPTO_SECRETBOX_NONCEBYTES,
        null,
        '8bit'
    );

    $plain = sodium_crypto_secretbox_open(
        $ciphertext,
        $nonce,
        $key
    );
    if (!is_string($plain)) {
        throw new Exception('Invalid MAC');
    }

    return $plain;
}
