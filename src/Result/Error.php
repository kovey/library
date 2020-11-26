<?php
/**
 *
 * @description 接口对外返回值结构
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
     * @description 获取结果数据
     *
     * @param int $code
     *
     * @param string $msg
     *
     * @param Array $data
     *
     * @return Array
     */
    public static function getArray(int $code, string $msg, Array $data = array()) : Array
    {
        $res = new self($code, $msg, $data);
        return $res->toArray();
    }

    /**
     * @description 获取结果JSON
     *
     * @param int $code
     *
     * @param string $msg
     *
     * @param Array $data
     *
     * @return string
     */
    public static function getJson(int $code, string $msg, Array $data = array())
    {
        $res = new self($code, $msg, $data);
        return $res->toJson();
    }
}
