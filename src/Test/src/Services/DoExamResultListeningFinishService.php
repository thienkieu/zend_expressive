<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

class DoExamResultListeningFinishService extends DoBaseExamResultService
{
    public function isHandler($dto, $options = []){
        if ($dto instanceof \Test\DTOs\ExamResult\ListeningQuestionListeningFinishedDTO) {
            return true;
        }

        return false;
    }

    protected function updateSubQuestionAnswer(& $examResult, $dto) {
        $listeningQuestion = $this->getQuestion($examResult, $dto);
        if (!($listeningQuestion instanceof \Test\Documents\Test\ListeningQuestionDocument)) {
            $message = $this->translator->translate('This is not listening question');
            throw new \Test\Exceptions\UpdateAnswerException($message);
        }

        $listeningQuestion->setIsFinished(true);
        
        return $examResult;
    }
}
