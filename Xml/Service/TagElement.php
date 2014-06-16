<?php
/**
 * This file is part of the Manipulator package.
 *
 * (c) Lars Vierbergen <vierbergenlars@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace vierbergenlars\Bundle\ManipulatorBundle\Xml\Service;

use vierbergenlars\Bundle\ManipulatorBundle\Xml\AbstractElement;
use vierbergenlars\Bundle\ManipulatorBundle\Xml\ArrayAttributes;
use vierbergenlars\Bundle\ManipulatorBundle\Xml\YamlExportableInterface;

class TagElement extends AbstractElement implements YamlExportableInterface
{
    private $attrs;
    public function __construct($tag, array $attributes)
    {
        $this->attrs = array_merge(array('name'=>$tag),$attributes);
    }

    public function getName()
    {
        return 'tag';
    }

    public function attributes()
    {
        return new ArrayAttributes($this->attrs);
    }

    public function toYamlArray()
    {
        return $this->attributes()->toArray();
    }
}
