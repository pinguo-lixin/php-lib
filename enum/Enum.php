<?php

/**
 * @package enum
 * @version 1.0.0
 * @author lixin <lixin@126.com>
 */

/**
 * Base enum class
 * @static
 * @abstract
 */
abstract class Enum implements \IteratorAggregate, \Countable
{
    /**
     * get value of a defined constant
     * @param string $name
     * @return mixed return false if the constant not defined
     */
    final public static function getConstant($name)
    {
        return (new \ReflectionClass(get_called_class()))->getConstant($name);
    }
    
    /**
     * get all constants defined with key-value pairs
     * @return array
     */
    final public static function getConstants()
    {
        return (new \ReflectionClass(get_called_class()))->getConstants();
    }
    
    /**
     * whether contains a constant
     * @param string $name
     * @return bool
     */
    final public static function hasConstant($name)
    {
        return (new \ReflectionClass(get_called_class()))->hasConstant($name);
    }

    /**
     * {@inheritDoc}
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator(static::getConstants());
    }

    /**
     * {@inheritDoc}
     * @return int
     */
    public function count()
    {
        return count(static::getConstants());
    }
}
