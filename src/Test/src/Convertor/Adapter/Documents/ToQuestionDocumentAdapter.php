<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertAdapterInterface;

class ToQuestionDocumentAdapter implements ConvertAdapterInterface {
    public function isHandle($dtoObject) : bool
    {
        if ($dtoObject instanceof \Test\DTOs\QuestionSectionDTO) {
            return true;
        }

        return false;
    }
    
    public function convert($dtoObject) 
    {
        $document = new \Test\Documents\QuestionDocument();        
        $document->setContent(json_encode($dtoObject->getContent()));
        $document->setOrder($dtoObject->getOrder());
        
        $answers = $dtoObject->getAnswers();
        foreach($answers as $answer){
            $a = new \Test\Documents\AnswerDocument();
            $a->setContent($answer->getContent());
            $a->setOrder($answer->getOrder());
            $document->addAnswer($a);
        }
        
        return $document;            
    }
}