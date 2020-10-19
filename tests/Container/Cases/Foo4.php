<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2020-10-19 14:10:29
 *
 */
namespace Kovey\Library\Container\Cases;

class Foo4
{
    #[Foo()]
    private Foo $foo;

    public function getFoo() : Foo
    {
        return $this->foo;
    }
}
