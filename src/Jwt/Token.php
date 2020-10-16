<?php
/**
 * @description Token
 *
 * @package Library\Jwt
 *
 * @author kovey
 *
 * @time 2020-02-12 10:42:18
 *
 */
namespace Kovey\Library\Jwt;

class Token
{
    /**
     * @description encode
     *
     * @param Array $ext
     *
     * @param string $key
     *
     * @param int $expire
     *
     * @return string
     */
    public static function encode(Array $ext, string $key, int $expire) : string
    {
        $jwt = new Jwt($key, $expire);
        return $jwt->encode($ext);
    }

    /**
     * @description decode
     *
     * @param string $token
     *
     * @param string $key
     *
     * @param int $expire
     *
     * @return Array
     *
     */
    public static function decode(string $token, string $key, int $expire) : Array
    {
        $jwt = new Jwt($key, $expire);
        return $jwt->decode($token);
    }
}
