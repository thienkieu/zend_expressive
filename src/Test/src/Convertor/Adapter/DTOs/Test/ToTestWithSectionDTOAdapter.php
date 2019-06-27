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
        if ($options === DTOName::Test && !empty($type)) {
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
        
        $sectionDTOs = [];
        foreach ($jsonObject->sections as $jsonSection) {
            $section = $this->convertor->convertToDTO($jsonSection, \Test\DTOs\Test\SectionDTO::class);
            $sectionDTOs[] = $section;
        }

        $dtoObject->setSections($sectionDTOs);
        return $dtoObject;            
    }
}