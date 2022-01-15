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
use RM\Bundle\ClientBundle\Entity\EntityRegistry;
use RM\Component\Client\Event\HydratedEvent;

/**
 * Class HydrationListener.
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class HydrationListener
{
    private EntityManagerInterface $entityManager;
    private EntityRegistry $entityRegistry;

    public function __construct(EntityManagerInterface $entityManager, EntityRegistry $entityRegistry)
    {
        $this->entityManager = $entityManager;
        $this->entityRegistry = $entityRegistry;
    }

    public function __invoke(HydratedEvent $event): void
    {
        $entity = $event->getEntity();

        if (!$this->entityRegistry->isDoctrine($entity)) {
            return;
        }

        $this->entityManager->persist($entity);
        $this->entityManager->refresh($entity);

        $event->setEntity($entity);
    }
}
