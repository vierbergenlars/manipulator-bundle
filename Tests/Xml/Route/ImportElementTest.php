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
use vierbergenlars\Bundle\ManipulatorBundle\Xml\Route\ImportElement;

class ImportElementTest extends \PHPUnit_Framework_TestCase
{
    private function wrap($element)
    {
        $rootElement = new XmlElement(new \SimpleXMLElement('<routes />'));
        $rootElement->addChild($element);
        return $rootElement;
    }

    private function getElement()
    {
        return new ImportElement('@AcmeHelloBundle/Resources/config/routing.yml');
    }
    public function testElementXml()
    {
        $this->assertEquals('<?xml version="1.0"?>'."\n".
            '<routes><import resource="@AcmeHelloBundle/Resources/config/routing.yml"/></routes>'."\n", $this->wrap($this->getElement())->__toString());
    }

    public function testElementYaml()
    {
        $this->assertSame(array(
            'resource' => '@AcmeHelloBundle/Resources/config/routing.yml',
        ), $this->getElement()->toYamlArray());
    }

    private function getAllParameters()
    {
        return new ImportElement('acme.demo.user.controller', array(
            'type'=>'rest',
            'prefix'=>'/api',
            'host'=>'api.example.org',
            'schemes'=>'https',
            'methods'=>'HEAD|GET'
        ));
    }

    public function testAllParametersXml()
    {
        $this->assertEquals('<?xml version="1.0"?>'."\n".
            '<routes><import resource="acme.demo.user.controller" type="rest" prefix="/api" host="api.example.org" schemes="https" methods="HEAD|GET"/></routes>'."\n", $this->wrap($this->getAllParameters())->__toString());
    }

    public function testAllParametersYaml()
    {
        $this->assertEquals(array(
            'resource' => 'acme.demo.user.controller',
            'type' => 'rest',
            'prefix'=>'/api',
            'host'=>'api.example.org',
            'schemes'=>'https',
            'methods'=>'HEAD|GET',
        ), $this->getAllParameters()->toYamlArray());
    }

    private function getOptions()
    {
        return new ImportElement('acme.demo.user.controller', array(), array(
            'defaults'=>array('a'=>'b', 'xxyy'=>'yyzz'),
            'requirements'=>array('_format'=>'html|xml', 'year'=>'\d+'),
            'options'=>array('compiler_class'=>'RouteCompiler'),
            'condition'=>'request.headers.get(\'User-Agent\') matches \'/firefox/i\'',
        ));
    }

    public function testOptionsXml()
    {
        $this->assertEquals('<?xml version="1.0"?>'."\n".
            '<routes><import resource="acme.demo.user.controller">'.
                '<default key="a">b</default>'.
                '<default key="xxyy">yyzz</default>'.
                '<requirement key="_format">html|xml</requirement>'.
                '<requirement key="year">\d+</requirement>'.
                '<option key="compiler_class">RouteCompiler</option>'.
                '<condition>request.headers.get(\'User-Agent\') matches \'/firefox/i\'</condition>'.
            '</import></routes>'."\n", $this->wrap($this->getOptions())->__toString());
    }

    public function testOptionsYaml()
    {
        $this->assertSame(array(
            'resource'=>'acme.demo.user.controller',
            'defaults'=>array('a'=>'b', 'xxyy'=>'yyzz'),
            'requirements'=>array('_format'=>'html|xml', 'year'=>'\d+'),
            'options'=>array('compiler_class'=>'RouteCompiler'),
            'condition'=>'request.headers.get(\'User-Agent\') matches \'/firefox/i\'',
        ), $this->getOptions()->toYamlArray());
    }
}
