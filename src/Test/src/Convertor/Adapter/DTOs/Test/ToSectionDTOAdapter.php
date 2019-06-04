<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Test;

use Zend\Hydrator\ReflectionHydrator;
use Infrastructure\Convertor\ToDTOAdapter;

use Test\Enum\DTOName;
use Config\AppConstant;

class ToSectionDTOAdapter extends ToDTOAdapter {
    public function isHandle($dtoObject, $name) : bool
    {
        if ($name === DTOName::Section) {
            return true;
        }

        return false;
    }
    
    public function getDTOClass() {
        return \Test\DTOs\Test\SectionDTO::class;
    }

    public function convert($jsonObject) 
    {  
        $dtoObject = new \Test\DTOs\Test\SectionDTO();
        $dtoObject->setName($jsonObject->name);
        $dtoObject->setDescription($jsonObject->description);
        
        $questionDTOs = [];
        foreach ($jsonObject->questions as $jsonQuestion) {
            $question = $this->convertor->convertToDTO($jsonQuestion, \Test\DTOs\Test\QuestionDTO::class);
            $questionDTOs[] = $question;
        }

        $dtoObject->setQuestions($questionDTOs);
        
        return $dtoObject;            
    }
}