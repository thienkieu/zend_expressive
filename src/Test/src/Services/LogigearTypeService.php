<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

class LogigearTypeService extends TypeService
{
    public function isHandler($param, $options = []){
        return true;
    }

    public function isWritingQuestion($question) {
        if (
            $question instanceof \Test\Documents\Question\WritingQuestionDocument ||
            $question instanceof \Test\DTOs\Question\WritingQuestionDTO ||
            $question instanceof \Test\Documents\Test\WritingQuestionDocument
        ) {
            return true;
        }
    }
    
}
