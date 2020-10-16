<?php
/**
 *
 * @description 接口成功时的返回值
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
	 * @description 获取成功结果数据
	 *
	 * @param Array $data
	 *
	 * @return Array
	 */
    public static function getArray(Array $data = array()) : Array
    {
        $res = new self(ErrorCode::SUCCESS, '成功', $data);
        return $res->toArray();
    }

	/**
	 * @description 获取成功结果数据JSON
	 *
	 * @param Array $data
	 *
	 * @return string
	 */
    public static function getJson(Array $data = array()) : string
    {
        $res = new self(ErrorCode::SUCCESS, '成功', $data);
        return $res->toJson();
    }
}
