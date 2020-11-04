<?php
/**
 *
 * @description 日志管理类
 *
 * @package     Library\Logger
 *
 * @time        Tue Sep 24 09:06:05 2019
 *
 * @author      kovey
 */
namespace Kovey\Library\Logger;

use Kovey\Library\Util\Json;

class Logger
{
	/**
	 * @description INFO日志路径
	 *
	 * @var string
	 */
	private static string $infoPath;

	/**
	 * @description Exception日志路径
	 *
	 * @var string
	 */
	private static string $exceptionPath;

	/**
	 * @description Error日志路径
	 *
	 * @var string
	 */
	private static string $errorPath;

	/**
	 * @description Warning日志路径
	 *
	 * @var string
	 */
	private static string $warningPath;

    /**
     * @description 日志分类
     *
     * @var string
     */
    private static string $category = '';

	/**
	 * @description 设置日志路径
	 *
	 * @param string $info
	 *
	 * @param string $exception
	 *
	 * @param string $error
	 *
	 * @param string $warning
	 *
	 * @return null
	 */
	public static function setLogPath(string $info, string $exception, string $error, string $warning)
	{
		self::$infoPath = $info;
		if (!is_dir($info)) {
			mkdir($info, 0777, true);
		}

		self::$exceptionPath = $exception;
		if (!is_dir($exception)) {
			mkdir($exception, 0777, true);
		}

		self::$errorPath = $error;
		if (!is_dir($error)) {
			mkdir($error, 0777, true);
		}

		self::$warningPath = $warning;
		if (!is_dir($warning)) {
			mkdir($warning, 0777, true);
		}
	}

	/**
	 * @description 写入日志
	 *
	 * @param int $line
	 *
	 * @param string $file
	 *
	 * @param string | Array $msg
	 *
	 * @return void
	 */
    public static function writeInfoLog(int $line, string $file, string | Array $msg, string $traceId = '')
    {
		go (function (int $line, string $file, string | Array $msg, string $traceId) {
            $content = array(
                'time' => date('Y-m-d H:i:s'),
                'category' => self::$category,
                'type' => 'Info',
                'message' => $msg,
                'trace' => '',
                'line' => $line,
                'file' => $file,
                'traceId' => $traceId
            );
			file_put_contents(
				self::$infoPath . '/' . date('Y-m-d') . '.log',
				Json::encode($content) . PHP_EOL,
				FILE_APPEND
			);
		}, $line, $file, $msg, $traceId);
    }

	/**
	 * @description 写入错误日志
	 *
	 * @param int $line
	 *
	 * @param string $file
	 *
	 * @param string | Array $msg
	 *
	 * @return void
	 */
    public static function writeErrorLog(int $line, string $file, string | Array $msg, string $traceId = '')
    {
		go (function (int $line, string $file, string | Array $msg, string $traceId) {
            $content = array(
                'time' => date('Y-m-d H:i:s'),
                'category' => self::$category,
                'type' => 'Error',
                'message' => $msg,
                'trace' => '',
                'line' => $line,
                'file' => $file,
                'traceId' => $traceId
            );
			file_put_contents(
				self::$errorPath . '/' . date('Y-m-d') . '.log',
				Json::encode($content) . PHP_EOL,
				FILE_APPEND
			);
		}, $line, $file, $msg, $traceId);
    }

	/**
	 * @description 写入警告日志
	 *
	 * @param int $line
	 *
	 * @param string $file
	 *
	 * @param string | Array $msg
     *
     * @param string $traceId
	 *
	 * @return void
	 */
    public static function writeWarningLog(int $line, string $file, string | Array $msg, string $traceId = '')
    {
        go (function (int $line, string $file, string | Array $msg, string $traceId) {
            $content = array(
                'time' => date('Y-m-d H:i:s'),
                'category' => self::$category,
                'type' => 'Warning',
                'message' => $msg,
                'trace' => '',
                'line' => $line,
                'file' => $file,
                'traceId' => $traceId
            );
			file_put_contents(
				self::$warningPath . '/' . date('Y-m-d') . '.log',
				Json::encode($content) . PHP_EOL,
				FILE_APPEND
			);
		}, $line, $file, $msg, $traceId);
    }

	/**
	 * @description 写入异常日志
	 *
	 * @param int $line
	 *
	 * @param string $file
	 *
	 * @param Array | Throwable $e
     *
     * @param string $traceId
	 *
	 * @return Array
	 */
    public static function writeExceptionLog(int $line, string $file, \Throwable $e, string $traceId = '')
    {
		go (function (int $line, string $file, \Throwable $e, string $traceId) {
            $content = array(
                'time' => date('Y-m-d H:i:s'),
                'category' => self::$category,
                'type' => 'Exception',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line' => $line,
                'file' => $file,
                'traceId' => $traceId
            );
            file_put_contents(
                self::$exceptionPath . '/' . date('Y-m-d') . '.log',
                Json::encode($content) . PHP_EOL,
                FILE_APPEND
            );
		}, $line, $file, $e, $traceId);
    }

    /**
     * @description 设置日志分类
     *
     * @param string $category
     *
     * @return null
     */
    public static function setCategory(string $category)
    {
        self::$category = $category;
    }
}
