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

namespace RM\Bundle\ClientBundle;

use RM\Bundle\ClientBundle\DependencyInjection\Compiler\ServiceRepositoryFactoryPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class RmClientBundle extends Bundle
{
    public const NAME = 'rm_client';

    public const TAG_REPOSITORY = self::NAME . '.repository';
    public const TAG_AUTHENTICATOR = self::NAME . '.authenticator';

    public const APP_ID_PARAMETER = self::NAME . '.app_id';
    public const APP_SECRET_PARAMETER = self::NAME . '.app_secret';

    public const ALLOW_AUTH_EXCEPTION_PARAMETER = self::NAME . '.auth.exception_on_failed';
    public const AUTO_AUTH_PARAMETER = self::NAME . '.auth.auto_auth';

    public const COLLECTOR = self::NAME;

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return dirname(parent::getPath());
    }

    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new ServiceRepositoryFactoryPass());
    }
}
