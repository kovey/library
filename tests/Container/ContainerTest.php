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
}
