<?php
require __DIR__.'/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

define('ENCRYPTION_KEY', getenv('ENCRYPTION_KEY'));
define('CIPHER_METHOD', 'AES-256-ECB'); // AES-256 with ECB mode (no IV needed)

/**
 * Encrypts a plain text string.
 *
 * @param string $plainText The string to encrypt.
 * @return string The encrypted string (Base64 encoded).
 */
function encrypt($plainText)
{
    $key = hash('sha256', ENCRYPTION_KEY, true); // Generate a 256-bit key

    $encrypted = openssl_encrypt($plainText, CIPHER_METHOD, $key, 0);
    return $encrypted !== false ? base64_encode($encrypted) : false;
}

/**
 * Decrypts an encrypted string.
 *
 * @param string $encryptedText The Base64 encoded string to decrypt.
 * @return string|false The decrypted string, or false on failure.
 */
function decrypt($encryptedText)
{
    $key = hash('sha256', ENCRYPTION_KEY, true); // Generate a 256-bit key

    // Decode the Base64 encoded string
    $decoded = base64_decode($encryptedText);
    if ($decoded === false) {
        return false; // Decoding failed
    }

    // Decrypt the cipher text
    return openssl_decrypt($decoded, CIPHER_METHOD, $key, 0);
}
?>