<?php

declare(strict_types=1);

namespace Test\Factories;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Test\Middlewares\Validators\CreateSectionValidatorMiddleware;

class CreateSectionValidatorMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : CreateSectionValidatorMiddleware
    {        
        return new CreateSectionValidatorMiddleware();
    }
}
