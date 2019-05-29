<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertAdapterInterface;

class ToListeningDocumentAdapter implements ConvertAdapterInterface {
    public function isHandle($dtoObject) : bool
    {
        if ($dtoObject instanceof \Test\DTOs\ListeningSectionDTO) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto) 
    {  
        $document = new \Test\Documents\Section\ListeningSectionDocument();
        $document->setContent(json_encode($dto->getContent()));
        $document->setPath($dto->getPath());
        $document->setRepeat($dto->getRepeat());

        $toQuestionDocumentAdatper = new ToQuestionDocumentAdapter();
        $questions = $dto->getQuestions();

        foreach($questions as $question) {
            $questionDocument = $toQuestionDocumentAdatper->convert($question);
            $document->addQuestion($questionDocument);            
        }
        
        return $document;
            
    }
}