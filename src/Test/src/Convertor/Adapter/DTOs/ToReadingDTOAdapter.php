<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ToDTOAdapter;
use Test\Enum\DTOName;
use Config\AppConstant;

class ToReadingDTOAdapter extends ToDTOAdapter {
    public function isHandle($dtoObject, $name) : bool
    {
        $type = isset($dtoObject->type) ? $dtoObject->type: '';
        if ($name === DTOName::QuestionDTO && $type === DTOName::Reading ) {
            return true;
        }

        return false;
    }
    
    public function getDTOClass() {
        return \Test\DTOs\Question\ReadingQuestionDTO::class;
    }
    
}