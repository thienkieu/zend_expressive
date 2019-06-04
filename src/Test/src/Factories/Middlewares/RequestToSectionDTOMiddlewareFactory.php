<?php

declare(strict_types=1);

namespace Test\Factories\Middlewares;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Test\Middlewares\RequestToSectionDTOMiddleware;

class RequestToSectionDTOMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : RequestToSectionDTOMiddleware
    {        
        return new RequestToSectionDTOMiddleware();
    }
}
