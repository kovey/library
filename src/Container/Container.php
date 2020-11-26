<?php
/**
 * @description container
 *
 * @package Parse
 *
 * @author kovey
 *
 * @time 2019-10-16 10:36:36
 *
 */
namespace Kovey\Library\Container;

use Kovey\Library\Exception\KoveyException;

class Container implements ContainerInterface
{
    /**
     * @description cache
     *
     * @var Array
     */
    private Array $instances;

    /**
     * @description methods cache
     *
     * @var Array
     */
    private Array $methods;

    /**
     * @description keywords
     *
     * @var Array
     */
    private Array $keywords;

    /**
     * @description events
     *
     * @var Array
     */
    private Array $events;

    /**
     * @description construct
     *
     * @return Container
     */
    public function __construct()
    {
        $this->instances = array();
        $this->methods = array();
        $this->keywords = array(
            'Transaction' => true, 
            'Database' => 'database', 
            'Redis' => 'redis', 
            'ShardingDatabase' => 'database', 
            'ShardingRedis' => 'redis', 
            'GlobalId' => 'globalId'
        );
        $this->events = array();
    }

    /**
     * @description get object
     *
     * @param string $class
     *
     * @param string $traceId
     *
     * @param Array $ext
     *
     * @param ... $args
     *
     * @return mixed
     *
     * @throws Throwable
     */
    public function get(string $class, string $traceId, Array $ext = array(), ...$args)
    {
        $class = new \ReflectionClass($class);
        if (!isset($this->instances[$class->getName()])) {
            $this->resolve($class);
        }

        if (count($args) < 1) {
            if ($class->hasMethod('__construct')) {
                $args = $this->getMethodArguments($class->getName(), '__construct', $traceId);
            }
        }

        return $this->bind($class, $traceId, $this->instances[$class->getName()], $ext, $args);
    }

    /**
     * @description bind
     *
     * @param ReflectionClass | ReflectionAttribute $class
     *
     * @param string $traceId
     *
     * @param Array $dependencies
     *
     * @param Array $ext
     *
     * @param Array $args
     *
     * @return mixed
     */
    private function bind(\ReflectionClass | \ReflectionAttribute $class, string $traceId, Array $dependencies, Array $ext = array(), Array $args = array())
    {
        $obj = null;
        if (count($args) > 0) {
            $obj = $class->newInstanceArgs($args);
        } else {
            $obj = $class->newInstance();
        }

        $obj->traceId = $traceId;
        foreach ($ext as $field => $val) {
            $obj->$field = $val;
        }

        if (count($dependencies) < 1) {
            return $obj;
        }

        foreach ($dependencies as $dependency) {
            $dep = $this->bind($dependency['class'], $traceId, $this->instances[$dependency['class']->getName()] ?? array(), $ext);
            $dependency['property']->setValue($obj, $dep);
        }

        return $obj;
    }

    /**
     * @description cache
     *
     * @param string $classMethod
     *
     * @return Array
     */
    private function resolveMethod(string $classMethod) : Array
    {
        $method = new \ReflectionMethod($classMethod);
        $attrs = array(
            'keywords' => array(),
            'arguments' => array()
        );

        foreach ($method->getAttributes() as $attr) {
            $isKeywords = false;
            foreach (array_keys($this->keywords) as $keyword) {
                if (substr($attr->getName(), 0 - strlen($keyword)) === $keyword) {
                    $attrs['keywords'][$keyword] = $attr->getArguments();
                    $isKeywords = true;
                }
            }

            if ($isKeywords) {
                continue;
            }

            $attrs['arguments'][] = $attr;
        }

        return $attrs;
    }

    /**
     * @description cache
     *
     * @param ReflectionClass | ReflectionAttribute $class
     *
     * @return null
     */
    private function resolve(\ReflectionClass | \ReflectionAttribute $class)
    {
        $this->instances[$class->getName()] = $this->getAts($class);
        foreach ($this->instances[$class->getName()] as $deps) {
            $this->resolve($deps['class']);
        }
    }

    /**
     * @description get all reject
     *
     * @param ReflectionClass | ReflectionAttribute $ref
     *
     * @return Array
     */
    private function getAts(\ReflectionClass | \ReflectionAttribute $ref) : Array
    {
        $properties = null;
        if ($ref instanceof \ReflectionAttribute) {
            $refl = new \ReflectionClass($ref->getName());
            $properties = $refl->getProperties();
        } else {
            $properties = $ref->getProperties();
        }
        $ats = array();
        foreach ($properties as $property) {
            $attrs = $property->getAttributes();
            if (empty($attrs)) {
                continue;
            }

            foreach ($attrs as $attr) {
                if ($property->isPrivate()
                    || $property->isProtected()
                ) {
                    $property->setAccessible(true);
                }

                $ats[$property->getName()] = array(
                    'class' => $attr,
                    'property' => $property
                );

                break;
            }
        }

        return $ats;
    }

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
    public function getMethodArguments(string $class, string $method, string $traceId, Array $ext = array()) : Array
    {
        $classMethod = $class . '::' . $method;
        $this->methods[$classMethod] ??= $this->resolveMethod($classMethod);
        $attrs = $this->methods[$classMethod]['arguments'];
        array_walk ($attrs, function(&$attr) use ($traceId, $ext) {
            $obj = $this->get($attr->getName(), $traceId, $ext, ...$attr->getArguments());
            $obj->traceId = $traceId;
            $attr = $obj;
        });

        return $attrs;
    }

    /**
     * @description 获取关键字
     *
     * @param string $class
     *
     * @param string $methods
     * 
     * @return Array
     */
    public function getKeywords(string $class, string $method) : Array
    {
        $classMethod = $class . '::' . $method;
        $this->methods[$classMethod] ??= $this->resolveMethod($classMethod);
        $objectExt = array(
            'ext' => array()
        );

        $hasTransation = false;
        $hasDatabase = false;
        foreach ($this->methods[$classMethod]['keywords'] as $keyword => $params) {
            if ($keyword === 'Transaction') {
                $hasTransation = true;
                continue;
            }
            if (!isset($this->events[$keyword])) {
                continue;
            }

            $pool = call_user_func($this->events[$keyword], ...$params);

            if ($keyword === 'Database' || $keyword === 'Redis') {
                if ($keyword === 'Database') {
                    $hasDatabase = true;
                }
                $objectExt[$this->keywords[$keyword]] = $pool;
                if (is_object($pool) && method_exists($pool, 'getConnection')) {
                    $objectExt['ext'][$this->keywords[$keyword]] = $pool->getConnection();
                } else {
                    $objectExt['ext'][$this->keywords[$keyword]] = $pool;
                }
            } else {
                $objectExt['ext'][$this->keywords[$keyword]] = $pool;
            }
        }

        $objectExt['openTransaction'] = $hasTransation && $hasDatabase;
        return $objectExt;
    }

    /**
     * @description events
     *
     * @param string $events
     * 
     * @param callable | Array $fun
     *
     * @return $this
     */
    public function on(string $event, callable | Array $fun) : ContainerInterface
    {
        if (!isset($this->keywords[$event])) {
            throw new KoveyException("$event is not support.");
        }

        if (!is_callable($fun)) {
            throw new KoveyException('fun is not callable');
        }

        $this->events[$event] = $fun;
        return $this;
    }
}
