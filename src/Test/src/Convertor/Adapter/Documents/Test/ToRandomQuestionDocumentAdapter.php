<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents\Test;


use Infrastructure\Convertor\ConvertAdapterInterface;

class ToRandomQuestionDocumentAdapter implements ConvertAdapterInterface {
    public function isHandle($dtoObject) : bool
    {
        if ($dtoObject instanceof \Test\DTOs\Test\RandomQuestionDTO) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto) 
    {  
        $document = new \Test\Documents\Test\RandomQuestionDocument();
        $document->setType($dto->getType());
        $document->setSubType($dto->getSubType());
        $document->setIsDifferentSource($dto->getIsDifferentSource());
        $document->setNumberQuestion($dto->getNumberQuestion());
        
        return $document;
            
    }
}