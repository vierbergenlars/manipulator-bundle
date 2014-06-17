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
use Symfony\Component\HttpKernel\BundleInterface;

interface ServiceManipulatorInterface extends ManipulatorInterface
{
    public function addService($id, Definition $service);
    public function removeService($id);
}
