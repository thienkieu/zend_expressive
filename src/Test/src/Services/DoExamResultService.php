<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

class DoExamResultService extends DoBaseExamResultService
{
    public function isHandler($dto, $options = []){
        if ($dto instanceof \Test\DTOs\ExamResult\PickupAnswerDTO) {
            return true;
        }
        
        return false;
    }
  
    protected function updateSubQuestionAnswer(& $examResult, $dto) {
        $questionInfo = $this->getQuestion($examResult, $dto);
        if (!$questionInfo) {
            $message = $this->translator->translate('cannot found your question');
            throw new \Test\Exceptions\UpdateAnswerException($message);
        }

        $subQuestions = $questionInfo->getSubQuestions();
        $hasUserChoose = false;
        foreach ($subQuestions as $subQuestion) {
            if ($subQuestion->getId() == $dto->getSubQuestionId()) {
                $answers = $subQuestion->getAnswers();
                foreach ($answers as $answer) {
                    $answerStatus = $this->getAnswerStatus($answer->getId(), $dto->getAnswers());
                    if($answerStatus === true) {
                        $hasUserChoose = true;
                    }
                    $answer->setIsUserChoice($answerStatus);
                }
                break;
            }
        }

        if ($hasUserChoose === false) {
            $message = $this->translator->translate('cannot found your answer');
            throw new \Test\Exceptions\UpdateAnswerException($message); 
        }
    }

    protected function getAnswerStatus($answerId, $userChoices) {
        foreach ($userChoices as $userChoice) {
            if ($userChoice->getId() == $answerId) {
                return $userChoice->getIsUserChoice();
            }
        }
    }

}
