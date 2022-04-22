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

use RM\Bundle\ClientBundle\EventListener\ServiceAuthenticatorListener;
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
        ->set(ServiceAuthenticatorListener::class)
            ->tag('kernel.event_listener')
    ;
};

