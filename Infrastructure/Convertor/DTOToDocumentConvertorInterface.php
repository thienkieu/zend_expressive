<?php 

declare(strict_types=1);

namespace Infrastructure\Convertor;

use Zend\Hydrator\ReflectionHydrator;

interface DTOToDocumentConvertorInterface {    
    public function convertToDocument($dto);
}