<?php

declare(strict_types=1);

namespace Infrastructure\Convertor;

use Psr\Container\ContainerInterface;

class DocumentToDTOConvertorFactory
{
    public function __invoke(ContainerInterface $container) : DocumentToDTOConvertor
    {
        $appConfig = $container->get('config');
        $convertorAdapters = $appConfig['convertorDocumentToDTOAdapters'];

        $convertor = new DocumentToDTOConvertor($container, $convertorAdapters);
        return $convertor;
    }
}
