<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;
use time;
class DoExamResultListeningClickToListenService extends DoBaseExamResultService
{
    public function isHandler($dto, $options = []){
        if ($dto instanceof \Test\DTOs\ExamResult\ListeningQuestionClickToListenDTO) {
            return true;
        }

        return false;
    }

    protected function updateSubQuestionAnswer(& $examResult, $dto) {
        $sections = $examResult->getTest()->getSections();
        foreach ($sections as $section) {
            $questions = $section->getQuestions();
            foreach ($questions as $question) {
                $questionInfo = $question->getQuestionInfo();
                if ($questionInfo instanceof \Test\Documents\Test\ListeningQuestionDocument) {
                    $questionInfo->setLatestClick(null); 
                }
            }
        }

        $listeningQuestion = $this->getQuestion($examResult, $dto);
        if (!($listeningQuestion instanceof \Test\Documents\Test\ListeningQuestionDocument)) {
            $message = $this->translator->translate('This is not listening question');
            throw new \Test\Exceptions\UpdateAnswerException($message);
        }
        
        $listeningQuestion->setLatestClick(time());
        
        return $examResult;
    }
}
