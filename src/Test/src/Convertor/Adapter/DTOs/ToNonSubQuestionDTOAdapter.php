<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ToDTOAdapter;
use Test\Enum\DTOName;
use Config\AppConstant;

class ToNonSubQuestionDTOAdapter extends ToDTOAdapter {
    public function isHandleConvertToDTO($dtoObject, $options = []) : bool
    {
        $type = isset($dtoObject->type) ? $dtoObject->type: '';
        if (isset($options[\Config\AppConstant::DTOKey]) && $options[\Config\AppConstant::DTOKey] === DTOName::QuestionDTO && $type === \Config\AppConstant::NonSub) {
            return true;
        }

        return false;
    }
    
    public function getDTOClass() {
        return \Test\DTOs\Question\NonSubQuestionDTO::class;
    }
    
}