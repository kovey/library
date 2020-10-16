<?php
/**
 *
 * @description Protocol Exception
 *
 * @package     Library\Exception
 *
 * @time        2019-11-16 18:13:16
 *
 * @author      kovey
 */
namespace Kovey\Rpc\Protocol;

use Kovey\Library\Exception\KoveyException;

class ProtocolException extends KoveyException
{
	/**
	 * @description 错误类型
	 *
	 * @var string
	 */
	private string $errorType;

	/**
	 * @description 构造函数
	 *
	 * @param string $msg
	 *
	 * @param int $code
	 *
	 * @param string $type
	 *
	 * @return Exception
	 */
	public function __construct(string $msg, int $code, string $type)
	{
		$this->errorType = $type;

		parent::__construct($msg, $code);
	}

	/**
	 * @description 获取错误类型
	 *
	 * @return string
	 */
	public function getErrorType() : string
	{
		return $this->errorType;
	}
}
