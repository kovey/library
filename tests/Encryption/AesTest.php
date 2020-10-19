<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2020-10-19 14:29:05
 *
 */
namespace Kovey\Library\Encryption;

use PHPUnit\Framework\TestCase;
use Kovey\Library\Exception\KoveyException;

class AesTest extends TestCase
{
    public function testEncryptFailureInvalidKey()
    {
        $this->expectException(KoveyException::class);
        Aes::encrypt('aaa', 'bbb');
    }

    public function testEncrypt()
    {
        $this->assertEquals('YYKiIdErJTudQ98Fmomgcg==', Aes::encrypt('aaa', md5('bbb')));
    }

    public function testDecryptFailureShortKey()
    {
        $this->expectException(KoveyException::class);
        Aes::decrypt('aaa', 'bbb');
    }

    public function testDecryptFailure()
    {
        $this->expectException(KoveyException::class);
        Aes::decrypt('YYKiIdErJTudQ98Fmomgcg==', md5('ccc'));
    }

    public function testDecrypt()
    {
        $this->assertEquals('aaa', Aes::decrypt('YYKiIdErJTudQ98Fmomgcg==', md5('bbb')));
    }
}
