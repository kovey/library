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

class ProtocolExceptionTest extends TestCase
{
    public function testException()
    {
        $this->expectException(ProtocolException::class);
        $e = new ProtocolException('protocol exception', 1000, 'exception');
        $this->assertInstanceOf(KoveyException::class, $e);
        $this->assertEquals('protocol exception', $e->getMessage());
        $this->assertEquals(1000, $e->getCode());
        $this->assertEquals('exception', $e->getErrorType());
        throw $e;
    }
}
