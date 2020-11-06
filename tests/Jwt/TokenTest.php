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
        $result = Token::encode(Array('a' => 'b'), 'vGKUOiH8jF6z9atNR3Ty3po4rVXQV1Qa9UzNV91mO9f', 20);
        $this->assertTrue(count(explode('.', $result)) == 3);
    }

    public function testDecodeFailureInvalidToken()
    {
        $this->expectException(TokenExpiredException::class);
        $this->expectExceptionMessage('token format error');
        Token::decode('aaabbbccc', 'vGKUOiH8jF6z9atNR3Ty3po4rVXQV1Qa9UzNV91mO9f', 20);
    }

    public function testDecodeFailure()
    {
        $this->expectException(TokenExpiredException::class);
        $this->expectExceptionMessage('token expired');
        Token::decode('7wjSy_fwGrakTAsqJ4njevie9jyq71uuUzV1sJPaCmI.LZamEKMje_gf42u48BPgzyRXgfF2TJEI5SJd9c-pSOVl6bs3qYmQsSFJlB-78-4UlGwE-TDKn5l8mlY5oz_rytaNeTmQBrsYuXcw3z5w8AUuRNsgB0T-OZRDTnVtX371Mz6uNWSVoylvSICqs4OQ4sEUfory8c8t0IsZDt4cDpBX4D76nbgrEGVrFIWn2xohICMO9c7U0pXLWMbXurLX5w.q9BQbC1NDU5Fvd_AxsmQhYlxtAcBS7A5a3S2iRdvYmdVJiapp7AQVBEueDftIFBk', 'vGKUOiH8jF6z9atNR3Ty3po4rVXQV1Qa9UzNV91mO9f', 20);
    }

    public function testDecode()
    {
        $this->assertEquals(array('a' => 'b'), Token::decode(Token::encode(array('a' => 'b'), 'vGKUOiH8jF6z9atNR3Ty3po4rVXQV1Qa9UzNV91mO9f', 20), 'vGKUOiH8jF6z9atNR3Ty3po4rVXQV1Qa9UzNV91mO9f', 20));
    }
}
