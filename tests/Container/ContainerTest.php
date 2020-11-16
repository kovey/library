<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2020-10-16 13:16:13
 *
 */
namespace Kovey\Library\Container;

require_once __DIR__ . '/Cases/Foo.php';
require_once __DIR__ . '/Cases/Foo1.php';
require_once __DIR__ . '/Cases/Foo2.php';
require_once __DIR__ . '/Cases/Foo4.php';
require_once __DIR__ . '/Cases/Foo5.php';
require_once __DIR__ . '/Cases/Foo6.php';
require_once __DIR__ . '/Cases/Foo7.php';

use PHPUnit\Framework\TestCase;
use Kovey\Library\Container\Cases;

class ContainerTest extends TestCase
{
    public function testGet()
    {
        $traceId = hash('sha256', '123456');
        $container = new Container();
        $foo = $container->get('Kovey\Library\Container\Cases\Foo', $traceId);
        $container->on('Database', function () {
            return 'Db';
        });
        $this->assertInstanceOf(Cases\Foo::class, $foo);
        $this->assertEquals($traceId, $foo->traceId);
        $foo1 = $foo->getFoo1();
        $this->assertInstanceOf(Cases\Foo1::class, $foo1);
        $this->assertEquals($traceId, $foo1->traceId);
        $foo2 = $foo1->getFoo2();
        $this->assertInstanceOf(Cases\Foo2::class, $foo2);
        $this->assertEquals($traceId, $foo2->traceId);
        $this->assertEquals('this is test', $foo2->getName());
        $args = $container->getMethodArguments('Kovey\Library\Container\Cases\Foo', 'test', $traceId);
        $keywords = $container->getKeywords('Kovey\Library\Container\Cases\Foo', 'test');
        $this->assertEquals(1, count($args));
        $this->assertInstanceOf(Cases\Foo1::class, $args[0]);
        $this->assertEquals('Db', $keywords['ext']['database']);
        $this->assertTrue(!isset($keywords['ext']['redis']));
        $this->assertTrue($keywords['openTransaction']);
        $this->assertEquals('Db', $keywords['database']);
    }

    public function testGetFailure()
    {
        $traceId = hash('sha256', '123456');
        $this->expectException(\ReflectionException::class);
        $container = new Container();
        $foo = $container->get('Kovey\\NotExistsClass', $traceId);
    }

    public function testGetFailureWithNonAttributeClass()
    {
        $traceId = hash('sha256', '123456');
        $this->expectException(\Error::class);
        $container = new Container();
        $foo = $container->get('Kovey\\Library\\Container\\Cases\\Foo4', $traceId);
    }

    public function testGetConstructDefault()
    {
        $traceId = hash('sha256', '123456');
        $container = new Container();
        $foo5 = $container->get('Kovey\\Library\\Container\\Cases\\Foo5', $traceId);
        $this->assertInstanceOf(Cases\Foo5::class, $foo5);
        $this->assertEquals($traceId, $foo5->traceId);
        $foo6 = $foo5->getFoo6();
        $this->assertInstanceOf(Cases\Foo6::class, $foo6);
        $this->assertEquals($traceId, $foo6->traceId);
        $foo7 = $foo6->getFoo7();
        $this->assertInstanceOf(Cases\Foo7::class, $foo7);
        $this->assertEquals($traceId, $foo7->traceId);
        $this->assertEquals('this is foo7', $foo7->getName());
        $foo1 = $foo7->getFoo1();
        $this->assertInstanceOf(Cases\Foo1::class, $foo1);
        $this->assertEquals($traceId, $foo1->traceId);
        $foo2 = $foo1->getFoo2();
        $this->assertInstanceOf(Cases\Foo2::class, $foo2);
        $this->assertEquals($traceId, $foo2->traceId);
        $this->assertEquals('this is test', $foo2->getName());
    }
}
