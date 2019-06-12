<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Exam;

use Zend\Hydrator\ReflectionHydrator;
use Infrastructure\Convertor\ToDTOAdapter;

use Test\Enum\DTOName;
use Config\AppConstant;

class ToPinInfoDTOAdapter extends ToDTOAdapter {
    public function isHandle($dtoObject, $name) : bool
    {         
        if ($name === DTOName::PinInfoDTO) {
            return true;
        }
        
        return false;
    }
    
    public function getDTOClass() {
        return \Test\DTOs\Exam\PinInfoDTO::class;
    }
}