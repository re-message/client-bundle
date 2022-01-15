<?php
/*
 * This file is a part of Relations Messenger Client Bundle.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/client-bundle
 * @link      https://dev.relmsg.ru/packages/client-bundle
 * @copyright Copyright (c) 2018-2022 Relations Messenger
 * @author    Oleg Kozlov <h1karo@relmsg.ru>
 * @license   https://legal.relmsg.ru/licenses/client-bundle
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Bundle\ClientBundle\Repository;

use RM\Component\Client\Entity\User;
use RM\Component\Client\Hydrator\HydratorInterface;
use RM\Component\Client\Repository\UserRepository as BaseUserRepository;
use RM\Component\Client\Transport\TransportInterface;

class UserRepository extends BaseUserRepository
{
    private string $class;

    public function __construct(TransportInterface $transport, HydratorInterface $hydrator, string $class = User::class)
    {
        parent::__construct($transport, $hydrator);

        $this->class = $class;
    }

    public function getEntity(): string
    {
        return $this->class;
    }
}
