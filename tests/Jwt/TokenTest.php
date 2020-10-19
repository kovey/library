<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2020-10-19 17:10:48
 *
 */
namespace Kovey\Library\Jwt;

use PHPUnit\Framework\TestCase;
use Kovey\Library\Exception\TokenExpiredException;

class TokenTest extends TestCase
{
    public function testEncode()
    {
        $result = Token::encode(Array('a' => 'b'), '12345678901234567890', 20);
        $this->assertTrue(count(explode('.', $result)) == 3);
    }

    public function testDecodeFailureInvalidToken()
    {
        $this->expectException(TokenExpiredException::class);
        $this->expectExceptionMessage('token format error');
        Token::decode('aaabbbccc', '12345678901234567890', 20);
    }

    public function testDecodeFailure()
    {
        $this->expectException(TokenExpiredException::class);
        $this->expectExceptionMessage('token expired');
        Token::decode('BcrxH8Zz2Ucd3yxhpE4SXCIpYvTjOctYjlcQfeaE_sE.o2VcDgNm2lKzs5xBKspEBvNXy1XlaoFJE7AxzUDOGW0gOWaUzpXxTrki8-0Qo88yHX_sre-nE7MOX3fRb8NMXhHxAeomCzxBDA1rIbWfhPqZHK7W90CwgboMOsbGIMO0LseM1ALm9OyjH-GItBu76jrKLDdylG38JBhWlmTgTokeSZpL70lDv4_E7ZP67zVOuz7ZU3p1yCNSPxoEJyDTbg.flIA8SXfRxWMzTTOxgeFaDpHbsuKpZ8ol5Fb6I4SNiPWIS7mtrdkZmuo5pvd2DHS', '12345678901234567890', 20);
    }

    public function testDecode()
    {
        $this->assertEquals(array('a' => 'b'), Token::decode(Token::encode(array('a' => 'b'), '12345678901234567890', 20), '12345678901234567890', 20));
    }
}
