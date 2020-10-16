<?php
/**
 * @description 用于处理并发
 *
 * @package Kovey\Components\Wait
 *
 * @author kovey
 *
 * @time 2020-06-18 10:37:53
 *
 */
namespace Kovey\Library\Wait;

use Swoole\Coroutine\Channel;

class Group
{
    /**
     * @description channel
     * 
     * @var Channel
     */
    private Channel $chan;

    /**
     * @description 并发数量
     *
     * @var int
     */
    private int $count = 0;

    /**
     * @description 是否处于等待状态
     *
     * @var bool
     */
    private bool $isWaiting = false;

    public function __construct()
    {
        $this->chan = new Channel(1);
    }

    /**
     * @description 执行
     *
     * @param int $step = 1
     *
     * @return bool
     */
    public function exec(int $step = 1) : bool
    {
        if ($this->isWaiting) {
            return false;
        }

        if ($step < 0) {
            return false;
        }

        $this->count += $step;
        return true;
    }

    /**
     * @description 完成
     *
     * @return bool
     */
    public function done() : bool
    {
        $count = $this->count - 1;
        if ($count < 0) {
            return false;
        }
        $this->count = $count;

        // 全部完成
        if ($this->isWaiting && $count === 0) {
            $this->chan->push(true);
        }

        return true;
    }

    /**
     * @description wait
     *
     * @param float $timeout
     *
     * @return bool
     */
    public function wait(float $timeout = -1) : bool
    {
        if ($this->isWaiting) {
            return false;
        }

        if ($this->count < 1) {
            return false;
        }

        $this->isWaiting = true;
        $result = $this->chan->pop($timeout);
        $this->isWaiting = false;

        return $result;
    }
}
