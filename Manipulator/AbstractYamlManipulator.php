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

use Symfony\Component\Yaml\Yaml;

abstract class AbstractYamlManipulator extends AbstractManipulator
{
    protected $yamlArray;

    public function __construct($file)
    {
        parent::__construct($file);
        $this->getYamlArray();
    }

    private function getYamlArray()
    {
        if($this->yamlArray === null) {
            $this->yamlArray = Yaml::parse(file_get_contents($this->file));
        }
        return $this->yamlArray;
    }

    public function write()
    {
        file_put_contents($this->file, Yaml::dump($this->getYamlArray()));
    }
}
