<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2020-11-04 13:36:06
 *
 */
namespace Kovey\Library\Logger;

use PHPUnit\Framework\TestCase;
use Kovey\Library\Util\Json;
use Swoole\Coroutine\System;

class LoggerTest extends TestCase
{
    public function testDbSetDir()
    {
        Logger::setLogPath(__DIR__ . '/log/info', __DIR__ . '/log/exception', __DIR__ . '/log/error', __DIR__ . '/log/warning');
        $this->assertTrue(is_dir(__DIR__ . '/log'));
        $this->assertTrue(is_dir(__DIR__ . '/log/info'));
        $this->assertTrue(is_dir(__DIR__ . '/log/exception'));
        $this->assertTrue(is_dir(__DIR__ . '/log/error'));
        $this->assertTrue(is_dir(__DIR__ . '/log/warning'));
    }

    public function testWrite()
    {
        Logger::setLogPath(__DIR__ . '/log/info', __DIR__ . '/log/exception', __DIR__ . '/log/error', __DIR__ . '/log/warning');
        Logger::setCategory('kovey');
        $traceId = md5('123456');
        Logger::writeInfoLog(1, 'test.php', 'kovey framework', $traceId);
        Logger::writeErrorLog(1, 'test.php', 'kovey framework', $traceId);
        Logger::writeWarningLog(1, 'test.php', 'kovey framework', $traceId);
        Logger::writeExceptionLog(1, 'test.php', new \Exception('kovey exception'), $traceId);
        System::sleep(0.1);
        $this->assertFileExists(__DIR__ . '/log/info/' . date('Y-m-d') . '.log');
        $this->assertFileExists(__DIR__ . '/log/exception/' . date('Y-m-d') . '.log');
        $this->assertFileExists(__DIR__ . '/log/error/' . date('Y-m-d') . '.log');
        $this->assertFileExists(__DIR__ . '/log/warning/' . date('Y-m-d') . '.log');
        $log = Json::decode(file_get_contents(__DIR__ . '/log/info/' . date('Y-m-d') . '.log'));
        $this->assertEquals('kovey', $log['category']);
        $this->assertEquals('Info', $log['type']);
        $this->assertEquals('kovey framework', $log['message']);
        $this->assertEquals(1, $log['line']);
        $this->assertEquals('test.php', $log['file']);
        $this->assertEquals($traceId, $log['traceId']);
        $log = Json::decode(file_get_contents(__DIR__ . '/log/exception/' . date('Y-m-d') . '.log'));
        $this->assertEquals('kovey', $log['category']);
        $this->assertEquals('Exception', $log['type']);
        $this->assertEquals('kovey exception', $log['message']);
        $this->assertEquals(1, $log['line']);
        $this->assertEquals('test.php', $log['file']);
        $this->assertEquals($traceId, $log['traceId']);
        $log = Json::decode(file_get_contents(__DIR__ . '/log/error/' . date('Y-m-d') . '.log'));
        $this->assertEquals('kovey', $log['category']);
        $this->assertEquals('Error', $log['type']);
        $this->assertEquals('kovey framework', $log['message']);
        $this->assertEquals(1, $log['line']);
        $this->assertEquals('test.php', $log['file']);
        $this->assertEquals($traceId, $log['traceId']);
        $log = Json::decode(file_get_contents(__DIR__ . '/log/warning/' . date('Y-m-d') . '.log'));
        $this->assertEquals('kovey', $log['category']);
        $this->assertEquals('Warning', $log['type']);
        $this->assertEquals('kovey framework', $log['message']);
        $this->assertEquals(1, $log['line']);
        $this->assertEquals('test.php', $log['file']);
        $this->assertEquals($traceId, $log['traceId']);
    }

    public static function tearDownAfterClass() : void
    {
        if (is_file(__DIR__ . '/log/info/' . date('Y-m-d') . '.log')) {
            unlink(__DIR__ . '/log/info/' . date('Y-m-d') . '.log');
        }
        if (is_dir(__DIR__ . '/log/info')) {
            rmdir(__DIR__ . '/log/info');
        }
        if (is_file(__DIR__ . '/log/exception/' . date('Y-m-d') . '.log')) {
            unlink(__DIR__ . '/log/exception/' . date('Y-m-d') . '.log');
        }
        if (is_dir(__DIR__ . '/log/exception')) {
            rmdir(__DIR__ . '/log/exception');
        }
        if (is_file(__DIR__ . '/log/warning/' . date('Y-m-d') . '.log')) {
            unlink(__DIR__ . '/log/warning/' . date('Y-m-d') . '.log');
        }
        if (is_dir(__DIR__ . '/log/warning')) {
            rmdir(__DIR__ . '/log/warning');
        }
        if (is_file(__DIR__ . '/log/error/' . date('Y-m-d') . '.log')) {
            unlink(__DIR__ . '/log/error/' . date('Y-m-d') . '.log');
        }
        if (is_dir(__DIR__ . '/log/error')) {
            rmdir(__DIR__ . '/log/error');
        }
        if (is_dir(__DIR__ . '/log')) {
            rmdir(__DIR__ . '/log');
        }
    }
}
