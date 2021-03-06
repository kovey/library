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

class KoveyExceptionTest extends TestCase
{
    public function testException()
    {
        $this->expectException(KoveyException::class);
        $e = new KoveyException();
        $this->assertInstanceOf(\RuntimeException::class, $e);
        throw $e;
    }
}
