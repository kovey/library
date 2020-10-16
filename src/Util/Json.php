<?php
/**
 *
 * @description Json
 *
 * @package     Library\Util
 *
 * @time        Tue Sep 24 08:52:33 2019
 *
 * @author      kovey
 */
namespace Kovey\Library\Util;

use Kovey\Library\Exception\KoveyException;

class Json
{
	/**
	 * @description convert array to json string
	 *
	 * @param Array $data
	 *
	 * @return string
     *
     * @throws KoveyException
	 */
	public static function encode(Array $data) : string
	{
		$result = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if (empty($result)) {
            throw new KoveyException(json_last_error_msg());
        }

        return $result;
	}

	/**
	 * @description convert json string to array
	 *
	 * @package string $data
	 *
	 * @return Array
	 */
	public static function decode(string $data) : Array
	{
		$result = json_decode($data, true);
        if (empty($result)) {
            throw new KoveyException(json_last_error_msg());
        }

        return $result;
	}
}
