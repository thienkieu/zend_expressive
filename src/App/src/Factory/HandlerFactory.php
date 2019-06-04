<?php

declare(strict_types=1);

namespace App\Factory;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

use function get_class;

class HandlerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) : RequestHandlerInterface
    {
        $router   = $container->get(RouterInterface::class);
               
        return new $requestedName(get_class($container), $container, $router);
    }
}
