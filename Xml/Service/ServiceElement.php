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

use vierbergenlars\Xml\XmlElementInterface;
use vierbergenlars\Bundle\ManipulatorBundle\Xml\AbstractElement;
use Symfony\Component\DependencyInjection\Definition;
use vierbergenlars\Xml\XmlArrayCollection;
use vierbergenlars\Bundle\ManipulatorBundle\Xml\YamlExportableInterface;
use vierbergenlars\Bundle\ManipulatorBundle\Xml\ArrayAttributes;
use vierbergenlars\Bundle\ManipulatorBundle\Xml\GenericElement;

class ServiceElement extends AbstractElement implements YamlExportableInterface
{
    private $id;
    private $definition;
    public function __construct($id, Definition $definition)
    {
        $this->id = $id;
        $this->definition = $definition;
    }
    public function getName()
    {
        return 'service';
    }

    public function attributes()
    {
        $attributes = array(
            'id'              => $this->id,
            'class'           => $this->definition->getClass(),
            'scope'           => $this->definition->getScope(),
            'public'          => $this->definition->isPublic(),
            'synthetic'       => $this->definition->isSynthetic(),
            'synchronized'    => $this->definition->isSynchronized(),
            'lazy'            => $this->definition->isLazy(),
            'abstract'        => $this->definition->isAbstract(),
            'factory-class'   => $this->definition->getFactoryClass(),
            'factory-method'  => $this->definition->getFactoryMethod(),
            'factory-service' => $this->definition->getFactoryService(),
        );
        $defaults = array(
            'id'              => null,
            'class'           => null,
            'scope'           => 'container',
            'public'          => true,
            'synthetic'       => false,
            'synchronized'    => false,
            'lazy'            => false,
            'abstract'        => false,
            'factory-class'   => null,
            'factory-method'  => null,
            'factory-service' => null,
        );
        $diff = array_diff_assoc($attributes, $defaults);
        return new ArrayAttributes($diff);
    }

    public function children($name = null)
    {
        $children = array();
        foreach($this->definition->getArguments() as $argument) {
            $children[] = new ArgumentElement($argument);
        }

        foreach($this->definition->getMethodCalls() as $methodCall) {
            $children[] = new CallElement($methodCall[0], $methodCall[1]);
        }

        foreach($this->definition->getProperties() as $property => $value) {
            $children[] = new PropertyElement($property, $value);
        }

        foreach($this->definition->getTags() as $key => $tagsAttributes) {
            foreach($tagsAttributes as $attributes) {
                $children[] = new TagElement($key, $attributes);
            }
        }

        if($this->definition->getFile() !== null) {
            $children[] = new GenericElement('file', array(), $this->definition->getFile());
        }

        // @codeCoverageIgnoreStart
        if($this->definition->getConfigurator()) {
            throw new \RuntimeException('Not implemented');
        }
        // @codeCoverageIgnoreEnd
        return new XmlArrayCollection($children);
    }

    public function toYamlArray()
    {
        $data = array();
        foreach($this->attributes()->toArray() as $k=>$v) {
            if($k !== 'id') {
                $k = str_replace('-', '_', $k);
                $data[$k] = $v;
            }
        }
        foreach($this->children() as $child) {
            if($child instanceof YamlExportableInterface) {
                switch($child->getName()) {
                    case 'file':
                        $data['file'] = $child->toYamlArray();
                        break;
                    case 'property':
                        if(!isset($data['properties'])) {
                            $data['properties'] = array();
                        }
                        $data['properties'] = array_merge($data['properties'], $child->toYamlArray());
                        break;
                    default:
                        $data[$child->getName().'s'][] = $child->toYamlArray();
                }
            } else {
                // @codeCoverageIgnoreStart
                throw new \RuntimeException('Cannot export to yaml '.get_class($child));
                // @codeCoverageIgnoreEnd
            }
        }
        return $data;
    }
}
