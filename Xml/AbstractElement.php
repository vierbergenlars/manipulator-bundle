<?php
/**
 * This file is part of the Manipulator package.
 *
 * (c) Lars Vierbergen <vierbergenlars@gmail.com>s
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace vierbergenlars\Bundle\ManipulatorBundle\Xml;

use vierbergenlars\Xml\XmlElementInterface;
use vierbergenlars\Xml\XmlArrayCollection;

/**
 * @codeCoverageIgnore
 */
abstract class AbstractElement implements XmlElementInterface
{
    public function text()
    {
        return '';
    }

    public function setText($text)
    {
        throw new \LogicException;
    }

    public function attr($name)
    {
        return $this->attributes()->get($name);
    }

    public function addChild($name, $value = null)
    {
        throw new \LogicException;
    }

    public function child($name = null, $filter = array(), $pos = 0)
    {
        return $this->children($name)->find($filter)->get($pos);
    }

    public function find($attributes = array())
    {
        return $this->children()->find($attributes);
    }

    public function children($name = null)
    {
        return new XmlArrayCollection(array());
    }

    public function _swapoutInternal(\SimpleXMLElement $elem)
    {
        // noop
    }
}
