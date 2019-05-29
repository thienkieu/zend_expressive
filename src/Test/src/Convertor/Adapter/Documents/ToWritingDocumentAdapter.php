<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertAdapterInterface;

class ToWritingDocumentAdapter implements ConvertAdapterInterface {
    public function isHandle($dtoObject) : bool
    {
        if ($dtoObject instanceof \Test\DTOs\WritingSectionDTO) {
            return true;
        }

        return false;
    }
    
    public function convert($dto) 
    {  
        $document = new \Test\Documents\Section\WritingSectionDocument();
        $document->setContent(json_encode($dto->getContent()));

        $toQuestionDocumentAdatper = new ToQuestionDocumentAdapter();
        $questions = $dto->getQuestions();

        foreach($questions as $question) {
            $questionDocument = $toQuestionDocumentAdatper->convert($question);
            $document->addQuestion($questionDocument);            
        }
        
        return $document;
            
    }
}