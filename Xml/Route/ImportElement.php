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

use vierbergenlars\Bundle\ManipulatorBundle\Xml\ArrayAttributes;

class ImportElement extends AbstractConfigElement
{
    public function getName()
    {
        return 'import';
    }

    public function __construct($resource, array $attrs = array(), array $config = array())
    {
        $attrs = array_merge(array('resource' => $resource), $attrs);
        parent::__construct($attrs, $config);
    }
}
