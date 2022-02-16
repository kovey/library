<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2022-02-16 15:23:12
 *
 */
namespace Kovey\Library\Context;

use PHPUnit\Framework\TestCase;

class ContextTest extends TestCase
{
    public function testContext()
    {
        $context = new Context('1111', '2222', array('kovey' => 'ext'));
        $this->assertEquals('1111', $context->getTraceId());
        $this->assertEquals('2222', $context->getSpanId());
        $this->assertEquals('ext', $context->getExt()['kovey']);
    }
}
