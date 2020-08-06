<?php
/*
 * This file is a part of Relations Messenger Client Bundle.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/client-bundle
 * @link      https://dev.relmsg.ru/packages/client-bundle
 * @copyright Copyright (c) 2018-2020 Relations Messenger
 * @author    Oleg Kozlov <h1karo@relmsg.ru>
 * @license   https://legal.relmsg.ru/licenses/client-bundle
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Bundle\ClientBundle\Repository;

use BadMethodCallException;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use RM\Component\Client\Repository\Factory\AbstractFactory;
use RM\Component\Client\Repository\RepositoryInterface;

/**
 * Class ServiceRepositoryFactory
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
final class ServiceRepositoryFactory extends AbstractFactory
{
    private Collection $repositories;

    public function __construct(Reader $reader)
    {
        parent::__construct($reader);

        $this->repositories = new ArrayCollection();
    }

    public function setRepository(RepositoryInterface $repository, ?string $name = null): void
    {
        $name = $name ?? get_class($repository);

        if ($this->repositories->containsKey($name)) {
            throw new BadMethodCallException('Cannot overwrite already passed repositories.');
        }

        $this->repositories->set($name, $repository);
    }

    public function build(string $entity): RepositoryInterface
    {
        $class = $this->getRepositoryClass($entity);
        return $this->repositories->get($class);
    }
}
