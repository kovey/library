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

use Swoole\Coroutine\System;

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
        go (function ($sql, $spentTime) {
            $spentTime = round($spentTime * 1000, 2) . 'ms';

            $content = sprintf("Time: %s\nSql: %s\nSpent Time: %s\n", date('Y-m-d H:i:s'), $sql, $spentTime);
            System::writeFile(
                self::$logDir . '/' . date('Y-m-d') . '.log',
                $content,
                FILE_APPEND
            );
        }, $sql, $spentTime);
	}
}
