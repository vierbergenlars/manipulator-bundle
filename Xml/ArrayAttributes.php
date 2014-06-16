<?php
/**
 * This file is part of the Manipulator package.
 *
 * (c) Lars Vierbergen <vierbergenlars@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace vierbergenlars\Bundle\ManipulatorBundle\Xml;

use vierbergenlars\Xml\XmlAttributesInterface;

/**
 * @codeCoverageIgnore
 */
class ArrayAttributes implements XmlAttributesInterface
{
    public function __construct(array $array)
    {
        $this->array = array_filter($array, function($elem) {
            return $elem !== null;
        });
    }

    public function get($name)
    {
        return $this[$name];
    }

    public function set($name, $value)
    {
        $this[$name] = $value;
        return $this;
    }

    public function current()
    {
        return $this->mangleData(current($this->array));
    }

    public function key()
    {
        return key($this->array);
    }

    public function next()
    {
        next($this->array);
    }

    public function rewind()
    {
        reset($this->array);
    }

    public function valid()
    {
        return key($this->array) !== null;
    }

    public function offsetSet($index, $newval)
    {
        throw new \LogicException;
    }

    public function offsetUnset($index)
    {
        throw new \LogicException;
    }

    public function count()
    {
        return count($this->array);
    }

    public function offsetExists($offset)
    {
        return isset($this->array[$offset]);
    }

    public function offsetGet($offset)
    {
        if(!isset($this->array[$offset]))
            return null;
        return $this->mangleData($this->array[$offset]);
    }

    private function mangleData($data)
    {
        if($data === true) {
            return 'true';
        } elseif($data === false) {
            return 'false';
        } else {
            return $data;
        }
    }

    public function toArray()
    {
        return $this->array;
    }
}
