<?php
/**
 * @description encrypt data
 *
 * @package Kovey\Library\Encryption
 *
 * @author kovey
 *
 * @time 2019-11-14 21:16:58
 *
 */
namespace Kovey\Library\Encryption;

use Kovey\Library\Exception\KoveyException;
use Kovey\Library\Jwt\Aes;

class Encryption
{
    /**
     * @description encrypt
     *
     * @param string $data
     *
     * @param string $key
     *
     * @param string $type
     *
     * @param bool isPub
     *
     * @return string
     *
     * @throws KoveyException
     */
    public static function encrypt(string $data, string $key, string $type = 'aes', bool $isPub = false) : string
    {
        if (strtolower($type) === 'no') {
            return $data;
        }

        if (strtolower($type) === 'aes') {
            return Aes::encrypt($data, $key);
        }

        if (strtolower($type) === 'rsa') {
            if ($isPub) {
                return PubRsa::encrypt($data, $key);
            }

            return PriRsa::encrypt($data, $key);
        }

        throw new KoveyException("$type is not support");
    }

    /**
     * @description decrypt
     *
     * @param string $data
     *
     * @param string $key
     *
     * @param string $type
     *
     * @param bool isPub
     *
     * @return string
     *
     * @throws KoveyException
     */
    public static function decrypt(string $data, string $key, string $type = 'aes', bool $isPub = false) : string
    {
        if (strtolower($type) === 'no') {
            return $data;
        }

        if (strtolower($type) === 'aes') {
            return Aes::decrypt($data, $key);
        }

        if (strtolower($type) === 'rsa') {
            if ($isPub) {
                return PubRsa::decrypt($data, $key);
            }

            return PriRsa::decrypt($data, $key);
        }

        throw new KoveyException("$type is not support");
    }
}
