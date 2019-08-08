<?php

declare(strict_types=1);

namespace Test\Services\Question;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

class VerbalQuestionService extends QuestionService
{
    public function isHandler($param, $options = []){
        if (isset($options['document']) && $options['document'] instanceof \Test\Documents\Test\VerbalQuestionDocument){
            return true;
        }
        return false;
    }

    public function caculateMark(&$questionDocument) {}
    

}
