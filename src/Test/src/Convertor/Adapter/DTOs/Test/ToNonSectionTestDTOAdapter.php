<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Zend\Hydrator\ReflectionHydrator;
use Infrastructure\Convertor\ToDTOAdapter;

use Test\Enum\DTOName;
use Config\AppConstant;

class ToNonSectionTestDTOAdapter extends ToDTOAdapter {
    public function isHandleConvertToDTO($dtoObject, $options = []) : bool
    {
        if ($options === \Test\DTOs\Test\NonSectionTestDTO::class) {
            return true;
        }

        return false;
    }
    
    public function getDTOClass() {
        return \Test\DTOs\Test\NonSectionTestDTO::class;
    }

    public function convert($request) 
    {  
        $dtoClass = $this->getDTOClass();
       
        $mapper = new \JsonMapper();
        $dto = $mapper->map($request->getParsedBody(), new $dtoClass());
        return $dto;            
    }
}