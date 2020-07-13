<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Test;

use Zend\Hydrator\ReflectionHydrator;
use Infrastructure\Convertor\ToDTOAdapter;

use Test\Enum\DTOName;
use Config\AppConstant;

class ToSectionDTOAdapter extends ToDTOAdapter {
    public function isHandleConvertToDTO($dtoObject, $options = []) : bool
    {
        if (isset($options[\Config\AppConstant::DTOKey]) && $options[\Config\AppConstant::DTOKey] === DTOName::Section) {
            return true;
        }

        return false;
    }
    
    public function getDTOClass() {
        return \Test\DTOs\Test\SectionDTO::class;
    }

    public function convert($jsonObject, $options = []) 
    {  
        $dtoObject = new \Test\DTOs\Test\SectionDTO();
        $dtoObject->setName($jsonObject->name);
        $dtoObject->setDescription($jsonObject->description);
        $dtoObject->setIsOption($jsonObject->isOptionQuestion);
        $dtoObject->setRequiredQuestion($jsonObject->numberOptionQuestion);
        
        $mark = 0;
        if (isset($jsonObject->mark)) $mark = $jsonObject->mark;
        $dtoObject->setMark($mark);
        
        $questionDTOs = [];
        foreach ($jsonObject->questions as $jsonQuestion) {
            $question = $this->convertor->convertToDTO($jsonQuestion, [\Config\AppConstant::DTOKey => \Test\DTOs\Test\QuestionDTO::class]);
            $questionDTOs[] = $question;
        }

        $dtoObject->setQuestions($questionDTOs);
        
        return $dtoObject;            
    }
}