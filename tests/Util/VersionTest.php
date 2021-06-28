<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2021-06-28 18:17:53
 *
 */
namespace Kovey\Library\Util;

use PHPUnit\Framework\TestCase;

class VersionTest extends TestCase
{
    public function testEq()
    {
        $this->assertEquals(Version::EQ, Version::compare('1.0.1', '1.0.1'));
        $this->assertEquals(Version::EQ, Version::compare('1.0.1.0', '1.0.1'));
        $this->assertEquals(Version::EQ, Version::compare('1.0.1.0.0', '1.0.1'));
    }

    public function testGt()
    {
        $this->assertEquals(Version::GT, Version::compare('1.0.2', '1.0.1'));
        $this->assertEquals(Version::GT, Version::compare('1.0.1.1', '1.0.1'));
        $this->assertEquals(Version::GT, Version::compare('1.1.0', '1.0.1'));
        $this->assertEquals(Version::GT, Version::compare('2.0.0', '1.0.1'));
    }

    public function testLt()
    {
        $this->assertEquals(Version::LT, Version::compare('1.0.0', '1.0.1'));
        $this->assertEquals(Version::LT, Version::compare('1.0.1.1', '1.0.1.2'));
        $this->assertEquals(Version::LT, Version::compare('1.1.0', '2.0.1'));
        $this->assertEquals(Version::LT, Version::compare('2.0.0', '2.0.0.0.0.1'));
        $this->assertEquals(Version::LT, Version::compare('2.0.0', '2.0.1'));
    }
}
