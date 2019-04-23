<?php

declare(strict_types=1);

namespace Test\Factory\Middlewares;

use Psr\Container\ContainerInterface;
use App\Validator\ValidatorMiddleware;

class CreateSectionValidatorMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : ValidatorMiddleware
    {  
        return new CreateSectionValidatorMiddleware();
    }
}
