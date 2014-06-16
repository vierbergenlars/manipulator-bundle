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

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use vierbergenlars\Bundle\ManipulatorBundle\Xml\YamlExportableInterface;
use vierbergenlars\Bundle\ManipulatorBundle\Xml\AbstractElement;
use vierbergenlars\Bundle\ManipulatorBundle\Xml\ArrayAttributes;

class ArgumentElement extends AbstractElement implements YamlExportableInterface
{
    private $argument;
    public function __construct($argument)
    {
        $this->argument = $argument;
    }

    public function getName()
    {
        return 'argument';
    }

    public function text()
    {
        if($this->argument instanceof Reference || $this->argument instanceof Definition) {
            return '';
        }
        return (string)$this->argument;
    }

    public function attributes()
    {
        if($this->argument instanceof Reference) {
            $attributes = array(
                'type' => 'service',
                'id'   => (string)$this->argument,
            );
            switch($this->argument->getInvalidBehavior()) {
                case ContainerInterface::IGNORE_ON_INVALID_REFERENCE:
                    $attributes['on-invalid'] = 'ignore';
                    break;
                case ContainerInterface::NULL_ON_INVALID_REFERENCE:
                    $attributes['on-invalid'] = 'null';
            }
            return new ArrayAttributes($attributes);
        } else if($this->argument instanceof Definition) {
            return new ArrayAttributes(array(
                'type'=>'service',
            ));
        } else {
            return new ArrayAttributes(array(
                'type'=> 'string',
            ));
        }
    }

    public function children($name = null)
    {
        if($this->argument instanceof Definition) {
            $service = new ServiceElement(null, $this->argument);
            return $service->children();
        } else {
            return parent::children($name);
        }
    }

    public function toYamlArray()
    {
        switch($this->attr('type')) {
            case 'service':
                if($this->attr('id')) {
                    $text = '@'.$this->attr('id');
                } else {
                    throw new \RuntimeException('Cannot convert inline service definition');
                }
                break;
            case 'string':
                $text = $this->text();
                if($text[0] === '@') {
                    $text = '@'.$text;
                }
                break;
            // @codeCoverageIgnoreStart
            default:
                throw new \RuntimeException('Not implemented');
            // @codeCoverageIgnoreEnd
        }
        return $text;
    }
}
