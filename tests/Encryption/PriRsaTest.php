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

class PriRsaTest extends TestCase
{
    public function testEncryptFailureInvalidKey()
    {
        $this->expectException(KoveyException::class);
        PriRsa::encrypt('aaaaaaabbbb', 'aaaaaaa');
    }

    public function testEncrypt()
    {
        $result = PriRsa::encrypt('aaabbb', __DIR__ . '/conf/private.pem');
        $this->assertEquals('JnwKMrvUISWx2p55Je9zKD3tXLd0N/DmKvkGByp/eCm/8yzyA7NGFWxPoXAf7+x9BQl5rYhT324ml/VBynvu6eYwsZRNP5c76WHxdCamHlyzmm2gQWDcjxevhql/ocyIyjVDt9QR1GHtiLoeGdAaU5Bk0IvaxzFBRERKQ9nw5CKMzmR2uauKWCln/EnufOaPr2Dwh7aDl2LqFIpuQReuJsGrWoNCLE5mwQxqqFYX5cLXz/C7RQ+vMw1/BSiFP8qGN2OTz6yBIJaWv74CbclJxNwJajW2d9XJlrybtL65uArGughTjHOJiICOHE0nCktFNa0rB3eZZ/y8wd7HcHi3EA==', $result);
    }

    public function testDecryptFailureInvalidKey()
    {
        $this->expectException(KoveyException::class);
        PriRsa::encrypt('aaaaaaabbbb', 'aaaaaa');
    }

    public function testDecrypt()
    {
        $result = PriRsa::decrypt('bMtua5FvK79WlWO2KCyDTgXsJiYFAmwPVrER2rYp3JbIimLj0r61x/xVPA/xoluy1zj55aZ24yoxV8B1Xh1ECjfh8sNo2veRzf5v5dRUt7eygt0GyP31y34S5iHxt4esQRWWYKCZRRBcdB4zAyqd+ucY3ENNDKoIQ0Ruv8vCU4zXeoN4JabO4ClgmvIuh2qhjo1i2gBxA9Z6sfeoNciF5a1mYO4CuU6spnUw7RQMwwDSBx3Umn52cVQg2bwe+98qhLYTqMBMRkVW0b6PXK2osE2KaFDrFzLeTS4ZsPWqMe7ZINe8YnxpfV6SqTjeSAgIvCy3PMCis+7MvxcvvCQenw==', __DIR__ . '/conf/private.pem');
        $this->assertEquals('aaabbb', $result);
    }
}
