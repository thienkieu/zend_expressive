<?php

declare(strict_types=1);

namespace Infrastructure\Convertor;

use Psr\Container\ContainerInterface;

class RequestToDTOConvertorFactory
{
    public function __invoke(ContainerInterface $container) : RequestToDTOConvertor
    {
        $appConfig = $container->get('config');
        $convertorAdapters = $appConfig['convertorDTOAdapters'];

        $convertor = new RequestToDTOConvertor($container, $convertorAdapters);
        return $convertor;
    }
}
