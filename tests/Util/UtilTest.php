<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2020-10-20 18:07:31
 *
 */
namespace Kovey\Library\Util;

use PHPUnit\Framework\TestCase;

class UtilTest extends TestCase
{
    public function testIsPath()
    {
        $this->assertTrue(Util::isPath('path'));
        $this->assertFalse(Util::isPath('a*jj'));
    }

    public function testGetMicrotime()
    {
        $this->assertTrue(Util::getMicrotime() > 0);
        $this->assertTrue(is_float(Util::getMicrotime()));
    }

    public function testGetRandom()
    {
        $this->assertTrue(strlen(Util::getRandom(20)) == 20);
    }

    public function getEncryptMobile()
    {
        $this->assertEquals('135****8899', Util::encryptMobile('13587658899'));
    }

    public function testGetRandomNumber()
    {
        $this->assertTrue(ctype_digit(Util::getRandomNumber(8)));
        $this->assertTrue(strlen(Util::getRandomNumber(8)) == 8);
    }

    public function testIsNumber()
    {
        $this->assertTrue(Util::isNumber('123456'));
        $this->assertFalse(Util::isNumber('1aaa123'));
    }

    public function testGetBirthday()
    {
        $this->assertNull(Util::getBirthday('100100'));
        $this->assertNull(Util::getBirthday('1001001999'));
        $this->assertNull(Util::getBirthday('100100199905'));
        $this->assertEquals('1999-05-12', Util::getBirthday('100100199905120375'));
    }

    public function testConvertIdCard()
    {
        $result = Util::convertIdCard('130503670401001');
        $this->assertTrue(strlen($result) == 18);
        $this->assertEquals('130503196704010016', $result);
    }
}
