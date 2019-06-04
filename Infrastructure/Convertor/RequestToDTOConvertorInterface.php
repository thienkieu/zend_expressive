<?php 

declare(strict_types=1);

namespace Infrastructure\Convertor;

use Zend\Hydrator\ReflectionHydrator;

interface RequestToDTOConvertorInterface {    
    public function convertToDTO($dtoObject, $toDTOName);
}