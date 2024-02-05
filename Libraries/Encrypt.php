<?php

/**
 * File Encrypt
 */

namespace Libraries;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * class Encrypt
 */
class Encrypt
{
    //@var string $secret_key secret_key
    private static $secret_key = "aeb0423474cae7bc96d2e7faab13ad30";

    //@var string $secret_key_internal secret_key_internal use for internal apis
    private static $secret_key_internal = "59gsv4Bn7VcEyM10uiZiyY72l2KJp31N";

    //@var string $secret_iv secret_iv
    private static $secret_iv = "1af0891441629cc190fe276bc7618841";

    //@var string $encrypt_method encrypt_method
    private static $encrypt_method = "AES-256-CBC";

    //@var string $jwt_secret jwt_secret
    private static $jwt_secret = "j5bn5bf004j85jch3ycxlo188agm56ui";

    /**
     * encode
     *
     * @param  string $value
     * @param  string $type = E (external), type = I (internal)
     * @return string
     */
    public static function encode(string $value, string $type = "E")
    {
        if ($type == "E") {
            $key = hash('sha256', self::$secret_key);
        } else {
            $key = hash('sha256', self::$secret_key_internal);
        }
        $iv = substr(hash('sha256', self::$secret_iv), 0, 16);
        $output = openssl_encrypt($value, self::$encrypt_method, $key, 0, $iv);
        return base64_encode($output);
    }


    /**
     * encryptJwt
     *
     * @param  string $string string to encrypt
     * @param  string $type = E (external), type = I (internal)
     * @throws \Exception
     * @return array
     */
    public static function encryptJwt($string, string $type = "E")
    {
        $time = time();
        $exp = $time + 6000;
        $payload = [
            'sub' => $string,
            'iss' => $_SERVER['HTTP_HOST'] ?? 'local',
            'aud' => $_SERVER['HTTP_USER_AGENT'] ?? 'local',
            'iat' => $time,
            'exp' => $exp
        ];
        $headers = [];
        try {
            if ($type == "E") {
                $jwt_secret = self::$jwt_secret;
            } else {
                $jwt_secret = self::$secret_key_internal;
            }
            return ["token" => JWT::encode($payload, $jwt_secret, 'HS256', null, $headers), "expire" => $exp];
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }


    /**
     * decryptJwt
     *
     * @param  string $jwt jwt token to decrypt
     * @param  string $type = E (external), type = I (internal)
     * @throws \Exception
     * @return array
     */
    public static function decryptJwt($jwt, string $type = "E")
    {
        try {
            if ($type == "E") {
                $jwt_secret = new Key(self::$jwt_secret, 'HS256');
            } else {
                $jwt_secret = new Key(self::$secret_key_internal, 'HS256');
            }
            return  (array)JWT::decode($jwt, $jwt_secret);
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
}
