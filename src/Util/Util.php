<?php
/**
 *
 * @description utils
 *
 * @package     Util
 *
 * @time        Tue Sep 24 08:51:42 2019
 *
 * @author      kovey
 */
namespace Kovey\Library\Util;

class Util
{
	/**
	 * @description char array
	 *
	 * @var Array
	 */
	private static Array $char = array(
		'A', 'a', 'B', 'b', 'C', 'c', 'D', 'd', 'E', 'e', 'F', 'f', 'G', 'g', 'H', 'h', 'I', 'i', 'J', 'j',
		'K', 'k', 'L', 'l', 'M', 'm', 'N', 'n', 'O', 'o', 'P', 'p', 'Q', 'q', 'R', 'r', 'S', 's', 'T', 't',
		'U', 'u', 'V', 'v', 'W', 'w', 'X', 'x', 'Y', 'y', 'Z', 'z', '1', '2', '3', '4', '5', '6', '7', '8',
		'9', '0'
	);

	/**
	 * @description number array
	 *
	 * @var Array
	 */
	private static Array $num = array(
		'1', '2', '3', '4', '5', '6', '7', '8', '9', '0'
	);

	/**
	 * @description
	 *
	 * @param string $path
	 *
	 * @return bool
	 */
    public static function isPath(string $data) : bool
    {
        return (bool)preg_match('/^[a-zA-Z0-9]+$/', $data);
    }

	/**
	 * @description microtime
	 *
	 * @return float
	 */
	public static function getMicrotime() : float
	{
		return microtime(true);
	}

	/**
	 * @description random string
	 *
	 * @param int $size
	 *
	 * @return string
	 */
	public static function getRandom(int $size) : string
	{
		$rand = '';
		$index = count(self::$char) - 1;
		for ($i = 0; $i < $size; $i ++) {
			$rand .= self::$char[random_int(0, $index)];
		}

		return $rand;
	}

	/**
	 * @description encrypt mobile
	 *
	 * @param string $mobile
	 *
	 * @return string
	 */
	public static function encryptMobile(string $mobile) : string
	{
		return substr_replace($mobile, '****', 3, 4);
	}

	/**
	 * @description random number string
	 *
	 * @param int $size
	 *
	 * @return string
	 */
	public static function getRandomNumber(int $size) : string
	{
		$rand = '';
		$index = count(self::$num) - 1;
		for ($i = 0; $i < $size; $i ++) {
			$rand .= self::$num[random_int(0, $index)];
		}

		return $rand;
	}

	/**
	 * @description get age from user id card
	 *
	 * @param string $idCard
	 *
	 * @return int
	 */
	public static function getUserAge(string $idCard) : int
	{
		$year = substr($idCard, 6, 8);
		if ($year === false) {
			return 0;
		}

		return intval((time() - strtotime($year)) / (365 * 24 * 3600));
	}

	/**
	 * @description get birthday from user id card
	 *
	 * @param string $idCard
	 *
	 * @return string
	 */
	public static function getBirthday(string $idCard) : string
	{
		$year = substr($idCard, 6, 4);
		if ($year === false) {
			return null;
		}
		$month = substr($idCard, 10, 2);
		if ($month === false) {
			return null;
		}
		$day = substr($idCard, 10, 2);
		if ($day === false) {
			return null;
		}

		return $year . '-' . $month . '-' . $day;
	}

	/**
	 * @description convert 15 id card to 18 id card
	 *
	 * @param string $idCard
	 *
	 * @return string
	 */
	public static function convertIdCard(string $idCard) : string
	{
		if (15 == strlen($idCard)) {
			$W = array (7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2,1);
			$A = array ("1","0","X","9","8","7","6","5","4","3","2");
			$s = 0;
			$idCard18 = substr($idCard, 0, 6) . "19" . substr($idCard, 6);
			$idCard18_len = strlen($idCard18);
			for ($i = 0; $i < $idCard18_len; $i ++) {
				$s = $s + substr($idCard18, $i, 1) * $W[$i];
			}
			$idCard18 .= $A[$s % 11];
			return $idCard18;
		}

		return $idCard;
	}

	/**
	 * @description check is number
	 *
	 * @param string $num
	 *
	 * @return bool
	 */
    public static function isNumber(string $num) : bool
    {
        return ctype_digit($num);
    }
}
