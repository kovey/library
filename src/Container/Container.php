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
	 * @description construct
     *
     * @return Container
	 */
    public function __construct()
    {
        $this->instances = array();
        $this->methods = array();
    }

	/**
	 * @description get object
	 *
	 * @param string $class
	 *
	 * @param ...mixed $args
	 *
	 * @return mixed
	 *
	 * @throws Throwable
	 */
    public function get(string $class, ...$args)
    {
        $class = new \ReflectionClass($class);

        if (isset($this->instances[$class->getName()])) {
            return $this->bind($class, $this->instances[$class->getName()], $args);
        }

        $this->resolveMethod($class);
        $this->resolve($class);
        return $this->bind($class, $this->instances[$class->getName()], $args);
    }

	/**
	 * @description bind
	 *
	 * @param ReflectionClass $class
	 *
	 * @param Array $dependencies
	 *
	 * @param Array $args
	 *
	 * @return mixed
	 */
    private function bind(\ReflectionClass $class, Array $dependencies, Array $args = array())
    {
		$obj = null;
		if (count($args) > 0) {
			$obj = $class->newInstanceArgs($args);
		} else {
			$obj = $class->newInstance();
		}
        if (count($dependencies) < 1) {
            return $obj;
        }

        foreach ($dependencies as $dependency) {
            $dep = $this->bind($dependency['class'], $this->instances[$dependency['class']->getName()] ?? array());
            $dependency['property']->setValue($obj, $dep);
        }

        return $obj;
    }

	/**
	 * @description cache
	 *
	 * @param ReflectionClass $class
	 *
	 * @return null
	 */
    private function resolveMethod(\ReflectionClass $class)
    {
        $methods = $class->getMethods();
        foreach ($methods as $method) {
            $this->methods[$class->getName() . '::' . $method->getName()] = $method->getAttributes();
        }
    }

	/**
	 * @description cache
	 *
	 * @param ReflectionClass $class
	 *
	 * @return null
	 */
    private function resolve(\ReflectionClass $class)
    {
        $this->instances[$class->getName()] = $this->getAts($class);
        foreach ($this->instances[$class->getName()] as $deps) {
            $this->resolve($deps['class']);
        }
    }

	/**
	 * @description get all reject
	 *
	 * @param ReflectionClass $ref
	 *
	 * @return Array
	 */
    private function getAts(\ReflectionClass $ref) : Array
    {
        $properties = $ref->getProperties();
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
     * @return Array
     */
    public function getMethodArguments(string $class, string $method) : Array
    {
        $attrs = $this->methods[$class . '::' . $method] ?? array();
        $result = array();
        foreach ($attrs as $attr) {
            $result[] = $this->get($attr->getName());
        }

        return $result;
    }
}
