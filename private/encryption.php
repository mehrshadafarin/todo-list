<?php
require_once 'env_loader.php';
$env = loadEnv();
$key = $env['ENCRYPTION_KEY'];

function encrypt_cookie($data)
{
    global $key;
    $cipher = "aes-256-ecb"; // AES-256 in ECB mode
    return openssl_encrypt($data, $cipher, $key);
}


function decrypt_cookie($encrypted_data)
{
    global $key;
    $cipher = "aes-256-ecb"; // AES-256 in ECB mode
    return openssl_decrypt($encrypted_data, $cipher, $key);
}


?>