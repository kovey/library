<?php
/**
 * @description RSA 私钥解密
 *
 * @package Kovey\Library\Encryption
 *
 * @author kovey
 *
 * @time 2019-12-17 19:57:26
 *
 */
namespace Kovey\Library\Encryption;

use Kovey\Library\Exception\KoveyException;

class PriRsa
{
	/**
	 * @description 加密
	 *
	 * @param string $data
	 *
	 * @param string $key
     *
     * @param bool $isBase64 = true
	 *
     * @return string
     *
     * @throws KoveyException
	 */
    public static function encrypt(string $data, string $key, bool $isBase64 = true) : string
    {
        if (is_file($key)) {
            $key = 'file://' . $key;
        }

        if (!openssl_private_encrypt($data, $crypted, $key)) {
            throw new KoveyException(openssl_error_string());
        }

        if (!$isBase64) {
            return $crypted;
        }

        return base64_encode($crypted);
    }

	/**
	 * @description 解密
	 *
	 * @param string $encrypt
	 *
	 * @param string $key
     *
     * @param bool $isBase64 = true
	 *
     * @return string
     *
     * @throws KoveyException
	 */
    public static function decrypt(string $encrypt, string $key, bool $isBase64 = true) : string
    {
        if (is_file($key)) {
            $key = 'file://' . $key;
        }

        if ($isBase64) {
            $encrypt = base64_decode($encrypt);
        }

        if (!openssl_private_decrypt($encrypt, $decrypted, $key)) {
            throw new KoveyException(openssl_error_string());
        }

        return $decrypted;
    }
}
