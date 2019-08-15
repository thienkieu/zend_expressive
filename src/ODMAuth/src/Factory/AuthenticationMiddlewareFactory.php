<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-authentication for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-authentication/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ODMAuth\Factory;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Authentication\AuthenticationInterface;
use \ODMAuth\Middleware\AuthenticationMiddleware;

class AuthenticationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : AuthenticationMiddleware
    {
        $authentication = $container->has(AuthenticationInterface::class) ?
                          $container->get(AuthenticationInterface::class) :
                          null;
        if (null === $authentication) {
            throw new Exception\InvalidConfigException(
                'AuthenticationInterface service is missing'
            );
        }
        return new \ODMAuth\Middleware\AuthenticationMiddleware($authentication, $container);
    }
}
