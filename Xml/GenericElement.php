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

use vierbergenlars\Xml\XmlArrayCollection;

/**
 * @codeCoverageIgnore
 */
class GenericElement extends AbstractElement implements YamlExportableInterface
{
    private $tagname;
    private $attrs;
    private $contents;
    public function __construct($tagname, array $attributes = array(), $contents = '')
    {
        $this->tagname = $tagname;
        $this->attrs = $attributes;
        $this->contents = $contents;
    }

    public function getName()
    {
        return $this->tagname;
    }

    public function attributes()
    {
        return new ArrayAttributes($this->attrs);
    }

    public function text()
    {
        if(is_string($this->contents)) {
            return $this->contents;
        } else {
            return '';
        }
    }

    public function children($name = null)
    {
        if(is_string($this->contents)) {
            return new XmlArrayCollection(array());
        } else {
            return new XmlArrayCollection($this->contents);
        }
    }

    public function toYamlArray()
    {
        return $this->text();
    }
}
