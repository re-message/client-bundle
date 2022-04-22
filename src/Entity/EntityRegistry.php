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

namespace RM\Bundle\ClientBundle\Entity;

use RM\Component\Client\Entity\EntityInterface;

/**
 * Class EntityRegistry.
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
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
