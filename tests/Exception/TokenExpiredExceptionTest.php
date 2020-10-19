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

class TokenExpiredExceptionTest extends TestCase
{
    public function testException()
    {
        $this->expectException(TokenExpiredException::class);
        $e = new TokenExpiredException();
        $this->assertInstanceOf(KoveyException::class, $e);
        throw $e;
    }
}
