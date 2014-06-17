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

class XmlServiceManipulator extends AbstractXmlManipulator implements ServiceManipulatorInterface
{
    public function addService($id, Definition $service)
    {
        $services = $this->getXmlRoot()->child('services');
        $serviceXml = new ServiceElement($id, $service);
        $services->addChild($serviceXml);
    }

    public function removeService($id)
    {
        $services = $this->getXmlRoot()->child('services')->children();
        foreach($services as $i => $service)
        {
            if($service->attr('id') == $id) {
                unset($services[$i]);
                break;
            }
        }
    }
}
