<?php

declare(strict_types=1);

namespace Test\Services\Question;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

class WritingQuestionService extends QuestionService
{
    public function isHandler($param, $options = []){
        if ((isset($options['document']) && $options['document'] instanceof \Test\Documents\Test\WritingQuestionDocument)||
        (isset($options[\Config\AppConstant::DTOKey]) && ($options[\Config\AppConstant::DTOKey] instanceof \Test\DTOs\Test\QuestionDTO) && ($options[\Config\AppConstant::DTOKey])->getQuestionInfo()->getRenderType() === \Config\AppConstant::Writing)){
            return true;
        }
        return false;
    }

    public function caculateMark(&$questionDocument) {}

    protected function limitSubQuestion($questionDTO, $numberSubQuestion, $isKeepQuestionOrder = false, $isRandomAnswer = false) {
        return [];
    }

    

}
