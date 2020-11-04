<?php
/**
 *
 * @description 事件日志，包括请求等
 *
 * @package     Library\Logger
 *
 * @time        2020-01-18 17:56:17
 *
 * @author      kovey
 */
namespace Kovey\Library\Logger;

use Kovey\Library\Util\Json;

class Db
{
	/**
	 * @description 日志mulu
	 *
	 * @var string
	 */
	private static string $logDir;

	/**
	 * @description 设置日志目录
	 *
	 * @param string $logDir
	 */
	public static function setLogDir(string $logDir)
	{
		self::$logDir = $logDir;
		if (!is_dir($logDir)) {
			mkdir($logDir, 0777, true);
		}
	}

	/**
	 * @description 写入日志
	 *
	 * @param string $sql
	 *
	 * @param float $spentTime
	 */
	public static function write(string $sql, float $spentTime)
	{
        go (function (string $sql, float $spentTime) {
            $spentTime = round($spentTime * 1000, 2) . 'ms';
            $content = array(
                'time' => date('Y-m-d H:i:s'),
                'sql' => $sql,
                'delay'  => $spentTime
            );
            file_put_contents(
                self::$logDir . '/' . date('Y-m-d') . '.log',
                Json::encode($content) . PHP_EOL,
                FILE_APPEND
            );
        }, $sql, $spentTime);
	}
}
