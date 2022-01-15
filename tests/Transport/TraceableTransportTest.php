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

namespace RM\Bundle\ClientBundle\Tests\Transport;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use RM\Bundle\ClientBundle\Transport\TraceableTransport;
use RM\Component\Client\Transport\TransportInterface;
use RM\Standard\Message\Action;
use RM\Standard\Message\Response;

class TraceableTransportTest extends TestCase
{
    public function testThrowException(): void
    {
        $transport = new TraceableTransport(
            $parent = $this->createMock(TransportInterface::class),
            $logger = $this->createMock(LoggerInterface::class)
        );

        $action = new Action('action');
        $response = new Response(['some' => 'value']);

        $parent
            ->method('send')
            ->willReturn($response)
        ;

        $logger
            ->expects(self::once())
            ->method('debug')
            ->with(
                self::isType('string'),
                self::logicalAnd(
                    self::isType('array'),
                    self::containsIdentical($action->toArray()),
                    self::containsIdentical($response->toArray())
                )
            )
        ;

        $actual = $transport->send($action);

        self::assertEquals($response, $actual);

    }
}
