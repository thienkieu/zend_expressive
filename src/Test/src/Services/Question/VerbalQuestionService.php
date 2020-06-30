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
        if ((isset($options['document']) && $options['document'] instanceof \Test\Documents\Test\VerbalQuestionDocument)||
        (isset($options[\Config\AppConstant::DTOKey]) && ($options[\Config\AppConstant::DTOKey] instanceof \Test\DTOs\Test\QuestionDTO) && ($options[\Config\AppConstant::DTOKey])->getQuestionInfo()->getRenderType() === \Config\AppConstant::Verbal)){
            return true;
        }
        return false;
    }

    public function caculateMark(&$questionDocument) {}
    
    protected function limitSubQuestion($questionDTO, $numberSubQuestion, $isKeepQuestionOrder = false, $isRandomAnswer = false) {
        return [];
    }

}
