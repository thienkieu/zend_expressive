<?php

declare(strict_types=1);

namespace Infrastructure\Validator;

use Psr\Container\ContainerInterface;

class ValidatorRequestFactory
{
    public function __invoke(ContainerInterface $container) : ValidatorRequest
    {
        $appConfig = $container->get(\Config\AppConstant::AppConfig);
        $validatorAdapters = $appConfig['validatorRequestAdapters'];

        $validator = new ValidatorRequest($container, $validatorAdapters);
        return $validator;
    }
}
