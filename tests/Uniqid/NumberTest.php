<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2020-10-20 17:48:42
 *
 */
namespace Kovey\Library\Uniqid;

use PHPUnit\Framework\TestCase;

class NumberTest extends TestCase
{
    public function testGet()
    {
        $number = new Number();
        $this->assertEquals(10000000, $number->get());
        $this->assertEquals(10000001, $number->get());
        $this->assertEquals(10000002, $number->get());
    }

    public function testGetOrderId()
    {
        $number = new Number();
        $this->assertTrue(strlen($number->getOrderId(32)) == 32);
        $this->assertFalse(strlen($number->getOrderId(20)) == 20);
        $this->assertTrue(strlen($number->getOrderId(22)) == 22);
        $orderId = $number->getOrderId(32, 'B');
        $this->assertTrue(strlen($orderId) == 32);
        $this->assertStringStartsWith('B', $orderId);
    }
}
