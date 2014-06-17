<?php
/**
 * This file is part of the Manipulator package.
 *
 * (c) Lars Vierbergen <vierbergenlars@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace vierbergenlars\Bundle\ManipulatorBundle\Manipulator;

use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use vierbergenlars\Bundle\ManipulatorBundle\Xml\Route\RouteElement;
use vierbergenlars\Bundle\ManipulatorBundle\Xml\Route\ImportElement;
use Symfony\Component\DependencyInjection\Container;

class YamlRouteManipulator extends AbstractYamlManipulator implements RouteManipulatorInterface
{
    private function splitAttributesAndOptions(array $options)
    {
        $attrs = array();
        $opts  = array();
        foreach($options as $key => $value) {
            switch($key) {
                case 'condition':
                case 'defaults':
                case 'options':
                case 'requirements':
                    $opts[$key] = $value;
                    break;
                default:
                    $attrs[$key] = $value;
            }
        }
        return array($attrs, $opts);
    }

    public function addRoute($id, array $options = array())
    {
        list($attrs, $opts) = $this->splitAttributesAndOptions($options);
        $route = new RouteElement($id, $attrs, $opts);
        $this->yamlArray[$id] = $route->toYamlArray();
    }

    public function removeRoute($id)
    {
        unset($this->yamlArray[$id]);
    }

    public function addImport($resource, array $options = array())
    {
        list($attrs, $opts) = $this->splitAttributesAndOptions($options);
        $import = new ImportElement($resource, $attrs, $opts);
        $this->yamlArray[str_replace('.', '_', Container::underscore($resource))] = $import->toYamlArray();
    }

    public function removeImport($resource)
    {
        foreach($this->yamlArray as $key=>$child) {
            if(isset($child['resource'])&&$child['resource'] === $resource) {
                unset($this->yamlArray[$key]);
                break;
            }
        }
    }
}
