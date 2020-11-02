<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2020-10-21 16:52:34
 *
 */
namespace Kovey\Library\Wait;

use PHPUnit\Framework\TestCase;

class GroupTest extends TestCase
{
    public function testGroup()
    {
        $group = new Group();
        $group->exec();
        go (function ($group) {
            \Swoole\Coroutine\System::sleep(0.05);
            $group->done();
        }, $group);
        $group->exec();
        go (function ($group) {
            \Swoole\Coroutine\System::sleep(0.05);
            $group->done();
        }, $group);
        $this->assertTrue($group->wait());
    }
}
