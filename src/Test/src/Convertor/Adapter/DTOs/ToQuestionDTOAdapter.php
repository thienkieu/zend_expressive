<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ToDTOAdapter;
use Test\Enum\DTOName;

class ToQuestionDTOAdapter extends ToDTOAdapter {
    public function isHandle($dtoObject, $name) : bool
    {
        if ($name === DTOName::QuestionDTO) {
            return true;
        }

        return false;
    }
    
    public function getDTOClass() {
        return \Test\DTOs\Question\QuestionDTO::class;
    }
}