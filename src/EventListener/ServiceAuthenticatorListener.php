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
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Bundle\ClientBundle\EventListener;

use RM\Bundle\ClientBundle\RelmsgClientBundle;
use RM\Component\Client\Security\Authenticator\ServiceAuthenticator;
use RM\Component\Client\Security\Storage\AuthorizationStorageInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Class ServiceAuthenticatorListener
 *
 * @package RM\Bundle\ClientBundle\EventListener
 * @author  h1karo <h1karo@outlook.com>
 */
class ServiceAuthenticatorListener
{
    private ServiceAuthenticator          $authenticator;
    private AuthorizationStorageInterface $storage;
    private ParameterBagInterface         $parameterBag;

    public function __construct(
        ServiceAuthenticator $authenticator,
        AuthorizationStorageInterface $storage,
        ParameterBagInterface $parameterBag
    ) {
        $this->authenticator = $authenticator;
        $this->storage = $storage;
        $this->parameterBag = $parameterBag;
    }

    public function __invoke(RequestEvent $event)
    {
        if ($this->storage->has(ServiceAuthenticator::getTokenType())) {
            return;
        }

        $appId = $this->parameterBag->get(RelmsgClientBundle::APP_ID_PARAMETER);
        $appSecret = $this->parameterBag->get(RelmsgClientBundle::APP_SECRET_PARAMETER);

        $this->authenticator
            ->setId($appId)
            ->setSecret($appSecret)
            ->authenticate()
        ;
    }
}