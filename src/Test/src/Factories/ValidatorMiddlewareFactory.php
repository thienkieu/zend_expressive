<?php

declare(strict_types=1);

namespace Test\Factory;

use Psr\Container\ContainerInterface;
use App\Validator\ValidatorMiddleware;

class CreateSectionValidatorMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : ValidatorMiddleware
    {  
        return new CreateSectionValidatorMiddleware();
    }
}
