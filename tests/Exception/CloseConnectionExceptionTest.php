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

class CloseConnectionExceptionTest extends TestCase
{
    public function testException()
    {
        $this->expectException(CloseConnectionException::class);
        $e = new CloseConnectionException();
        $this->assertInstanceOf(KoveyException::class, $e);
        throw $e;
    }
}
