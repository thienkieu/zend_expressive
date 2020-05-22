<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ToDTOAdapter;
use Test\Enum\DTOName;

class ToUpdateWritingAnswerDTOAdapter extends ToDTOAdapter {
    public function isHandleConvertToDTO($dtoObject, $options = []) : bool
    {
        if (isset($options[\Config\AppConstant::DTOKey]) && $options[\Config\AppConstant::DTOKey] === DTOName::UserAnswerDTO && isset($dtoObject->writingContent)) {
            return true;
        }

        return false;
    }
    
    public function getDTOClass() {
        return \Test\DTOs\ExamResult\UpdateWritingAnswerDTO::class;
    }
}