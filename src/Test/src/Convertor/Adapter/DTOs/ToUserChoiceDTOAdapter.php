<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ToDTOAdapter;
use Test\Enum\DTOName;

class ToUserChoiceDTOAdapter extends ToDTOAdapter {
    public function isHandleConvertToDTO($dtoObject, $options = []) : bool
    {
        if ($options === DTOName::UserChoiceDTO) {
            return true;
        }

        return false;
    }
    
    public function getDTOClass() {
        return \Test\DTOs\ExamResult\UserChoiceDTO::class;
    }
}