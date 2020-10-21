<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2020-10-20 17:17:31
 *
 */
namespace Kovey\Library\Result;

use PHPUnit\Framework\TestCase;

class ErrorTest extends TestCase
{
    public function testGetArray()
    {
        $result = Error::getArray(1000, 'test error', array('test' => 'case'));
        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('msg', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals(array('code' => 1000, 'msg' => 'test error', 'data' => array('test' => 'case')), $result);
    }

    public function testGetJson()
    {
        $result = Error::getJson(1000, 'test error');
        $this->assertEquals('{"code":1000,"msg":"test error","data":[]}', $result);
    }
}
