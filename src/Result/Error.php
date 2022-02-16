<?php
/**
 *
 * @description error return
 *
 * @package     Library\Result
 *
 * @time        Tue Sep 24 09:11:06 2019
 *
 * @author      kovey
 */
namespace Kovey\Library\Result;

class Error extends Result
{
    /**
     * @description get array
     *
     * @param int $code
     *
     * @param string $msg
     *
     * @param Array $data
     *
     * @return Array
     */
    public static function getArray(int $code, string $msg, Array | \ArrayObject $data = array()) : Array
    {
        $res = new self($code, $msg, $data);
        return $res->toArray();
    }

    /**
     * @description get json
     *
     * @param int $code
     *
     * @param string $msg
     *
     * @param Array $data
     *
     * @return string
     */
    public static function getJson(int $code, string $msg, Array | \ArrayObject $data = array())
    {
        $res = new self($code, $msg, $data);
        return $res->toJson();
    }
}
