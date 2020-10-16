<?php
/**
 * @description AES加密
 *
 * @package Kovey\Library\Encryption
 *
 * @author kovey
 *
 * @time 2019-12-17 20:37:36
 *
 */
namespace Kovey\Library\Encryption;

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
     * @param bool $isBase64 = true
	 *
	 * @return string
     *
     * @throws KoveyException
	 */
    public static function encrypt(string $data, string $key, bool $isBase64 = true) : string
    {
        if (strlen($key) < 16) {
            throw new KoveyException('key length is too small');
        }

        $iv = substr($key, 0, 16);
        $result = openssl_encrypt($data, 'AES-256-CBC', $key, $isBase64 ? OPENSSL_RAW_DATA : 0, $iv);
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
     * @param bool $isBase64 = true
	 *
	 * @return string
     *
     * @throws KoveyException
	 */
    public static function decrypt(string $encrypt, string $key, bool $isBase64 = true) : string
    {
        if (strlen($key) < 16) {
            throw new KoveyException('key length is too small');
        }

        $iv = substr($key, 0, 16);
        $clear = openssl_decrypt($encrypt, 'AES-256-CBC', $key, $isBase64 ? 0 : OPENSSL_RAW_DATA, $iv);
        if ($clear === false) {
            throw new KoveyException(openssl_error_string());
        }

        return $clear;
    }
}
