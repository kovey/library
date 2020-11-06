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
        $jwt = new Jwt('vGKUOiH8jF6z9atNR3Ty3po4rVXQV1Qa9UzNV91mO9f');
        $result = $jwt->encode(Array('a' => 'b'));
        $this->assertTrue(count(explode('.', $result)) == 3);
    }

    public function testDecodeFailureInvalidToken()
    {
        $this->expectException(TokenExpiredException::class);
        $this->expectExceptionMessage('token format error');
        $jwt = new Jwt('vGKUOiH8jF6z9atNR3Ty3po4rVXQV1Qa9UzNV91mO9f');
        $jwt->decode('aaabbbccc');
    }

    public function testDecodeFailure()
    {
        $this->expectException(TokenExpiredException::class);
        $this->expectExceptionMessage('token expired');
        $jwt = new Jwt('vGKUOiH8jF6z9atNR3Ty3po4rVXQV1Qa9UzNV91mO9f');
        $jwt->decode('7wjSy_fwGrakTAsqJ4njevie9jyq71uuUzV1sJPaCmI.LZamEKMje_gf42u48BPgzyRXgfF2TJEI5SJd9c-pSOVl6bs3qYmQsSFJlB-78-4UlGwE-TDKn5l8mlY5oz_rytaNeTmQBrsYuXcw3z5w8AUuRNsgB0T-OZRDTnVtX371Mz6uNWSVoylvSICqs4OQ4sEUfory8c8t0IsZDt4cDpBX4D76nbgrEGVrFIWn2xohICMO9c7U0pXLWMbXurLX5w.q9BQbC1NDU5Fvd_AxsmQhYlxtAcBS7A5a3S2iRdvYmdVJiapp7AQVBEueDftIFBk');
    }

    public function testDecode()
    {
        $jwt = new Jwt('vGKUOiH8jF6z9atNR3Ty3po4rVXQV1Qa9UzNV91mO9f');
        $this->assertEquals(array('a' => 'b'), $jwt->decode($jwt->encode(array('a' => 'b'))));
    }
}
