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

namespace RM\Bundle\ClientBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use RM\Bundle\ClientBundle\Entity\EntityRegistry;
use RM\Component\Client\Event\HydratedEvent;

/**
 * Class HydrationListener.
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
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
