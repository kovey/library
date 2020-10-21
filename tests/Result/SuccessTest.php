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

class SuccessTest extends TestCase
{
    public function testGetArray()
    {
        $result = Success::getArray(array('test' => 'case'));
        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('msg', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals(array('code' => 0, 'msg' => 'success', 'data' => array('test' => 'case')), $result);
    }

    public function testGetJson()
    {
        $result = Success::getJson(array('test' => 'case'));
        $this->assertEquals('{"code":0,"msg":"success","data":{"test":"case"}}', $result);
    }
}
