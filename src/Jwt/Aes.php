<?php
/**
 * @description AES加密
 *
 * @package
 *
 * @author kovey
 *
 * @time 2019-12-17 20:37:36
 *
 */
namespace Kovey\Library\Jwt;

use Kovey\Library\Exception\KoveyException;

class Aes
{
	/**
	 * @description 加密
	 *
	 * @param string $data
	 *
	 * @param string $key
	 *
	 * @return string
	 */
    public static function encrypt(string $data, $key, $isBase64 = true) : string
    {
        if (strlen($key) != 43) {
            throw new KoveyException('key length is too small');
        }

        $key = base64_decode($key . "=");
        $iv = substr($key, 0, 16);
        $result = openssl_encrypt($data, 'AES-256-CBC', $key, $isBase64 ? 0 :  OPENSSL_RAW_DATA, $iv);
        if ($result === false) {
            throw new KoveyException(openssl_error_string());
        }

        return $result;
    }

	/**
	 * @description 解密
	 *
	 * @param string $encrypt
	 *
	 * @param string $key
	 *
	 * @return string
	 */
    public static function decrypt($encrypt, $key, $isBase64 = true) : string
    {
        if (strlen($key) != 43) {
            throw new KoveyException('key length is too small');
        }

        $key = base64_decode($key . "=");
        $iv = substr($key, 0, 16);
        $clear = openssl_decrypt($encrypt, 'AES-256-CBC', $key, $isBase64 ? 0 : OPENSSL_RAW_DATA, $iv);
        if ($clear === false) {
            throw new KoveyException(openssl_error_string());
        }
        return $clear;
    }
}
