<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2020-10-19 16:55:43
 *
 */
namespace Kovey\Library\Jwt;

use PHPUnit\Framework\TestCase;
use Kovey\Library\Exception\TokenExpiredException;

class JwtTest extends TestCase
{
    public function testEncode()
    {
        $jwt = new Jwt('12345678901234567890');
        $result = $jwt->encode(Array('a' => 'b'));
        $this->assertTrue(count(explode('.', $result)) == 3);
    }

    public function testDecodeFailureInvalidToken()
    {
        $this->expectException(TokenExpiredException::class);
        $this->expectExceptionMessage('token format error');
        $jwt = new Jwt('123456789012345678901');
        $jwt->decode('aaabbbccc');
    }

    public function testDecodeFailure()
    {
        $this->expectException(TokenExpiredException::class);
        $this->expectExceptionMessage('token expired');
        $jwt = new Jwt('12345678901234567890');
        $jwt->decode('BcrxH8Zz2Ucd3yxhpE4SXCIpYvTjOctYjlcQfeaE_sE.o2VcDgNm2lKzs5xBKspEBvNXy1XlaoFJE7AxzUDOGW0gOWaUzpXxTrki8-0Qo88yHX_sre-nE7MOX3fRb8NMXhHxAeomCzxBDA1rIbWfhPqZHK7W90CwgboMOsbGIMO0LseM1ALm9OyjH-GItBu76jrKLDdylG38JBhWlmTgTokeSZpL70lDv4_E7ZP67zVOuz7ZU3p1yCNSPxoEJyDTbg.flIA8SXfRxWMzTTOxgeFaDpHbsuKpZ8ol5Fb6I4SNiPWIS7mtrdkZmuo5pvd2DHS');
    }

    public function testDecode()
    {
        $jwt = new Jwt('12345678901234567890');
        $this->assertEquals(array('a' => 'b'), $jwt->decode($jwt->encode(array('a' => 'b'))));
    }
}
