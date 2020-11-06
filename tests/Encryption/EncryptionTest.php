<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2020-10-19 15:23:05
 *
 */
namespace Kovey\Library\Encryption;

use PHPUnit\Framework\TestCase;
use Kovey\Library\Exception\KoveyException;

class EncryptionTest extends TestCase
{
    public function testEncryptUnsupportType()
    {
       $this->expectException(KoveyException::class) ;
       Encryption::encrypt('aaabbb', md5('bbb'), 'md5');
    }

    public function testAesEncryptFailureInvalidKey()
    {
       $this->expectException(KoveyException::class) ;
       Encryption::encrypt('aaabbb', 'aaaaa');
    }

    public function testAesEncrypt()
    {
       $this->assertEquals('9HDabDC8gmC/yDM8Gadn+A==', Encryption::encrypt('aaabbb', 'vGKUOiH8jF6z9atNR3Ty3po4rVXQV1Qa9UzNV91mO9f', 'aes'));
    }

    public function testAesDecryptUnsupportType()
    {
       $this->expectException(KoveyException::class) ;
       Encryption::decrypt('aaabbb', 'vGKUOiH8jF6z9atNR3Ty3po4rVXQV1Qa9UzNV91mO9f', 'md5');
    }

    public function testAesDescryptFailureInvalidKey()
    {
       $this->expectException(KoveyException::class) ;
       Encryption::decrypt('aaabbb', 'aaaaa');
    }

    public function testAesDescrypt()
    {
       $this->assertEquals('aaabbb', Encryption::decrypt('9HDabDC8gmC/yDM8Gadn+A==', 'vGKUOiH8jF6z9atNR3Ty3po4rVXQV1Qa9UzNV91mO9f', 'aes'));
    }

    public function testPubRsaEncryptFailureInvalidKey()
    {
       $this->expectException(KoveyException::class) ;
       Encryption::encrypt('aaabbb', 'aaaaa', 'rsa', true);
    }

    public function testPubRsaEncrypt()
    {
        $result = Encryption::encrypt('aaabbb', __DIR__ . '/conf/public.pem', 'rsa', true);
        $this->assertTrue(strlen($result) > 0);
    }

    public function testPubRsaDecryptFailureInvalidKey()
    {
       $this->expectException(KoveyException::class) ;
       Encryption::decrypt('aaabbb', 'aaaaa', 'rsa', true);
    }

    public function testDecrypt()
    {
        $result = Encryption::decrypt('JnwKMrvUISWx2p55Je9zKD3tXLd0N/DmKvkGByp/eCm/8yzyA7NGFWxPoXAf7+x9BQl5rYhT324ml/VBynvu6eYwsZRNP5c76WHxdCamHlyzmm2gQWDcjxevhql/ocyIyjVDt9QR1GHtiLoeGdAaU5Bk0IvaxzFBRERKQ9nw5CKMzmR2uauKWCln/EnufOaPr2Dwh7aDl2LqFIpuQReuJsGrWoNCLE5mwQxqqFYX5cLXz/C7RQ+vMw1/BSiFP8qGN2OTz6yBIJaWv74CbclJxNwJajW2d9XJlrybtL65uArGughTjHOJiICOHE0nCktFNa0rB3eZZ/y8wd7HcHi3EA==', __DIR__ . '/conf/public.pem', 'rsa', true);
        $this->assertEquals('aaabbb', $result);
    }

    public function testPriRsaEncryptFailureInvalidKey()
    {
        $this->expectException(KoveyException::class);
        Encryption::encrypt('aaaaaaabbbb', 'aaaaaaa', 'rsa');
    }

    public function testPriRsaEncrypt()
    {
        $result = Encryption::encrypt('aaabbb', __DIR__ . '/conf/private.pem', 'rsa');
        $this->assertEquals('JnwKMrvUISWx2p55Je9zKD3tXLd0N/DmKvkGByp/eCm/8yzyA7NGFWxPoXAf7+x9BQl5rYhT324ml/VBynvu6eYwsZRNP5c76WHxdCamHlyzmm2gQWDcjxevhql/ocyIyjVDt9QR1GHtiLoeGdAaU5Bk0IvaxzFBRERKQ9nw5CKMzmR2uauKWCln/EnufOaPr2Dwh7aDl2LqFIpuQReuJsGrWoNCLE5mwQxqqFYX5cLXz/C7RQ+vMw1/BSiFP8qGN2OTz6yBIJaWv74CbclJxNwJajW2d9XJlrybtL65uArGughTjHOJiICOHE0nCktFNa0rB3eZZ/y8wd7HcHi3EA==', $result);
    }

    public function testPriRsaDecryptFailureInvalidKey()
    {
        $this->expectException(KoveyException::class);
        Encryption::encrypt('aaaaaaabbbb', 'aaaaaa', 'rsa');
    }

    public function testPriRsaDecrypt()
    {
        $result = Encryption::decrypt('bMtua5FvK79WlWO2KCyDTgXsJiYFAmwPVrER2rYp3JbIimLj0r61x/xVPA/xoluy1zj55aZ24yoxV8B1Xh1ECjfh8sNo2veRzf5v5dRUt7eygt0GyP31y34S5iHxt4esQRWWYKCZRRBcdB4zAyqd+ucY3ENNDKoIQ0Ruv8vCU4zXeoN4JabO4ClgmvIuh2qhjo1i2gBxA9Z6sfeoNciF5a1mYO4CuU6spnUw7RQMwwDSBx3Umn52cVQg2bwe+98qhLYTqMBMRkVW0b6PXK2osE2KaFDrFzLeTS4ZsPWqMe7ZINe8YnxpfV6SqTjeSAgIvCy3PMCis+7MvxcvvCQenw==', __DIR__ . '/conf/private.pem', 'rsa');
        $this->assertEquals('aaabbb', $result);
    }
}
