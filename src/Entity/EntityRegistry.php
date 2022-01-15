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

namespace RM\Bundle\ClientBundle\Entity;

use RM\Component\Client\Entity\EntityInterface;

/**
 * Class EntityRegistry.
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class EntityRegistry
{
    private array $entities;

    public function __construct(array $entities = [])
    {
        $this->entities = $entities;
    }

    public function isDoctrine(EntityInterface $entity): bool
    {
        $class = get_class($entity);
        $config = $this->findConfig($class) ?? [];

        return $config['doctrine'] ?? false === true;
    }

    protected function findConfig(string $class): ?array
    {
        foreach ($this->entities as $entity) {
            if ($entity['class'] === $class) {
                return $entity;
            }
        }

        return null;
    }
}
