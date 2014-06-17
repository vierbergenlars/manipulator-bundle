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

use vierbergenlars\Xml\XmlElement;

abstract class AbstractXmlManipulator extends AbstractManipulator
{
    private $xmlroot;
    protected function getXmlRoot()
    {
        if($this->xmlroot === null) {
            $xmlElement = new \SimpleXMLElement(file_get_contents($this->file));
            $this->xmlroot = new XmlElement($xmlElement);
        }
        return $this->xmlroot;
    }

    public function write()
    {
        file_put_contents($this->file, $this->getXmlRoot()->__toString());
    }
}
