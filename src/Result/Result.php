<?php
/**
 *
 * @description return with interface
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
     * @description error code
     *
     * @var int
     */
    protected int $code;

    /**
     * @description error msg
     *
     * @var string
     */
    protected string $msg;

    /**
     * @description return data
     *
     * @var Array
     */
    protected Array | \ArrayObject $data;

    /**
     * @description is dev
     *
     * @var bool
     */
    protected bool $isDev;

    /**
     * @description construct result
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
     * @description to array
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
     * @description to json
     *
     * @return string
     */
    protected function toJson() : string
    {
        return Json::encode($this->toArray());
    }
}
