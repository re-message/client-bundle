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

namespace RM\Bundle\ClientBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use RM\Component\Client\Event\HydratedEvent;

/**
 * Class HydrationListener.
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class HydrationListener
{
    private EntityManagerInterface $entityManager;
    private array $entities;

    public function __construct(EntityManagerInterface $entityManager, array $entities = [])
    {
        $this->entityManager = $entityManager;
        $this->entities = $entities;
    }

    public function __invoke(HydratedEvent $event): void
    {
        $entity = $event->getEntity();

        if ($this->isDoctrineEntity($entity)) {
            $this->entityManager->persist($entity);
            $this->entityManager->refresh($entity);

            $event->setEntity($entity);
        }
    }

    public function isDoctrineEntity(object $entity): bool
    {
        $class = get_class($entity);
        return $this->entities[$class] ?? false === true;
    }
}
