<?php
namespace Gugusd999\Web;
class HPass{
    public static function encrypt($plaintext="", $password="asds") {
        $iv_length = openssl_cipher_iv_length($cipher="AES-256-CBC");
        $iv = openssl_random_pseudo_bytes($iv_length);
        $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $password, $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $password, $as_binary=true);
        return base64_encode( $iv.$hmac.$ciphertext_raw );
    }
    
    public static function decrypt($encrypted_text="", $password="asds") {
        $c = base64_decode($encrypted_text);
        $iv_length = openssl_cipher_iv_length($cipher="AES-256-CBC");
        $iv = substr($c, 0, $iv_length);
        $hmac = substr($c, $iv_length, $sha2len=32);
        $ciphertext_raw = substr($c, $iv_length+$sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $password, $options=OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $password, $as_binary=true);
        if (hash_equals($hmac, $calcmac)) { // Compare MACs in constant time
            return $original_plaintext;
        }
        return false;
    }
}
