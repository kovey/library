<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2020-10-19 14:53:04
 *
 */
namespace Kovey\Library\Encryption;

use PHPUnit\Framework\TestCase;
use Kovey\Library\Exception\KoveyException;

class PubRsaTest extends TestCase
{
    public function testEncryptFailureInvalidKey()
    {
        $this->expectException(KoveyException::class);
        PubRsa::encrypt('aaaaaaabbbb', 'aaaaaaa');
    }

    public function testEncrypt()
    {
        $result = PubRsa::encrypt('aaabbb', __DIR__ . '/conf/public.pem');
        $this->assertTrue(strlen($result) > 0);
    }

    public function testDecryptFailureInvalidKey()
    {
        $this->expectException(KoveyException::class);
        PubRsa::encrypt('aaaaaaabbbb', 'aaaaaa');
    }

    public function testDecrypt()
    {
        $result = PubRsa::decrypt('JnwKMrvUISWx2p55Je9zKD3tXLd0N/DmKvkGByp/eCm/8yzyA7NGFWxPoXAf7+x9BQl5rYhT324ml/VBynvu6eYwsZRNP5c76WHxdCamHlyzmm2gQWDcjxevhql/ocyIyjVDt9QR1GHtiLoeGdAaU5Bk0IvaxzFBRERKQ9nw5CKMzmR2uauKWCln/EnufOaPr2Dwh7aDl2LqFIpuQReuJsGrWoNCLE5mwQxqqFYX5cLXz/C7RQ+vMw1/BSiFP8qGN2OTz6yBIJaWv74CbclJxNwJajW2d9XJlrybtL65uArGughTjHOJiICOHE0nCktFNa0rB3eZZ/y8wd7HcHi3EA==', __DIR__ . '/conf/public.pem');
        $this->assertEquals('aaabbb', $result);
    }
}
