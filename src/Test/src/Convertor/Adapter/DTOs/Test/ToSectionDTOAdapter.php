<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Test;

use Zend\Hydrator\ReflectionHydrator;
use Infrastructure\Convertor\ToDTOAdapter;

use Test\Enum\DTOName;
use Config\AppConstant;

class ToSectionDTOAdapter extends ToDTOAdapter {
    public function isHandle($request) : bool
    {
        $name = $request->getAttribute(AppConstant::RequestDTOName);
        $body = $request->getParsedBody();
        $type = isset($body->sections) ? $body->sections: '';
        if ($name === DTOName::Test && !empty($type)) {
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
            $adapter = new ToQuestionDTOAdapter();
            $question = $adapter->convert($jsonQuestion);
            $questionDTOs[] = $question;
        }

        $dtoObject->setQuestions($questionDTOs);
        
        return $dtoObject;            
    }
}