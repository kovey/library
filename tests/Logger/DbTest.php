<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2020-10-19 18:22:56
 *
 */
namespace Kovey\Library\Logger;

use PHPUnit\Framework\TestCase;

class DbTest extends TestCase
{
    public function testDbSetDir()
    {
        Db::setLogDir(__DIR__ . '/log');
        $this->assertTrue(is_dir(__DIR__ . '/log'));
    }

    public function testWrite()
    {
        Db::setLogDir(__DIR__ . '/log');
        Db::write('SELECT * FROM test Where id = 1', 0.0145);
        $this->assertFileExists(__DIR__ . '/log/' . date('Y-m-d') . '.log');
    }

    public static function tearDownAfterClass() : void
    {
        if (is_file(__DIR__ . '/log/' . date('Y-m-d') . '.log')) {
            unlink(__DIR__ . '/log/' . date('Y-m-d') . '.log');
        }
        if (is_dir(__DIR__ . '/log')) {
            rmdir(__DIR__ . '/log');
        }
    }
}
