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

use RM\Bundle\ClientBundle\Exception\AuthorizationFailedException;
use RM\Bundle\ClientBundle\RemessageClientBundle;
use RM\Component\Client\Exception\ErrorException;
use RM\Component\Client\Security\Authenticator\ServiceAuthenticator;
use RM\Component\Client\Security\Storage\AuthorizationStorageInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Class ServiceAuthenticatorListener
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class ServiceAuthenticatorListener extends DisableListener
{
    private ServiceAuthenticator $authenticator;
    private AuthorizationStorageInterface $storage;
    private ParameterBagInterface $parameterBag;

    public function __construct(
        ServiceAuthenticator $authenticator,
        AuthorizationStorageInterface $storage,
        ParameterBagInterface $parameterBag
    ) {
        $this->authenticator = $authenticator;
        $this->storage = $storage;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @param RequestEvent $event
     *
     * @throws AuthorizationFailedException
     */
    public function __invoke(RequestEvent $event): void
    {
        if (!$this->isEnabled()) {
            return;
        }

        if ($this->storage->has(ServiceAuthenticator::getTokenType())) {
            return;
        }

        $appId = $this->parameterBag->get(RemessageClientBundle::APP_ID_PARAMETER);
        $appSecret = $this->parameterBag->get(RemessageClientBundle::APP_SECRET_PARAMETER);
        $this->authenticator->setId($appId)->setSecret($appSecret);

        $isAutoAuth = $this->parameterBag->get(RemessageClientBundle::AUTO_AUTH_PARAMETER);
        if (!$isAutoAuth) {
            return;
        }

        try {
            $this->authenticator->authenticate();
        } catch (ErrorException $e) {
            $isExceptionAllowed = $this->parameterBag->get(RemessageClientBundle::ALLOW_AUTH_EXCEPTION_PARAMETER);
            if (!$isExceptionAllowed) {
                return;
            }

            throw new AuthorizationFailedException($e);
        }
    }
}
