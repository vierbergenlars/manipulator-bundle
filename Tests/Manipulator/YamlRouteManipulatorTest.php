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

use vierbergenlars\Bundle\ManipulatorBundle\Manipulator\YamlRouteManipulator;
use org\bovigo\vfs\vfsStream;

class YamlRouteManipulatorTest extends TestCase
{
    private $manipulator;
    protected function setUp()
    {
        parent::setUp();
        $this->manipulator = new YamlRouteManipulator(vfsStream::url('root/routing.yml'));
    }

    public function testAddRoute()
    {
        $this->manipulator->addRoute('get_user', array(
            'path' => '/users/{id}.{_format}',
            'defaults'=>array(
                '_controller'=>'AcmeDemoBundle:User:get',
                 '_format'=>'xml',
            ),
            'requirements' => array(
                '_format'=>'xml|html',
            ),
        ));
        $this->manipulator->write();

        $this->assertYamlFileEquals(vfsStream::url('root/results/testAddRoute.yml'), vfsStream::url('root/routing.yml'));
    }

    public function testRemoveRoute()
    {
        $this->manipulator->removeRoute('fos_user_group_list');
        $this->manipulator->write();

        $this->assertYamlFileEquals(vfsStream::url('root/results/testRemoveRoute.yml'), vfsStream::url('root/routing.yml'));
    }

    public function testAddImport()
    {
        $this->manipulator->addImport('acme.demo.note.controller', array(
            'type'=>'rest',
            'prefix' => '/api',
            'defaults'=>array(
                '_format'=>'xml',
            ),
            'requirements'=>array(
                '_format'=>'xml|json'
            )
        ));
        $this->manipulator->write();

        $this->assertYamlFileEquals(vfsStream::url('root/results/testAddImport.yml'), vfsStream::url('root/routing.yml'));
    }

    public function testRemoveImport()
    {
        $this->manipulator->removeImport('acme.demo.user.controller');
        $this->manipulator->write();

        $this->assertYamlFileEquals(vfsStream::url('root/results/testRemoveImport.yml'), vfsStream::url('root/routing.yml'));
    }
}