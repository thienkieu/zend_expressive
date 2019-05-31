<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertAdapterInterface;

class ToSectionDocumentAdapter implements ConvertAdapterInterface {
    public function isHandle($dtoObject) : bool
    {
        if ($dtoObject instanceof \Test\DTOs\Test\SectionDTO) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto) 
    {  
        $document = new \Test\Documents\Test\SectionDocument();
        $document->setName($dto->getName());
        $document->setDescription($dto->getDescription());
                
        $toQuestionDocumentAdatper = new Test\ToQuestionDocumentAdapter();
        $questions = $dto->getQuestions();
        
        foreach($questions as $question) {
            $questionDocument = $toQuestionDocumentAdatper->convert($question);
            $document->addQuestion($questionDocument);            
        }
        
        return $document;            
    }
}