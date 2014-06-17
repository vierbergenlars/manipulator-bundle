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

abstract class AbstractManipulator implements ManipulatorInterface
{
    protected $file;
    public function __construct($file)
    {
        $this->file = $file;
        if(!is_file($file)) {
            throw new \RuntimeException(sprintf('%s does not exist', $this->file));
        }
    }
}
