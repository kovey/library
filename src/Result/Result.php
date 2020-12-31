<?php
/**
 *
 * @description 接口返回值基类
 *
 * @package     Library\Result
 *
 * @time        Tue Sep 24 09:12:05 2019
 *
 * @author      kovey
 */
namespace Kovey\Library\Result;

use Kovey\Library\Util\Json;

abstract class Result
{
    /**
     * @description 错误码
     *
     * @var int
     */
    protected int $code;

    /**
     * @description 错误消息
     *
     * @var string
     */
    protected string $msg;

    /**
     * @description 返回数据
     *
     * @var Array
     */
    protected Array | \ArrayObject $data;

    /**
     * @description 是否开发模式
     *
     * @var bool
     */
    protected bool $isDev;

    /**
     * @description 构造结果
     *
     * @param int $code
     *
     * @param string $msg
     *
     * @param Array $data
     *
     * @return Result
     */
    public function __construct(int $code, string $msg, Array | \ArrayObject $data)
    {
        $this->code = $code;
        $this->msg = $msg;
        $this->data = $data;
        $this->isDev = true;
    }

    /**
     * @description 转为数组
     *
     * @return Array
     */
    protected function toArray() : Array
    {
        return array(
            'code' => $this->code,
            'msg' => $this->isDev ? $this->msg : '',
            'data' => $this->data
        );
    }

    /**
     * @description 转为JSON
     *
     * @return string
     */
    protected function toJson() : string
    {
        return Json::encode($this->toArray());
    }
}
