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
        $document = new \Test\Documents\Question\ListeningQuestionDocument();
        $document->setContent(json_encode($dto->getContent()));
        $document->setPath($dto->getPath());
        $document->setRepeat($dto->getRepeat());
        $document->setSource($dto->getSource());
        $document->setType($dto->getType());
        $document->setSubType($dto->getSubType());

        $toQuestionDocumentAdatper = new ToQuestionDocumentAdapter();
        $questions = $dto->getSubQuestions();

        foreach($questions as $question) {
            $questionDocument = $toQuestionDocumentAdatper->convert($question);
            $document->addSubQuestion($questionDocument);            
        }
        
        return $document;
            
    }
}