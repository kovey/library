<?php
/**
 *
 * @description success return
 *
 * @package     Library\Result
 *
 * @time        Tue Sep 24 09:12:43 2019
 *
 * @author      kovey
 */
namespace Kovey\Library\Result;

class Success extends Result
{
    /**
     * @description get array
     *
     * @param Array $data
     *
     * @return Array
     */
    public static function getArray(Array | \ArrayObject $data = array()) : Array
    {
        $res = new self(ErrorCode::SUCCESS, 'success', $data);
        return $res->toArray();
    }

    /**
     * @description get json
     *
     * @param Array $data
     *
     * @return string
     */
    public static function getJson(Array | \ArrayObject $data = array()) : string
    {
        $res = new self(ErrorCode::SUCCESS, 'success', $data);
        return $res->toJson();
    }
}
