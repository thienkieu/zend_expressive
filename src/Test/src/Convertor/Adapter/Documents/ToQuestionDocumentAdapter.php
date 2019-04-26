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
        $document->setContent($dtoObject['content']);
        
        $answers = $dtoObject['answers'];
        foreach($answers as $answer){
            $a = new \Test\Documents\AnswerDocument();
            $a->setContent($answer['content']);
            $document->addAnswer($a);
        }
        
        return $document;            
    }
}