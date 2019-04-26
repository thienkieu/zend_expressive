<?php

declare(strict_types=1);

namespace Infrastructure\Convertor;

use Psr\Container\ContainerInterface;

class DTOToDocumentConvertorFactory
{
    public function __invoke(ContainerInterface $container) : DTOToDocumentConvertor
    {
        $appConfig = $container->get('config');
        $convertorAdapters = $appConfig['convertorDocumentAdapters'];

        $convertor = new DTOToDocumentConvertor($convertorAdapters);
        return $convertor;
    }
}
