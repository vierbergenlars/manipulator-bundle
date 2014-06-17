<?php
/**
 * This file is part of the Manipulator package.
 *
 * (c) Lars Vierbergen <vierbergenlars@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace vierbergenlars\Bundle\ManipulatorBundle\Tests\Manipulator;

use vierbergenlars\Bundle\ManipulatorBundle\Manipulator\XmlServiceManipulator;
use Symfony\Component\DependencyInjection\Definition;
use org\bovigo\vfs\vfsStream;

class XmlServiceManipulatorTest extends TestCase
{
    private $manipulator;
    protected function setUp()
    {
        parent::setUp();
        $this->manipulator = new XmlServiceManipulator(vfsStream::url('root/services.xml'));
    }
    public function testAddService()
    {
        $service = new Definition();
        $service->setClass('Acme\DemoBundle\Controller\CommentController');
        $service->addTag('radrest.controller', array('resource'=>'comment'));
        $this->manipulator->addService('acme.demo.comment.controller', $service);
        $this->manipulator->write();

        $this->assertXmlFileEqualsXmlFile(vfsStream::url('root/results/testAddService.xml'), vfsStream::url('root/services.xml'));
    }

    public function testRemoveService()
    {
        $this->manipulator->removeService('acme.demo.user.resource_manager');
        $this->manipulator->write();

        $this->assertXmlFileEqualsXmlFile(vfsStream::url('root/results/testRemoveService.xml'), vfsStream::url('root/services.xml'));
    }
}