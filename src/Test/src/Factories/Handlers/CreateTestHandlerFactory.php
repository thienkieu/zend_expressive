<?php

declare(strict_types=1);

namespace Test\Factories\Handlers;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

use function get_class;

class CreateTestHandlerFactory
{
    public function __invoke(ContainerInterface $container) : RequestHandlerInterface
    {
        $router   = $container->get(RouterInterface::class);

        return new \Test\Handlers\CreateTestHandler(get_class($container), $container, $router);
    }
}
