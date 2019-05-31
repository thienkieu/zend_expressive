<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Test;

use Zend\Hydrator\ReflectionHydrator;
use Infrastructure\Convertor\ToDTOAdapter;

use Test\Enum\DTOName;
use Config\AppConstant;

class ToTestWithSectionDTOAdapter extends ToDTOAdapter {
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
        return \Test\DTOs\Test\TestWithSectionDTO::class;
    }

    public function convert($jsonObject) 
    {
        $dtoObject = new \Test\DTOs\Test\TestWithSectionDTO();
        $dtoObject->setTitle($jsonObject->title);
        
        $sectionDTOs = [];
        foreach ($jsonObject->sections as $jsonSection) {
            $adapter = new ToSectionDTOAdapter();
            $section = $adapter->convert($jsonSection);
            $sectionDTOs[] = $section;
        }

        $dtoObject->setSections($sectionDTOs);
        
        return $dtoObject;            
    }
}