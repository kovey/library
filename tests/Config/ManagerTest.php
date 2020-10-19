<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2020-10-16 12:17:13
 *
 */
namespace Kovey\Library\Config;

use PHPUnit\Framework\TestCase;
use Kovey\Library\Exception\KoveyException;

class ManagerTest extends TestCase
{
    public function testGet()
    {
        Manager::init(__DIR__ . '/data');
        $this->assertEquals('d', Manager::get('test.manager.b'));
        $this->assertEquals(array(
            'manager' => array(
                'a' => 1,
                'b' => 'd',
                'c' => 'f'
            ),
            'arr' => array(1, 2),
            'more' => array(
                array(
                    'test' => 1,
                    'manager' => 2
                ),
                array(
                    'test' => 11,
                    'manager' => 12
                )
            )
        ), Manager::get('test'));
    }

    public function testGetFailure()
    {
        Manager::init(__DIR__ . '/data');
        $this->expectException(KoveyException::class);
        Manager::get('kovey');
    }
}
