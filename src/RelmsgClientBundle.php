<?php
/**
 * This file is a part of Relations Messenger Client Bundle.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/client-bundle
 * @link      https://dev.relmsg.ru/packages/client-bundle
 * @copyright Copyright (c) 2018-2020 Relations Messenger
 * @author    Oleg Kozlov <h1karo@outlook.com>
 * @license   https://legal.relmsg.ru/licenses/client-bundle
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Bundle\ClientBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class RelmsgClientBundle
 *
 * @author Oleg Kozlov <h1karo@outlook.com>
 */
class RelmsgClientBundle extends Bundle
{
    public const NAME = 'relmsg_client';

    public const TAG_REPOSITORY = self::NAME . '.repository';
    public const TAG_AUTHENTICATOR = self::NAME . '.authenticator';

    public const APP_ID_PARAMETER = self::NAME . '.app_id';
    public const APP_SECRET_PARAMETER = self::NAME . '.app_secret';

    public const ALLOW_AUTH_EXCEPTION_PARAMETER = self::NAME . '.auth.exception_on_failed';
}
