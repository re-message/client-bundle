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

namespace RM\Bundle\ClientBundle\Hydrator;

use Doctrine\ORM\EntityManagerInterface;
use RM\Component\Client\Hydrator\DecoratedHydrator;
use RM\Component\Client\Hydrator\EntityHydrator;

class DoctrineHydrator extends DecoratedHydrator
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityHydrator $hydrator, EntityManagerInterface $entityManager)
    {
        parent::__construct($hydrator);
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    public function hydrate(array $data, string $class): object
    {
        $entity = parent::hydrate($data, $class);

        $this->entityManager->persist($entity);
        $this->entityManager->refresh($entity);

        return $entity;
    }
}
