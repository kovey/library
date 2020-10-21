<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2020-10-19 16:42:47
 *
 */
namespace Kovey\Library\Exception;

use PHPUnit\Framework\TestCase;

class BusiExceptionTest extends TestCase
{
    public function testException()
    {
        $this->expectException(BusiException::class);
        $e = new BusiException();
        $this->assertInstanceOf(KoveyException::class, $e);
        throw $e;
    }

    public function testExceptionExt()
    {
        $this->expectException(BusiException::class);
        $e = new BusiException('test', 1000, array('data' => array('id' => 1, 'dd' => 'ff')));
        $this->assertInstanceOf(KoveyException::class, $e);
        $this->assertEquals('test', $e->getMessage());
        $this->assertEquals(1000, $e->getCode());
        $this->assertEquals(array('data' => array('id' => 1, 'dd' => 'ff')), $e->getExt());
        throw $e;
    }
}
