<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertAdapterInterface;

class ToTestDocumentAdapter implements ConvertAdapterInterface {
    public function isHandle($dtoObject) : bool
    {
        if ($dtoObject instanceof \Test\DTOs\Test\TestWithSectionDTO) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto) 
    {  
        $document = new \Test\Documents\Test\TestWithSectionDocument();
        $document->setTitle($dto->getTitle());
                
        $toSectionDocumentAdatper = new ToSectionDocumentAdapter();
        $sections = $dto->getSections();

        foreach($sections as $section) {
            $sectionDocument = $toSectionDocumentAdatper->convert($section);
            $document->addSection($sectionDocument);            
        }
        
        return $document;
            
    }
}