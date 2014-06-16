<?php
/**
 * This file is part of the Manipulator package.
 *
 * (c) Lars Vierbergen <vierbergenlars@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace vierbergenlars\Bundle\ManipulatorBundle\Xml\Route;

class RouteElement extends AbstractConfigElement
{
    public function getName()
    {
        return 'route';
    }

    public function __construct($id, array $attrs = array(), array $config = array())
    {
        $attrs = array_merge(array('id' => $id), $attrs);
        parent::__construct($attrs, $config);
    }

    public function toYamlArray()
    {
        $yaml = parent::toYamlArray();
        unset($yaml['id']);
        return $yaml;
    }
}
