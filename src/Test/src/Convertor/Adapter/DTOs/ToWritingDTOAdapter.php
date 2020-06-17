<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ToDTOAdapter;
use Test\Enum\DTOName;
use Config\AppConstant;

class ToWritingDTOAdapter extends ToDTOAdapter {
    public function isHandleConvertToDTO($dtoObject, $options = []) : bool
    {
        $type = isset($dtoObject->renderType) ? $dtoObject->renderType: '';
        if (isset($options[\Config\AppConstant::DTOKey]) && $options[\Config\AppConstant::DTOKey] === DTOName::QuestionDTO && $type === DTOName::Writing ) {
            return true;
        }

        return false;
    }
    
    public function getDTOClass() {
        return \Test\DTOs\Question\WritingQuestionDTO::class;
    }
}