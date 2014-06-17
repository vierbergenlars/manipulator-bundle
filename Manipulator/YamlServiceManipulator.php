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

use Symfony\Component\DependencyInjection\Definition;
use vierbergenlars\Bundle\ManipulatorBundle\Xml\Service\ServiceElement;
use Symfony\Component\Yaml\Yaml;

class YamlServiceManipulator extends AbstractYamlManipulator implements ServiceManipulatorInterface
{
    public function addService($id, Definition $service)
    {
        $serviceXml = new ServiceElement($id, $service);
        $this->yamlArray['services'][$serviceXml->attr('id')] = $serviceXml->toYamlArray();
    }

    public function removeService($id)
    {
        unset($this->yamlArray['services'][$id]);
    }
}
