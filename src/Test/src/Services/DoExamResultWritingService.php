<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

class DoExamResultWritingService extends DoBaseExamResultService
{
    public function isHandler($dto, $options = []){
        if ($dto instanceof \Test\DTOs\ExamResult\UpdateWritingAnswerDTO) {
            return true;
        }

        return false;
    }

    protected function updateSubQuestionAnswer(& $examResult, $dto) {
        $writingQuestion = $this->getQuestion($examResult, $dto);
        if (!($writingQuestion instanceof \Test\Documents\Test\WritingQuestionDocument)) {
            $message = $this->translator->translate('This is not writing question');
            throw new \Test\Exceptions\UpdateAnswerException($message);
        }
        $writingQuestion->setAnswer($dto->getWritingContent());
        
        return $examResult;
    }
}
