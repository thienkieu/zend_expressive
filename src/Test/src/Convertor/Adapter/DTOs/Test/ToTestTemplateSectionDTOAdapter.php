<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Test;

use Zend\Hydrator\ReflectionHydrator;
use Infrastructure\Convertor\ToDTOAdapter;

use Test\Enum\DTOName;
use Config\AppConstant;

class ToTestTemplateSectionDTOAdapter extends ToDTOAdapter {
    public function isHandleConvertToDTO($dtoObject, $options = []) : bool
    {
        if (isset($options[\Config\AppConstant::DTOKey]) && $options[\Config\AppConstant::DTOKey] === DTOName::TestTemplate && isset($dtoObject->path)) {
            return true;
        }

        return false;
    }
    
    public function getDTOClass() {
        return \Test\DTOs\Test\TestTemplateDTO::class;
    }

    public function convert($jsonObject, $options = []) 
    {
        $dtoObject = new \Test\DTOs\Test\TestTemplateDTO();
        $dtoObject->setTitle($jsonObject->title);
        $testId = isset($jsonObject->id) ? $jsonObject->id: '';
        $dtoObject->setId($testId);

        $dtoObject->setPlatform($jsonObject->platform);
        
        $mediaFolder = \Config\AppConstant::MediaQuestionFolder . \Config\AppConstant::DS.date('Ymdhis');
        \Infrastructure\CommonFunction::createFolder($mediaFolder);
        $path =  \Infrastructure\CommonFunction::getRealPath($jsonObject->path);
        if ($path !== false && strpos($path, 'http://') === false) {
            \Infrastructure\CommonFunction::moveFileToFolder($path, \realpath($mediaFolder));                    
            $dtoObject->setPath(\Config\AppConstant::HOST_REPLACE.'/'.$mediaFolder.'/'.\basename($path));
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