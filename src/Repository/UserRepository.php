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
