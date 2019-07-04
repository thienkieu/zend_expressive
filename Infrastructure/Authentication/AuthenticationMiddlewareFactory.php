<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-authentication for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-authentication/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Infrastructure\Authentication;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Authentication\AuthenticationInterface;

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
        return new AuthenticationMiddleware($authentication, $container);
    }
}
