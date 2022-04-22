<?php
/*
 * This file is a part of Re Message Client Bundle.
 * This package is a part of Re Message.
 *
 * @link      https://github.com/re-message/client-bundle
 * @link      https://dev.remessage.ru/packages/client-bundle
 * @copyright Copyright (c) 2018-2022 Re Message
 * @author    Oleg Kozlov <h1karo@remessage.ru>
 * @license   Apache License 2.0
 * @license   https://legal.remessage.ru/licenses/client-bundle
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Bundle\ClientBundle\Security\Config;

use ReflectionClass;
use RM\Component\Client\ClientInterface;
use Symfony\Component\Config\FileLocator as BaseFileLocator;

/**
 * Class FileLocator
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class FileLocator extends BaseFileLocator
{
    public function __construct()
    {
        $reflect = new ReflectionClass(ClientInterface::class);
        $packageDir = dirname($reflect->getFileName(), 2);
        parent::__construct($packageDir);
    }
}
