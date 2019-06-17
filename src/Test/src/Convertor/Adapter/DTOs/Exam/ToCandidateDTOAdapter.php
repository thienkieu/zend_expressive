<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Exam;

use Zend\Hydrator\ReflectionHydrator;
use Infrastructure\Convertor\ToDTOAdapter;

use Test\Enum\DTOName;
use Config\AppConstant;

class ToCandidateDTOAdapter extends ToDTOAdapter {
    public function isHandleConvertToDTO($dtoObject, $options = []) : bool
    {         
        if ($options === DTOName::Candidate) {
            return true;
        }
        
        return false;
    }
    
    public function getDTOClass() {
        return \Test\DTOs\Exam\CandidateDTO::class;
    }
}