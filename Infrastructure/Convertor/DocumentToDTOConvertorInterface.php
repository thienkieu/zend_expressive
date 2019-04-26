<?php 

declare(strict_types=1);

namespace Infrastructure\Convertor;

use Zend\Hydrator\ReflectionHydrator;

interface DocumentToDTOConvertorInterface {    
    public function convertToDTO($document);
}