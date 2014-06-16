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

use vierbergenlars\Bundle\ManipulatorBundle\Xml\ArrayAttributes;

class PropertyElement extends ArgumentElement
{
    private $property;
    public function __construct($property, $argument)
    {
        $this->property = $property;
        parent::__construct($argument);
    }

    public function getName()
    {
        return 'property';
    }

    public function attributes()
    {
        $attributes = parent::attributes()->toArray();
        $attributes['name'] = $this->property;
        return new ArrayAttributes($attributes);
    }

    public function toYamlArray()
    {
        return array(($this->attr('name'))=>parent::toYamlArray());
    }
}
