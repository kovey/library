<?php
/**
 *
 * @description 监控日志
 *
 * @package     Library\Monitor
 *
 * @time        2020-01-18 17:48:50
 *
 * @author      kovey
 */
namespace Kovey\Library\Logger;

use Kovey\Library\Util\Json;

class Monitor
{
	/**
	 * @description 日志目录
	 *
	 * @var string
	 */
	private static string $logDir;

	/**
	 * @description 设置日志目录
	 *
	 * @param string $logDir
	 *
	 * @return null
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
	 * @param Array $content
	 *
	 * @return null
	 */
	public static function write(Array $content)
	{
		go (function (Array $content) {
			$content = Json::encode($content);
			file_put_contents(
				self::$logDir . '/' . date('Y-m-d') . '.log',
				$content . PHP_EOL,
				FILE_APPEND
			);
		}, $content);
	}
}
