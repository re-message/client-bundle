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

use RM\Component\Client\EventListener\LazyLoadListener;
use RM\Component\Client\EventListener\ThrowableSendListener;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->defaults()
            ->autowire(true)
            ->autoconfigure(true)
        ->set(ThrowableSendListener::class)
            ->tag('kernel.event_listener')
        ->set(LazyLoadListener::class)
            ->tag('kernel.event_listener')
        ->load('RM\\Bundle\\ClientBundle\\EventListener\\', '../src/EventListener')
            ->tag('kernel.event_listener')
    ;
};

