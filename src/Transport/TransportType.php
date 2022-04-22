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

namespace RM\Bundle\ClientBundle\Transport;

use InvalidArgumentException;
use ReflectionClass;
use RM\Component\Client\Transport\HttpTransport;

/**
 * Class TransportType
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
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
        return (new ReflectionClass(static::class))->getConstants();
    }
}
