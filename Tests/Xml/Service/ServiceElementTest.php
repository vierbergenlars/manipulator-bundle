<?php
/**
 * This file is part of the Manipulator package.
 *
 * (c) Lars Vierbergen <vierbergenlars@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace vierbergenlars\Bundle\ManipulatorBundle\Tests\Xml\Service;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerInterface;
use vierbergenlars\Bundle\ManipulatorBundle\Xml\Service\ServiceElement;
use vierbergenlars\Xml\XmlElement;
use Symfony\Component\Yaml\Yaml;

class ServiceElementTest extends \PHPUnit_Framework_TestCase
{
    private function getElement()
    {
        $service = new Definition();
        $service->setClass('stdClass');
        $service->setArguments(array(
            new Reference('arg.one'),
            new Reference('arg.two', ContainerInterface::IGNORE_ON_INVALID_REFERENCE),
            new Reference('arg.three', ContainerInterface::NULL_ON_INVALID_REFERENCE),
            '@arg.four',
        ));
        $service->addTag('tag.one');
        $service->addTag('tag.two', array('attr'=>'one'));
        $service->addTag('tag.two', array('attr'=>'two'));

        return new ServiceElement('service.one', $service);
    }

    public function testElementXml()
    {
        $serviceFile = new XmlElement(new \SimpleXMLElement('<container><services></services></container>'));

        $serviceFile->child('services')->addChild($this->getElement());

        $this->assertEquals('<?xml version="1.0"?>'."\n".
        '<container><services>'.
            '<service id="service.one" class="stdClass">'.
                '<argument type="service" id="arg.one"/>'.
                '<argument type="service" id="arg.two" on-invalid="ignore"/>'.
                '<argument type="service" id="arg.three" on-invalid="null"/>'.
                '<argument type="string">@arg.four</argument>'.
                '<tag name="tag.one"/>'.
                '<tag name="tag.two" attr="one"/>'.
                '<tag name="tag.two" attr="two"/>'.
            '</service>'.
        '</services></container>'."\n", $serviceFile->__toString());
    }

    public function testElementYaml()
    {
        $this->assertSame(array(
            'class'     => 'stdClass',
            'arguments' => array(
                '@arg.one',
                '@arg.two',
                '@arg.three',
                '@@arg.four',
            ),
            'tags'      => array(
                array('name' => 'tag.one'),
                array('name' => 'tag.two', 'attr' => 'one'),
                array('name' => 'tag.two', 'attr' => 'two'),
            ),
        ), $this->getElement()->toYamlArray());
    }

    private function getAllParameters()
    {
        $service = new Definition();
        $service->setClass('stdClass');
        $service->setAbstract(true);
        $service->setFactoryClass('stdClass');
        $service->setFactoryMethod('get');
        $service->setFactoryService('service.fact');
        $service->setFile('/file.php');
        $service->setLazy(true);
        $service->addMethodCall('methodCall1');
        $service->addMethodCall('methodCall2', array('arg1', 'arg2'));
        $service->addMethodCall('methodCall3', array(new Reference('arg.one')));
        $service->setProperty('prop1', 'val1');
        $service->setProperty('prop2', new Reference('prop.one'));
        $service->setPublic(false);
        $service->setScope('request');
        $service->setSynchronized(true);
        $service->setSynthetic(true);

        return new ServiceElement('service.two', $service);

    }

    public function testAllParametersXml()
    {

        $serviceFile = new XmlElement(new \SimpleXMLElement('<container><services></services></container>'));

        $serviceFile->child('services')->addChild($this->getAllParameters());

        $this->assertEquals(
        '<?xml version="1.0"?>'."\n".
        '<container><services>'.
        '<service id="service.two" class="stdClass" scope="request" public="false" synthetic="true" synchronized="true" lazy="true" abstract="true" factory-class="stdClass" factory-method="get" factory-service="service.fact">'.
            '<call method="methodCall1"/>'.
            '<call method="methodCall2">'.
                '<argument type="string">arg1</argument>'.
                '<argument type="string">arg2</argument>'.
            '</call>'.
            '<call method="methodCall3">'.
                '<argument type="service" id="arg.one"/>'.
            '</call>'.
            '<property type="string" name="prop1">val1</property>'.
            '<property type="service" id="prop.one" name="prop2"/>'.
            '<file>/file.php</file>'.
            '</service>'.
        '</services></container>'."\n", $serviceFile->__toString());
    }

    public function testAllParametersYaml()
    {
        $this->assertSame(array(
            'class'            => 'stdClass',
            'scope'            => 'request',
            'public'           => false,
            'synthetic'        => true,
            'synchronized'     => true,
            'lazy'             => true,
            'abstract'         => true,
            'factory_class'    => 'stdClass',
            'factory_method'   => 'get',
            'factory_service'  => 'service.fact',
            'calls'            => array(
                array('methodCall1'),
                array('methodCall2', array('arg1', 'arg2')),
                array('methodCall3', array('@arg.one')),
            ),
            'properties'         => array(
                'prop1' => 'val1',
                'prop2' => '@prop.one',
            ),
            'file'             => '/file.php',
        ), $this->getAllParameters()->toYamlArray());
    }

    private function getNestedServices()
    {
        $service = new Definition();
        $service->setClass('stdClass');

        $service1 = new Definition();
        $service1->setClass('stdClass');
        $service1->setArguments(array(
            new Reference('arg.one'),
            new Reference('arg.two', ContainerInterface::IGNORE_ON_INVALID_REFERENCE),
            new Reference('arg.three', ContainerInterface::NULL_ON_INVALID_REFERENCE),
            'arg.four',
        ));

        $service->addArgument($service1);

        return new ServiceElement('service.one', $service);
    }

    public function testNestedServicesXml()
    {
        $serviceFile = new XmlElement(new \SimpleXMLElement('<container><services></services></container>'));

        $serviceFile->child('services')->addChild($this->getNestedServices());

        $this->assertEquals('<?xml version="1.0"?>'."\n".
        '<container><services>'.
            '<service id="service.one" class="stdClass">'.
                '<argument type="service">'.
                    '<argument type="service" id="arg.one"/>'.
                    '<argument type="service" id="arg.two" on-invalid="ignore"/>'.
                    '<argument type="service" id="arg.three" on-invalid="null"/>'.
                    '<argument type="string">arg.four</argument>'.
                '</argument>'.
            '</service>'.
        '</services></container>'."\n", $serviceFile->__toString());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testNestedServicesYaml()
    {
        Yaml::dump($this->getNestedServices()->toYamlArray());
    }
}
