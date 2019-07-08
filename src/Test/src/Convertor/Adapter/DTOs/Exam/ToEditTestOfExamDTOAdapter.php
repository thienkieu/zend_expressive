<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Exam;

use Zend\Hydrator\ReflectionHydrator;
use Infrastructure\Convertor\ToDTOAdapter;

use Test\Enum\DTOName;
use Config\AppConstant;

class ToEditTestOfExamDTOAdapter extends ToDTOAdapter {
    public function isHandleConvertToDTO($dtoObject, $options = []) : bool
    {              
        if (isset($options[\Config\AppConstant::DTOKey]) && $options[\Config\AppConstant::DTOKey] === DTOName::EditTestOfExam) {
            return true;
        }
    
        return false;
    }
    
    public function getDTOClass() {
        return \Test\DTOs\Exam\EditTestOfExamDTO::class;
    }

    public function convert($jsonObject, $options = []) 
    {
        $dtoObject = new \Test\DTOs\Exam\EditTestOfExamDTO();
        $dtoObject->setId($jsonObject->id);
        
        $jsonTest = $jsonObject->test;
        $test = $this->convertor->convertToDTO($jsonTest, [\Config\AppConstant::DTOKey => \Test\DTOs\Test\BaseTestDTO::class]);
        $dtoObject->setTest($test);

        return $dtoObject;            
    }
}