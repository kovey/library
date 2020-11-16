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
     * @param string $traceId
     *
     * @param Array $ext
	 *
	 * @param ...mixed $args
	 *
	 * @return mixed
	 */
    public function get(string $class, string $traceId, Array $ext = array(), ...$args);

    /**
     * @description method arguments
     *
     * @param string $class
     *
     * @param string $method
     *
     * @param string $traceId
     *
     * @param Array $ext
     *
     * @return Array
     */
    public function getMethodArguments(string $class, string $method, string $traceId, Array $ext = array()) : Array;

    /**
     * @description 获取关键字
     *
     * @param string $class
     *
     * @param string $methods
     * 
     * @return Array
     */
    public function getKeywords(string $class, string $method) : Array;

    /**
     * @description events
     *
     * @param string $events
     * 
     * @param callable | Array $fun
     *
     * @return $this
     */
    public function on(string $event, callable | Array $fun) : ContainerInterface;
}
