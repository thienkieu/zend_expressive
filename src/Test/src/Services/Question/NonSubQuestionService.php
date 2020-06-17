<?php

declare(strict_types=1);

namespace Test\Services\Question;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

class NonSubQuestionService extends QuestionService
{
    public function isHandler($param, $options = []){
        if (isset($options['document']) && $options['document'] instanceof \Test\Documents\Test\NonSubQuestionDocument){
            return true;
        }
        return false;
    }

    public function caculateMark(&$questionDocument) {
        $candidateMark = 0;
        $numberCorrectSubQuestion = $this->getNumberCorrectSubQuestion($questionDocument, $candidateMark);
        $questionDocument->setNumberCorrectSubQuestion($numberCorrectSubQuestion);
        $this->setCandidateMark($questionDocument, $candidateMark);
    }

    protected function getNumberCorrectSubQuestion($questionDocument, & $candidateMark) {
        $answers = $questionDocument->getAnswers();
        $isCorrect = false;
        foreach ($answers as $answer) {
            $isCorrectValue = $answer->getIsCorrect();
            $isUserChoice = $answer->getIsUserChoice();
            if ($isCorrectValue === $isUserChoice && $isCorrectValue == true ) {
                $isCorrect = true;
                break;
            }

        }
        
        if ($isCorrect) {
            $candidateMark = $questionDocument->getMark();
            return 1;
        }
        
        return 0;
    }
    
}
