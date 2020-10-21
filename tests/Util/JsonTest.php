<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2020-10-20 17:58:05
 *
 */
namespace Kovey\Library\Util;

use PHPUnit\Framework\TestCase;
use Kovey\Library\Exception\KoveyException;

class JsonTest extends TestCase
{
    public function testEncode()
    {
        $this->assertEquals(
            '{"name":"kovey","labels":["label1","帅"],"info":{"nickname":"kovey"}}', Json::encode(array('name' => 'kovey', 'labels' => array('label1', '帅'), 'info' => array('nickname' => 'kovey')))
        );
    }

    public function testDecodeFailure()
    {
        $this->expectException(KoveyException::class);
        $this->expectExceptionMessage('Syntax error');
        Json::decode('{aa,bbb}');
    }

    public function testDecode()
    {
        $this->assertEquals(
            array('name' => 'kovey', 'labels' => array('label1', '帅'), 'info' => array('nickname' => 'kovey')), 
            Json::decode('{"name":"kovey","labels":["label1","帅"],"info":{"nickname":"kovey"}}')
        );
    }
}
