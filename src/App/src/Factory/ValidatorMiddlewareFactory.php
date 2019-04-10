<?php

declare(strict_types=1);

namespace App\Factory;

use Psr\Container\ContainerInterface;
use App\Validator\ValidatorMiddleware;

class ValidatorMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : ValidatorMiddleware
    {  
        return new ValidatorMiddleware();
    }
}
