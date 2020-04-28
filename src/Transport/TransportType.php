<?php
/**
 * This file is a part of Relations Messenger Client Bundle.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/client-bundle
 * @link      https://dev.relmsg.ru/packages/client-bundle
 * @copyright Copyright (c) 2018-2020 Relations Messenger
 * @author    h1karo <h1karo@outlook.com>
 * @license   https://legal.relmsg.ru/licenses/client-bundle
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Bundle\ClientBundle\Transport;

use InvalidArgumentException;
use ReflectionClass;
use RM\Component\Client\Transport\HttpTransport;

/**
 * Class TransportType
 *
 * @package RM\Bundle\ClientBundle\Transport
 * @author  h1karo <h1karo@outlook.com>
 */
class TransportType
{
    /**
     * @see HttpTransport
     */
    public const HTTP = 'http';

    private string $name;

    public function __construct(string $name)
    {
        if (!in_array($name, self::all(), true)) {
            throw new InvalidArgumentException(sprintf('Strategy with name %s does not exist.', $name));
        }

        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function is(string $strategy): bool
    {
        return $this->name === $strategy;
    }

    public static function all(): array
    {
        $reflect = new ReflectionClass(static::class);
        return $reflect->getConstants();
    }
}
