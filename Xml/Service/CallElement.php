<?php
/**
 * This file is part of the Manipulator package.
 *
 * (c) Lars Vierbergen <vierbergenlars@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace vierbergenlars\Bundle\ManipulatorBundle\Xml\Service;

use vierbergenlars\Xml\XmlArrayCollection;
use vierbergenlars\Bundle\ManipulatorBundle\Xml\AbstractElement;
use vierbergenlars\Bundle\ManipulatorBundle\Xml\YamlExportableInterface;
use vierbergenlars\Bundle\ManipulatorBundle\Xml\ArrayAttributes;

class CallElement extends AbstractElement implements YamlExportableInterface
{
    private $method;
    private $arguments;
    public function getName()
    {
        return 'call';
    }

    public function __construct($method, $arguments)
    {
        $this->method = $method;
        $this->arguments = $arguments;
    }

    public function attributes()
    {
        return new ArrayAttributes(array(
            'method' => $this->method,
        ));
    }

    public function children($name = null)
    {
        $children = array();
        foreach($this->arguments as $argument)
        {
            $children[] = new ArgumentElement($argument);
        }

        return new XmlArrayCollection($children);
    }

    public function toYamlArray()
    {
        $data = array($this->attr('method'));
        foreach($this->children() as $child) {
            $data[1][] = $child->toYamlArray();
        }
        return $data;
    }
}
