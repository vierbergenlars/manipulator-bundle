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

interface RouteManipulatorInterface extends ManipulatorInterface
{
    public function addRoute($id, array $options = array());
    public function addImport($resource, array $options = array());
    public function removeRoute($id);
    public function removeImport($resource);
}
