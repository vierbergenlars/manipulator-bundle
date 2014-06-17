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

class XmlRouteManipulator extends AbstractXmlManipulator implements RouteManipulatorInterface
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
        $this->getXmlRoot()->addChild($route);
    }

    public function removeRoute($id)
    {
        $children = $this->getXmlRoot()->children();
        foreach($children as $key=>$child) {
            if($child->attr('id') === $id) {
                unset($children[$key]);
                break;
            }
        }
    }

    public function addImport($resource, array $options = array())
    {
        list($attrs, $opts) = $this->splitAttributesAndOptions($options);
        $import = new ImportElement($resource, $attrs, $opts);
        $this->getXmlRoot()->addChild($import);
    }

    public function removeImport($resource)
    {
        $children = $this->getXmlRoot()->children();
        foreach($children as $key=>$child) {
            if($child->attr('resource') === $resource) {
                unset($children[$key]);
                break;
            }
        }
    }
}
