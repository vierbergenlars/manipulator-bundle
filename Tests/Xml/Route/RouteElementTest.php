<?php
/**
 * This file is part of the Manipulator package.
 *
 * (c) Lars Vierbergen <vierbergenlars@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace vierbergenlars\Bundle\ManipulatorBundle\Tests\Xml\Route;

use vierbergenlars\Xml\XmlElement;
use vierbergenlars\Bundle\ManipulatorBundle\Xml\Route\RouteElement;

class RouteElementTest extends \PHPUnit_Framework_TestCase
{
    private function wrap($element)
    {
        $rootElement = new XmlElement(new \SimpleXMLElement('<routes />'));
        $rootElement->addChild($element);
        return $rootElement;
    }

    private function getElement()
    {
        return new RouteElement('get_users', array('path'=>'/users'));
    }
    public function testElementXml()
    {
        $this->assertEquals('<?xml version="1.0"?>'."\n".
            '<routes><route id="get_users" path="/users"/></routes>'."\n", $this->wrap($this->getElement())->__toString());
    }

    public function testElementYaml()
    {
        $this->assertSame(array(
            'path'=>'/users',
        ), $this->getElement()->toYamlArray());
    }

    private function getAllParameters()
    {
        return new RouteElement('get_users', array(
            'path'=>'/users',
            'host'=>'api.example.org',
            'schemes'=>'https',
            'methods'=>'HEAD|GET'
        ));
    }

    public function testAllParametersXml()
    {
        $this->assertEquals('<?xml version="1.0"?>'."\n".
            '<routes><route id="get_users" path="/users" host="api.example.org" schemes="https" methods="HEAD|GET"/></routes>'."\n", $this->wrap($this->getAllParameters())->__toString());
    }

    public function testAllParametersYaml()
    {
        $this->assertEquals(array(
            'path'=>'/users',
            'host'=>'api.example.org',
            'schemes'=>'https',
            'methods'=>'HEAD|GET',
        ), $this->getAllParameters()->toYamlArray());
    }

    private function getOptions()
    {
        return new RouteElement('get_users', array(), array(
            'defaults'=>array('a'=>'b', 'xxyy'=>'yyzz'),
            'requirements'=>array('_format'=>'html|xml', 'year'=>'\d+'),
            'options'=>array('compiler_class'=>'RouteCompiler'),
            'condition'=>'request.headers.get(\'User-Agent\') matches \'/firefox/i\'',
        ));
    }

    public function testOptionsXml()
    {
        $this->assertEquals('<?xml version="1.0"?>'."\n".
            '<routes><route id="get_users">'.
                '<default key="a">b</default>'.
                '<default key="xxyy">yyzz</default>'.
                '<requirement key="_format">html|xml</requirement>'.
                '<requirement key="year">\d+</requirement>'.
                '<option key="compiler_class">RouteCompiler</option>'.
                '<condition>request.headers.get(\'User-Agent\') matches \'/firefox/i\'</condition>'.
            '</route></routes>'."\n", $this->wrap($this->getOptions())->__toString());
    }

    public function testOptionsYaml()
    {
        $this->assertSame(array(
            'defaults'=>array('a'=>'b', 'xxyy'=>'yyzz'),
            'requirements'=>array('_format'=>'html|xml', 'year'=>'\d+'),
            'options'=>array('compiler_class'=>'RouteCompiler'),
            'condition'=>'request.headers.get(\'User-Agent\') matches \'/firefox/i\'',
        ), $this->getOptions()->toYamlArray());
    }
}
