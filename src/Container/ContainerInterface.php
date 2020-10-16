<?php
/**
 *
 * @description container interface
 *
 * @package     Library\Container
 *
 * @time        2019-10-18 09:15:37
 *
 * @author      kovey
 */

namespace Kovey\Library\Container;

interface ContainerInterface
{
	/**
	 * @description 获取实例
	 *
	 * @param string $class
	 *
	 * @param ...mixed $args
	 *
	 * @return mixed
	 */
    public function get(string $class, ...$args);
}
