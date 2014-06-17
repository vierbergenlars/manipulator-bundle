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

use org\bovigo\vfs\vfsStream;
use Symfony\Component\Yaml\Yaml;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    protected  $vfs;
    protected function setUp()
    {
        $this->vfs = vfsStream::setup();
        vfsStream::copyFromFileSystem(__DIR__.'/../Fixtures/filesystem');
    }

    static public function assertYamlFileEquals($expected, $actual, $message='')
    {
        self::assertFileExists($expected);
        self::assertFileExists($actual);
        $expected = Yaml::parse(file_get_contents($expected));
        $actual = Yaml::parse(file_get_contents($actual));

        self::assertEquals($expected, $actual, $message);
    }
}
