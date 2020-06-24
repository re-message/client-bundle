<?php
/**
 * This file is a part of Relations Messenger Client Bundle.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/client-bundle
 * @link      https://dev.relmsg.ru/packages/client-bundle
 * @copyright Copyright (c) 2018-2020 Relations Messenger
 * @author    Oleg Kozlov <h1karo@outlook.com>
 * @license   https://legal.relmsg.ru/licenses/client-bundle
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Bundle\ClientBundle\Repository;

use Doctrine\Common\Annotations\Reader;
use Psr\Container\ContainerInterface;
use RM\Component\Client\Repository\Factory\AbstractFactory;
use RM\Component\Client\Repository\RepositoryInterface;

/**
 * Class ServiceRepositoryFactory
 *
 * @package RM\Bundle\ClientBundle\Repository
 * @author  h1karo <h1karo@outlook.com>
 */
final class ServiceRepositoryFactory extends AbstractFactory
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container, Reader $reader)
    {
        parent::__construct($reader);
        $this->container = $container;
    }

    public function build(string $entity): RepositoryInterface
    {
        $class = $this->getRepositoryClass($entity);
        return $this->container->get($class);
    }
}
