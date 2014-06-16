<?php
/**
 * This file is part of the Manipulator package.
 *
 * (c) Lars Vierbergen <vierbergenlars@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace vierbergenlars\Bundle\ManipulatorBundle\Xml\Route;

use vierbergenlars\Bundle\ManipulatorBundle\Xml\AbstractElement;
use vierbergenlars\Bundle\ManipulatorBundle\Xml\GenericElement;
use vierbergenlars\Xml\XmlArrayCollection;
use vierbergenlars\Bundle\ManipulatorBundle\Xml\ArrayAttributes;
use vierbergenlars\Bundle\ManipulatorBundle\Xml\YamlExportableInterface;

abstract class AbstractConfigElement extends AbstractElement implements YamlExportableInterface
{
    private $config;
    private $attrs;

    protected function __construct(array $attrs = array(), array $config = array())
    {
        $this->config = $config;
        $this->attrs = $attrs;
    }

    public function attributes()
    {
        return new ArrayAttributes($this->attrs);
    }

    public function children($name = null)
    {
        $children = array();
        foreach($this->config as $type => $settings) {
            if($type === 'condition') {
                $children[] = new GenericElement('condition', array(), $settings);

            } else {
                foreach($settings as $key => $value) {
                    $children[] = new GenericElement(substr($type, 0, -1), array('key'=>$key), $value);
                }
            }
        }

        return new XmlArrayCollection($children);
    }

    public function toYamlArray()
    {
        return array_merge($this->attrs, $this->config);
    }
}
