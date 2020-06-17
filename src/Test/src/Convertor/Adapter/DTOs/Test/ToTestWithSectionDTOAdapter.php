<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Test;

use Zend\Hydrator\ReflectionHydrator;
use Infrastructure\Convertor\ToDTOAdapter;

use Test\Enum\DTOName;
use Config\AppConstant;

class ToTestWithSectionDTOAdapter extends ToDTOAdapter {
    public function isHandleConvertToDTO($dtoObject, $options = []) : bool
    {
        $type = isset($dtoObject->sections) ? $dtoObject->sections: '';
        if (isset($options[\Config\AppConstant::DTOKey]) && $options[\Config\AppConstant::DTOKey] === DTOName::Test && !empty($type)) {
            return true;
        }

        return false;
    }
    
    public function getDTOClass() {
        return \Test\DTOs\Test\TestWithSectionDTO::class;
    }

    public function convert($jsonObject, $options = []) 
    {
        $dtoObject = new \Test\DTOs\Test\TestWithSectionDTO();
        $dtoObject->setTitle($jsonObject->title);
        $testId = isset($jsonObject->id) ? $jsonObject->id: '';
        $dtoObject->setId($testId);
        
        if (isset($jsonObject->user)) {
            $dtoObject->setUser($jsonObject->user);
        }
        
        $sectionDTOs = [];
        foreach ($jsonObject->sections as $jsonSection) {
            $section = $this->convertor->convertToDTO($jsonSection, [\Config\AppConstant::DTOKey => \Test\DTOs\Test\SectionDTO::class]);
            $sectionDTOs[] = $section;
        }

        $dtoObject->setSections($sectionDTOs);
        return $dtoObject;            
    }
}