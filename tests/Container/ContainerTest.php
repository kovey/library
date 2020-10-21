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
        $container = new Container();
        $foo = $container->get('Kovey\Library\Container\Cases\Foo');
        $this->assertInstanceOf(Cases\Foo::class, $foo);
        $foo1 = $foo->getFoo1();
        $this->assertInstanceOf(Cases\Foo1::class, $foo1);
        $foo2 = $foo1->getFoo2();
        $this->assertInstanceOf(Cases\Foo2::class, $foo2);
        $this->assertEquals('this is test', $foo2->getName());
    }

    public function testGetFailure()
    {
        $this->expectException(\ReflectionException::class);
        $container = new Container();
        $foo = $container->get('Kovey\\NotExistsClass');
    }

    public function testGetFailureWithNonAttributeClass()
    {
        $this->expectException(\Error::class);
        $container = new Container();
        $foo = $container->get('Kovey\\Library\\Container\\Cases\\Foo4');
    }

    public function testGetConstructDefault()
    {
        $container = new Container();
        $foo5 = $container->get('Kovey\\Library\\Container\\Cases\\Foo5');
        $this->assertInstanceOf(Cases\Foo5::class, $foo5);
        $foo6 = $foo5->getFoo6();
        $this->assertInstanceOf(Cases\Foo6::class, $foo6);
        $foo7 = $foo6->getFoo7();
        $this->assertInstanceOf(Cases\Foo7::class, $foo7);
        $this->assertEquals('this is foo7', $foo7->getName());
        $foo1 = $foo7->getFoo1();
        $this->assertInstanceOf(Cases\Foo1::class, $foo1);
        $foo2 = $foo1->getFoo2();
        $this->assertInstanceOf(Cases\Foo2::class, $foo2);
        $this->assertEquals('this is test', $foo2->getName());
    }
}
