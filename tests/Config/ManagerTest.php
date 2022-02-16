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
        $this->assertEquals('kovey', Manager::get('test.name'));
        $this->assertEquals(array(
            'name' => 'kovey',
            'config' => array('1', '2', 'a'),
            'map' => array(
                'test' => array(
                    'aa' => "bb"
                ),
                'env' => 'On'
            ),
            'bool' => true
        ), Manager::get('test'));
    }

    public function testGetFailure()
    {
        Manager::init(__DIR__ . '/data');
        $this->expectException(KoveyException::class);
        Manager::get('kovey');
    }
}
