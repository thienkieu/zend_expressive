<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertAdapterInterface;

class ToReadingDocumentAdapter implements ConvertAdapterInterface {
    public function isHandle($dtoObject) : bool
    {
        if ($dtoObject instanceof \Test\DTOs\ReadingSectionDTO) {
            return true;
        }

        return false;
    }
    
    public function convert($dto) 
    {  
        $document = new \Test\Documents\Section\ReadingSectionDocument();
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