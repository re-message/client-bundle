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

namespace RM\Bundle\ClientBundle\Tests;

use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class TestCase
 *
 * @package RM\Bundle\ClientBundle\Tests
 * @author  h1karo <h1karo@outlook.com>
 */
abstract class TestCase extends WebTestCase
{
    protected function setUp(): void
    {
        $fs = new Filesystem();
        $fs->remove(sys_get_temp_dir() . '/ClientBundle/');
    }

    protected static function createKernel(array $options = []): KernelInterface
    {
        if (null === static::$class) {
            static::$class = static::getKernelClass();
        }

        if (isset($options['environment'])) {
            $env = $options['environment'];
        } elseif (isset($_ENV['APP_ENV'])) {
            $env = $_ENV['APP_ENV'];
        } elseif (isset($_SERVER['APP_ENV'])) {
            $env = $_SERVER['APP_ENV'];
        } else {
            $env = 'test';
        }

        if (isset($options['debug'])) {
            $debug = $options['debug'];
        } elseif (isset($_ENV['APP_DEBUG'])) {
            $debug = $_ENV['APP_DEBUG'];
        } elseif (isset($_SERVER['APP_DEBUG'])) {
            $debug = $_SERVER['APP_DEBUG'];
        } else {
            $debug = true;
        }

        $reflect = new ReflectionClass(static::class);
        $testCase = str_replace('Test', '', $reflect->getShortName());

        return new static::$class($env, $debug, $testCase);
    }
}
